<!-- BEGIN: main -->
<!-- BEGIN: listcat -->
<form id="listcat">
    <table summary="" class="tab1"> 
        <tbody class="second">
            <tr>
                <td width="20px"><input name="check_all[]" id="checkall" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" /></td>
                <td><strong>Tên chủ đề</strong></td>
                <td><strong>Chức năng</strong></td>
            </tr>
        </tbody>
        <!-- BEGIN: loop -->
        <tbody {LISTCAT.class}>
            <tr>
                <td><input name="idcheck[]" type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{LISTCAT.catid}" /></td>
                <td><a href="{LISTCAT.cat_url}"><strong>{LISTCAT.title}</strong></a> <span style="color:#FF0101;">({LISTCAT.numsubcat})</span></td>
                <td><a href="{LISTCAT.addphoto_url}">Thêm ảnh</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="{LISTCAT.edit_url}">Sửa</a>&nbsp;&nbsp;&nbsp;&nbsp;<a style="cursor: pointer" onclick="np_delete('category', 'catid', {LISTCAT.catid})">Xóa</a></td>
            </tr>
        </tbody>
        <!-- END: loop -->
    </table>
    <div><input type="button" id="deleteAll" value="Xóa chủ đề đã chọn" /></div>
</form>
<!-- END: listcat -->
<div id="edit">    
    <!-- BEGIN: content -->
	<form action="{FORM_ACTION}" method="post">
    <input name="savecat" type="hidden" value="1" />
    <input name="catid" type="hidden" value="{CAT.catid}" />
    <input name="old_parentid" type="hidden" value="{CAT.parentid}" />
    <input name="old_admins" type="hidden" value="{CAT.adminids}" />
    <table summary="" class="tab1">
		<caption>Thêm chủ đề</caption>  
		<tbody>
			<tr>
				<td align="right"><strong>Tên chủ đề: </strong></td>
				<td><input style="width: 600px" name="title" type="text" value="{CAT.title}" maxlength="255" id="idtitle"/></td>
			</tr>
		</tbody>
        <tbody class="second">
			<tr>
				<td align="right"><strong>Liên kết tĩnh: </strong></td>
				<td>
                	<input style="width: 600px" name="alias" type="text" value="{CAT.alias}" maxlength="255" id="idalias"/>
                    <img src="{NV_BASE_SITEURL}images/refresh.png" width="16" style="cursor: pointer; vertical-align: middle;" onclick="get_alias('cat', {CAT.catid}, 'idtitle', 'idalias');" alt="" height="16" />
                </td>
			</tr>
		</tbody>
        <tbody>
			<tr>
				<td align="right"><strong>Thuộc chủ đề: </strong></td>
				<td>
                    <select name="parentid">
                        <!-- BEGIN: listcat -->
                        <option value="{listcat.value}" {listcat.selected}>{listcat.title}</option>
                        <!-- END: listcat -->
                    </select>
				</td>
			</tr>
		</tbody>
        <tbody class="second">
			<tr>
				<td align="right"><strong>Ảnh: </strong></td>
				<td>
                	<input style="width: 500px" name="image" type="text" value="{CAT.image}" maxlength="255" id="image"/>
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
    get_alias( "category", 0, 'idtitle', 'idalias' );
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
	$("#listcat input:checked").each(function(i)
	{
		val[i] = $(this).val();
	})
	if( $.inArray('yes', val) != -1 ) val.splice( $.inArray('yes', val), 1 );
	//alert( val.join(',') );
	np_delete('category', 'catid', val);
})
</script>
<!-- END: getalias -->
<!-- END: content -->
<!-- END: main -->