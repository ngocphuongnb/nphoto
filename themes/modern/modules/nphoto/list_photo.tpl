<!-- BEGIN: main  -->
<div class="np-box">
	<div class="np-wrap">
    	<!-- BEGIN: listphoto  -->
        <div class="np-main-content">
            <!-- BEGIN: loop -->
            <div class="np-photo-item">
                <div class="np-item-content">
                    <a href="{PHOTO.view_link}" rel="gallery" class="pirobox_gall" title="{PHOTO.title}"><img class="np-image-wrap np-margin" src="{PHOTO.image}" title="" alt="" width="90px" height="90px" /></a>
                </div>
                <div class="np-item-content" style="padding: 0">
                    <a href="{PHOTO.imgurl}" onclick="np_view_photo('{PHOTO.pid}', '{PHOTO.imgurl}'); return false;">{PHOTO.title}</a>
                </div>
            </div>
            <!-- END: loop -->
        </div>
        <!-- END: listphoto  -->
        <!-- BEGIN: generate_page -->
        <center>{GENERATE_PAGE}</center>
        <!-- END: generate_page -->
    </div>
</div>
<div class="np-feature">
    <!-- BEGIN: voting -->
    <a class="fl" id="like-{ALBID}-bt" {CLASS_LIKED} {LIKE_ACTION} >Like</a>
    <a class="addthis_button_expanded"><span class="number" id="like-{ALBID}">{LIKES}</span></a>
    <a class="fl" id="dislike-{ALBID}-bt" {CLASS_DISLIKED} {DISLIKE_ACTION}>Dislike</a>
    <a class="addthis_button_expanded"><span class="number" id="dislike-{ALBID}">{DISLIKES}</span></a>
    <!-- END: voting -->
</div>


<!-- BEGIN: comment -->
<div id="idcomment">
    <div id="showcomment" class="list-comments">
        {COMMENTCONTENT}
    </div>
</div>
<!-- END: comment -->
<!-- END: main -->
