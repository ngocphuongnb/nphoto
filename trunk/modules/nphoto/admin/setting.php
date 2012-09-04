<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-10-2010 18:49
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$title = filter_text_input( 'title', 'post', '' );
$to = filter_text_input( 'to', 'post', '' );
$contents = '';

$id = $nv_Request->get_int( 'id', 'post', 0 );
$mod = $nv_Request->get_string( 'mod', 'post', '' );

$upload_file_type = array( 'jpg', 'gif', 'png', 'bmp', 'tif', 'tiff', 'jpe', 'jpeg', 'jfif' );
$comment_type = array( 0 => 'Không dùng', 1 => 'Dùng bình luận module', 2 => 'Dùng bình luận facebook' );

if ( $nv_Request->get_int( 'save', 'post' ) == '1' )
{
    $array_setting['maxfilenums'] = $nv_Request->get_int( 'maxfilenums', 'post', 0 );
    $array_setting['maxfilesize'] = $nv_Request->get_int( 'maxfilesize', 'post', 0 );
	$array_setting['upload_filetype'] = $nv_Request->get_typed_array( 'upload_filetype', 'post', 'string' );
    $array_setting['comment_album'] = $nv_Request->get_int( 'comment_album', 'post', 0 );
    $array_setting['comment_photos'] = $nv_Request->get_int( 'comment_photos', 'post', 0 );
    $array_setting['hide_real_imgurl'] = $nv_Request->get_int( 'hide_real_imgurl', 'post', 0 );
    $array_setting['upload_img_maxwidht'] = $nv_Request->get_int( 'upload_img_maxwidht', 'post', 0 );
    $array_setting['upload_img_maxheight'] = $nv_Request->get_int( 'upload_img_maxheight', 'post', 0 );
	$array_setting['thumb_maxwidht'] = $nv_Request->get_int( 'thumb_maxwidht', 'post', 0 );
	$array_setting['thumb_maxheight'] = $nv_Request->get_int( 'thumb_maxheight', 'post', 0 );
	
	$array_setting['home_cat_numphotos'] = $nv_Request->get_int( 'home_cat_numphotos', 'post', 0 );
    $array_setting['view_cat_numphotos'] = $nv_Request->get_int( 'view_cat_numphotos', 'post', 0 );
	$array_setting['view_all_numphotos'] = $nv_Request->get_int( 'view_all_numphotos', 'post', 0 );
	$array_setting['view_album_numphotos'] = $nv_Request->get_int( 'view_album_numphotos', 'post', 0 );
	$array_setting['view_numalbums'] = $nv_Request->get_int( 'view_numalbums', 'post', 0 );
	
	$array_setting['member_post'] = $nv_Request->get_int( 'member_post', 'post', 0 );
	$array_setting['home_category'] = $nv_Request->get_typed_array( 'home_category', 'post', 'string' );
	$array_setting['home_album'] = $nv_Request->get_typed_array( 'home_album', 'post', 'string' );
	
	if ( $array_setting['maxfilesize'] <= 0 or $array_setting['maxfilesize'] > NV_UPLOAD_MAX_FILESIZE )
    {
        $array_setting['maxfilesize'] = NV_UPLOAD_MAX_FILESIZE;
    }
	$array_setting['upload_filetype'] = ( ! empty( $array_setting['upload_filetype'] ) ) ? implode( ',', $array_setting['upload_filetype'] ) : '';
	$array_setting['home_category'] = ( ! empty( $array_setting['home_category'] ) ) ? implode( ',', $array_setting['home_category'] ) : '';
	$array_setting['home_album'] = ( ! empty( $array_setting['home_album'] ) ) ? implode( ',', $array_setting['home_album'] ) : '';
	
	foreach ( $array_setting as $config_name => $config_value )
    {
        $query = "REPLACE INTO `" . NV_PREFIXLANG . "_" . $module_data . "_setting` VALUES (" . $db->dbescape( $config_name ) . "," . $db->dbescape( $config_value ) . ")";
        $db->sql_query( $query );
    }
	nv_del_moduleCache( $module_name );

    Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
    die();
}

//print_array( $setting );

$xtpl = new XTemplate( "setting.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'SETTING', $setting );
$xtpl->assign( 'MAX_SIZE', NV_UPLOAD_MAX_FILESIZE );
$xtpl->assign( 'HIDE_LINK', $setting['hide_real_imgurl'] == 1 ? 'checked' : '' );
$xtpl->assign( 'MEMBER_POST', $setting['member_post'] == 1 ? 'checked' : '' );

$file_type = explode( ',', $setting['upload_filetype'] );
$home_category = explode( ',', $setting['home_category'] );
$home_album = explode( ',', $setting['home_album'] );

foreach( $upload_file_type as $uftype )
{
	$xtpl->assign( 'UPLOAD_TYPE', array( 'name' => $uftype, 'checked' => in_array( $uftype, $file_type ) ? 'checked' : '' ) );
	$xtpl->parse( 'main.filetype' );
}

foreach( $comment_type as $key => $ctype )
{
	$xtpl->assign( 'CALBUM', array( 'name' => $ctype, 'value' => $key, 'selected' => ( $key == $setting['comment_album'] ) ? 'selected' : '' ) );
	$xtpl->assign( 'CPHOTO', array( 'name' => $ctype,  'value' => $key, 'selected' => ( $key == $setting['comment_photos'] ) ? 'selected' : '' ) );
	$xtpl->parse( 'main.albumcomment' );
	$xtpl->parse( 'main.photocomment' );
}

if( !empty( $allcats ) )
{
	foreach( $allcats as $catid => $cat )
	{
		in_array( $catid, $home_category ) ? $cat['checked'] = "checked" : $cat['checked'] = '';
		$xtpl->assign( 'CAT', $cat );
		$xtpl->parse( 'main.listcat' );
	}
}
if( !empty( $allalbs ) )
{
	foreach( $allalbs as $albid => $album )
	{
		in_array( $albid, $home_album ) ? $album['checked'] = "checked" : $album['checked'] = '';
		$xtpl->assign( 'ALB', $album );
		$xtpl->parse( 'main.listalb' );
	}
}
	

$xtpl->parse( 'main' );
$contents .= $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>