<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$array_in_cat = array();
$status = $alias = $log_action = $hook = "";
$action = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;
$catid = $nv_Request->get_int( 'catid', 'get', 0 );
$parentid = $nv_Request->get_int( 'parentid', 'get', 0 );

$cats = array( 'catid' => 0, 'parentid' => $parentid, 'adminids' => '1', 'subcatids' => '', 'numsubcat' => 10, 'image' => '', 'thumbpath' => '', 'title' => '', 'alias' => '', 'meta_title' => '', 'meta_keywords' => '', 'meta_description' => '', 'tags_cloud' => '', 'bodytext' => '', 'allowed_comm' => 1, 'allowed_rating' => 1, 'add_time' => NV_CURRENTTIME, 'edit_time' => NV_CURRENTTIME, 'who_view' => 0, 'groups_view' => '', 'status' => 1 );

if( $catid > 0 )
{
	$np->CheckAdminAccess('listcatid', $catid);
	if( $np->status() )
	{
		$cats = $np->getItems( 'category', 'catid', 'catid', $catid );
		$cats = $cats[$catid];
		$array_in_cat = GetCatidInParent( $catid );
	}
}

if( $nv_Request->get_int( 'savecat', 'post' ) == '1' )
{
	$catdata['catid'] = $nv_Request->get_int( 'catid', 'post', 0 );
	$catdata['parentid'] = $nv_Request->get_int( 'parentid', 'post', 0 );
	$catdata['title'] = filter_text_input( 'title', 'post', '', 1 );
	$catdata['alias'] = filter_text_input( 'alias', 'post', '', 1 );
	$catdata['image'] = filter_text_input( 'image', 'post', '' );
	$catdata['meta_title'] = filter_text_input( 'meta_title', 'post', '', 1 );
	$catdata['meta_keywords'] = filter_text_input( 'meta_keywords', 'post', '', 1 );
	$catdata['tags_cloud'] = $catdata['meta_keywords'];
	$catdata['meta_description'] = filter_text_input( 'meta_description', 'post', '', 1 );
	$catdata['who_view'] = $nv_Request->get_int( 'who_view', 'post', 0 );
	$groups_view = "";
	
	if( ! nv_is_url( $catdata['image'] ) and file_exists( NV_DOCUMENT_ROOT . $catdata['image'] ) )
	{
		$lu = strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" );
		$catdata['image'] = substr( $catdata['image'], $lu );
	}

	$groups = $nv_Request->get_typed_array( 'groups_view', 'post', 'int', array() );
	$groups = array_intersect( $groups, array_keys( $groups_list ) );
	$catdata['groups_view'] = implode( ",", $groups );
	$oldAdminArray = filter_text_input( 'old_admins', 'post', '', 1 );
	if( empty( $catdata['alias'] ) ) $catdata['alias'] = change_alias( $catdata['title'] );
	
	$newAdminArray = array_unique( $nv_Request->get_typed_array( 'adminids', 'post', 'int', array() ) );
	$old_parentid = $nv_Request->get_int( 'old_parentid', 'post', 0 );
	$catdata['adminids'] = implode( ',', $newAdminArray );
	
	if( empty( $catdata['title'] ) ) $np->error[] = "- Chưa có tiêu đề";
	
	if( $catdata['catid'] > 0 )
	{
		$np->CheckAdminAccess('listcatid', $catdata['catid'] );
		$np->updateItem( 'category', $catdata, 'catid' );
		//np_create_CatDataTable( $catdata['catid'] );
		if( $db->sql_affectedrows() > 0 )
		{
			$np->setAdminRoll( $newAdminArray, $oldAdminArray, 'admins', 'listcatid', 'userid', $catdata['catid'] );
			$db->sql_freeresult();
			if( $catdata['parentid'] != $old_parentid )
			{
				list( $weight ) = $db->sql_fetchrow( $db->sql_query( "SELECT max(`weight`) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_category` WHERE `parentid`=" . intval( $catdata['parentid'] ) ) );
				$weight = intval( $weight ) + 1;
				$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_category` SET `weight`=" . $weight . " WHERE `catid`=" . intval( $catdata['catid'] );
				$db->sql_query( $sql );
			}
		}
		$log_action = "Sửa chủ đề";
	}
	else
	{
		$np->CheckAdminAccess('add_cat', 0 );
		$catdata['catid'] = 'NULL';
		list( $weight ) = $db->sql_fetchrow( $db->sql_query( "SELECT max(`weight`) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_category` WHERE `parentid`=" . $db->dbescape( $catdata['parentid'] ) ) );
		$catdata['weight'] = intval( $weight ) + 1;
		$newcatid = $np->addItem( 'category', $catdata );
		if( (int) $newcatid > 0 )
		{
			$catdata['catid'] = $newcatid;
			np_create_CatDataTable( $newcatid );
			$np->setAdminRoll( $newAdminArray, '', 'admins', 'listcatid', 'userid', $newcatid );
			$log_action = "Thêm chủ đề";
		}
	}
	if( !empty( $log_action ) )
	{
		$imgpath =  NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_name . '/' . $catdata['image'];
		$thumbpath = NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_name . '/thumbs/category/';
		if( ! is_dir( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/category/' . $catdata['alias'] ) )
		{
			nv_mkdir( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/category/', $catdata['alias'] );
			nv_loadUploadDirList( false );
		}
		if( ! is_dir( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/category/' . $catdata['alias'] . '/thumbs' ) )
		{
			nv_mkdir( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/category/' . $catdata['alias'] . '/', 'thumbs' );
			nv_loadUploadDirList( false );
		}
		$catdata['imgfolder'] = strtolower( $catdata['alias'] );
		if( $catdata['image'] != '' and !file_exists( $thumbpath . '/' . $catdata['image'] ) )
		{
			$catdata['thumbpath'] = createthumb( $imgpath, $thumbpath, '', 80, 80 );
		}	
		$np->updateItem( 'category', $catdata, 'catid' );
		
		nv_fix_cat_order();
		nv_insert_logs( NV_LANG_DATA, $module_name, $log_action, $catdata['title'], $admin_info['userid'] );
		
	}
	nv_del_moduleCache( $module_name );
	if( empty( $np->error ) and empty( $np->warning ) )
	{
		//Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
		//die();
		$np->success[] = "Lưu chủ đề thành công";
		$np->status();
		//redriect ( "", NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
	}
}

$cats['groups_view'] = explode( ",", $cats['groups_view'] );

if( ! empty( $array_cat_list ) )
{
	$listcat = array();
	foreach( $array_cat_list as $catid_i => $title_i )
	{
		if( !in_array( $catid_i, $array_in_cat ) )
		{			
			$listcat[] = array(
				"value" => $catid_i,
				"selected" => ( $catid_i == $cats['parentid'] ) ? " selected=\"selected\"" : "",
				"title" => $title_i );
		}
	}
	
	$who_views = array();
	foreach( $array_who_view as $k => $w )
	{
		$who_views[] = array(
			"value" => $k,
			"selected" => ( $cats['who_view'] == $k ) ? " selected=\"selected\"" : "",
			"title" => $w );
	}
	
	$groups_views = array();
	foreach( $groups_list as $group_id => $grtl )
	{
		$groups_views[] = array(
			"value" => $group_id,
			"checked" => in_array( $group_id, $cats['groups_view'] ) ? " checked=\"checked\"" : "",
			"title" => $grtl );
	}
}

$xtpl = new XTemplate( "category.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'FORM_ACTION', $action );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'UPLOADS_DIR', NV_UPLOADS_DIR );
$xtpl->assign( 'CURRENT_DIR', NV_UPLOADS_DIR . '/' . $module_name );

$showcats = $np->getItems( 'category', 'catid', 'parentid', $parentid );

if( !empty( $showcats ) )
{
	$i = 1;
	foreach( $showcats as $catid => $cat )
	{
		$cat['cat_url'] = $action . "&amp;parentid=" . $cat['catid'];
		$cat['edit_url'] = $action . "&amp;catid=" . $cat['catid'];
		$cat['del_url'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=delete&amp;type=cat&amp;catid=" . $cat['catid'];
		$cat['addphoto_url'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=addphoto&amp;type=category&amp;typeid=" . $cat['catid'];
		( $i%2 == 0 ) ? ( $cat['class'] = 'class="second"' ) : ( $cat['class'] = '' );
		$xtpl->assign( 'LISTCAT', $cat );
		$xtpl->parse( 'main.listcat.loop' );
		++$i;
	}
	$xtpl->parse( 'main.listcat' );
}

if( ! empty( $array_cat_list ) )
{
	if( empty( $alias ) )
	{
		$xtpl->parse( 'main.content.getalias' );
	}
	
	foreach( $listcat as $data )
	{
		$xtpl->assign( 'listcat', $data );
		$xtpl->parse( 'main.content.listcat' );
	}
}

$hook .= custom_metatag( $cats );
$hook .= configdata( $who_views, $groups_views, $cats['who_view'] );
$hook .= setAdmin( $cats );

$xtpl->assign( 'CAT', $cats );
$xtpl->assign( 'HOOK', $hook );
$xtpl->parse( 'main.content' );
$xtpl->parse( 'main' );
$contents .= $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>