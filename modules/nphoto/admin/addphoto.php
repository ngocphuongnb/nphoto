<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-10-2010 18:49
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$type = $nv_Request->get_string( 'type', 'get', '' );

$contents = "";
$my_head .= '<script type="text/javascript" src="' . NV_BASE_SITEURL . 'modules/' . $module_name . '/js/jquery.form.js"></script>' . PHP_EOL;

$xtpl = new XTemplate( "addphoto.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=upload_handler" );

if( empty( $type ) )
{
	if( ! empty( $array_cat_list ) )
	{
		$listcat = array();
		unset( $array_cat_list[0] );
		foreach( $array_cat_list as $catid_i => $title_i )
		{
			$listcat[] = array(
					"link" => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=addphoto&amp;type=category&amp;typeid=" . $catid_i,
					"value" => $catid_i,
					"title" => $title_i );
		}	
		foreach( $listcat as $data )
		{
			$xtpl->assign( 'listcat', $data );
			$xtpl->parse( 'main.listcat.loop' );
		}
		$xtpl->parse( 'main.listcat' );
	}
	
	if( !empty( $array_alb_list ) )
	{
		$listalb = array();
		foreach( $array_alb_list as $albid_i => $title_i )
		{
			$listalb[] = array(
					"link" => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=addphoto&amp;type=album&amp;typeid=" . $albid_i,
					"value" => $albid_i,
					"title" => $title_i );
		}	
		foreach( $listalb as $data )
		{
			$xtpl->assign( 'listalb', $data );
			$xtpl->parse( 'main.listalbum.loop' );
		}
		$xtpl->parse( 'main.listalbum' );
	}
}
else
{
	$typeid = $nv_Request->get_int( 'typeid', 'get', 0 );
	if( $type == 'category' )
	{
		$condition = 'listcatid';
		$caption = "Chủ đề: " . $allcats[$typeid]['title'];
	}
	else
	{
		$condition = 'listalbid';
		$caption = "Album: " . $allalbs[$typeid]['title'];
	}
	$np->CheckAdminAccess($condition, $typeid);
	if( $np->status() )
	{
		$xtpl->assign( 'CAPTION', $caption );
		$xtpl->assign( 'type', $type );
		$xtpl->assign( 'typeid', $typeid );
		$xtpl->parse( 'main.upload' );
	}
}

$xtpl->parse( 'main' );
$contents .= $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>