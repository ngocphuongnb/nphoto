<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES ., JSC. All rights reserved
 * @Createdate Jul 11, 2010  8:43:46 PM
 */

if( ! defined( 'NV_IS_MOD_NPHOTO' ) ) die( 'Stop!!!' );
$contents = '';

$page_title = 'Album';
$key_words = $module_info['keywords'];
$list_album = $list_photo = array();

if( $albid > 0 )
{
	$array_mod_title[] = array(
		'catid' => 0,
		'title' => $allalbs[$albid]['title'],
		'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . '=album/' . $allalbs[$albid]['alias']
	);
}

if( !empty( $album_alias ) and $albid == 0 )
{
	$contents = np_theme_message('Album bạn yêu cầu không tồn tại hoặc đã bị xóa');
}
elseif( empty( $album_alias ) and $albid == 0 )
{
	$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=album";
	$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_album` LIMIT " . $limit;
	$query = $db->sql_query( $sql );
	
	while( $album =$db->sql_fetch_assoc( $query ) )
	{
		$list_album[] = $album;
	}
	
	$result_all = $db->sql_query( "SELECT FOUND_ROWS()" );
	list( $all_page ) = $db->sql_fetchrow( $result_all );
	
	$generate_page = nv_alias_page( $page_title, $base_url, $all_page, $per_page, $page );
	$contents = np_album_theme( $list_album, $generate_page );
}
else
{
	$func_who_view = $allalbs[$albid]['who_view'];
	$allowed = false;
	if( $func_who_view == 0 )
	{
		$allowed = true;
	}
	if( $func_who_view == 1 and defined( 'NV_IS_USER' ) )
	{
		$allowed = true;
	}
	elseif( $func_who_view == 2 and defined( 'NV_IS_MODADMIN' ) )
	{
		$allowed = true;
	}
	elseif( $func_who_view == 3 and defined( 'NV_IS_USER' ) and nv_is_in_groups( $user_info['in_groups'], $allalbs[$albid]['groups_view'] ) )
	{
		$allowed = true;
	}
	
	if( $allowed )
	{
		$per_page = $setting['view_album_numphotos'];
		$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_photos` " . $where . " LIMIT " . $limit;
		$query = $db->sql_query( $sql );
		
		while( $photo =$db->sql_fetch_assoc( $query ) )
		{
			$list_photo[] = $photo;
		}
		
		$result_all = $db->sql_query( "SELECT FOUND_ROWS()" );
		list( $all_page ) = $db->sql_fetchrow( $result_all );	
		$generate_page = nv_alias_page( $page_title, $base_url, $all_page, $per_page, $page );
		
		// Get comment
		$feature = array( 'cmable' => $allalbs[$albid]['allowed_comm'], 'voteable' => $allalbs[$albid]['allowed_rating'] );
		$comment_array = np_comment( 'album', $albid, $allalbs[$albid]['allowed_comm'], 0 );
		$alb_comments = comment_theme( 'album', $albid, $feature, $comment_array );
		
		$contents = np_list_photo( $list_photo, $generate_page, $alb_comments, $allalbs[$albid] );
	}
	else 
	{
		$msg = no_permission( $func_who_view );
		$contents = np_theme_message($msg);
	}
}	
 
include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>