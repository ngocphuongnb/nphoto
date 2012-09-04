<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/31/2009 2:29
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );
require_once ( NV_ROOTDIR . "/modules/" . $module_file . "/nphoto.class.php" );
require_once ( NV_ROOTDIR . "/modules/" . $module_file . "/global.functions.php" );

$admin_id = $admin_info['admin_id'];

$array_who_view = array( 
    $lang_global['who_view0'], $lang_global['who_view1'], $lang_global['who_view2'], $lang_global['who_view3'] 
);
$array_allowed_comm = array( 
    $lang_global['no'], $lang_global['who_view0'], $lang_global['who_view1'] 
);
$array_cat_list = array();

$np = new nphoto;

$globalAdmins = globalAdmins();
$adminModuleData = AdminModuleData();
//$allcats = $np->getItems( 'category', 'catid', '', 0, 0, '', 'order', 'ASC' );
//$allalbs = $np->getItems( 'album', 'albid' );
$groups_list = nv_groups_list();

if ( defined( 'NV_IS_SPADMIN' ) )
{
    define( 'NV_IS_ADMIN_MODULE', true );
    define( 'NV_IS_ADMIN_FULL_MODULE', true );
	$array_cat_list[0] = "Là chủ đề chính";
}
else
{
    if ( isset( $adminModuleData[$admin_id] ) )
    {
        define( 'NV_IS_ADMIN_MODULE', true );
        if ( intval( $adminModuleData[$admin_id]['admin'] ) == 2 )
        {
            define( 'NV_IS_ADMIN_FULL_MODULE', true );
        }
    }
}

foreach( $allcats as $catid_i => $array_value )
{
	$lev_i = $array_value['lev'];
	if( defined( 'NV_IS_ADMIN_MODULE' ) and in_array( $catid_i, $adminModuleData[$admin_id]['listcatid'] ) )
	{
		$xtitle_i = "";
		if( $lev_i > 0 )
		{
			$xtitle_i .= "&nbsp;&nbsp;&nbsp;|";
			for( $i = 1; $i <= $lev_i; ++$i )
			{
				$xtitle_i .= "---";
			}
			$xtitle_i .= ">&nbsp;";
		}
		$xtitle_i .= $array_value['title'];
		$array_cat_list[$catid_i] = $xtitle_i;
	}
}

foreach( $allalbs as $albid_i => $array_value )
{
	if( defined( 'NV_IS_ADMIN_MODULE' ) and in_array( $albid_i, $adminModuleData[$admin_id]['listalbid'] ) )
	{
		$array_alb_list[$albid_i] = $array_value['title'];
	}
}

$submenu['category'] = 'Quản lý chủ đề';
$submenu['album'] = 'Quản lý album';

$allow_func = array( 'main', 'category', 'alias', 'delete', 'album', 'image' );

if( $np->CheckAdminAccess('addphoto', $admin_id) )
{
	$submenu['addphoto'] = "Thêm ảnh";
	$allow_func[] = 'addphoto';
	$allow_func[] = 'upload_handler';
}

if( defined( 'NV_IS_GODADMIN' ) )
{
	$submenu['adminroll'] = "Quyền admin";
	$submenu['setting'] = "Cài đặt";
	$allow_func[] = 'adminroll';
	$allow_func[] = 'setting';
}

function np_create_CatDataTable( $catid )
{
	global $db, $module_name, $module_data;
	
    $db->sql_query( "SET SQL_QUOTE_SHOW_CREATE = 1" );
    $result = $db->sql_query( "SHOW CREATE TABLE `" . NV_PREFIXLANG . "_" . $module_data . "_photos`" );
    $show = $db->sql_fetchrow( $result );
    $db->sql_freeresult( $result );
    $show = preg_replace( '/(KEY[^\(]+)(\([^\)]+\))[\s\r\n\t]+(USING BTREE)/i', '\\1\\3 \\2', $show[1] );
    $sql = preg_replace( '/(default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP|DEFAULT CHARSET=\w+|COLLATE=\w+|character set \w+|collate \w+|AUTO_INCREMENT=\w+)/i', ' \\1', $show );
    $sql = str_replace( NV_PREFIXLANG . "_" . $module_data . "_photos", NV_PREFIXLANG . "_" . $module_data . "_" . $catid, $sql );
    $db->sql_query( $sql );
    $db->sql_query( "TRUNCATE TABLE " . NV_PREFIXLANG . "_" . $module_data . "_" . $catid . "" );
}

