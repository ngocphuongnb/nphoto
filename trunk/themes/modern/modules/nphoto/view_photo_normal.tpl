<!-- BEGIN: main -->
<center>
	<img src="{PHOTO.src}" title="{PHOTO.alt}" align="{PHOTO.alt}" style="max-width: 600px" class="np-image-wrap np-margin" /><br />
    <center>{PHOTO.title}</center><br />
    <div class="np-feature">
    <!-- BEGIN: voting -->
    <a class="fl" id="like-{PHOTO.pid}-bt" {CLASS_LIKED} {LIKE_ACTION} >Like</a>
    <a class="addthis_button_expanded"><span class="number" id="like-{PHOTO.pid}">{PHOTO.like}</span></a>
    <a class="fl" id="dislike-{PHOTO.pid}-bt" {CLASS_DISLIKED} {DISLIKE_ACTION}>Dislike</a>
    <a class="addthis_button_expanded"><span class="number" id="dislike-{PHOTO.pid}">{PHOTO.dislike}</span></a>
    <!-- END: voting -->
</div>
</center>
<!-- BEGIN: comment -->
<div id="idcomment">
    <div id="showcomment" class="list-comments">
        {COMMENTCONTENT}
    </div>
</div>
<!-- END: comment -->
<!-- END: main -->