<!-- BEGIN: main  -->
<!-- BEGIN: listalbum  -->
<div class="np-box">
	<div class="np-wrap">
        <div class="np-header">
        	<strong>Album nổi bật</strong>
        </div>
        <div class="np-main-content np-album-wrap-bg">
        <!-- BEGIN: loop  -->
    	<div class="np-home-album-item">
        	<span class="tape"></span>
        	<div class="np-album-content">
                <img src="{ALBUM.image}" class="np-margin" width="178px" height="120px" title="{ALBUM.title}" alt="{ALBUM.title}" />
                <h3><a href="{ALBUM.url}" title="">{ALBUM.title}</a></h3>
            </div>
        </div>
        <!-- END: loop  -->
        </div>
     </div>
</div>
<!-- END: listalbum  -->
<!-- BEGIN: generate_page -->
{GENERATE_PAGE}
<!-- END: generate_page -->
<!-- END: main -->
