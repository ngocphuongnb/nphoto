<!-- BEGIN: main -->
<tr id="info">
	<td style="background: #FFF"><center><img src="{PHOTO.src}" height="120px"></center></td>
    <td colspan="{colspan}" id="td-{formid}" align="center" valign="middle">
    	<form id="form-{formid}" name="form-{formid}">
        	<input type="hidden" name="current_catid" value="{PHOTO.catid}" />
            <table summary="" class="tab1">
                <tbody class="second">
                    <tr>
                        <td><span onclick="np_saveinfo({formid}, {PHOTO.pid}, {colspan});" class="btn btn-primary start"><span>Lưu lại</span> </span></td>
                        <td colspan="3">
                            <input name="allowed_comm" type="checkbox" {PHOTO.allowed_comm} value="1" /><strong>&nbsp;&nbsp;Cho phép bình luận&nbsp;&nbsp;</strong>
                            <input name="allowed_rating" type="checkbox" {PHOTO.allowed_rating} value="1" /><strong>&nbsp;&nbsp;Cho phép chấm điểm</strong>
                        </td>
                    </tr>
                </tbody>
                <!-- BEGIN: catlist -->
                <tbody>
                    <tr>
                        <td align="right"><strong>{label}</strong></td>
                        <td colspan="3">
                            <select name="catid">
                                <!-- BEGIN: loop -->
                                <option value="{CAT.id}" {CAT.selected}>{CAT.title}</option>
                                <!-- END: loop -->
                            </select>
                        </td>
                    </tr>
                </tbody>
                <!-- END: catlist -->
                <!-- BEGIN: alblist -->
                <tbody>
                    <tr>
                        <td align="right"><strong>{label}</strong></td>
                        <td colspan="3">
                            <select name="albid">
                                <!-- BEGIN: loop -->
                                <option value="{ALB.id}" {ALB.selected}>{ALB.title}</option>
                                <!-- END: loop -->
                            </select>
                        </td>
                    </tr>
                </tbody>
                <!-- END: alblist -->
                <tbody class="second">
                    <tr>
                        <td align="right"><strong>Tiêu đề: </strong></td>
                        <td><input name="title" style="width: 200px" type="text" value="{PHOTO.title}" maxlength="255" id="idtitle"/></td>
                        <td align="right"><strong>Meta title: </strong></td>
                        <td>
                            <input name="meta_title" id="meta_title" style="width: 200px" type="text" value="{PHOTO.meta_title}" maxlength="255" onKeyDown="textCounter('meta_title','counttitle',70,this.form)" onKeyUp="textCounter('meta_title','counttitle',70,this.form)"/>&nbsp;&nbsp;
                            <input id="counttitle" style="color: green; font-weight: bold; width: 20px; text-align: center" disabled="disabled" value="70" />
                        </td>
                    </tr>
                </tbody>
                <tbody>
                    <tr>
                        <td align="right"><strong>Meta Keywords: </strong></td>
                        <td><input name="meta_keywords" style="width: 200px" type="text" value="{PHOTO.meta_keywords}" maxlength="255"/></td>
                        <td align="right"><strong>Meta Description: </strong></td>
                        <td>
                            <input name="meta_description" id="meta_description" style="width: 200px" type="text" value="{PHOTO.meta_description}" maxlength="255" onKeyDown="textCounter('meta_description','countdes',141,this.form)" onKeyUp="textCounter('meta_description','countdes',141,this.form)"/>&nbsp;&nbsp;
                            <input id="countdes" style="color: green; font-weight: bold; width: 30px; text-align: center" disabled="disabled" value="141" />
                        </td>
                    </tr>
                </tbody>
                <tbody class="second">
                    <tr>
                        <td align="right"><strong>Alternative text: </strong></td>
                        <td><input name="alt" style="width: 200px" type="text" value="{PHOTO.alt}" maxlength="255"/></td>
                        {views}
                    </tr>
                </tbody>
            </table>
        </form>
	</td>
</tr>
<!-- END: main -->