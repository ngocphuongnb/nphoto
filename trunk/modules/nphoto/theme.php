<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES ., JSC. All rights reserved
 * @Createdate Jul 11, 2010  8:43:46 PM
 */

if( ! defined( 'NV_IS_MOD_NPHOTO' ) ) die( 'Stop!!!' );

/**
 * nv_nphoto_main()
 * 
 * @param mixed $row
 * @param mixed $ab_links
 * @return
 */
 
function np_home_theme( $array_cat, $generate_page )
{
	global $global_config, $module_name, $module_file, $global_array_cat, $lang_module, $module_config, $module_info, $my_head, $client_info;

	$my_head .= '<link href="' . NV_BASE_SITEURL . 'themes/modern/images/' . $module_name . '/np-lightbox/style.css" rel="stylesheet" type="text/css" />';
	$my_head .= '<script type="text/javascript" src="' . NV_BASE_SITEURL . 'modules/' . $module_name . '/js/np-lightbox.js"></script>';
    $my_head .= '<script type="text/javascript">
				var main_url = "' . $client_info['selfurl'] . '"
				</script>';
	$xtpl = new XTemplate( "home_theme.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	
	foreach( $array_cat as $key => $array_cat_i )
	{
		if( isset( $array_cat[$key]['content'] ) )
		{
			$array_cat_i['caturl'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . '=' . $array_cat_i['alias'];
			$xtpl->assign( 'CAT', $array_cat_i );
			
			foreach( $array_cat[$key]['content'] as $array_row_i )
			{
				$array_row_i['view_link'] = NV_MY_DOMAIN . NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $array_row_i['filepath'] . '/' . $array_row_i['filename'];
				$array_row_i['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $array_row_i['filepath'] . '/thumbs/' . $array_row_i['filename'];
				$array_row_i['imgurl'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . '=photo/' . $array_row_i['alias'] . '-' . $array_row_i['pid'];
				$xtpl->assign( 'CONTENT', $array_row_i );
			
				$img_size = explode( '-', $array_row_i['img_size'] );
				$img_width = intval( $img_size[0] );
				$img_height = intval( $img_size[1] );
				$xtpl->assign( 'IMGID', $img_width . '-' . $img_height . '-' . $array_row_i['pid'] );				
				
				$xtpl->parse( 'main.listcat.content' );
			}
			$xtpl->parse( 'main.listcat' );
		}
	}
	if( ! empty( $generate_page ) )
	{
		$xtpl->assign( 'GENERATE_PAGE', $generate_page );
		$xtpl->parse( 'main.generate_page' );
	}
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

function np_album_theme( $list_album, $generate_page )
{
	global $global_config, $module_name, $module_file, $global_array_cat, $lang_module, $module_config, $module_info;
	$xtpl = new XTemplate( "album_theme.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	
	if( !empty( $list_album ) )
	{
		foreach( $list_album as $album )
		{
			$album['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/thumbs/album/' . $album['thumbpath'];
			$album['url'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . '=album/' . $album['alias'];
			$xtpl->assign( 'ALBUM', $album );
			$xtpl->parse( 'main.listalbum.loop' );
		}
		$xtpl->parse( 'main.listalbum' );
	}
	if( ! empty( $generate_page ) )
	{
		$xtpl->assign( 'GENERATE_PAGE', $generate_page );
		$xtpl->parse( 'main.generate_page' );
	}
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

function np_list_photo( $list_photo, $generate_page, $comment_data, $albumdata = '' )
{
	global $global_config, $module_name, $module_file, $global_array_cat, $lang_module, $module_config, $module_info, $user_info, $admin_info, $my_head, $client_info;
	$my_head .= '<link href="' . NV_BASE_SITEURL . 'themes/modern/images/' . $module_name . '/np-lightbox/style.css" rel="stylesheet" type="text/css" />';
	$my_head .= '<script type="text/javascript" src="' . NV_BASE_SITEURL . 'modules/' . $module_name . '/js/np-lightbox.js"></script>';
    $my_head .= '<script type="text/javascript">
				var main_url = "' . $client_info['selfurl'] . '"
				</script>';
	$xtpl = new XTemplate( "list_photo.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$checkss = md5( $albumdata['albid'] . session_id() . $global_config['sitekey'] );
	
	foreach( $list_photo as $photo )
	{
		$photo['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $photo['filepath'] . '/thumbs/' . $photo['filename'];
		$photo['view_link'] = NV_MY_DOMAIN . NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $photo['filepath'] . '/' . $photo['filename'];
		$photo['imgurl'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . '=photo/' . $photo['alias'] . '-' . $photo['pid'];
		$xtpl->assign( 'PHOTO', $photo );
		$xtpl->parse( 'main.listphoto.loop' );
	}
	$xtpl->parse( 'main.listphoto' );
	
	if( !empty( $comment_data ) )
	{
		$xtpl->assign( 'COMMENTCONTENT', $comment_data );
		$xtpl->parse( 'main.comment' );
	}
	
	if( ! empty( $generate_page ) )
	{
		$xtpl->assign( 'GENERATE_PAGE', $generate_page );
		$xtpl->parse( 'main.generate_page' );
	}
	if( !empty($albumdata) and $albumdata['allowed_rating'] == 1 )
	{
		$xtpl->assign( 'ALBID', $albumdata['albid'] );
		$xtpl->assign( 'LIKES', $albumdata['like'] );
		$xtpl->assign( 'DISLIKES', $albumdata['dislike'] );
		$userid = 0;
		if( defined( 'NV_IS_USER' ) )
		{
			$userid = $user_info['userid'];
		}
		elseif( defined( 'NV_IS_ADMIN' ) )
		{
			$userid = $admin_info['userid'];
		}
		$liked_array = explode( ',', $albumdata['user_like'] );
		$disliked_array = explode( ',', $albumdata['user_dislike'] );
		if( in_array( $userid, $liked_array ) )
		{
			$xtpl->assign( 'CLASS_LIKED', "style='color: #999; cursor: default'" );
		}
		else
		{
			$xtpl->assign( 'CLASS_LIKED', "" );
			$xtpl->assign( 'LIKE_ACTION', "onclick=\"np_like('like', 'album', '', '" . $albumdata['albid'] . "', '', '" . $checkss . "', '" . 1 . "');\"" );
			$xtpl->parse( 'main.voting.likeaction' );
		}
		if( in_array( $userid, $disliked_array ) )
		{
			$xtpl->assign( 'CLASS_DISLIKED', "style='color: #999; cursor: default'" );
		}
		else
		{
			$xtpl->assign( 'CLASS_DISLIKED', "" );
			$xtpl->assign( 'DISLIKE_ACTION', "onclick=\"np_like('dislike', 'album', '', '" . $albumdata['albid'] . "', '', '" . $checkss . "', '" . 1 . "');\"" );
			$xtpl->parse( 'main.voting.dislikeaction' );
		}
		
		$xtpl->assign( 'VOTEABLE', $albumdata['allowed_rating'] );
		$xtpl->parse( 'main.voting' );
	}
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

function np_view_photo( $photo_data, $ver = 0 )
{
	global $global_config, $module_name, $module_file, $global_array_cat, $lang_module, $module_config, $module_info, $user_info, $admin_info;
	( $ver == 0 ) ? $temp_file = "view_photo_normal.tpl" : $temp_file = "view_photo_theater.tpl";
	$xtpl = new XTemplate( $temp_file, NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$main_cm_area = $photo_data['fitheight'] - 95;
	$xtpl->assign( 'MAINCMH', "style='height: " . $main_cm_area . "px'" );
	
	$photo_data['src'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $photo_data['filepath'] . '/' . $photo_data['filename'];
	$xtpl->assign( 'PHOTO', $photo_data );
	
	$xtpl->assign( 'COMMENTCONTENT', $photo_data['comments'] );
	$xtpl->parse( 'main.comment' );
	
	if( $photo_data['allowed_rating'] == 1 )
	{
		$checkss = md5( $photo_data['pid'] . session_id() . $global_config['sitekey'] );
		$userid = 0;
		if( defined( 'NV_IS_USER' ) )
		{
			$userid = $user_info['userid'];
		}
		elseif( defined( 'NV_IS_ADMIN' ) )
		{
			$userid = $admin_info['userid'];
		}
		$liked_array = explode( ',', $photo_data['user_like'] );
		$disliked_array = explode( ',', $photo_data['user_dislike'] );
		if( in_array( $userid, $liked_array ) )
		{
			$xtpl->assign( 'CLASS_LIKED', "style='color: #999; cursor: default'" );
		}
		else
		{
			$xtpl->assign( 'CLASS_LIKED', "" );
			$xtpl->assign( 'LIKE_ACTION', "onclick=\"np_like('like', 'photos', '', '" . $photo_data['pid'] . "', '', '" . $checkss . "', '" . 1 . "');\"" );
			$xtpl->parse( 'main.voting.likeaction' );
		}
		if( in_array( $userid, $disliked_array ) )
		{
			$xtpl->assign( 'CLASS_DISLIKED', "style='color: #999; cursor: default'" );
		}
		else
		{
			$xtpl->assign( 'CLASS_DISLIKED', "" );
			$xtpl->assign( 'DISLIKE_ACTION', "onclick=\"np_like('dislike', 'photos', '', '" . $photo_data['pid'] . "', '', '" . $checkss . "', '" . 1 . "');\"" );
			$xtpl->parse( 'main.voting.dislikeaction' );
		}
		
		$xtpl->assign( 'VOTEABLE', $photo_data['allowed_rating'] );
		$xtpl->parse( 'main.voting' );
	}
	if( $ver == 0 ) $xtpl->assign( 'WIDTH', 'width="420px"' );
	if( $photo_data['scwidth'] > 0 and $photo_data['scheight'] > 0 )
	{
		$xtpl->assign( 'MAINWIDTH', $photo_data['fitwidth'] );
		$xtpl->assign( 'MAINHEIGHT', $photo_data['fitheight'] );
		$photo_data['fitheight'] -= 50;
		$photo_data['fitwidth'] -= 330;
		$xtpl->assign( 'NIMGWIDTH', $photo_data['fitwidth'] );
		$xtpl->assign( 'IMGWIDTH', 'width=' . $photo_data['fitwidth'] . 'px' );
		$xtpl->assign( 'IMGHEIGHT', 'height=' . $photo_data['fitheight'] . 'px' );
	}
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

function np_theme_message( $msg )
{
	global $module_name, $module_file, $module_info;
	$xtpl = new XTemplate( "np_theme_msg.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'MSG', $msg );
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}


function comment_theme( $type, $id, $feature, $comment_array )
{
	global $global_config, $module_info, $module_name, $module_file, $module_config, $lang_module, $admin_info, $lang_global, $user_info;

	$xtpl = new XTemplate( "comment.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'TEMPLATE', $global_config['site_theme'] );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'TYPE', $type );
	$xtpl->assign( 'NEWSID', $id );
	$checkss = md5( $id . session_id() . $global_config['sitekey'] );
	$xtpl->assign( 'NEWSCHECKSS', $checkss );
	$xtpl->assign( 'CMABLE', $feature['cmable'] );
	$setting['album_comment_voteable'] = $setting['photo_comment_voteable'] = 1;
	
	if( isset( $comment_array['ver'] ) and $comment_array['ver'] > 0 )
	{
		$xtpl->assign( 'CMCLASS', 'theater-cmbox' );
		$xtpl->assign( 'POS', 'cm-position' );
		$xtpl->assign( 'MAINCM', 'main-comment' );
	}
	else
	{
		$xtpl->assign( 'CMCLASS', 'cmbox' );
		$xtpl->assign( 'DIALOG', '<span class="dialog"></span>' );
	}

	if( $feature['cmable'] == 1 )
	{
		if( defined( 'NV_IS_ADMIN' ) )
		{
			$xtpl->assign( 'NAME', $admin_info['full_name'] );
			$xtpl->assign( 'EMAIL', $admin_info['email'] );
			$xtpl->assign( 'DISABLED', " disabled=\"disabled\"" );
		}
		elseif( defined( 'NV_IS_USER' ) )
		{
			$xtpl->assign( 'NAME', $user_info['full_name'] );
			$xtpl->assign( 'EMAIL', $user_info['email'] );
			$xtpl->assign( 'DISABLED', " disabled=\"disabled\"" );
		}
		else
		{
			$xtpl->assign( 'NAME', $lang_module['comment_name'] );
			$xtpl->assign( 'EMAIL', $lang_module['comment_email'] );
			$xtpl->assign( 'DISABLED', "" );
		}

		$xtpl->assign( 'N_CAPTCHA', $lang_global['securitycode'] );
		$xtpl->assign( 'CAPTCHA_REFRESH', $lang_global['captcharefresh'] );
		$xtpl->assign( 'GFX_NUM', NV_GFX_NUM );
		$xtpl->assign( 'GFX_WIDTH', NV_GFX_WIDTH );
		$xtpl->assign( 'GFX_WIDTH', NV_GFX_WIDTH );
		$xtpl->assign( 'GFX_HEIGHT', NV_GFX_HEIGHT );
		$xtpl->assign( 'CAPTCHA_REFR_SRC', NV_BASE_SITEURL . "images/refresh.png" );
		$xtpl->assign( 'SRC_CAPTCHA', NV_BASE_SITEURL . "index.php?scaptcha=captcha" );
		$xtpl->parse( 'main.form' );
	}
	elseif( $feature['cmable'] == 2 )
	{
		global $client_info;

		$link_login = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=users&amp;" . NV_OP_VARIABLE . "=login&amp;nv_redirect=" . nv_base64_encode( $client_info['selfurl'] . "#formcomment" );
		$xtpl->assign( 'COMMENT_LOGIN', "<a title=\"" . $lang_global['loginsubmit'] . "\" href=\"" . $link_login . "\">" . $lang_module['comment_login'] . "</a>" );
		$xtpl->parse( 'main.form_login' );
	}

	$k = 0;
	foreach( $comment_array['comment'] as $comment_array_i )
	{
		$comment_array_i['post_time'] = nv_date( "d/m/Y H:i", $comment_array_i['post_time'] );

		if( ! empty( $comment_array_i['photo'] ) && file_exists( NV_ROOTDIR . "/" . $comment_array_i['photo'] ) )
		{
			$comment_array_i['photo'] = NV_BASE_SITEURL . $comment_array_i['photo'];
		}
		else
		{
			$comment_array_i['photo'] = NV_BASE_SITEURL . "themes/" . $global_config['module_theme'] . "/images/users/no_avatar.jpg";
		}
	
		$comment_array_i['bg'] = ( $k % 2 ) ? " bg" : "";

		$xtpl->assign( 'COMMENT', $comment_array_i );
		$lmargin = 0;
		if( $comment_array_i['level'] != "" )
		{
			$comment_array_i['level'] = explode( '.', $comment_array_i['level'] );
			$lev = sizeof( $comment_array_i['level'] );
			for( $i = 1; $i < $lev; ++$i )
			{
				$lmargin += 30;
			}
		}
		$xtpl->assign( 'LMARGIN', $lmargin );
		
		if( ( $type == 'album' and $setting['album_comment_voteable'] == 1 ) or ( $type == 'photos' and $setting['photo_comment_voteable'] == 1 ) )
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
			$liked_array = explode( ',', $comment_array_i['user_like'] );
			$disliked_array = explode( ',', $comment_array_i['user_dislike'] );
			if( in_array( $userid, $liked_array ) )
			{
				$xtpl->assign( 'CLASS_LIKED', "style='color: #999; cursor: default'" );
			}
			else
			{
				$xtpl->assign( 'CLASS_LIKED', "" );
				$xtpl->assign( 'LIKE_ACTION', "onclick=\"np_like('like', '" . $type . "', 'comment_', '" . $id . "', '" . $comment_array_i['cid'] . "', '" . $checkss . "', '" . 1 . "');\"" );
				$xtpl->parse( 'main.detail.voting.likeaction' );
			}
			if( in_array( $userid, $disliked_array ) )
			{
				$xtpl->assign( 'CLASS_DISLIKED', "style='color: #999; cursor: default'" );
			}
			else
			{
				$xtpl->assign( 'CLASS_DISLIKED', "" );
				$xtpl->assign( 'DISLIKE_ACTION', "onclick=\"np_like('dislike', '" . $type . "', 'comment_', '" . $id . "', '" . $comment_array_i['cid'] . "', '" . $checkss . "', '" . 1 . "');\"" );
				$xtpl->parse( 'main.detail.voting.dislikeaction' );
			}
			
			$xtpl->assign( 'VOTEABLE', $feature['voteable'] );
			$xtpl->parse( 'main.detail.voting' );
		}

		$xtpl->parse( 'main.detail' );
		++$k;
	}

	if( ! empty( $comment_array['page'] ) )
	{
		$xtpl->assign( 'PAGE', $comment_array['page'] );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

// Show comment form
function np_show_comment_form( $type, $id, $level, $cmcount, $commentenable, $is_theater = 0 )
{
	global $global_config, $module_info, $module_name, $module_file, $module_config, $lang_module, $admin_info, $lang_global, $user_info;

	$xtpl = new XTemplate( "cmform.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'TEMPLATE', $global_config['site_theme'] );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'TYPE', $type );
	$xtpl->assign( 'N_CAPTCHA', $lang_global['securitycode'] );
	$xtpl->assign( 'CAPTCHA_REFRESH', $lang_global['captcharefresh'] );
	$xtpl->assign( 'GFX_NUM', NV_GFX_NUM );
	$xtpl->assign( 'GFX_WIDTH', NV_GFX_WIDTH );
	$xtpl->assign( 'GFX_WIDTH', NV_GFX_WIDTH );
	$xtpl->assign( 'GFX_HEIGHT', NV_GFX_HEIGHT );
	$xtpl->assign( 'CAPTCHA_REFR_SRC', NV_BASE_SITEURL . "images/refresh.png" );
	$xtpl->assign( 'SRC_CAPTCHA', NV_BASE_SITEURL . "index.php?scaptcha=captcha" );
	$xtpl->assign( 'NEWSID', $id );
	$xtpl->assign( 'NEWSCHECKSS', md5( $id . session_id() . $global_config['sitekey'] ) );
	$xtpl->assign( 'CMABLE', $commentenable );
	$xtpl->assign( 'LEVEL', $level );
	$xtpl->assign( 'CMCOUNT', $cmcount );
	( $is_theater == 1 ) ? $xtpl->assign( 'CMCLASS', 'theater-cmform' ) : $xtpl->assign( 'CMCLASS', 'cmbox' );
	
	if( $commentenable == 1 )
	{
		if( defined( 'NV_IS_ADMIN' ) )
		{
			$xtpl->assign( 'NAME', $admin_info['full_name'] );
			$xtpl->assign( 'EMAIL', $admin_info['email'] );
			$xtpl->assign( 'DISABLED', " disabled=\"disabled\"" );
			if( ! empty( $admin_info['photo'] ) && file_exists( NV_ROOTDIR . "/" . $admin_info['photo'] ) )
			{
				$photo = NV_BASE_SITEURL . $admin_info['photo'];
			}
			else
			{
				$photo = NV_BASE_SITEURL . "themes/" . $global_config['module_theme'] . "/images/users/no_avatar.jpg";
			}
		}
		elseif( defined( 'NV_IS_USER' ) )
		{
			$xtpl->assign( 'NAME', $user_info['full_name'] );
			$xtpl->assign( 'EMAIL', $user_info['email'] );
			$xtpl->assign( 'DISABLED', " disabled=\"disabled\"" );
			if( ! empty( $user_info['photo'] ) && file_exists( NV_ROOTDIR . "/" . $user_info['photo'] ) )
			{
				$photo = NV_BASE_SITEURL . $user_info['photo'];
			}
			else
			{
				$photo = NV_BASE_SITEURL . "themes/" . $global_config['module_theme'] . "/images/users/no_avatar.jpg";
			}
		}
		else
		{
			$xtpl->assign( 'NAME', $lang_module['comment_name'] );
			$xtpl->assign( 'EMAIL', $lang_module['comment_email'] );
			$xtpl->assign( 'DISABLED', "" );
			$photo = NV_BASE_SITEURL . "themes/" . $global_config['module_theme'] . "/images/users/no_avatar.jpg";
		}

		$xtpl->assign( 'N_CAPTCHA', $lang_global['securitycode'] );
		$xtpl->assign( 'PHOTO', $photo );
		$xtpl->assign( 'CAPTCHA_REFRESH', $lang_global['captcharefresh'] );
		$xtpl->assign( 'GFX_NUM', NV_GFX_NUM );
		$xtpl->assign( 'GFX_WIDTH', NV_GFX_WIDTH );
		$xtpl->assign( 'GFX_WIDTH', NV_GFX_WIDTH );
		$xtpl->assign( 'GFX_HEIGHT', NV_GFX_HEIGHT );
		$xtpl->assign( 'CAPTCHA_REFR_SRC', NV_BASE_SITEURL . "images/refresh.png" );
		$xtpl->assign( 'SRC_CAPTCHA', NV_BASE_SITEURL . "index.php?scaptcha=captcha" );
		$xtpl->parse( 'main.form' );
	}
	elseif( $commentenable == 2 )
	{
		global $client_info;

		$link_login = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=users&amp;" . NV_OP_VARIABLE . "=login&amp;nv_redirect=" . nv_base64_encode( $client_info['selfurl'] . "#formcomment" );
		$xtpl->assign( 'COMMENT_LOGIN', "<a title=\"" . $lang_global['loginsubmit'] . "\" href=\"" . $link_login . "\">" . $lang_module['comment_login'] . "</a>" );
		$xtpl->parse( 'main.form_login' );
	}
	
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}
 
?>