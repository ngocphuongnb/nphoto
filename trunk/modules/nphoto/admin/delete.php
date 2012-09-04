<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-10-2010 18:49
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$type = filter_text_input( 'type', 'post,get', '' );
$by = filter_text_input( 'by', 'post,get', '' );
$albumid = filter_text_input( 'albumid', 'post,get', '' );
$arrayvalue = filter_text_input( 'value', 'post,get', '' );
$get_from = $nv_Request->get_string( 'get_from', 'post', 0 );

if( !empty( $arrayvalue ) )
{
	$arrayvalue = explode( ',', $arrayvalue );

	if( $type == 'category' )
	{
		foreach( $arrayvalue as $value )
		{
			if( $np->CheckAdminAccess('del_cat', $value) )
			{
				$category = $np->getItems($type, $by, $by, $value);			
				list( $numphotos ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) AS numphotos FROM `" . NV_PREFIXLANG . "_" . $module_name . "_" . $value . "`" ) );
				
				if( $category[$value]['numsubcat'] > 0 ) 
				{
					$np->warning[] = "- Chủ đề " . $allcats[$value]['title'] . " chứa chủ đề con, bạn phải xóa các chủ đề con trước";
				}
				elseif( $numphotos > 0 )
				{
					$np->warning[] = "- Chủ đề này chứa ảnh<br />";
					$np->warning[] = "<input type=\"button\" onclick=\"delcatdata(" . $value . ");\" value=\"Xóa tất cả ảnh\" />
									  <input type=\"button\" onclick=\"movecatdata(" . $value .", 0);\" value=\"Chuyển ảnh vào sọt rác\" /><br />";
					$new_CatData = "Chuyển ảnh vào chủ đề khác: 
									<select name=\"new_cat\" id=\"new_cat\">";
										$_listcat = $array_cat_list;
										$_listcat[0] = "Chọn chủ đề chuyển đến";
										unset( $_listcat[$value] );
										foreach( $_listcat as $catid_i => $title_i )
										{
											$new_CatData .= "<option value=" . $catid_i . ">" . $title_i . "</option>";
										}
					$new_CatData .= "</select>";
					$new_CatData .= "<input type=\"button\" onclick=\"movecatdata(" . $value .", 1);\" value=\"Chuyển ảnh\" /><br />";
					$np->warning[] = $new_CatData;
				}
				$np->CheckAdminAccess('listcatid', $category[$value]['catid'] );
				
				if( $np->deleteItem( $type, $by, $value) )
				{
					nv_fix_cat_order();
					nv_del_moduleCache( $module_name );
					if( $category[$value]['parentid'] > 0 )
					{
						$parentdata = $np->getItems($type, $by, 'parentid', $category[$value]['parentid'] );
						$parentdata[$value]['subcatids'] = array_diff( explode( ',', $parentdata[$value]['subcatids'] ), $value );
						$parentdata[$value]['subcatids'] = implode( ',', $parentdata[$value]['subcatids'] );
						$np->updateItem( $type, $parentdata[$value], 'catid' );
					}
					$db->sql_query( "DROP TABLE `" . NV_PREFIXLANG . "_" . $module_data . "_" . $value . "`" );
					$np->setAdminRoll( array(), $category[$value]['adminids'], 'admins', 'listcatid', 'userid', $value );
					$np->success[] = "- Xóa thành công chủ đề " . $allcats[$value]['title'];
				}
				elseif( empty( $np->warning ) )
				{
					$np->error[] = "- Không thể xóa chủ đề " . $allcats[$value]['title'];
				}
			}
		}
	}
	elseif( $type == 'album' )
	{
		foreach( $arrayvalue as $value )
		{
			if( $np->CheckAdminAccess('del_album', $value) )
			{
				$album = $np->getItems($type, $by, $by, $value);
				$np->CheckAdminAccess('listalbid', $album[$value]['albid'] );
				if( $np->deleteItem( $type, $by, $value) )
				{
					nv_del_moduleCache( $module_name );
					$np->setAdminRoll( array(), $album[$value]['adminids'], 'admins', 'listalbid', 'userid', $value );
					$np->success[] = "- Xóa thành công album " . $allalbs[$value]['title'];
				}
				else
				{
					$np->error[] = "- Không thể xóa album " . $allalbs[$value]['title'];
				}
			}
		}
	}
	elseif( $type == 'photos' )
	{
		foreach( $arrayvalue as $value )
		{			
			npdelphoto( $by, $value, 1, 0, 1, $get_from );
		}
	}
	elseif( $type == 'addtoalbum' )
	{
		foreach( $arrayvalue as $value )
		{
			$photo = $np->getItems('photos', 'pid', 'pid', $value);
			$_data['albumid'] = $albumid;
			$_data['pid'] = $value;
			if( $np->updateItem( 'photos', $_data, 'pid' ) )
			{
				if( $_data['catid'] != 0 )
				{
					$np->updateItem( $_data['catid'], $_data, 'pid' );
				}
				$np->success[] = "- Thêm vào album thành công";
			}
		}
	}
}

