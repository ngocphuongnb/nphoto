<!-- BEGIN: main -->
<div style="width: 25%; float: left">
    <table class="tab1" summary="">
        <caption> Admin List</caption>
        <tbody class="second">
            <tr>
                <td width="50px">Admin ID</td>
                <td>Login Name</td>
            </tr>
        </tbody>
        <!-- BEGIN: adminlist -->
        <tbody>
            <tr>
                <td>{adminID}</td>
                <td><a href="{link}"><strong>{adminName}</strong></a></td>
            </tr>
        </tbody>
        <!-- END: adminlist -->
    </table>
</div>
<!-- BEGIN: content -->
<div style="float: left; width: 70%; margin-left: 20px">
    <form action="{FORM_ACTION}" method="post">
    	<input name="save" type="hidden" value="1" />
        <input name="userid" type="hidden" value="{ADMIN.userid}" />
        <input name="old_catids" type="hidden" value="{ADMIN.listcatid}" />
        <input name="old_albids" type="hidden" value="{ADMIN.listalbid}" />
        <table class="tab1" summary="">
            <caption>Edit Permission {ADMIN.name}</caption>
            <tbody class="second">
                <tr style="font-weight: bold">
                    <td><input type="checkbox" name="add_cat" value="1" {CHECKED.add_cat} />&nbsp;&nbsp;Tạo chủ đề</td>
                    <td><input type="checkbox" name="del_cat" value="1" {CHECKED.del_cat} />&nbsp;&nbsp;Xóa chủ đề</td>
                    <td><input type="checkbox" name="add_album" value="1" {CHECKED.add_album} />&nbsp;&nbsp;Tạo album</td>
                    <td><input type="checkbox" name="del_album" value="1" {CHECKED.del_album} />&nbsp;&nbsp;Xóa album</td>
                    <td><input type="checkbox" name="addphoto" value="1" {CHECKED.addphoto} />&nbsp;&nbsp;Thêm ảnh</td>
                </tr>
            </tbody>
         </table>
         <table>
         	<tr>
            	<td valign="top" style="padding-right: 1%" width="49%">
                	<table class="tab1" summary="" style="font-weight: bold">
                        <tbody class="second">
                            <tr>
                                <td width="20px"><input name="all_cat[]" id="checkall" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'listcatid[]', 'all_cat[]',this.checked);" /></td>
                                <td>Quản lý chủ đề</td>
                            </tr>
                        </tbody>
                        <!-- BEGIN: listcat -->
                        <tbody>
                            <tr>
                                <td><input type="checkbox" onclick="nv_UncheckAll(this.form, 'listcatid[]', 'all_cat[]', this.checked);" name="listcatid[]" value="{CAT.catid}" {CAT.checked} /></td>
                                <td>{CAT.title}</td>
                            </tr>
                        </tbody>
                        <!-- END: listcat -->
                     </table>
                 </td>
                 <td valign="top" style="padding-left: 1%" width="49%">
                     <table class="tab1" summary="" style="font-weight: bold">
                        <tbody class="second">
                            <tr>
                                <td width="20px"><input name="all_alb[]" id="checkall" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'listalbid[]', 'all_alb[]',this.checked);" /></td>
                                <td>Quản lý album</td>
                            </tr>
                        </tbody>
                        <!-- BEGIN: listalb -->
                        <tbody>
                            <tr>
                                <td><input type="checkbox" onclick="nv_UncheckAll(this.form, 'listalbid[]', 'all_alb[]', this.checked);" name="listalbid[]" value="{ALB.albid}" {ALB.checked} /></td>
                                <td>{ALB.title}</td>
                            </tr>
                        </tbody>
                        <!-- END: listalb -->
                     </table>
                 </td>
              </tr>
         </table>
         <center><input name="submit" type="submit" value="Lưu lại" /></center>	
    </form>  
</div>
<!-- END: content -->
<!-- END: main -->