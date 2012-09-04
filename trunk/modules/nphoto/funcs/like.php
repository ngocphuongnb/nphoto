<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 3-6-2010 0:14
 */

if( ! defined( 'NV_IS_MOD_NPHOTO' ) ) die( 'Stop!!!' );
//if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

$id = $nv_Request->get_int( 'id', 'post', 0 );
$cmid = $nv_Request->get_int( 'cmid', 'post', 0 );
$checkss = $nv_Request->get_string( 'checkss', 'post', '' );
$action = $nv_Request->get_string( 'action', 'post', '' );
$iscomment = $nv_Request->get_string( 'iscomment', 'post', '' );
$type = $nv_Request->get_string( 'type', 'post', '' );
$voteable = $nv_Request->get_int( 'voteable', 'post', 0 );

if( ( defined( 'NV_IS_USER' ) or defined( 'NV_IS_ADMIN' ) ) and $voteable > 0 and $id > 0 and $checkss == md5( $id . session_id() . $global_config['sitekey'] ) )
{
	$condition = 1;
	if( $type == 'photos' and empty( $iscomment ) )
	{
		$condition = "`pid`=" . intval( $id );
	}
	elseif( $type == 'album' and empty( $iscomment ) )
	{
		$condition = "`albid`=" . intval( $id );
	}
	elseif( !empty( $iscomment ) )
	{
		$condition = "`cid`=" . intval( $cmid ) . " AND `id`=" . intval( $id ) . " AND `status`=1";
	}
	$sql = "SELECT `user_like`, `user_dislike` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $iscomment . $type . "` WHERE " . $condition;
	$check_vote = $db->sql_fetch_assoc( $db->sql_query( $sql ) );
	if( !empty( $check_vote ) )
	{
		$userid = 0;
		if( defined( 'NV_IS_USER' ) )
		{
			$userid = $user_info['userid'];
		}
		elseif( defined( 'NV_IS_ADMIN' ) )
		{
			$userid = $admin_info['userid'];
		}
		
		( $action == 'like' ) ? ( $again_action = 'dislike' ) : ( $again_action = 'like' );
		$action_array = explode( ',', $check_vote['user_' . $action] );
		$again_action_array = explode( ',', $check_vote['user_' . $again_action] );
		
		if( !in_array( $userid, $action_array ) )
		{
			$again_action_cdt = $again_user_cdt = $restore = '';
			if( !empty($again_action_array) and in_array( $userid, $again_action_array ) )
			{
				$again_action_cdt = ", `" . $again_action . "`=`" . $again_action . "`-1";
				$again_action_array = array_diff( array( $userid ), $again_action_array );
				$again_user_cdt = ", `user_" . $again_action . "`=" . $db->dbescape( implode( ',', $again_action_array ) );
				$restore = $again_action;
			}
				
			$action_array[] = $userid;
			$query = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_" . $iscomment . $type . "` SET `" . $action . "`=`" . $action . "`+1, `user_" . $action . "`=" . $db->dbescape( implode( ',', $action_array ) ) . $again_action_cdt . $again_user_cdt . " WHERE " . $condition;
			
			if( $db->sql_query( $query ))
			{
				if( $type == 'photos' and empty( $iscomment ) )
				{
					$sql = "SELECT `catid` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_photos` WHERE `pid`=" . intval ( $id ) . " LIMIT 1";
					$data = $db->sql_fetch_assoc( $db->sql_query( $sql ) );
					if( $data['catid'] > 0 ) $db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_" . $data['catid'] . "` SET `" . $action . "`=`" . $action . "`+1, `user_" . $action . "`=" . $db->dbescape( implode( ',', $action_array ) ) . $again_action_cdt . $again_user_cdt . " WHERE " . $condition );
				}
				if( $cmid == 0 ) $cmid = $id;
				$contents = "OK*" . $action . "-" . $iscomment . $cmid . "*" . $restore . "-" . $iscomment . $cmid;
			}
			else $contents = "FALSE*";
		}
	}
}
nv_del_moduleCache( $module_name );
include ( NV_ROOTDIR . "/includes/header.php" );
echo $contents;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>