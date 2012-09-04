<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/31/2009 2:29
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

$contents = '';
$page = $nv_Request->get_int( 'page', 'get,post', 0 );
$catid = $nv_Request->get_int( 'catid', 'get,post', 0 );
$albid = $nv_Request->get_int( 'albid', 'get,post', 0 );
$npo = $nv_Request->get_string( 'np', 'get' );
//( $catid == 0 ) ? ( $where = 'photos' ) : ( $where = $catid );
if( $npo == '' ) $npo = 'photos';
$where = $npo;
if( $catid > 0 )
{
	$where = $catid;
}

$q = $nv_Request->get_string( 'q', 'get' );
$by = $nv_Request->get_string( 'by', 'get');
$limit = $nv_Request->get_int( 'limit', 'get', 0 );
$orderby = $nv_Request->get_string( 'orderby', 'get' );
$order = $nv_Request->get_string( 'order', 'get' );
if( empty( $order ) ) $order = 'desc';

if( $limit == 0 or $limit > 30 ) $limit = 30;
if( $orderby == '' ) $orderby = 'pid';
( $albid > 0 ) ? ( $other_condition = " `albumid`=" . intval( $albid ) ) : ( $other_condition = '' );
$per_page = $limit;
$photos = $np->seachItems( $where, 'pid', $by, $q, $limit, $page, $orderby, $order, $other_condition );

$result_all = $db->sql_query( "SELECT FOUND_ROWS()" );
list( $all_page ) = $db->sql_fetchrow( $result_all );

$op_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;
$main_url = $op_url . "&amp;catid=" . $catid . "&amp;albid=" . $albid . "&amp;q=" . $q . "&amp;by=" . $by . "&amp;limit=" . $limit . "&amp;page=" . $page;
$base_url = $op_url . "&amp;catid=" . $catid . "&amp;albid=" . $albid . "&amp;q=" . $q . "&amp;by=" . $by . "&amp;limit=" . $limit . "&amp;orderby=" . $orderby . "&amp;order=" . $order;

$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );

$xtpl = new XTemplate( "main.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_NAME_VARIBLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'NPO', $npo );
$xtpl->assign( 'page', $page );
$xtpl->assign( 'limit', $limit );
$xtpl->assign( 'q', $q );
$xtpl->assign( 'orderby', $orderby );
$xtpl->assign( 'order', ( $order == 'asc' ) ? 'desc' : 'asc' );
$xtpl->assign( 'main_url', $main_url );
$xtpl->assign( 'mainurl', $op_url . "&amp;np=photos" );
$xtpl->assign( 'trash_url', $op_url . "&amp;np=0" );
$xtpl->assign( 'found', $all_page );
$xtpl->assign( 'ACT_MAIN', ( $npo == 'photos' ) ? 'btn-danger' : 'btn-success' );
$xtpl->assign( 'ACT_TRASH', ( $npo == '0' ) ? 'btn-danger' : 'btn-success' );

if( ! empty( $array_cat_list ) )
{	
	$array_cat_list[0] = "Tất cả chủ đề";
	foreach( $array_cat_list as $catid_i => $title_i )
	{
		$listcat[] = $data = array(
				"catid" => $catid_i,
				"selected" => ( $catid_i == $catid ) ? " selected=\"selected\"" : "",
				"title" => "Chủ đề: " . $title_i );
		$xtpl->assign( 'listcat', $data );
		$xtpl->parse( 'main.search.listcat' );
	}
	$listcat[0] = array( 'catid' => 0, 'title' => "Chuyển file đã chọn vào thùng rác" );
	foreach( $listcat as $data )
	{
		$xtpl->assign( 'listcat', $data );
		$xtpl->parse( 'main.photo.listcat' );
	}
}

if( !empty( $array_alb_list ) )
{
	$array_alb_list[0] = "Tất cả album";
	foreach( $array_alb_list as $albid_i => $title_i )
	{
		$listalb[] = $data = array(
				"albid" => $albid_i,
				"selected" => ( $albid_i == $albid ) ? " selected=\"selected\"" : "",
				"title" => "Album: " . $title_i );
		$xtpl->assign( 'listalb', $data );
		$xtpl->parse( 'main.search.listalb' );
	}
	$listalb[0] = array( 'albid' => 0, 'title' => "Thêm file đã chọn vào album ..." );
	foreach( $listalb as $data )
	{
		$xtpl->assign( 'listalb', $data );
		$xtpl->parse( 'main.photo.listalb' );
	}
}

$seartype = array(
					array( 'key' => 'title', 'value' => 'Tiêu đề' ),
					array( 'key' => 'alt', 'value' => 'Alternative text' ),
					array( 'key' => 'filename', 'value' => 'Tên file' ),
					array( 'key' => 'filepath', 'value' => 'Thư mục lưu file' ),
					array( 'key' => 'filetype', 'value' => 'Loại file' )
				  );
				  
foreach( $seartype as $stype )
{
	( $stype['key'] == $by ) ? $stype['selected'] = 'selected' : $stype['selected'] = '';
	$xtpl->assign( 'stype', $stype );
	$xtpl->parse( 'main.search.stype' );
}

$xtpl->parse( 'main.search' );

if( !empty( $photos ) )
{
	$i = 0;
	foreach( $photos as $photoid => $photo )
	{
		++$i;
		( $i % 2 == 0 ) ? ( $class = 'second' ) : ( $class = '' );
		$photo['src'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $photo['filepath'] . '/thumbs/' . $photo['filename'];
		( $photo['catid'] > 0 ) ? $photo['category'] = $allcats[$photo['catid']]['title'] : $photo['category'] = 'N/A';
		( $photo['albumid'] > 0 ) ? $photo['album'] = $allalbs[$photo['albumid']]['title'] : $photo['album'] = 'N/A';
		$xtpl->assign( 'i', $i );
		$xtpl->assign( 'CLASS', $class );
		$xtpl->assign( 'PHOTO', $photo );
		$xtpl->parse( 'main.photo.loop' );
	}
	$xtpl->parse( 'main.photo' );
}	

if( ! empty( $generate_page ) )
{
	$xtpl->assign( 'GENERATE_PAGE', $generate_page );
	$xtpl->parse( 'main.generate_page' );
}	

$xtpl->parse( 'main' );
$contents .= $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>