function AdminModuleData()
{
	global $admin_id, $np, $module_info, $db, $globalAdmins, $module_data;
	$adminData = $np->getItems( 'admins', 'userid' );
	$global_AdminData = array();

	if ( ! empty( $globalAdmins ) )
	{
		foreach ( $globalAdmins as $userid_i => $admin )
		{
			if ( ! isset( $adminData[$userid_i] ) )
			{
				$db->sql_query( "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_admins` (`userid`, `listcatid`, `listalbid`, `admin`, `add_cat`, `add_album`, `del_cat`, `del_album`, `addphoto`) VALUES ('" . $userid_i . "', '', '', '0', '0', '0', '0', '0', '1')" );
			}
		}
	}
	
	foreach( $adminData as $adminid => $admin )
	{
		if( isset( $admin['listcatid'] ) and !empty( $admin['listcatid'] ) ) 
		{
			$admin['listcatid'] = explode( ',', $admin['listcatid'] );
		}
		else
		{
			$admin['listcatid'] = array();
		}
		if( isset( $admin['listalbid'] ) and !empty( $admin['listalbid'] ) ) 
		{
			$admin['listalbid'] = explode( ',', $admin['listalbid'] );
		}
		else
		{
			$admin['listalbid'] = array();
		}
		$global_AdminData[$adminid] = $admin;
	}
	
	return $global_AdminData;
}
	
	

/**
 * nv_fix_cat_order()
 * 
 * @param integer $parentid
 * @param integer $order
 * @param integer $lev
 * @return
 */
function nv_fix_cat_order ( $parentid = 0, $order = 0, $lev = 0 )
{
    global $db, $db_config, $lang_module, $lang_global, $module_name, $module_data, $op;
    $query = "SELECT `catid`, `parentid` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_category` WHERE `parentid`=" . $parentid . " ORDER BY `weight` ASC";
    $result = $db->sql_query( $query );
    $array_cat_order = array();
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        $array_cat_order[] = $row['catid'];
    }
    $db->sql_freeresult();
    $weight = 0;
    if ( $parentid > 0 )
    {
        ++$lev;
    }
    else
    {
        $lev = 0;
    }
	//print_array( $array_cat_order );
    foreach ( $array_cat_order as $catid_i )
    {
        ++$order;
        ++$weight;
        $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_category` SET `weight`=" . $weight . ", `order`=" . $order . ", `lev`='" . $lev . "' WHERE `catid`=" . intval( $catid_i );
        $db->sql_query( $sql );
        $order = nv_fix_cat_order( $catid_i, $order, $lev );
    }
    $numsubcat = $weight;
    if ( $parentid > 0 )
    {
        $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_category` SET `numsubcat`=" . $numsubcat;
        if ( $numsubcat == 0 )
        {
            $sql .= ",`subcatids`=''";
        }
        else
        {
            $sql .= ",`subcatids`='" . implode( ",", $array_cat_order ) . "'";
        }
        $sql .= " WHERE `catid`=" . intval( $parentid );
        $db->sql_query( $sql );
    }
    return $order;
}

/**
 * GetCatidInParent()
 * 
 * @param mixed $catid
 * @return
 */
function GetCatidInParent ( $catid )
{
    global $allcats;
    $array_cat = array();
    $array_cat[] = $catid;
    $subcatid = explode( ",", $allcats[$catid]['subcatids'] );
    if ( ! empty( $subcatid ) )
    {
        foreach ( $subcatid as $id )
        {
            if ( $id > 0 )
            {
                if ( $allcats[$id]['numsubcat'] == 0 )
                {
                    $array_cat[] = $id;
                }
                else
                {
                    $array_cat_temp = GetCatidInParent( $id );
                    foreach ( $array_cat_temp as $catid_i )
                    {
                        $array_cat[] = $catid_i;
                    }
                }
            }
        }
    }
    return array_unique( $array_cat );
}

