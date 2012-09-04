<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/31/2009 2:29
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

$contents = '';
$pid = 0;
$imgdata = array();
$type = filter_text_input( 'type', 'post', '' );
$typeid = $nv_Request->get_int( 'typeid', 'post', 0 );

if( $type == 'category' )
{
	$data = $allcats;
	$main = 'catid';
	$second = 'albumid';
	$condition = 'listcatid';
}
elseif( $type == 'album' )
{
	$data = $allalbs;
	$main = 'albumid';
	$second = 'catid';
	$condition = 'listalbid';
}

$np->CheckAdminAccess($condition, $typeid);
if( $np->status() == false )
{
	die('nperror*You cannot access this action');
}

require_once ( NV_ROOTDIR . "/includes/class/upload.class.php" );
$upload = new upload( $admin_info['allow_files_type'], $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, NV_MAX_WIDTH, NV_MAX_HEIGHT );

if( is_uploaded_file( $_FILES['file']['tmp_name'] ) )
{
	$upload_info = $upload->save_file( $_FILES['file'], NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_name . '/' . $type . '/' . $data[$typeid]['imgfolder'], false );
	if( empty( $upload_info['error'] ) )
	{
		$imgpath =  NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_name . '/' . $type . '/' . $data[$typeid]['imgfolder'] . '/' . $upload_info['basename'];
		$thumbpath = NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_name . '/' . $type . '/' . $data[$typeid]['imgfolder'] . '/thumbs/';
		if( !file_exists( $thumbpath . '/' . $upload_info['basename'] ) )
		{
			$imgdata['thumbpath'] = createthumb( $imgpath, $thumbpath, '', 180, 180 );
		}	
		$src = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $type . '/' . $data[$typeid]['imgfolder'] . '/' . $upload_info['basename'];
		$lu = strlen( '.' . $upload_info['ext'] );
		$alias = substr( $upload_info['basename'], 0, -$lu );
		
		$alias = np_get_alias( 'photos', 0, $alias );
		
		$imgdata['pid'] = 'NULL';
		$imgdata['filename'] = $upload_info['basename'];
		$imgdata['filetype'] = $upload_info['mime'];
		$imgdata['filepath'] = $type . '/' . $data[$typeid]['imgfolder'];
		$imgdata[$main] = intval( $typeid );
		$imgdata[$second] = 0;
		$imgdata['alias'] = $imgdata['title'] = $alias;
		$imgdata['img_size'] = $upload_info['img_info'][0] . '-' . $upload_info['img_info'][1];
		$pid = $np->addItem( 'photos', $imgdata );
		if( $type == 'category' and $pid > 0 )
		{
			$imgdata['pid'] = $pid;
			$np->addItem( $typeid, $imgdata );
		}
	}
}
else
{
	$urlfile = trim( $nv_Request->get_string( 'fileurl', 'post' ) );
	$upload_info = $upload->save_urlfile( $urlfile, NV_ROOTDIR . '/' . NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_name, false );
}

if( ! empty( $upload_info['error'] ) )
{
	die( "nperror*" . $upload_info['error'] . "*" );
}
else
{
	
	die( "success*Upload thành công*" . $pid . "*" . $src );
}

?>