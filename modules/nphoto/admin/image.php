<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/31/2009 2:29
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$contents = '';
$type = $nv_Request->get_string( 'type', 'post,get', '' );
$pid = $nv_Request->get_int( 'pid', 'post,get', 0 );
$formid = $nv_Request->get_int( 'formid', 'post,get', 0 );
$save = $nv_Request->get_int( 'save', 'post,get', 0 );
$colspan = $nv_Request->get_int( 'colspan', 'post,get', 0 );

if( $save > 0 and $pid > 0 )
{
	$image['pid'] = $pid;
	$image['allowed_rating'] = $nv_Request->get_int( 'allowed_rating', 'post', 0 );
	$image['allowed_comm'] = $nv_Request->get_int( 'allowed_comm', 'post', 0 );
	$image['catid'] = $nv_Request->get_int( 'catid', 'post', 0 );
	$current_catid = $nv_Request->get_int( 'current_catid', 'post', 0 );
	$image['albumid'] = $nv_Request->get_int( 'albid', 'post', 0 );
	$image['title'] = filter_text_input( 'title', 'post', '', 1 );
	$image['meta_title'] = filter_text_input( 'meta_title', 'post', '', 1 );
	$image['meta_keywords'] = filter_text_input( 'meta_keywords', 'post', '' );
	$image['meta_description'] = filter_text_input( 'meta_description', 'post', '', 1 );
	$image['alt'] = filter_text_input( 'alt', 'post', '', 1 );
	$image['who_view'] = $nv_Request->get_int( 'who_view', 'post', 0 );
	$groups_view = "";

	$groups = $nv_Request->get_typed_array( 'groups_view', 'post', 'int', array() );
	$groups = array_intersect( $groups, array_keys( $groups_list ) );
	$image['groups_view'] = implode( ",", $groups );
	
	$result = $np->updateItem( 'photos', $image, 'pid' );
	if( $result )
	{
		$np->transferData( '', $pid, $current_catid, $image['catid'], 1, 'photos' );
		echo 'ok_' . $formid . '_' . $pid . '_' . $colspan;
	}
	else 
	{
		echo 'fail';
	}
	die();
}

$photo = array( 'pid' => 0, 'catid' => 0, 'albid' => 0, 'title' => '', 'meta_title' => '', 'meta_keywords' => '', 'meta_description' => '', 'alt' => '', 'allowed_comm' => 1, 'allowed_rating' => 1, 'add_time' => NV_CURRENTTIME, 'edit_time' => NV_CURRENTTIME, 'who_view' => 0, 'groups_view' => '', 'status' => 1 );

if( $pid > 0 );
{
	$photo = $np->getItems( 'photos', 'pid', 'pid', $pid );
	$photo = $photo[$pid];
}

$xtpl = new XTemplate( "image.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

if( in_array( $type, array( 'album', 'photo' ) ) and ! empty( $array_cat_list ) )
{
	$array_cat_list[0] = "Chọn chủ đề";
	foreach( $array_cat_list as $catid => $title )
	{
		( $photo['catid'] == $catid ) ? $selected = "selected" : $selected = "";
		$xtpl->assign( 'CAT', array( 'id' => $catid, 'title' => $title, 'selected' => $selected ) );
		$xtpl->parse( 'main.catlist.loop' );
	}
	$xtpl->assign( 'label', "Chọn chủ đề" );	
	$xtpl->parse( 'main.catlist' );	
}
if( in_array( $type, array( 'category', 'photo' ) ) and !empty( $allalbs ) )
{
	$allalbs[0] = array( 'id' => 0, 'title' => 'Chọn album');
	foreach( $allalbs as $albid => $album )
	{
		( $photo['albumid'] == $albid ) ? $selected = "selected" : $selected = "";
		$xtpl->assign( 'ALB', array( 'id' => $albid, 'title' => $album['title'], 'selected' => $selected ) );
		$xtpl->parse( 'main.alblist.loop' );
	}
	$xtpl->assign( 'label', "Chọn album" );	
	$xtpl->parse( 'main.alblist' );
}

$who_views = array();
foreach( $array_who_view as $k => $w )
{
	$who_views[] = array(
		"value" => $k,
		"selected" => ( $photo['who_view'] == $k ) ? " selected=\"selected\"" : "",
		"title" => $w );
}

$groups_views = array();
$photo['groups_view'] = explode( ",", $photo['groups_view'] );
foreach( $groups_list as $group_id => $grtl )
{
	$groups_views[] = array(
		"value" => $group_id,
		"checked" => in_array( $group_id, $photo['groups_view'] ) ? " checked=\"checked\"" : "",
		"title" => $grtl );
}

$views = configdata( $who_views, $groups_views, $photo['who_view'] );
( $photo['allowed_comm'] == 1 ) ? $photo['allowed_comm'] = 'checked="checked"' : $photo['allowed_comm'] = "";
( $photo['allowed_rating'] == 1 ) ? $photo['allowed_rating'] = "checked=\"checked\"" : $photo['allowed_rating'] = "";
$photo['src'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $photo['filepath'] . '/thumbs/' . $photo['filename'];
$xtpl->assign( 'PHOTO', $photo );
$xtpl->assign( 'views', $views );
$xtpl->assign( 'formid', $formid );
$xtpl->assign( 'colspan', $colspan );
$xtpl->parse( 'main' );
$contents .= $xtpl->text( 'main' );

echo $contents;
die();

?>