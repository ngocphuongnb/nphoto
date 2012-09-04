var uploaded = new Array();
var canceled = new Array();
var images = new Array();
//---------------------------------------
function get_alias(mod,id,from,to) {
	var title = strip_tags(document.getElementById(from).value);
	if (title != '') {
		nv_ajax('post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=alias&title=' + encodeURIComponent(title)+'&mod='+mod+'&id='+id+'&to='+to, '', 'res_get_alias');
	}
	return false;
}

function res_get_alias(res) {
	if (res != "") 
	{
		rs = res.split('_');
		document.getElementById(rs[0]).value = rs[1];
	} 
	return false;
}
//----------------------------------------
function np_delete( type, by, value, get_from )
{
	if( type !='' && by !='' && value != '' )
	{
		if( confirm('Bạn có chắc chắn xóa ' + type + ' này?' ) )
		{
			if( get_from == '' ) get_from = 'photos';
			nv_ajax('post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=delete&type=' +type+'&by='+by+'&get_from=' + get_from + '&value='+value, '', 'np_delete_rs');
		}
	}
}

function np_delete_rs(res)
{
	rs = res.split('*');
	var war = rs[0].split('#');
	var ok = rs[1].split('#');
	var err = rs[2].split('#');
	
	if( err[1] != '' )
	{
		$('#middle_column_r').before('<div class="nperror"><div class="msgcontent"><span class="title">Error!</span><br /><p>' + err[1] + '</p></div></div>');
		
	    $(".nperror").animate({
		  width: "400px",
	    }, 500 );
	}
	if( ok[1] != '' )
	{
		$('#middle_column_r').before('<div class="success"><div class="msgcontent"><span class="title">Success!</span><br /><p>' + ok[1] + '</p></div></div>');
		
	    $(".success").animate({
		  width: "400px",
	    }, 500 );
		setTimeout(function() {window.location.reload();},3000);
	}
	if( war[1] != '' )
	{
		$('#middle_column_r').before('<div class="warning"><div class="msgcontent"><span class="title">Warning!</span><br /><p>' + war[1] + '</p></div></div>');
		
	    $(".warning").animate({
		  width: "400px",
	    }, 500 );
		//setTimeout(function() {window.location.reload();},3000);
	}
}

function textCounter(field,cntfield,maxlimit, form) 
{
	var formname = form.name;
	if( formname != '' && form != '' )
	{
		formdata = document.forms[formname];
		formdata.elements[cntfield].value = maxlimit - formdata.elements[field].value.length;
		if( parseInt( formdata.elements[cntfield].value ) < 0 )
		{
			formdata.elements[cntfield].style.color = "red";
		}
		else
		{
			formdata.elements[cntfield].style.color = "green";
		}
	}
	else
	{
		document.getElementById(cntfield).innerHTML = maxlimit - document.getElementById(field).value.length;
		if( parseInt( document.getElementById(cntfield).innerHTML ) < 0 )
		{
			document.getElementById(cntfield).style.color = "red";
		}
		else
		{
			document.getElementById(cntfield).style.color = "green";
		}
	}
}

function showform(i,id, rf, col)
{
	if( $('tbody#item-' + i + ' button#upload').html() == '<span>Hide</span>' && rf == 0 )
	{
		$('tbody#item-' + i + ' button#upload').html('<span>Edit Info</span>');
		$('tbody#item-' + i + ' tr#info').remove();
	}
	else
	{
		if( jQuery.inArray( i, uploaded ) > -1 || rf == 1 || col == 7 )
		{
			var ntype = $('#type').val();
			$.get( script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=image', { type: ntype, pid: id, formid: i, colspan: col },function(data)
			{
				if( rf == 1 ) $('tbody#item-' + i + ' tr#info').remove();
				$('tbody#item-' + i).append(data);
				var count_title = 70 - $('tbody#item-' + i + ' #meta_title').val().length;
				var count_des = 140 - $('tbody#item-' + i + ' #meta_description').val().length;
				$('tbody#item-' + i + ' #counttitle').val( count_title );
				$('tbody#item-' + i + ' #countdes').val( count_des );
				if( count_title < 0 ) $('tbody#item-' + i + ' #counttitle').css('color','red');
				if( count_des < 0 ) $('tbody#item-' + i + ' #countdes').css('color','red');
   			});
			$('tbody#item-' + i).addClass('npinfo');
			$('tbody#item-' + i + ' button#upload').html('<span>Hide</span>');
		}
	}
}

