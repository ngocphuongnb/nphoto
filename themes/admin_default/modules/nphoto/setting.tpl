<!-- BEGIN: main -->
<form action="" method="post">
    <table class="tab1">
    	<tbody class="second">
            <tr>
                <td width="300px"><strong>Chủ đề trang chủ: </strong>
                <td>
                	<!-- BEGIN: listcat -->
                    <input type="checkbox" name="home_category[]" value="{CAT.catid}" {CAT.checked} />&nbsp;&nbsp;&nbsp;{CAT.title}<br /><br />
                    <!-- END: listcat -->
                </td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td><strong>Album trang chủ: </strong>
                <td>
                	<!-- BEGIN: listalb -->
                    <input type="checkbox" name="home_album[]" value="{ALB.albid}" {ALB.checked} />&nbsp;&nbsp;&nbsp;{ALB.title}<br /><br />
                    <!-- END: listalb -->
                </td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td width="300px"><strong>Số ảnh tối đa được upload một lần</strong>
                <td><input name="maxfilenums" value="{SETTING.maxfilenums}" /></td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td><strong>Dung lượng tối đa một ảnh</strong>
                <td>
                    <input name="maxfilesize" value="{SETTING.maxfilesize}" />&nbsp;Bytes, 
                    Dung lượng tối đa server cho phép tải lên: {MAX_SIZE} Bytes</td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td><strong>Loại ảnh được upload</strong>
                <td>
                	<!-- BEGIN: filetype -->
                	<input name="upload_filetype[]" type="checkbox" value="{UPLOAD_TYPE.name}" {UPLOAD_TYPE.checked} />&nbsp;&nbsp;&nbsp;{UPLOAD_TYPE.name}<br /><br />
                    <!-- END: filetype -->
                </td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td><strong>Bình luận Album</strong>
                <td>
                	<select name="comment_album">
                    	<!-- BEGIN: albumcomment -->
                        <option value="{CALBUM.value}" {CALBUM.selected}>{CALBUM.name}</option>
                        <!-- END: albumcomment -->
                    </select>
                </td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td><strong>Bình luận ảnh</strong>
                <td>
                	<select name="comment_photos">
                    	<!-- BEGIN: photocomment -->
                        <option value="{CPHOTO.value}" {CPHOTO.selected}>{CPHOTO.name}</option>
                        <!-- END: photocomment -->
                    </select>
                </td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td><strong>Ẩn link ảnh thật</strong>
                <td><input name="hide_real_imgurl" type="checkbox" value="1" {HIDE_LINK} /></td>
            </tr>
        </tbody>
        
        
        <tbody class="second">
            <tr>
                <td><strong>Số ảnh hiển thị trên một chủ đề tại trang chủ</strong>
                <td><input name="home_cat_numphotos" value="{SETTING.home_cat_numphotos}" /></td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td><strong>Số ảnh hiển thị trên một trang khi xem chủ đề</strong>
                <td><input name="view_cat_numphotos" value="{SETTING.view_cat_numphotos}" /></td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td><strong>Số ảnh hiển thịt rên một trang khi xem album</strong>
                <td><input name="view_album_numphotos" value="{SETTING.view_album_numphotos}" /></td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td><strong>Số album hiển thị trên một trang</strong>
                <td><input name="view_numalbums" value="{SETTING.view_numalbums}" /></td>
            </tr>
        </tbody>
        
        <tbody>
            <tr>
                <td><strong>Số ảnh hiển thị trên một trang khi xem tất cả ảnh</strong>
                <td><input name="view_all_numphotos" value="{SETTING.view_all_numphotos}" /></td>
            </tr>
        </tbody>
        
        <tbody class="second">
            <tr>
                <td><strong>Chiểu rộng tối đa ảnh tải lên</strong>
                <td><input name="upload_img_maxwidht" value="{SETTING.upload_img_maxwidht}" /></td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td><strong>Chiều cao tối đa ảnh tải lên</strong>
                <td><input name="upload_img_maxheight" value="{SETTING.upload_img_maxheight}" /></td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td><strong>Chiều rộng ảnh thu nhỏ</strong>
                <td><input name="thumb_maxwidht" value="{SETTING.thumb_maxwidht}" /></td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td><strong>Chiều cao ảnh thu nhỏ</strong>
                <td><input name="thumb_maxheight" value="{SETTING.thumb_maxheight}" /></td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td><strong>Cho phép thành viên đăng ảnh</strong>
                <td><input name="member_post" type="checkbox" value="1" {MEMBER_POST} /></td>
            </tr>
        </tbody>
    </table>
    <input type="hidden" name="save" value="1"  />
    <center><input type="submit" value="Lưu lại" /></center>
</form>
<!-- END: main -->