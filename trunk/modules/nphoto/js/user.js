/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 1 - 31 - 2010 5 : 12
 */

function sendrating(id, point, newscheckss) {
	if(point==1 || point==2 || point==3 || point==4 || point==5){
		nv_ajax('post', nv_siteroot + 'index.php', nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=rating&id=' + id + '&checkss=' + newscheckss + '&point=' + point, 'stringrating', '');
	}
}

function sendcommment(type, id, newscheckss, gfx_count, cmable, level, cmcount) 
{
	if( type == 'photos' || type == 'album' )
	{
		var commentname = document.getElementById('commentname');
		var commentemail = document.getElementById('commentemail_iavim');
		var commentseccode = document.getElementById('commentseccode_iavim');
		var commentcontent = strip_tags(document.getElementById('commentcontent').value);
		if (commentname.value == "") {
			alert(nv_fullname);
			commentname.focus();
		} else if (!nv_email_check(commentemail)) {
			alert(nv_error_email);
			commentemail.focus();
		} else if (!nv_name_check(commentseccode)) {
			error = nv_error_seccode.replace( /\[num\]/g, gfx_count );
			alert(error);
			commentseccode.focus();
		} else if (commentcontent == "") {
			alert(nv_content);
			document.getElementById('infoarea').style.display='none';
			document.getElementById('fade').style.display='none';
			document.getElementById('commentcontent').focus();
		} else {
			var sd = document.getElementById('buttoncontent');
			sd.disabled = true;
			nv_ajax('post', nv_siteroot + 'index.php', nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=sendcomment&type=' + type + '&id=' + id + '&cmable=' + cmable +'&level=' + level + '&cmcount=' + cmcount + '&checkss=' + newscheckss + '&name=' + commentname.value + '&email=' + commentemail.value + '&code=' + commentseccode.value + '&content=' + encodeURIComponent(commentcontent), '', 'nv_commment_result');
		}
	}
	return;
}

function nv_commment_result(res) {
	nv_change_captcha('vimg', 'commentseccode_iavim');
	var r_split = res.split("_");
	if (r_split[0] == 'OK') {
		document.getElementById('commentcontent').value = "";
		nv_show_hidden('showcomment', 1);
		nv_show_comment(r_split[1], r_split[2], r_split[3], r_split[4], r_split[6]);
		alert(r_split[5] );
	} else if (r_split[0] == 'ERR') {
		alert(r_split[1]);
	} else {
		alert(nv_content_failed);
	}
	nv_set_disable_false('buttoncontent');
	return false;
}

function nv_show_comment( type, id, checkss, page, cmable) {
	if( type == 'photos' || type == 'album' )
	{
		var is_theater = document.getElementById('is_theater').innerHTML;
		nv_ajax('get', nv_siteroot + 'index.php', nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=comment&action=showcm&type=' + type + '&id=' + id + '&is_theater=' + is_theater + '&cmable=' + cmable + '&checkss=' + checkss + '&page=' + page, 'showcomment', '');
	}
}
// Show comment form
function cmshowform( type, id, level, cmcount, newscheckss, cmable )
{
	if( type == 'photos' || type == 'album' )
	{
		var is_theater = document.getElementById('is_theater').innerHTML;
		var loading = "<center><div style='margin-top: 10px; color: red'>Loading comment form...<br /><img src='" + nv_siteroot + "/images/load_bar.gif'></div></center>";
		document.getElementById('cmshowform' + level).innerHTML = loading;
		nv_ajax('get', nv_siteroot + 'index.php', nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=comment&action=showform&type=' + type + '&id=' + id + '&is_theater=' + is_theater + '&cmable=' + cmable + '&checkss=' + newscheckss + '&level=' + level + '&cmcount=' + cmcount, 'cmshowform' + level, '');
	}
}

function getinfo()
{
	document.getElementById('infoarea').style.display='block';document.getElementById('fade').style.display='block';
}

function np_like(action, type, iscomment, id, cmid, checkss, voteable)
{
	nv_ajax('post', nv_siteroot + 'index.php', nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=like&type=' + type + '&action='+ action + '&id=' + id + '&cmid=' + cmid + '&iscomment=' + iscomment +'&voteable=' + voteable + '&checkss=' + checkss, '', 'np_like_result');
}

function np_like_result(res)
{
	rs = res.split('*');
	if( rs[0] = 'OK' )
	{
		var np_vote_button = document.getElementById(rs[1] + '-bt');
		np_vote_button.removeAttribute('onclick');
		np_vote_button.style.color = '#999';
		np_vote_button.style.cursor = 'default';
		var likes = parseInt( document.getElementById(rs[1]).innerHTML );
		++likes;
		document.getElementById(rs[1]).innerHTML = likes;
		if( rs[2] != '' || rs[2] != NULL )
		{
			var aglikes = parseInt( document.getElementById(rs[2]).innerHTML );
			aglikes = aglikes -1;
			document.getElementById(rs[2]).innerHTML = aglikes;
		}
	}
}

function np_view_photo( pid, imgurl )
{
	$('#img-'+pid).click();
	window.history.pushState("object or string", "Title", imgurl);
	var scwidth = $(window).width();
	var scheight = $(window).height();
	nv_ajax('post', nv_siteroot + 'index.php', nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=photo&pid=' + pid + '&scwidth=' + scwidth + '&scheight=' + scheight, 'div_reg', '');
	
}