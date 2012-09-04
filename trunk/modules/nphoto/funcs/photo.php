<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES ., JSC. All rights reserved
 * @Createdate Jul 11, 2010  8:43:46 PM
 */

if( ! defined( 'NV_IS_MOD_NPHOTO' ) ) die( 'Stop!!!' );
$contents = '';
$scwidth = $scheight = $fitwidth = $fitheight = $ver = 0;
if( defined( 'NV_IS_AJAX' ) )
{
	$pid = $nv_Request->get_int( 'pid', 'post', 0 );
	$scwidth = $nv_Request->get_int( 'scwidth', 'post', 0 );
	$scheight = $nv_Request->get_int( 'scheight', 'post', 0 );
	$fitwidth = $nv_Request->get_int( 'fitwidth', 'post', 0 );
	$fitheight = $nv_Request->get_int( 'fitheight', 'post', 0 );
	$ver = 1;
}

$page_title = 'Photo';
$key_words = $module_info['keywords'];
$list_photo = array();

if( $np_feature > 0 )
{
	$array_mod_title[] = array(
		'catid' => 0,
		'title' => $array_op[1],
		'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . '=photo/' . $array_op[1]
	);
}

if( !empty( $photo_alias ) and $pid == 0 )
{
	$contents = np_theme_message('Ảnh bạn yêu cầu không tồn tại hoặc đã bị xóa');
}
elseif( empty( $photo_alias ) and $pid == 0 )
{
	$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_photos` " . $order . " LIMIT " . $limit;
	$query = $db->sql_query( $sql );
	
	while( $photo =$db->sql_fetch_assoc( $query ) )
	{
		$list_photo[] = $photo;
	}
	
	$result_all = $db->sql_query( "SELECT FOUND_ROWS()" );
	list( $all_page ) = $db->sql_fetchrow( $result_all );
	
	$generate_page = nv_alias_page( $page_title, $base_url, $all_page, $per_page, $page );
	$contents = np_list_photo( $list_photo, $generate_page, '' );
}
else
{
	$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_photos` WHERE `pid`=" . intval ( $pid ) . " LIMIT 1";
	
	$photo_data = $db->sql_fetch_assoc( $db->sql_query( $sql ) );
	
	if( empty( $photo_data ) ) $contents = np_theme_message('Ảnh bạn yêu cầu không tồn tại hoặc đã bị xóa');
	else
	{
		$func_who_view = $photo_data['who_view'];
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
		elseif( $func_who_view == 3 and defined( 'NV_IS_USER' ) and nv_is_in_groups( $user_info['in_groups'], $global_array_cat[$catid]['groups_view'] ) )
		{
			$allowed = true;
		}
		
		if( $allowed )
		{
			$feature = array( 'cmable' => $photo_data['allowed_comm'], 'voteable' => $photo_data['allowed_rating'] );
			$comment_array = np_comment( 'photos', $pid, $photo_data['allowed_comm'], 0 );
			$comment_array['ver'] = $ver;
			$photo_data['comments'] = comment_theme( 'photos', $pid, $feature, $comment_array );
			$photo_data['scwidth'] = $scwidth;
			$photo_data['scheight'] = $scheight;
			$photo_data['fitwidth'] = $fitwidth;
			$photo_data['fitheight'] = $fitheight;
			$contents = np_view_photo( $photo_data, $ver );
		}
		else 
		{
			$msg = no_permission( $func_who_view );
			$contents = np_theme_message($msg);
		}
	}
}	
 
include ( NV_ROOTDIR . "/includes/header.php" );
if( $ver == 0 ) echo nv_site_theme( $contents );
else echo $contents;
include ( NV_ROOTDIR . "/includes/footer.php" );
?>