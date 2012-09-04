<!-- BEGIN: main  -->
<div id="np-popup-container"></div>
<!-- BEGIN: listcat  -->
<div class="np-box">
	<div class="np-wrap">
        <div class="np-header">
        	<a href="{CAT.caturl}" title="{CAT.title}" class="np-bold">{CAT.title}</a>
        </div>
        <div class="np-main-content">
            <!-- BEGIN: content -->
            <div class="np-photo-item">
                <div class="np-item-content">
                    <a href="{CONTENT.imgurl}" rel="np_gallery" id="{IMGID}" class="np-imgurl" title="{CONTENT.title}">
                    	<img class="np-image-wrap np-margin" src="{CONTENT.image}" title="" alt="" width="90px" height="90px" />
                    </a>
                </div>
                <div class="np-item-content" style="padding: 0">
                    <a href="{CONTENT.imgurl}" rel="np_gallery" class="np-img-title">{CONTENT.title}</a>
                </div>
            </div>
            <!-- END: content -->
        </div>
    </div>
</div>
<!-- END: listcat  -->
<!-- BEGIN: generate_page -->
<center>{GENERATE_PAGE}</center>
<!-- END: generate_page -->
<!-- END: main -->
