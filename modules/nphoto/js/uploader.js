function upload(filesToUp, i)
{
	var numsfile = filesToUp.files.length;
	if( i < numsfile )
	{
		if (window.XMLHttpRequest)
		{
			// code for IE7+, Firefox, Chrome, Opera, Safari
			xhr = new XMLHttpRequest();
		}
		else
		{
			// code for IE6, IE5
			xhr = new ActiveXObject("Microsoft.XMLHTTP");
		}
		
		var data = new FormData();
		var url = $('#uform').attr('action');
		data.append('file', filesToUp.files[i]);
		xhr.open("POST",url , true);
		
		 var progressBar = document.querySelector('#percent-file-' + i);
		 //globalprogress = $('#percent').html().split( '%' );
		 //globalprogress = parseFloat( globalprogress[0] );
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
				  
				  
				  var currengprogress = ( 100 / numsfile ) * i;
				  globalprogress = currengprogress + ( n / numsfile );
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
				//var gprogress = ( 100 / numsfile ) * ( i + 1 );
				//$('#percent').html( parseInt( gprogress ) + '%' );
				//$('#bar').css( 'width', gprogress + '%' );
				//$('#status-file-' + i).addClass('status').html(xhr.responseText);
				i++;
				upload(filesToUp, i);
			}
		}
	}
}

function showReadyUploadFile(file_list)
{
	var list_item = $("#file_list");
	list_item.find('li').remove();
	if( file_list.files.length > 0 )
	{
		for( var i = 0; i < file_list.files.length; i++ )
		{
			var _item = $('<li class="item-'+i+'">' +
						'<span class="name">' + file_list.files[i].name + '</span> ' + 
						'<span class="type">(' + file_list.files[i].type + ')</span> ' + 
						'<span class="size">' + bytesToSize(file_list.files[i].size) + '</span><br />' + 
						'<div class="progress">' + 
						  '<div class="bar" id="bar-file-' + i + '"></div>' + 
						  '<div class="percent" id="percent-file-' + i + '">0%</div>' + 
						'</div>' + 
						'<div id="status-file-' + i + '"></div>' +
						'</li>');
			
			_item.appendTo(list_item);
		}
	} 
}

/**
 * Convert number of bytes into human readable format
 *
 * @param integer bytes     Number of bytes to convert
 * @param integer precision Number of digits after the decimal separator
 * @return string
 */
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