function globalAdmins()
{
	global $db, $module_info;
	
	if( !empty( $module_info['admins'] ) )
	{
		$sql = "SELECT t1.admin_id as id, t1.lev as level, t2.username as admin_login, t2.email as admin_email, t2.full_name as admin_fullname FROM 
`" . NV_AUTHORS_GLOBALTABLE . "` AS t1 INNER JOIN  `" . NV_USERS_GLOBALTABLE . "` AS t2 ON t1.admin_id  = t2.userid WHERE ( t1.lev!=0 AND t1.is_suspend=0 AND t1.lev IN(1,2) ) OR t1.admin_id IN (" . $module_info['admins'] . " )";
	}
	else
	{
		$sql = "SELECT t1.admin_id as id, t1.lev as level, t2.username as admin_login, t2.email as admin_email, t2.full_name as admin_fullname FROM 
`" . NV_AUTHORS_GLOBALTABLE . "` AS t1 INNER JOIN  `" . NV_USERS_GLOBALTABLE . "` AS t2 ON t1.admin_id  = t2.userid WHERE ( t1.lev!=0 AND t1.is_suspend=0 AND t1.lev IN(1,2) )";
	}
	$result = $db->sql_query( $sql );

	$adms = array();
	while( $row = $db->sql_fetchrow( $result ) )
	{
		$adms[$row['id']] = array(
			'login' => $row['admin_login'], //
			'fullname' => $row['admin_fullname'], //
			'email' => $row['admin_email'], //
			'level' => intval( $row['level'] ) //
		);
	}
	return $adms;
}