function addphoto( type, id )
{
	top.location.href= script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=addphoto&type=' + type + '&id=' + id
}

/* Upload function */
function cancel(i)
{
	$('tbody#item-' + i).remove();
	canceled.push(i);
}

function preupload(i)
{
	var $f = $('#file');
    upload($f.get(0), i, 0);
}

function upload(filesToUp, i, single)
{
	var numsfile = filesToUp.files.length
	if( i < numsfile  )
	{
		$('tbody#item-'+i+' .feature #upload').attr('disabled', 'disabled');
		$('tbody#item-'+i+' .feature #reset').attr('disabled', 'disabled');
		$('tbody#item-'+i+' #status').html('Uploading...');
		if( ( jQuery.inArray( i, uploaded ) == -1 ) && ( jQuery.inArray( i, canceled ) == -1 ) )
		{
			$('tbody#item-'+i+' #upload').attr('disabled', 'disabled');
			if (window.XMLHttpRequest)
			{
				xhr = new XMLHttpRequest();
			}
			else
			{
				xhr = new ActiveXObject("Microsoft.XMLHTTP");
			}
			
			var data = new FormData();
			var url = $('#uform').attr('action');
			data.append('type', $('#type').val());
			data.append('typeid', $('#typeid').val());
			data.append('file', filesToUp.files[i]);
			xhr.open("POST",url , true);
			
			 var progressBar = document.querySelector('#percent-file-' + i);
			 var bar = $('#bar-file-' + i );
			 var gbar = $('#bar');
			 xhr.upload.onprogress = function(e) 
			 {
				 if (e.lengthComputable) 
				 {
					  var n = (e.loaded / e.total) * 100 ;
					  var p = parseInt(n);				  
					  progressBar.value = p + '%';
					  bar.css( 'width', progressBar.value );
					  progressBar.textContent = progressBar.value; // Fallback for unsupported browsers.
					  
					  
					  var currentgprogress = ( 100 / numsfile ) * uploaded.length;
					  globalprogress = currentgprogress + ( n / numsfile );
					  globalprogress = parseInt(globalprogress) + '%';
					  $('#percent').html( globalprogress );
					  $('#bar').css( 'width', globalprogress );
				 }
			 };
			 
			 xhr.send(data);
		
			xhr.onreadystatechange = function(e)
			{
				if(xhr.readyState == 4 && xhr.status == 200)
				{
					var result = xhr.responseText.split('*');
					$('#title-file-' + i).val(xhr.responseText);
					$('tbody#item-'+i+' #status').addClass(result[0]);
					$('tbody#item-'+i+' #status').css('width', '150px');
					$('tbody#item-'+i+' #status').css('background', 'none');
					$('tbody#item-'+i+' #status').css('margin', '0px');
					$('tbody#item-'+i+' #status').html(result[1]);
					if( result[0] == 'success' )
					{
						$('tbody#item-'+i+' .feature').html('<button id="upload" class="btn btn-success fileinput-button" onclick="showform(' + i + ', ' + result[2] + ', 0, 5);"><span>Edit Info</span></button><button type="button" class="btn btn-danger delete"><span>Delete</span></button>');
					}
					else
					{
						$('tbody#item-'+i+' .feature').html('');
					}
					images[i] = result[3];
					uploaded.push(i);
					if( single == 1 )
					{
						i++;
						upload(filesToUp, i, 1);
					}
				}
			}
		}
		else
		{
			i++;
			upload(filesToUp, i, 1);
		}	
	}
}

