<!-- BEGIN: main -->
<a href="{mainurl}"><span class="btn {ACT_MAIN}">Danh sách</span></a>
<a href="{trash_url}"><span class="btn {ACT_TRASH}">Thùng rác</span></a>
<!-- BEGIN: search -->
<form action="{FORM_ACTION}" id="search" method="get">
	<input type="hidden" name="{NV_NAME_VARIBLE}" value="{MODULE_NAME}" />
    <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
    <input type="hidden" name="np" value="{NPO}" />
    <input type="hidden" name="orderby" value="{orderby}" />
    <input type="hidden" name="order" value="{order}" />
	<table class="tab1" summary="">
    	<tbody class="second">
        	<tr>
            	<td><strong>Tìm kiếm: &nbsp;&nbsp;&nbsp;&nbsp;</strong>
                	<select name="catid">
                    	<!-- BEGIN: listcat -->
                        <option value="{listcat.catid}" {listcat.selected}>{listcat.title}</option>
                        <!-- END: listcat -->
                    </select>
                    <select name="albid">
                    	<!-- BEGIN: listalb -->
                        <option value="{listalb.albid}" {listalb.selected}>{listalb.title}</option>
                        <!-- END: listalb -->
                    </select>
                </td>
                <td width="200px"><strong>Tìm theo: &nbsp;&nbsp;&nbsp;&nbsp;</strong>
                	<select name="by">
                    	<!-- BEGIN: stype -->
                        <option value="{stype.key}" {stype.selected}>{stype.value}</option>
                        <!-- END: stype -->
                    </select>
                </td>
                <td><strong>Hiển thị: &nbsp;&nbsp;&nbsp;&nbsp;</strong>
                	<input type="number" name="limit" style="width: 50px" value="{limit}" />
                </td>
           	</tr>
         </tbody>
         <tbody>
        	<tr>
            	<td colspan="3"><strong>Từ khóa tìm kiếm: &nbsp;&nbsp;</strong>
                	<input name="q" style="width: 200px; height:21px" type="text" value="{q}" />
                    <input name="search" type="submit" class="btn btn-primary" value="Search"/>
                    <input name="reset" type="button" class="btn btn-warning" onClick="resetform('search');" value="Reset Form"/>
                    <strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Tìm thấy {found} kết quả.</strong>
                </td>
            </tr>
        </tbody>
    </table>
</form>
<!-- END: search -->
<!-- BEGIN: photo -->
<div>
<form id="feature">    
	<select name="movetocat" style="float:left; margin-bottom: 5px; margin-right: 5px; padding: 5px">
        <!-- BEGIN: listcat -->
        <option value="{listcat.catid}" >{listcat.title}</option>
        <!-- END: listcat -->
    </select>
    <input type="button" id="moveAll" class="btn btn-success" value="Chuyển" />
	<input type="button" id="deleteAll" class="btn btn-danger" value="Xóa files đã chọn" /><br /><br />
    
    <select name="addtoalbum" style="float:left; margin-bottom: 5px; margin-right: 5px; padding: 5px">
        <!-- BEGIN: listalb -->
        <option value="{listalb.albid}" >{listalb.title}</option>
        <!-- END: listalb -->
    </select>
    <input type="button" id="AddAll" class="btn btn-success" value="Thêm vào album" />
</form>
</div>
<form id="listphoto">
<input type="hidden" id="type" name="type" value="photo">
<table class="tab1" summary="">
	<tbody class="second">
    	<tr>
        	<td><input name="check_all[]" id="checkall" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" /></td>
            <td align="center"><strong><a href="{main_url}&amp;orderby=pid&order={order}">ID</a></strong></td>
            <td></td>
            <td align="center"><strong><a href="{main_url}&amp;orderby=title&order={order}">Tiêu đề</a></strong></td>
            <td align="center"><strong><a href="{main_url}&amp;orderby=alt&order={order}">Alt</a></strong></td>
            <td align="center"><strong>Chủ đề/album</strong></td>
            <td align="center"><strong><a href="{main_url}&amp;orderby=filename&order={order}">File name</a> - <a href="{main_url}&amp;orderby=filepath&order={order}">File path</a></strong></td>
            <td width="140px"></td>
        </tr>
    </tbody>
    <!-- BEGIN: loop -->
    <tbody class="{CLASS}" id="item-{i}">
    	<tr>
        	<td><input name="idcheck[]" type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{PHOTO.pid}" /></td>
            <td valign="top" align="center"><strong>{PHOTO.pid}</strong></td>
            <td><center><img src="{PHOTO.src}" width="60px" height="60px" /></center></td>
            <td valign="top"><strong>{PHOTO.title}</strong><br />{PHOTO.filetype}</td>
            <td valign="top"><strong>{PHOTO.alt}</strong></td>
            <td valign="top">
            	<strong>Chủ đề</strong>: {PHOTO.category}<br /><br />
                <strong>Album</strong>: {PHOTO.album}
            </td>
            <td valign="top">
            	<strong>{PHOTO.filename}</strong><br /><br />
                <strong>{PHOTO.filepath}</strong>
            </td>
            <td>
            	<button id="upload" class="btn btn-success" onclick="showform({i},{PHOTO.pid}, 0, 7);"><span>Edit Info</span></button>
                <button type="button" class="btn btn-danger" onclick="np_delete('photos', 'pid', {PHOTO.pid}, '{NPO}');"><span>Delete</span></button>
            </td>
        </tr>
    </tbody>
    <!-- END: loop -->
</table>
</form>
<!-- END: photo -->
<!-- BEGIN: generate_page -->
<center>{GENERATE_PAGE}</center>
<!-- END: generate_page -->
<script type="text/javascript">
$("#deleteAll").click(function() {
	var val = new Array();
	$("#listphoto input:checked").each(function(i)
	{
		val[i] = $(this).val();
	})
	if( $.inArray('yes', val) != -1 ) val.splice( $.inArray('yes', val), 1 );
	np_delete('photos', 'pid', val, '{NPO}');
})
$("#moveAll").click(function() {
	var val = new Array();
	var newcat = feature.movetocat.options[feature.movetocat.selectedIndex].value;
	$("#listphoto input:checked").each(function(i)
	{
		val[i] = $(this).val();
	})
	if( $.inArray('yes', val) != -1 ) val.splice( $.inArray('yes', val), 1 );
	np_movephoto(newcat, val, '{NPO}');
})
$("#AddAll").click(function() {
	var val = new Array();
	var albumid = feature.addtoalbum.options[feature.addtoalbum.selectedIndex].value;
	$("#listphoto input:checked").each(function(i)
	{
		val[i] = $(this).val();
	})
	if( $.inArray('yes', val) != -1 ) val.splice( $.inArray('yes', val), 1 );
	np_addToAlbum(albumid, val);
})
</script>
<!-- END: main -->