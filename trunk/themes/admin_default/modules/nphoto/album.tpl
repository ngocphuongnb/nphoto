<!-- BEGIN: main -->
<!-- BEGIN: listalb -->
<form id="listalb">
    <table summary="" class="tab1"> 
        <tbody class="second">
            <tr>
                <td width="20px"><input name="check_all[]" id="checkall" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" /></td>
                <td><strong>Tên album</strong></td>
                <td><strong>Chức năng</strong></td>
            </tr>
        </tbody>
        <!-- BEGIN: loop -->
        <tbody {LISTALB.class}>
            <tr>
                <td><input name="idcheck[]" type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{LISTALB.albid}" /></td>
                <td><strong>{LISTALB.title}</strong></td>
                <td><a href="{LISTALB.addphoto_url}">Thêm ảnh</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="{LISTALB.edit_url}">Sửa</a>&nbsp;&nbsp;&nbsp;&nbsp;<a style="cursor: pointer" onclick="np_delete('album', 'albid', {LISTALB.albid})">Xóa</a></td>
            </tr>
        </tbody>
        <!-- END: loop -->
    </table>
    <div><input type="button" id="deleteAll" value="Xóa album đã chọn" /></div>
</form>
<!-- END: listalb -->
<div id="edit">
	<!-- BEGIN: error -->
    <div class="quote" id="error" style="width:780px;">
    <blockquote class="error"><span>{ERROR}</span></blockquote>
    </div>
    <div class="clear"></div>
	<!-- END: error -->
    
    <!-- BEGIN: content -->
	<form action="{FORM_ACTION}" method="post">
    <input name="savealb" type="hidden" value="1" />
    <input name="albid" type="hidden" value="{ALB.albid}" />
    <input name="old_admins" type="hidden" value="{ALB.adminids}" />
    <table summary="" class="tab1">
		<caption>Thêm album</caption>  
		<tbody>
			<tr>
				<td align="right"><strong>Tên album: </strong></td>
				<td><input style="width: 600px" name="title" type="text" value="{ALB.title}" maxlength="255" id="idtitle"/></td>
			</tr>
		</tbody>
        <tbody class="second">
			<tr>
				<td align="right"><strong>Liên kết tĩnh: </strong></td>
				<td>
                	<input style="width: 600px" name="alias" type="text" value="{ALB.alias}" maxlength="255" id="idalias"/>
                    <img src="{NV_BASE_SITEURL}images/refresh.png" width="16" style="cursor: pointer; vertical-align: middle;" onclick="get_alias('alb', {ALB.albid}, 'idtitle', 'idalias');" alt="" height="16" />
                </td>
			</tr>
		</tbody>
        <tbody class="second">
			<tr>
				<td align="right"><strong>Ảnh: </strong></td>
				<td>
                	<input style="width: 500px" name="image" type="text" value="{ALB.image}" maxlength="255" id="image"/>
                    <input type="button" value="Browse server" name="selectimg" />
				</td>
			</tr>
		</tbody>
        {HOOK}
    </table>
    <br /><center><input name="submit" type="submit" value="Lưu lại" /></center>
	</form>
</div>

<!-- BEGIN: getalias -->
<script type="text/javascript">
$("#idtitle").change(function () {
    get_alias( "album", 0, 'idtitle', 'idalias' );
});

$("input[name=selectimg]").click(function() {
	var area = "image";
	var path = "{UPLOADS_DIR}";
	var currentpath = "{CURRENT_DIR}";
	var type = "image";
	nv_open_browse_file(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", "850", "420", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
	return false;
});

$("#deleteAll").click(function() {
	var val = new Array();
	$("#listalb input:checked").each(function(i)
	{
		val[i] = $(this).val();
	})
	if( $.inArray('yes', val) != -1 ) val.splice( $.inArray('yes', val), 1 );
	//alert( val.join(',') );
	np_delete('album', 'albid', val);
})
</script>
<!-- END: getalias -->
<!-- END: content -->
<!-- END: main -->