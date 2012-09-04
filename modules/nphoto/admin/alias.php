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

$id = $nv_Request->get_int( 'id', 'post', 0 );
$mod = $nv_Request->get_string( 'mod', 'post', '' );

$alias = np_get_alias( $mod, $id, $title );

include ( NV_ROOTDIR . "/includes/header.php" );
echo $to . '_' . $alias;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>