if( $nv_Request->get_int( 'cat_edit', 'post,get', 0 ) == 1 )
{
	$action = $nv_Request->get_string( 'action', 'post', '' );
	$current_catid = $nv_Request->get_int( 'current_catid', 'post', 0 );
	$new_catid = $nv_Request->get_int( 'new_catid', 'post', 0 );
	if( $action == 'delcatdata' )
	{
		$query = $db->sql_query( "SELECT `pid` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $current_catid . "`" );
		while( $row = $db->sql_fetchrow( $query ) )
		{
			npdelphoto( 'pid', $row['pid'] );
		}
		$db->sql_query( "DROP TABLE `" . NV_PREFIXLANG . "_" . $module_data . "_" . $current_catid . "`" );
		$np->deleteItem( 'category', 'catid', $current_catid );
		nv_fix_cat_order();
	}
	elseif( $action == 'movecatdata' )
	{
		$query = $db->sql_query( "SELECT `pid` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $current_catid . "`" );
		while( $row = $db->sql_fetchrow( $query ) )
		{
			$np->transferData( '', $row['pid'], $current_catid, $new_catid, 0, 'photos' );
			$_data['pid'] = $row['pid'];
			$_data['catid'] = $new_catid;
			if( $new_catid == 0 ) $_data['status'] = 1;
			$np->updateItem( 'photos', $_data, 'pid' );
			$np->updateItem( $new_catid, $_data, 'pid' );
			if( $new_catid == 0 ) npdelphoto( 'pid', $row['pid'], 0, 1, 0 );
		}
		$db->sql_query( "DROP TABLE `" . NV_PREFIXLANG . "_" . $module_data . "_" . $current_catid . "`" );
		$np->deleteItem( 'category', 'catid', $current_catid );
		nv_fix_cat_order();
		nv_del_moduleCache( $module_name );
		if( $new_catid == 0 )
		{
			$np->success = '';
			$np->success[] = "Chuyển thành công ảnh của chủ đề " . $allcats[$current_catid]['title'] . " vào sọt rác";
		}
		else $np->success[] = "Chuyển thành công ảnh của chủ đề " . $allcats[$current_catid]['title'] . " sang chủ đề " . $allcats[$new_catid]['title'];
	}
	elseif( $action == 'movephoto' )
	{
		foreach( $arrayvalue as $pid )
		{
			list( $current_catid ) = $db->sql_fetchrow( $db->sql_query( "SELECT `catid` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $get_from . "` WHERE `pid` = " . intval( $pid ) ) );
			//$np->error[] = $current_catid . '-' . $new_catid . '-' . $get_from;
			$np->transferData( '', $pid, $current_catid, $new_catid, 0, $get_from );
			$_data['pid'] = $pid;
			$_data['catid'] = $new_catid;
			$np->updateItem( 'photos', $_data, 'pid' );
			$np->updateItem( $new_catid, $_data, 'pid' );
			if( $new_catid == 0 ) npdelphoto( 'pid', $pid, 0, 1, 0 );
		}
	}
}

empty( $np->warning ) ? $warning = '' : $warning = implode( '<br />', $np->warning );
empty( $np->success ) ? $success = '' : $success = implode( '<br />', $np->success );
empty( $np->error ) ? $error = '' : $error = implode( '<br />', $np->error );
nv_del_moduleCache( $module_name );
echo 'WAR#' . $warning . '*' . 'OK#' . $success . '*' . 'ERR#' . $error;
die();

?>