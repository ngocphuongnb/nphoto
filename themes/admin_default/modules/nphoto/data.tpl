<!-- BEGIN: message -->
<script type="text/javascript">
$(document).ready(function() {
  $('#middle_column_r').before('<div class="{DATA.class}"><div class="msgcontent"><span class="title">{DATA.title}</span><br /><p>{DATA.msgcontent}</p></div></div>');
  $(".{DATA.class}").animate({
    width: "400px",
  }, 500 );
});
</script>
<!-- END: message -->

<!-- BEGIN: metatag -->
<script type="text/javascript">
window.onload = function() {
	textCounter('meta_title','counttitle',70,'')
	textCounter('meta_description','countdes',141,'')
}
//  End -->
</script>
    <tbody>
        <tr>
            <td align="right"><strong>Meta title: </strong></td>
            <td><input style="width: 600px" name="meta_title" id="meta_title" type="text" value="{DATA.meta_title}" maxlength="255"
                onKeyDown="textCounter('meta_title','counttitle',70,this.form)" onKeyUp="textCounter('meta_title','counttitle',70,this.form)"  /><br /><br />
                Tiêu đề có thể hiển thị trên máy chủ tìm kiếm tới 70 kí tự, còn lại <span id="counttitle" style="color: green; font-weight: bold">70</span> kí tự
            </td>
        </tr>
    </tbody>
    <tbody class="second">
        <tr>
            <td align="right"><strong>Meta keywords: </strong></td>
            <td><input style="width: 600px" name="meta_keywords" type="text" value="{DATA.meta_keywords}" maxlength="255" /></td>
        </tr>
    </tbody>
    <tbody>
        <tr>
            <td valign="top" align="right"><br /><strong>Meta description: </strong></td>
            <td>
                <textarea style="width: 600px" name="meta_description" id="meta_description" cols="100" rows="5"
                onKeyDown="textCounter('meta_description','countdes',141,this.form)" onKeyUp="textCounter('meta_description','countdes',141,this.form)">{DATA.meta_description}</textarea><br /><br />
                Thẻ Meta meta description có được giới hạn tới 141 kí tự, còn lại <span id="countdes" style="color: green; font-weight: bold">141</span> kí tự
            </td>
        </tr>
    </tbody>
<!-- END: metatag -->

<!-- BEGIN: admins -->
<tbody class="second">
    <tr>
        <td align="right"><strong>Admin: </strong></td>
        <td>
            <!-- BEGIN: loop -->
            <input type="checkbox"  name="adminids[]" value="{adminID}" {CHECKED} />&nbsp;&nbsp;{adminName}&nbsp;&nbsp;&nbsp;&nbsp;
            <!-- END: loop -->
        </td>
    </tr>
</tbody>
<!-- END: admins -->

<!-- BEGIN: configdata -->
<td align="right"><strong>Quyền xem:</strong></td>
<td>
    <div class="message_body">
        <select name="who_view" id="who_view" onchange="nv_sh('who_view','groups_list')">
            <!-- BEGIN: who_views -->
                <option value="{who_views.value}" {who_views.selected}>{who_views.title}</option>
            <!-- END: who_views -->
        </select>
        <br />
        <div id="groups_list" style="{hidediv}">
            <table style="margin-bottom:8px; width:250px;">
                <col valign="top" width="150px" />
                    <tr>
                        <td>
                            <!-- BEGIN: groups_views -->
                            <p><input name="groups_view[]" type="checkbox" value="{groups_views.value}" {groups_views.checked} />&nbsp;&nbsp;{groups_views.title}</p>
                            <!-- END: groups_views -->
                        </td>
                    </tr>
            </table>
        </div>
    </div>
</td>
<!-- END: configdata -->