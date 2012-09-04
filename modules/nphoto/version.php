<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 05/07/2010 09:47
 */
 
if( !defined( 'NV_ADMIN' ) or  !defined( 'NV_MAINFILE' ) ) die( 'stop!!!' );

$module_version = array(
	'name' => 'nphoto',
	"modfuncs" => "main,album,photo", //
	"submenu" => "album,photo", //
	"is_sysmod" => 0, //
	"virtual" => 0, //
	"version" => "1.0.00", //
	"date" => "Wed, 25 Jul 2012 18:48:00 GMT", //
	"author" => "Nguyen Ngoc Phuong ( nguyenngocphuongnb@gmail.com )", //
	"note" => "", //
	"uploads_dir" => array( $module_name, 
							$module_name . '/' . 'category', 
							$module_name . '/' . 'album', 
							$module_name . '/' . 'thumbs',
							$module_name . '/' . 'thumbs/category',
							$module_name . '/' . 'thumbs/album' )
);