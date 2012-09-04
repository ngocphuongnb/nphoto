<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$albs = array( 'albid' => 0, 'parentid' => 0, 'adminids' => '1', 'image' => '', 'thumbpath' => '', 'title' => '', 'alias' => '', 'meta_title' => '', 'meta_keywords' => '', 'meta_description' => '', 'tags_cloud' => '', 'bodytext' => '', 'allowed_comm' => 1, 'allowed_rating' => 1, 'add_time' => NV_CURRENTTIME, 'edit_time' => NV_CURRENTTIME, 'who_view' => 0, 'groups_view' => '', 'status' => 1 );

$status = $alias = $log_action = $hook = "";
$action = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;
$albid = $nv_Request->get_int( 'albid', 'get', 0 );

if( $albid > 0 )
{
	$np->CheckAdminAccess('listalbid', $albid);
	if( $np->status() )
	{
		$albs = $np->getItems( 'album', 'albid', 'albid', $albid );
		$albs = $albs[$albid];
	}
}

if( $nv_Request->get_int( 'savealb', 'post' ) == '1' )
{
	$albdata['albid'] = $nv_Request->get_int( 'albid', 'post', 0 );
	$albdata['title'] = filter_text_input( 'title', 'post', '', 1 );
	$albdata['alias'] = filter_text_input( 'alias', 'post', '', 1 );
	$albdata['image'] = filter_text_input( 'image', 'post', '' );
	$albdata['meta_title'] = filter_text_input( 'meta_title', 'post', '', 1 );
	$albdata['meta_keywords'] = filter_text_input( 'meta_keywords', 'post', '', 1 );
	$albdata['tags_cloud'] = $albdata['meta_keywords'];
	$albdata['meta_description'] = filter_text_input( 'meta_description', 'post', '', 1 );
	$albdata['who_view'] = $nv_Request->get_int( 'who_view', 'post', 0 );
	$groups_view = "";
	
	if( ! nv_is_url( $albdata['image'] ) and file_exists( NV_DOCUMENT_ROOT . $albdata['image'] ) )
	{
		$lu = strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" );
		$albdata['image'] = substr( $albdata['image'], $lu );
	}

	$groups = $nv_Request->get_typed_array( 'groups_view', 'post', 'int', array() );
	$groups = array_intersect( $groups, array_keys( $groups_list ) );
	$albdata['groups_view'] = implode( ",", $groups );
	$oldAdminArray = filter_text_input( 'old_admins', 'post', '', 1 );
	if( empty( $albdata['alias'] ) ) $albdata['alias'] = change_alias( $albdata['title'] );
	
	$newAdminArray = array_unique( $nv_Request->get_typed_array( 'adminids', 'post', 'int', array() ) );
	$albdata['adminids'] = implode( ',', $newAdminArray );
	
	if( empty( $albdata['title'] ) ) $np->error[] = "Chưa có tiêu đề";
	if( $albdata['albid'] > 0 )
	{
		$np->CheckAdminAccess('listalbid', $albdata['albid'] );
		$albdata['imgfolder'] = strtolower( $albdata['alias'] );
		$np->updateItem( 'album', $albdata, 'albid' );
		if( $db->sql_affectedrows() > 0 )
		{
			$np->setAdminRoll( $newAdminArray, $oldAdminArray, 'admins', 'listalbid', 'userid', $albdata['albid'] );
			$db->sql_freeresult();
		}
		$log_action = "Sửa album";
	}
	else
	{
		$np->CheckAdminAccess('add_album', 0 );
		$albdata['albid'] = 'NULL';
		$albdata['imgfolder'] = strtolower( $albdata['alias'] );
		$newalbid = $np->addItem( 'album', $albdata );
		if( (int) $newalbid > 0 )
		{
			$np->setAdminRoll( $newAdminArray, '', 'admins', 'listalbid', 'userid', $newalbid );
			$log_action = "Thêm album";
		}
	}
	if( !empty( $log_action ) )
	{
		$imgpath =  NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_name . '/' . $albdata['image'];
		$thumbpath = NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_name . '/thumbs/album/';
		if( ! is_dir( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/album/' . $albdata['alias'] ) )
		{
			nv_mkdir( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/album/', $albdata['alias'] );
			nv_loadUploadDirList( false );			
		}
		if( ! is_dir( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/album/' . $albdata['alias'] . '/thumbs' ) )
		{
			nv_mkdir( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/album/' . $albdata['alias'] . '/', 'thumbs' );
			nv_loadUploadDirList( false );
		}
		$albdata['imgfolder'] = strtolower( $albdata['alias'] );
		if( $albdata['image'] != '' and !file_exists( $thumbpath . '/' . $albdata['image'] ) )
		{
			$albdata['thumbpath'] = createthumb( $imgpath, $thumbpath, '', 180, 180 );
		}
		$np->updateItem( 'album', $albdata, 'albid' );
		nv_insert_logs( NV_LANG_DATA, $module_name, $log_action, $albdata['title'], $admin_info['userid'] );
	}	
	nv_del_moduleCache( $module_name );
	if( empty( $np->error ) and empty( $np->warning ) )
	{
		//Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
		//die();
		$np->success[] = "Lưu album thành công";
		$np->status();
		//redriect ( "", NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
	}
}

$albs['groups_view'] = explode( ",", $albs['groups_view'] );
	
$who_views = array();
foreach( $array_who_view as $k => $w )
{
	$who_views[] = array(
		"value" => $k,
		"selected" => ( $albs['who_view'] == $k ) ? " selected=\"selected\"" : "",
		"title" => $w );
}

$groups_views = array();
foreach( $groups_list as $group_id => $grtl )
{
	$groups_views[] = array(
		"value" => $group_id,
		"checked" => in_array( $group_id, $albs['groups_view'] ) ? " checked=\"checked\"" : "",
		"title" => $grtl );
}

$xtpl = new XTemplate( "album.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'FORM_ACTION', $action );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'UPLOADS_DIR', NV_UPLOADS_DIR );
$xtpl->assign( 'CURRENT_DIR', NV_UPLOADS_DIR . '/' . $module_name );

if( !empty( $allalbs ) )
{
	$i = 1;
	foreach( $allalbs as $albid => $alb )
	{
		$alb['edit_url'] = $action . "&amp;albid=" . $alb['albid'];
		$alb['del_url'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=delete&amp;type=alb&amp;albid=" . $alb['albid'];
		$alb['addphoto_url'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=addphoto&amp;type=album&amp;typeid=" . $alb['albid'];
		( $i%2 == 0 ) ? ( $alb['class'] = 'class="second"' ) : ( $alb['class'] = '' );
		$xtpl->assign( 'LISTALB', $alb );
		$xtpl->parse( 'main.listalb.loop' );
		++$i;
	}
	$xtpl->parse( 'main.listalb' );
}

if( empty( $alias ) )
{
	$xtpl->parse( 'main.content.getalias' );
}

$hook .= custom_metatag( $albs );
$hook .= configdata( $who_views, $groups_views, $albs['who_view'] );
$hook .= setAdmin( $albs );

$xtpl->assign( 'ALB', $albs );
$xtpl->assign( 'HOOK', $hook );
$xtpl->parse( 'main.content' );
$xtpl->parse( 'main' );
$contents .= $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>