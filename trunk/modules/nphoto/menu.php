<?php

/**
 * @Project NUKEVIET 3.1
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2011 VINADES.,JSC. All rights reserved
 * @Createdate 21-04-2011 11:17
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$arr_cat[] = array(
	'module' => $module, //
	'key' => 'cat', //
	'title' => 'Chủ đề', //
	'alias' => 'category',  //
);
$arr_cat[] = array(
	'module' => $module, //
	'key' => 'album', //
	'title' => 'Album', //
	'alias' => 'album',  //
);
$arr_cat[] = array(
	'module' => $module, //
	'key' => 'photo', //
	'title' => 'Photo', //
	'alias' => 'photo',  //
);

?>