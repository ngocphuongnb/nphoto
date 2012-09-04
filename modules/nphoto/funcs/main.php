<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES ., JSC. All rights reserved
 * @Createdate Jul 11, 2010  8:43:46 PM
 */

if( ! defined( 'NV_IS_MOD_NPHOTO' ) ) die( 'Stop!!!' );
$contents = '';

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];

if( $catid > 0 )
{
	$array_mod_title[] = array(
		'catid' => $catid,
		'title' => $allcats[$catid]['title'],
		'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . '=' . $allcats[$catid]['alias']
	);
}

if( !empty( $category_alias ) and $catid == 0 )
{
	$contents = np_theme_message('Chủ đề bạn yêu cầu không tồn tại hoặc đã bị xóa');
}
elseif( !empty( $home_category ) )
{
	if( $catid == 0  )
	{
		if( !empty( $setting['home_album'] ) )
		{
			$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_album` WHERE `albid` IN ( " . $setting['home_album'] . " )";
			$home_album = nv_db_cache( $sql, 'home_album', $module_name );
			$contents = np_album_theme( $home_album, '' );
		}
	}
	$array_cat = array();
	$key = 0;
	foreach( $home_category as $_catid )
	{
		$func_who_view = $allcats[$_catid]['who_view'];
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
			$array_cat[$key] = $allcats[$_catid];
			$sql = "SELECT " . $cal_found_rows . " `pid`, `filename`, `filepath`, `thumbpath`, `catid`, `userid`, `title`, `alias`, `alt`, `img_size` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $_catid . "` LIMIT " . $limit;
			$query = ( $db->sql_query( $sql ) );
			
			if( $catid > 0 )
			{
				$result_all = $db->sql_query( "SELECT FOUND_ROWS()" );
				list( $all_page ) = $db->sql_fetchrow( $result_all );
			}
			
			while( $photo =$db->sql_fetch_assoc( $query ) )
			{
				$array_cat[$key]['content'][] = $photo;
			}
		}
		++$key;
	}
	if( sizeof( $home_category ) == 1 and $home_category[0] > 0 )
	{
		$generate_page = nv_alias_page( $page_title, $base_url, $all_page, $per_page, $page );
	}
	else $generate_page = '';
	$contents .= np_home_theme( $array_cat, $generate_page );
}
 
include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>