function showReadyUploadFile(file_list)
{
	while(uploaded.length > 0) 
	{
        uploaded.pop();
	}
	while(canceled.length > 0) 
	{
        canceled.pop();
	}
	while(images.length > 0) 
	{
        images.pop();
	}
	$('tbody.item').remove();
	var list_item = $("#file_list");
	if( file_list.files.length > 0 )
	{
		for( var i = 0; i < file_list.files.length; i++ )
		{
			var _item = $('<tbody class="item" id="item-'+i+'"><tr>' +
						'<td>' + file_list.files[i].name + '</td>' + 
						'<td>' + file_list.files[i].type + '</td>' + 
						'<td>' + bytesToSize(file_list.files[i].size) + '</td>' + 
						'<td><div class="progress">' + 
						  '<div class="bar" id="bar-file-' + i + '"></div>' + 
						  '<div class="percent" id="percent-file-' + i + '">0%</div>' + 
						'</div></td>' + 
						'</td><td><div id="status"></div></td>' +
						'<td><div class="feature"><button id="upload" class="btn btn-primary start" onclick="preupload(' + i + ');"><span>Start</span></button>' +
						'<button id="reset" type="reset" class="btn btn-danger delete" onclick="cancel(' + i + ');" style="margin-left: 5px"><span>Remove</span></button>' +
						'<input style="margin-left: 5px" name="idcheck[]" type="checkbox" onclick="nv_UncheckAll(this.form, \'idcheck[]\', \'check_all[]\', this.checked);" value="' + i + '" /></div></td></tr></tbody>');
			
			_item.appendTo(list_item);
			if( i%2 == 1 ) $('#item-'+i).addClass('second');
		}
	}
}

function bytesToSize(bytes, precision)
{  
	var kilobyte = 1024;
	var megabyte = kilobyte * 1024;
	var gigabyte = megabyte * 1024;
	var terabyte = gigabyte * 1024;
   
	if ((bytes >= 0) && (bytes < kilobyte)) {
		return bytes + ' B';
 
	} else if ((bytes >= kilobyte) && (bytes < megabyte)) {
		return (bytes / kilobyte).toFixed(precision) + ' KB';
 
	} else if ((bytes >= megabyte) && (bytes < gigabyte)) {
		return (bytes / megabyte).toFixed(precision) + ' MB';
 
	} else if ((bytes >= gigabyte) && (bytes < terabyte)) {
		return (bytes / gigabyte).toFixed(precision) + ' GB';
 
	} else if (bytes >= terabyte) {
		return (bytes / terabyte).toFixed(precision) + ' TB';
 
	} else {
		return bytes + ' B';
	}
}

function np_saveinfo(formid, pid, col)
{
	var inputs = $('#form-' + formid + ' :input');
	var values = $('#form-' + formid).serialize();
	$('#td-' + formid).html('Saving...');
	nv_ajax('post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=image&save=1&pid=' + pid + '&formid=' + formid + '&colspan=' + col + '&' + values, '', 'np_saveinfo_rs');
}

function np_saveinfo_rs(res)
{
	rs = res.split('_');
	if( rs[0] == 'ok' )
	{
		$('#td-' + rs[1]).html('Lưu thành công');
		showform(rs[1], rs[2], 1, rs[3]);
	}
}

function resetform( formid )
{
	$('form#' + formid + ' select').val('');
	$('form#' + formid + ' input[type="text"]').val('');
	$('form#' + formid + ' input[type="number"]').val('');
}

function delcatdata(current_catid)
{
	nv_ajax('post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=delete&cat_edit=1&action=delcatdata&current_catid=' + current_catid, '', 'np_delete_rs');
}

function movecatdata( current_catid, new_catid )
{
	if( new_catid != 0 )
	{
		var e = document.getElementById("new_cat");
		new_catid = e.options[e.selectedIndex].value;
	}
	nv_ajax('post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=delete&cat_edit=1&action=movecatdata&current_catid=' + current_catid + '&new_catid=' + new_catid, '', 'np_delete_rs');
}

function np_movephoto( new_catid, value, get_from )
{
	if( get_from == '' ) get_from = 'photos';
	nv_ajax('post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=delete&cat_edit=1&action=movephoto&new_catid=' + new_catid + '&get_from=' + get_from + '&value=' + value, '', 'np_delete_rs');
}

function np_addToAlbum( albumid, listphoto )
{
	if( confirm('Bạn có chắc chắn thêm vào album này?' ) )
	{
		nv_ajax('post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=delete&type=addtoalbum&albumid=' + albumid + '&value=' + listphoto, '', 'np_delete_rs');
	}
}