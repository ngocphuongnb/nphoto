<!-- BEGIN: main -->
<!-- BEGIN: listcat -->
<div style="width: 45%; float: left">
    <table class="tab1" summary="">
        <tbody class="second">
            <tr>
                <td><strong>Chọn chủ đề upload</strong></td>
            </tr>
        </tbody>
        <!-- BEGIN: loop -->
        <tbody>
            <tr>
                <td><a href="{listcat.link}" style="text-decoration: none" >{listcat.title}</a></td>
            </tr>
        </tbody>
        <!-- END: loop -->
    </table>
</div>
<!-- END: listcat -->
<!-- BEGIN: listalbum -->
<div style="width: 45%; float: left; margin-left: 10px">
    <table class="tab1" summary="">
        <tbody class="second">
            <tr>
                <td><strong>Chọn album upload</strong></td>
            </tr>
        </tbody>
        <!-- BEGIN: loop -->
        <tbody>
            <tr>
                <td><a href="{listalb.link}" style="text-decoration: none" >{listalb.title}</a></td>
            </tr>
        </tbody>
        <!-- END: loop -->
    </table>
</div>
<!-- END: listalbum -->
<!-- BEGIN: upload -->
<p>
    <form id="uform" action="{FORM_ACTION}" method="post">
    	<input type="hidden" id="type" name="type" value="{type}">
        <input type="hidden" id="typeid" name="typeid" value="{typeid}">
        <span class="btn btn-success fileinput-button"><span>Add files...</span>
        	<input type="file" id="file" name="files[]" multiple>
        </span>
    </form>
    <div>
        <button id="upload" class="btn btn-primary start"><span>Start upload</span> </button>
        <button type="reset" class="btn btn-warning cancel"><span>Cancel upload</span> </button>
        <button type="button" class="btn btn-danger delete"><span>Remove</span> </button>
        <input name="check_all[]" checked="checked" id="checkall" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" />
    </div>
</p>

<div id="status"></div>

<div style="width: 90%">
<table class="tab1" summary="" style="font-weight: bold">
	<caption>{CAPTION}</caption>
	<tbody class="second">
    	<tr>
        	<td width="200px">Total</td>
        	<td>
                <div class="progress">
                  <div class="bar" id="bar"></div >
                  <div class="percent" id="percent">0%</div >
                </div>
            </td>
        </tr>
   	</tbody>
</table>
<table class="tab1" id="file_list" summary="" style="font-weight: bold">
	<tbody class="second">
    	<tr>
        	<td width="200px">Tên</td>
            <td width="80px" align="center">Loại file</td>
            <td width="70px" align="center">Dung lượng</td>
            <td align="center">Tiến trình</td>
            <td width="150px" align="center">Trạng thái</td>
            <td width="150px" align="center"></td>
        </tr>
    </tbody>
</table>
</div>

<script type="text/javascript">
        
    $('#file').change(function(){
        var file_list = $(this)[0];
        showReadyUploadFile(file_list);
    });
    $('#upload').click(function(){
        var $f = $('#file');
        upload($f.get(0), 0, 1);
    });
</script>
<!-- END: upload -->
<!-- END: main -->