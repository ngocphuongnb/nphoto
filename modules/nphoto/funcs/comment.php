<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 3-6-2010 0:14
 */

if( ! defined( 'NV_IS_MOD_NPHOTO' ) ) die( 'Stop!!!' );
if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

$contents = "";
$id = $nv_Request->get_int( 'id', 'get', 0 );
$checkss = $nv_Request->get_string( 'checkss', 'get', '' );
$page = $nv_Request->get_int( 'page', 'get', 0 );
$cmable = $nv_Request->get_string( 'cmable', 'get', '0' );
$level = $nv_Request->get_string( 'level', 'get', '' );
$cmcount = $nv_Request->get_int( 'cmcount', 'get', 0 );
$action = $nv_Request->get_string( 'action', 'get', '' );
$type = $nv_Request->get_string( 'type', 'get', '' );
$is_theater = $nv_Request->get_int( 'is_theater', 'get', 0 );

if( $cmable and $id > 0 and $checkss == md5( $id . session_id() . $global_config['sitekey'] ) )
{
	if( $action == "showform" )
	{
		$contents = np_show_comment_form( $type, $id, $level, $cmcount, $cmable, $is_theater );
	}
	else
	{
		$comment_array = np_comment( $type, $id, $cmable, $page );
		( $is_theater == 1 ) ? ( $comment_array['ver'] = 1 ) : ( $comment_array['ver'] = 0 );
		$contents = comment_theme( $type, $id, array( 'cmable' => $cmable, 'voteable' => 0 ), $comment_array );
	}
}
include ( NV_ROOTDIR . "/includes/header.php" );
echo $contents;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>