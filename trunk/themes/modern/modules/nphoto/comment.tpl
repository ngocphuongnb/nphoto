<!-- BEGIN: main -->
<div class="{MAINCM}">
    <!-- BEGIN: detail -->
    <div class="comment-item" style="margin-left: {LMARGIN}px">
      <div class="ava"> 
        <a href="#"><img src="{COMMENT.photo}" alt="Avata" /></a> 
      </div>
      <div class="{CMCLASS}"> 
        <span class="nonchange">
            <span><a href="#" >
                <strong>{COMMENT.post_name}</strong>
                <!-- BEGIN: emailcomm --> - <a title="mailto {COMMENT.post_email}" href="mailto:{COMMENT.post_email}"><span class="email">{COMMENT.post_email}</span></a> 
                <!-- END: emailcomm -->
            </a></span>
        </span>
        <span class="comment-time">{LANG.pubtime}: {COMMENT.post_time}</span> 
        <span class="comment-content clearfix">
            {COMMENT.content}<br />
            <span class="np-cmfeature">
                <!-- BEGIN: voting -->
                <a class="fl" id="like-comment_{COMMENT.cid}-bt" {CLASS_LIKED} {LIKE_ACTION} >Like</a>
                <a class="addthis_button_expanded"><span class="number" id="like-comment_{COMMENT.cid}">{COMMENT.like}</span></a>
                <a class="fl" id="dislike-comment_{COMMENT.cid}-bt" {CLASS_DISLIKED} {DISLIKE_ACTION}>Dislike</a>
                <a class="addthis_button_expanded"><span class="number" id="dislike-comment_{COMMENT.cid}">{COMMENT.dislike}</span></a>
                <!-- END: voting -->
                <a onClick="cmshowform('{TYPE}', '{NEWSID}', '{COMMENT.level}', '{COMMENT.cmcount}', '{NEWSCHECKSS}', '{CMABLE}');">Trả lời</a>
            </span>
        </span>
        {DIALOG}
      </div>
      <div id="cmshowform{COMMENT.level}" style="margin-left: 25px"></div> 
    </div>
    <!-- END: detail -->
</div>

<div id="formcomment">
    <!-- BEGIN: form -->
    <div class="{POS}">
        <div class="{CMCLASS} clearfix" style="margin-left: 0">
            <div id="infoarea" class="white_box">	
    			<div style="width: 100%; margin:0 auto">
                	<strong>Nhập tên</strong>
                    <input id="commentname" type="text" value="{NAME}" {DISABLED} style="width: 200px" class="input input-c fl" onblur="if(this.value=='')this.value='{LANG.comment_name}';" onclick="if(this.value=='{LANG.comment_name}')this.value='';"/><br />
                    <strong>Nhập email</strong>
                    <input id="commentemail_iavim" type="text" value="{EMAIL}" {DISABLED} style="width: 200px" class="input input-c fr" onblur="if(this.value=='')this.value='{LANG.comment_email}';" onclick="if(this.value=='{LANG.comment_email}')this.value='';"/><br />
                    <div class="clearfix"></div>
           			<strong>{LANG.comment_seccode}</strong><br />
                    <img id="vimg" alt="{N_CAPTCHA}" src="{SRC_CAPTCHA}" width="{GFX_WIDTH}" height="{GFX_HEIGHT}" />
                    <img alt="{CAPTCHA_REFRESH}" src="{CAPTCHA_REFR_SRC}" width="16" height="16" class="refresh" onclick="nv_change_captcha('vimg','commentseccode_iavim');"/>&nbsp; 
                    <input id="commentseccode_iavim" type="text" class="input capcha" />&nbsp; <br />
                    <input id="buttoncontent" type="submit" value="{LANG.comment_submit}" onclick="sendcommment('{TYPE}', '{NEWSID}', '{NEWSCHECKSS}', '{GFX_NUM}', '{CMABLE}', '', '-1');" class="button-2" style="margin-top: 5px" />
                </div>
            </div>
            <textarea id="commentcontent" cols="1" rows="1" title="{LANG.write_comment}" placeholder="{LANG.write_comment}" style="height:45px"></textarea>
            <input id="getinfo" type="button" value="{LANG.comment_submit} ..." class="button-2" onclick="getinfo();" />
            <input id="reset-cm" type="reset" value="RESET" class="button-2" />
        </div>
        <script type="text/javascript">
        $("#reset-cm").click(function(){
            $("#commentcontent,#commentseccode_iavim").val("");
        });
        </script>
    </div>
    <!-- END: form -->
    <a onclick="document.getElementById('fade').style.display='none'" href="javascript:void(0)">
        <div id="fade" class="black_overlay" onclick = "document.getElementById('infoarea').style.display='none';document.getElementById('fade').style.display='none'">
        </div>
    </a>
    <!-- BEGIN: form_login-->
    {COMMENT_LOGIN}
    <!-- END: form_login -->
</div>

<div class="page">
    {PAGE}
</div>
<!-- END: main -->