function custom_metatag( $data )
{
	global $global_config, $module_file;
	$xtpl = new XTemplate( "data.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
	$xtpl->assign( 'DATA', $data );
	$xtpl->parse( 'metatag' );
	return $xtpl->text( 'metatag' );
}

function setAdmin( $data )
{
	global $global_config, $module_file, $globalAdmins;
	
	$xtpl = new XTemplate( "data.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
	
	if( defined( 'NV_IS_GODADMIN' ) and !empty( $globalAdmins ) )
	{
		foreach( $globalAdmins as $adminid => $admin )
		{
			$checked = '';
			if( !empty( $data['adminids'] ) )
			{
				in_array( $adminid, explode( ',', $data['adminids'] ) ) ? $checked = "checked" : $checked = '';
			}
			$xtpl->assign( 'CHECKED', $checked );
			$xtpl->assign( 'adminID', $adminid );
			$xtpl->assign( 'adminName', empty( $admin['fullname'] ) ? $admin['login'] : $admin['fullname'] );
			$xtpl->parse( 'admins.loop' );
		}
		$xtpl->parse( 'admins' );
		return $xtpl->text( 'admins' );
	}
}

function configdata( $whos, $groups, $wv )
{
	global $global_config, $module_file, $globalAdmins;
	
	$xtpl = new XTemplate( "data.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
	
	foreach( $whos as $who )
	{
		$xtpl->assign( 'who_views', $who );
		$xtpl->parse( 'configdata.who_views' );
	}
	
	foreach( $groups as $group )
	{
		$xtpl->assign( 'groups_views', $group );
		$xtpl->parse( 'configdata.groups_views' );
	}
	$xtpl->assign( 'hidediv', $wv == 3 ? "visibility:visible;display:block;" : "visibility:hidden;display:none;" );
	$xtpl->parse( 'configdata' );
	return $xtpl->text( 'configdata' );
}

/**
 * redriect()
 * 
 * @param string $msg
 * @param mixed $nv_redirect
 * @return
 */
function redriect ( $msg, $nv_redirect )
{
    if ( empty( $nv_redirect ) )
    {
        $nv_redirect = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name;
    }
    $contents = $msg;
    $contents .= "<meta http-equiv=\"refresh\" content=\"1;url=" . $nv_redirect . "\" />";
    
    include ( NV_ROOTDIR . "/includes/header.php" );
    echo nv_admin_theme( $contents );
    include ( NV_ROOTDIR . "/includes/footer.php" );
    exit();
}

function createthumb( $imgpath, $thumbpath, $filename, $thumbwidth, $thumbheight )
{
	global $module_config, $module_name;
	if( file_exists( $imgpath ) )
	{
		require_once ( NV_ROOTDIR . "/includes/class/image.class.php" );
	
		$basename = basename( $imgpath );
		$image = new image( $imgpath, NV_MAX_WIDTH, NV_MAX_HEIGHT );
	
		$thumb_basename = $basename;
		$i = 1;
		while( file_exists( $thumbpath . '/' . $thumb_basename ) )
		{
			$thumb_basename = preg_replace( '/(.*)(\.[a-zA-Z]+)$/', '\1_' . $i . '\2', $basename );
			++$i;
		}
	
		$image->resizeXY( $thumbwidth, $thumbheight );
		$image->save( $thumbpath, $thumb_basename );
		$image_info = $image->create_Image_info;
		$thumb_name = str_replace( $thumbpath, '', $image_info['src'] );
	
		$image->close();
		return $thumb_name;
	}
	else return false;
}

function np_get_alias( $type, $id, $title )
{
	global $db, $module_data;
	
	$alias = change_alias( $title );
	
	if( $type == 'category' )
	{
		$key = 'catid';
	}
	elseif( $type == 'album' )
	{
		$key = 'albid';
	}
	else
	{
		$key = 'pid';
	}
	list( $nb ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $type . "` WHERE `" . $key . "`!=" . $id . " AND `alias`=" . $db->dbescape( $alias ) ) );
	if( ! empty( $nb ) )
	{
		$result = $db->sql_query( "SHOW TABLE STATUS WHERE `Name`=" . $db->dbescape( NV_PREFIXLANG . "_" . $module_data . "_" . $type ) );
		$item = $db->sql_fetch_assoc( $result );
		$db->sql_freeresult( $result );

		$alias .= "-" . $item['Auto_increment'];
	}
	return $alias;
}

function npdelphoto( $by, $value, $deletefile = 1, $moveto_trash = 0, $msg = 1, $get_from = 'photos' )
{
	global $db, $module_data, $module_name, $np;
	
	$photo = $np->getItems($get_from, $by, $by, $value);
	if( $photo[$value]['albumid'] > 0 ) $np->CheckAdminAccess('listalbid', $photo[$value]['albumid'] );
	if( $photo[$value]['catid'] > 0 ) $np->CheckAdminAccess('listcatid', $photo[$value]['catid'] );
	
	if( ( $value > 0 or !empty( $value ) ) and $np->status() )
	{
		if( empty( $np->warning ) and empty( $np->error ) and $deletefile == 1 and !empty( $photo[$value]['filepath'] ) and !empty( $photo[$value]['filename'] ) )
		{
			if( is_file( NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_name . '/' . $photo[$value]['filepath'] . '/' . $photo[$value]['filename'] ) )
			{
				@nv_deletefile( NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_name . '/' . $photo[$value]['filepath'] . '/' . $photo[$value]['filename'] );
			}
			if( is_file( NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_name . '/' . $photo[$value]['filepath'] . '/thumbs/' . $photo[$value]['filename'] ) )
			{
				@nv_deletefile( NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_name . '/' . $photo[$value]['filepath'] . '/thumbs/' . $photo[$value]['filename'] );
			}
		}
	}
	if( $np->deleteItem( $get_from, $by, $value) )
	{
		if( $moveto_trash == 0 ) $np->deleteItem( $photo[$value]['catid'], $by, $value);
		if( $msg == 1 ) $np->success[] = "- Xóa thành công ảnh " . $photo[$value]['title'];
		return true;
	}
	else
	{
		if( $msg == 1 ) $np->error[] = "- Không thể xóa ảnh " . $photo[$value]['title'];
		return false;
	}
}

function formatBytes($size, $precision = 2)
{
    $base = log($size) / log(1024);
    $suffixes = array('B', 'KB', 'MB', 'GB', 'TB');   

    return round(pow(1024, $base - floor($base)), $precision) .  ' ' . $suffixes[floor($base)];
}

define( 'NV_IS_FILE_ADMIN', true );

?>