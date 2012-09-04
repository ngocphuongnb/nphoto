<!-- BEGIN: main -->
<div class="clearfix" style="height:{MAINHEIGHT}px; width: 100%">
    <div style="visibility:hidden; display: none" id="is_theater">1</div>
    <div class="np-main-img fl">
        <img src="{PHOTO.src}" title="{PHOTO.alt}" align="{PHOTO.alt}" {WIDTH} {IMGWIDTH} {IMGHEIGHT} class="np-image-wrap np-margin" />
        <div class="clearfix"></div>
        <div class="np-theater-title" style="width: {NIMGWIDTH}px">
            <p class="fl">{PHOTO.title}</p>
            <div class="np-feature">
                <!-- BEGIN: voting -->
                <a class="fl" id="like-{PHOTO.pid}-bt" {CLASS_LIKED} {LIKE_ACTION} >Like</a>
                <a class="addthis_button_expanded"><span class="number" id="like-{PHOTO.pid}">{PHOTO.like}</span></a>
                <a class="fl" id="dislike-{PHOTO.pid}-bt" {CLASS_DISLIKED} {DISLIKE_ACTION}>Dislike</a>
                <a class="addthis_button_expanded"><span class="number" id="dislike-{PHOTO.pid}">{PHOTO.dislike}</span></a>
                <!-- END: voting -->
            </div>
       </div>
    </div>
    <div class="np-theater-cm-container fr">
        <div class="np-photo-sharebt">Share</div>
        <div class="np-split"></div>
        <div>
            <!-- BEGIN: comment -->
            <div id="idcomment">
                <div id="showcomment" class="np-comment-theaterbox" {MAINCMH}>
                    {COMMENTCONTENT}
                </div>
            </div>
            <!-- END: comment -->
        </div>
    </div>
</div>
<!-- END: main -->