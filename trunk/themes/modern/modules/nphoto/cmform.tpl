<!-- BEGIN: main -->
<div class="ava"> 
  <a href="#"><img src="{PHOTO}" alt="Avata" /></a><strong style=" margin-left: 5px; vertical-align: middle; color: #369;">Enter your comment below</strong>
</div>
<div class="{CMCLASS}">
	<!-- BEGIN: form -->
    <textarea id="commentcontent" cols="1" rows="1" title="{LANG.write_comment}" placeholder="{LANG.write_comment}"></textarea><br />
    <input id="getinfo" type="button" value="{LANG.comment_submit} ..." class="button-2 fr" onclick="getinfo();" />
	<div id="infoarea" class="white_box">	
    	<div style="width: 200px; margin:0 auto">
        	<strong>Nhập tên</strong>
            <input id="commentname" type="text" style="width: 200px" value="{NAME}" {DISABLED} class="input input-c fl" onblur="if(this.value=='')this.value='{LANG.comment_name}';" onclick="if(this.value=='{LANG.comment_name}')this.value='';"/><br />
            <strong>Nhập email</strong>
            <input id="commentemail_iavim" style="width: 200px" type="text" value="{EMAIL}" {DISABLED} class="input input-c fl" onblur="if(this.value=='')this.value='{LANG.comment_email}';" onclick="if(this.value=='{LANG.comment_email}')this.value='';"/><br />
            <div class="clearfix"></div>
            <strong>{LANG.comment_seccode}</strong><br />
            <img id="vimg" alt="{N_CAPTCHA}" src="{SRC_CAPTCHA}" width="{GFX_WIDTH}" height="{GFX_HEIGHT}" />
            <img alt="{CAPTCHA_REFRESH}" src="{CAPTCHA_REFR_SRC}" width="16" height="16" class="refresh" onclick="nv_change_captcha('vimg','commentseccode_iavim');"/>&nbsp; <br />
            <input id="commentseccode_iavim" style="width: 200px" type="text" class="input capcha" />&nbsp; <br />
            <input id="buttoncontent" type="submit" value="{LANG.comment_submit}" onclick="sendcommment('{TYPE}', '{NEWSID}', '{NEWSCHECKSS}', '{GFX_NUM}', '{CMABLE}', '{LEVEL}', '{CMCOUNT}');" class="button-2" style="margin-top: 5px" />&nbsp; 
        </div>
    </div>
    <!-- END: form -->
    <!-- BEGIN: form_login-->
    {COMMENT_LOGIN}
    <!-- END: form_login -->    
    <a onclick="document.getElementById('fade').style.display='none'" href="javascript:void(0)">
        <div id="fade" class="black_overlay" onclick = "document.getElementById('infoarea').style.display='none';document.getElementById('fade').style.display='none'">
        </div>
	</a>
</div>
<!-- END: main -->