<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/31/2009 2:29
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

//Get setting value
$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_setting`";
$settingdata = nv_db_cache( $sql, 'name', $module_name );
foreach( $settingdata as $data )
{
	$setting[$data['name']] = $data['value'];
}
//Get all categories
$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_category` WHERE `status`=0 ORDER BY `order`";
$allcats = nv_db_cache( $sql, 'catid', $module_name );
//Get all albums
$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_album` WHERE `status`=0 ORDER BY `weight`";
$allalbs = nv_db_cache( $sql, 'albid', $module_name );

function print_array( $array )
{
	echo '<pre>';
	print_r( $array );
	echo '</pre>';
	die();
}

/**
 * np_comment()
 * 
 * @param mixed $type
 * @param mixed $id
 * @param mixed $page
 * @return
 */
function np_comment( $type, $id, $commentenable, $page )
{
	global $db, $module_name, $module_data, $global_config, $module_config, $per_page_comment;
	$comment_array = array();
	$per_page = $per_page_comment = 20;
	$sql = "SELECT SQL_CALC_FOUND_ROWS a.cid, a.level, a.cmcount, a.content, a.like, a.dislike, a.user_like, a.user_dislike, a.post_time, a.post_name, a.post_email, b.userid, b.email, b.full_name, b.photo, b.view_mail FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comment_" . $type . "` as a LEFT JOIN `" . NV_USERS_GLOBALTABLE . "` as b ON a.userid =b.userid  WHERE a.id= '" . $id . "' AND a.status=1 ORDER BY a.level ASC LIMIT " . $page . "," . $per_page;
	$comment = $db->sql_query( $sql );
	$result_all = $db->sql_query( "SELECT FOUND_ROWS()" );
	list( $all_page ) = $db->sql_fetchrow( $result_all );

	$commentid = 0;
	while( list( $cid, $level, $cmcount, $content, $like, $dislike, $user_like, $user_dislike, $post_time, $post_name, $post_email, $userid, $user_email, $user_full_name, $photo, $view_mail ) = $db->sql_fetchrow( $comment ) )
	{
		if( $userid > 0 )
		{
			$post_email = $user_email;
			$post_name = $user_full_name;
		}
		//$post_email = ( $module_config[$module_name]['emailcomm'] and $view_mail ) ? $post_email : "";
		$comment_array[] = array(
			"cid" => $cid,
			"level" => $level,
			"cmcount" => $cmcount,
			"content" => $content,
			"like" => $like,
			"dislike" => $dislike,
			"user_like" => $user_like,
			"user_dislike" => $user_dislike,
			"post_time" => $post_time,
			"userid" => $userid,
			"post_name" => $post_name,
			"post_email" => $post_email,
			"photo" => $photo
		);
	}
	$db->sql_freeresult( $comment );
	unset( $row, $comment );
	$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=comment&amp;type=" . $type . "&amp;id=" . $id . "&cmable=" . $commentenable . "&checkss=" . md5( $id . session_id() . $global_config['sitekey'] );
	$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page, true, true, 'nv_urldecode_ajax', 'showcomment' );
	return array( "comment" => $comment_array, "page" => $generate_page );
}

function no_permission( $func_who_view )
{
	global $module_info, $module_file, $global_config, $lang_global, $lang_module, $db, $module_name;
	if( $func_who_view == 1 )
	{
		$no_permission = "Chỉ thành viên mới được xem ảnh này";
	}
	elseif( $func_who_view == 2 )
	{
		$no_permission = "Chỉ admin mới được xem ảnh này";
	}
	elseif( $func_who_view == 3 )
	{
		$no_permission = "Nhóm thành viên của bạn không được xem ảnh này";
	}
	return $no_permission;
}


?>