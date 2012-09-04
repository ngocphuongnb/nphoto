<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/31/2009 2:29
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

$id = $nv_Request->get_int( 'id', 'get', 0 );
$contents = $data = '';
$checked = array();
$action = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;

if( $nv_Request->get_int( 'save', 'post' ) == '1' )
{
	$data['userid'] = $nv_Request->get_int( 'userid', 'post', 0 );
	$data['add_cat'] = $nv_Request->get_int( 'add_cat', 'post', 0 );
	$data['del_cat'] = $nv_Request->get_int( 'del_cat', 'post', 0 );
	$data['add_album'] = $nv_Request->get_int( 'add_album', 'post', 0 );
	$data['del_album'] = $nv_Request->get_int( 'del_album', 'post', 0 );
	$data['addphoto'] = $nv_Request->get_int( 'addphoto', 'post', 0 );
	$new_catids = array_unique( $nv_Request->get_typed_array( 'listcatid', 'post', 'int', array() ) );
	$data['listcatid'] = implode( ',', $new_catids );
	$new_albids = array_unique( $nv_Request->get_typed_array( 'listalbid', 'post', 'int', array() ) );
	$data['listalbid'] = implode( ',', $new_albids );
	$old_catids = filter_text_input( 'old_catids', 'post', '', 1 );
	$old_albids = filter_text_input( 'old_albids', 'post', '', 1 );
	
	if( $data['userid'] > 0 )
	{
		$np->updateItem( 'admins', $data, 'userid' );
		if( $db->sql_affectedrows() > 0 )
		{
			$np->setAdminRoll( $new_catids, $old_catids, 'category', 'adminids', 'catid', $data['userid'] );
			$np->setAdminRoll( $new_albids, $old_albids, 'album', 'adminids', 'albid', $data['userid'] );
			
			$db->sql_freeresult();
			nv_insert_logs( NV_LANG_DATA, $module_name, 'sửa quyền admin module', $data['userid'], $admin_info['userid'] );
		}
	}
	if( empty( $np->error ) )
	{
		//Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&id=" . $data['userid'] );
		//die();
	}
}

$xtpl = new XTemplate( "adminroll.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'FORM_ACTION', $action );


foreach( $globalAdmins as $adminid => $admin )
{
	$xtpl->assign( 'adminID', $adminid );
	$xtpl->assign( 'adminName', empty( $admin['fullname'] ) ? $admin['login'] : $admin['fullname'] );
	$xtpl->assign( 'link', $action . '&amp;id=' . $adminid );
	$xtpl->parse( 'main.adminlist' );
}

if( $id > 0 )
{
	$admin = $adminModuleData[$id];
	if( !empty( $allcats ) )
	{
		foreach( $allcats as $catid => $cat )
		{
			in_array( $catid, $admin['listcatid'] ) ? $cat['checked'] = "checked" : $cat['checked'] = '';
			$xtpl->assign( 'CAT', $cat );
			$xtpl->parse( 'main.content.listcat' );
		}
	}
	if( !empty( $allalbs ) )
	{
		foreach( $allalbs as $albid => $album )
		{
			in_array( $albid, $admin['listalbid'] ) ? $album['checked'] = "checked" : $album['checked'] = '';
			$xtpl->assign( 'ALB', $album );
			$xtpl->parse( 'main.content.listalb' );
		}
	}
	( $admin['add_cat'] == 1 ) ? ( $checked['add_cat'] = 'checked' ) : ( $checked['add_cat'] = '' );
	( $admin['del_cat'] == 1 ) ? ( $checked['del_cat'] = 'checked' ) : ( $checked['del_cat'] = '' );
	( $admin['add_album'] == 1 ) ? ( $checked['add_album'] = 'checked' ) : ( $checked['add_album'] = '' );
	( $admin['del_album'] == 1 ) ? ( $checked['del_album'] = 'checked' ) : ( $checked['del_album'] = '' );
	( $admin['addphoto'] == 1 ) ? ( $checked['addphoto'] = 'checked' ) : ( $checked['addphoto'] = '' );
	$admin['listcatid'] = implode( ',', $admin['listcatid'] );
	$admin['listalbid'] = implode( ',', $admin['listalbid'] );
	
	$xtpl->assign( 'CHECKED', $checked );
	$admin['name'] = empty( $globalAdmins[$id]['fullname'] ) ? $globalAdmins[$id]['login'] : $globalAdmins[$id]['fullname'];
	$xtpl->assign( 'ADMIN', $admin );
	$xtpl->parse( 'main.content' );
}
else
{
	$np->information[] = "Lựa chọn một admin từ danh sách để sửa quyền";
	$np->status();
}
$xtpl->parse( 'main' );
$contents .= $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>