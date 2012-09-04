<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 10/03/2010 10:51
 */

if( ! defined( 'NV_SYSTEM' ) ) die( 'Stop!!!' );

define( 'NV_IS_MOD_NPHOTO', true );
require_once ( NV_ROOTDIR . "/modules/" . $module_file . "/global.functions.php" );

$pid = $catid = $all_page = $albid = $np_feature = 0;
$page = 1;
$per_page = $setting['home_cat_numphotos'];
$photo_alias = $album_alias = $category_alias = $base_url = $cal_found_rows = $where = $order ='';
$limit = $setting['home_cat_numphotos'];
$home_category = $home_album = array();

if( !empty( $setting['home_category'] ) ) $home_category = explode( ',', $setting['home_category'] );

$count_op = sizeof( $array_op );

if( ! empty( $array_op ) and $op == "main" )
{
	$category_alias = isset( $array_op[0] ) ? $array_op[0] : "";
	if( !empty( $category_alias ) )
	{
		foreach( $allcats as $_catid => $catdata )
		{
			if( $catdata['alias'] == $category_alias )
			{
				$catid = $_catid;
				$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $catdata['alias'];
				$home_category = array( $catdata['catid'] );
			}
		}
	}
	if( $count_op > 2 or ( $count_op == 2 and substr( $array_op[1], 0, 5 ) != "page-" and $catid > 0 ) )
	{
		nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );
	}
	elseif( $count_op == 1 or substr( $array_op[1], 0, 5 ) == "page-" )
	{
		$category_alias = isset( $array_op[0] ) ? $array_op[0] : "";
		
		if( $count_op > 1 )
		{
			$page = intval( substr( $array_op[1], 5 ) );
		}
		
		if( $catid > 0 )
		{
			$per_page = $setting['view_cat_numphotos'];
			$limit = ( $page - 1 ) * $per_page . "," . $per_page;
			$cal_found_rows = "SQL_CALC_FOUND_ROWS";
		}
	}
}
elseif( $op == "album" )
{
	if( ! empty( $array_op ) )
	{
		$per_page = $setting['view_numalbums'];
		$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=album";
		$album_alias = isset( $array_op[1] ) ? $array_op[1] : "";
		if( !empty( $album_alias ) )
		{
			foreach( $allalbs as $_albid => $albdata )
			{
				if( $albdata['alias'] == $album_alias )
				{
					$albid = $_albid;
					$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=album/" . $album_alias;
				}
			}
		}
		
		if( $albid > 0 )
		{
			$where = "WHERE `albumid`=" . $albid;
			$limit = $per_page = $setting['view_album_numphotos'];
		}
		if( $count_op == 2 )
		{
			if( substr( $array_op[1], 0, 5 ) == "page-" )
			{
				$album_alias = '';
				$page = intval( substr( $array_op[1], 5 ) );		
				$limit = ( $page - 1 ) * $per_page . "," . $per_page;
			}
		}
		elseif( $count_op == 3 )
		{		
			if( substr( $array_op[2], 0, 5 ) == "page-" )
			{
				$page = intval( substr( $array_op[2], 5 ) );		
				$limit = ( $page - 1 ) * $per_page . "," . $per_page;
			}
			else
			{
				nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );
			}
		}
		elseif( $count_op > 3 )
		{		
			nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );
		}
	}
	else $limit = $per_page = $setting['view_numalbums'];
}
elseif( $op == "photo" )
{
	$limit = $per_page = $setting['view_all_numphotos'];
	$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=photo";
	if( $count_op > 3 )
	{		
		nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );
	}
	elseif( $count_op == 2 )
	{
		if( substr( $array_op[1], 0, 5 ) == "page-" )
		{
			$page = intval( substr( $array_op[1], 5 ) );		
			$limit = ( $page - 1 ) * $per_page . "," . $per_page;
		}
		elseif( in_array( $array_op[1], array( 'lastest', 'mostview', 'like', 'dislike' ) ) )
		{
			//
		}
		else
		{
			$isset_photo = explode( "-", $array_op[1] );
			$pid = intval( end( $isset_photo ) );
			if( $pid == 0 )
			{		
				nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );
			}
			$length = strlen( $pid ) + 1;
			$photo_alias = substr( $array_op[1], 0, -$length );
		}
	}
	elseif( $count_op == 3 )
	{
		if( substr( $array_op[2], 0, 5 ) == "page-" )
		{
			$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=photo/" . $array_op[1];
			$page = intval( substr( $array_op[2], 5 ) );		
			$limit = ( $page - 1 ) * $per_page . "," . $per_page;
		}
		else
		{		
			nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );
		}
	}
	if( isset( $array_op[1] ) )
	{
		if( in_array( $array_op[1], array( 'lastest', 'mostview', 'like', 'dislike' ) ) )
		{
			$np_feature = 1;
			$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=photo/" . $array_op[1];
			$photo_alias = '';
			if( $array_op[1] == 'lastest' )
			{
				$order = "ORDER BY `pid` DESC";
			}
			if( $array_op[1] == 'mostview' )
			{
				$order = "ORDER BY `viewed` DESC";
			}
			if( $array_op[1] == 'like' )
			{
				$order = "ORDER BY `like` DESC";
			}
			if( $array_op[1] == 'dislike' )
			{
				$order = "ORDER BY `dislike` DESC";
			}
		}
	}
}

?>