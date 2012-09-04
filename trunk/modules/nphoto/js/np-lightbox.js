$(document).ready(function() {
	
	$.fn.piroFadeOut = function(speed, callback) {
		$(this).fadeOut(speed, function() {
		if(jQuery.browser.msie)
			$(this).get(0).style.removeAttribute('filter');
		if(callback != undefined)
			callback();
		});
	};
	
	$('html').bind('keyup', function (c) {
		 if(c.keyCode == 13) {
			c.preventDefault();
			$('#getinfo').click();
		}
	});
	
	var np_object = $('a[class*="np-imgurl"]');
	var map = new Object();
	
	for ( var i = 0; i < np_object.length; i++) 
	{
		var it = $(np_object[i]);
		map['a.'+ it.attr('class').match(/^np-imgurl\w*/)]=0;
	}
	var np_settings = new Array();
	for ( var key in map ) 
	{
		np_settings.push(key);
	}
	for ( var i = 0; i < np_settings.length; i++ ) 
	{
		$(np_settings[i]+':first').addClass('first');
		$(np_settings[i]+':last').addClass('last');
	}
	var np_gallery = $(np_object);
	$('a[class*="np-imgurl"]').each(function(rev){this.rev = rev+0});
	
	var struct =(
		'<div class="piro_overlay"></div>'+
		'<table class="piro_html" cellpadding="0" cellspacing="0">'+
		'<tr>'+
		'<td class="h_t_l"></td>'+
		'<td class="h_t_c" title="drag me!!"></td>'+
		'<td class="h_t_r"></td>'+
		'</tr>'+
		'<tr>'+
		'<td class="h_c_l"></td>'+
		'<td class="h_c_c">'+
		'<div class="piro_loader" title="close"><span></span></div>'+
		'<div class="resize">'+
		'<div class="nav_container">'+
		'<a href="#prev" class="piro_prev" title="previous"></a>'+
		'<a href="#next" class="piro_next" title="next"></a>'+
		'<div class="piro_prev_fake">prev</div>'+
		'<div class="piro_next_fake">next</div>'+
		'<div class="piro_close" title="close"></div>'+
		'</div>'+
		'<div class="div_reg" id="div_reg"></div>'+
		'</div>'+
		'</td>'+
		'<td class="h_c_r"></td>'+
		'</tr>'+
		'<tr>'+
		'<td class="h_b_l"></td>'+
		'<td class="h_b_c"></td>'+
		'<td class="h_b_r"></td>'+
		'</tr>'+
		'</table>'
		);
	
	var rz_img =0.95; /*::::: ADAPT IMAGE TO BROWSER WINDOW SIZE :::::*/
	
	$(this).unbind(); 
	$('a[rel*="np_gallery"]').bind('click', function (e) {
		pageurl = $(this).attr('href');
		e.preventDefault();
		if( pageurl != window.location){
			window.history.pushState({path:pageurl},'',pageurl);	
		}
		
		$('#np-popup-container').append(struct);
		$('.nav_container').hide();

		var y = $(window).height();
		var x = $(window).width();
		var wrapper = $('.piro_html'),
		piro_capt = $('.caption'),
		piro_bg = $('.piro_overlay'),
		piro_next = $('.piro_next'),
		piro_prev = $('.piro_prev'),
		piro_next_fake = $('.piro_next_fake'),
		piro_prev_fake = $('.piro_prev_fake'),
		piro_close = $('.piro_close'),
		div_reg = $('.div_reg'),
		piro_loader = $('.piro_loader'),
		resize = $('.resize'),
		btn_info = $('.btn_info');
		
		var current_img = $(this);
		var params = current_img.attr('id').split('-');
		params[0] = parseInt( params[0] );
		params[1] = parseInt( params[1] );
		
		wrapper.css({left:  ((x/2)-(250))+ 'px',top: parseInt($(document).scrollTop())+(100)});
		//piro_bg.css({'opacity':0.9});	
		piro_bg.css({ 'opacity': 0.5, 'display': 'block' });
		
		$(piro_prev).add(piro_next).bind('click',function(c) {
			$('.nav_container').hide();
			c.preventDefault();
			piro_next.add(piro_prev).hide();
			var obj_count = parseInt($('a[class*="np-imgurl"]').filter('.item').attr('rev'));
			var start = $(this).is('.piro_prev') ? $('a[class*="np-imgurl"]').eq(obj_count - 1) : $('a[class*="np-imgurl"]').eq(obj_count + 1);
			$('#np-popup-container').html('');
			start.click();
		});
		
		$(window).resize(function(){
			var new_y = $(window).height();
			var new_x = $(window).width();
			var new_h = wrapper.height();
			var new_w = wrapper.width();
			wrapper.css({
				left:  ((new_x/2)-(new_w/2))+ 'px',
				top: parseInt($(document).scrollTop())+(new_y-new_h)/2
				});			  
		});	
		function scrollIt (){
			$(window).scroll(function(){
				var new_y = $(window).height();
				var new_x = $(window).width();
				var new_h = wrapper.height();
				var new_w = wrapper.width();
				wrapper.css({
					left:  ((new_x/2)-(new_w/2))+ 'px',
					top: parseInt($(document).scrollTop())+(new_y-new_h)/2
				});			  
			});
		}
		var piro_scroll = true;
		if( piro_scroll == true){
			scrollIt();
		}
		
		$(np_gallery).filter('.item').removeClass('item');
		$(this).addClass('item');
		
		open_all();
		$("body").css("overflow", "hidden");
		nv_ajax('post', nv_siteroot + 'index.php', nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=photo&pid=' + params[2] + '&scwidth=' + x + '&scheight=' + y + '&fitwidth=' + params[0] + '&fitheight=' + params[1], 'div_reg', '');
		piro_loader.hide();
		
		if($(this).is('.first'))
		{
			piro_prev.hide();
			piro_next.show();
			piro_prev_fake.show().css({'opacity':0.5,'visibility':'hidden'});
		}
		else
		{
			piro_next.add(piro_prev).show();
			piro_next_fake.add(piro_prev_fake).hide();	  
		}
		if($(this).is('.last'))
		{
			piro_prev.show();
			piro_next_fake.show().css({'opacity':0.5,'visibility':'hidden'});
			piro_next.hide();	
		}
		if($(this).is('.pirobox'))
		{
			piro_next.add(piro_prev).hide();	
		}
		
		function close_all()
		{
			if($('.piro_close').is(':visible'))
			{
				$('.my_frame').remove();
				wrapper.add(div_reg).add(resize).stop();
				var ie_sucks = wrapper;
				if ( $.browser.msie ) 
				{
					ie_sucks = div_reg.add(piro_bg);
					$('.div_reg img').remove();
				}
				else
				{
					ie_sucks = wrapper.add(piro_bg);
				}
				ie_sucks.piroFadeOut(200,function(){
					//window.location = main_url;
					window.history.pushState({path:main_url},'',main_url);	
					$('#np-popup-container').html('');
					$("body").css("overflow", "auto");
				});
			}
		}
		
		function open_all() 
		{
			wrapper.add(piro_bg).add(div_reg).add(piro_loader).show();
		
			function animate_html()
			{
				var y = $(window).height();
				var x = $(window).width();
				
				if( params[1] + 50 > y || params[0] + 330 > x)
				{
					var _x = ( params[0] )/x;
					var _y = ( params[1] )/y;
					if ( _y > _x )
					{
						params[0] = Math.round( params[0]*(rz_img/_y) );
						params[1] = Math.round( params[1]*(rz_img/_y) );
						params[0] = Math.round( params[0]*( (params[1]-50)/params[1] ) );
						params[1] -= 50;
						if( params[0] + 330 > x )
						{
							params[1] = Math.round( params[1]*(x-330)/params[0] );
							params[0] = x - 70;
						}
						else
						{
							params[0] += 330;
						}
						params[1] += 50;
					}
					else
					{
						params[0] = Math.round( params[0]*(rz_img/_x) );
						params[1] = Math.round( params[1]*(rz_img/_x) );
						params[1] = Math.round( params[1]*( (params[0]-330)/params[0] ) );
						params[0] -= 330;
						if( params[1] + 50 > y )
						{
							params[0] = Math.round( params[0]*(y-50)/params[1] );
							params[1] = y - 55;
						}
						else
						{
							params[1] += 50;
						}
						params[0] += 330;
					}
					params[0] = Math.round( params[0]*( (params[1] - 80)/params[1] ) );
					params[1] -= 80;
				}
				else
				{
					params[0] += 330;
					params[1] += 50;
					params[0] = Math.round( params[0]*( (params[1] - 20)/params[1] ) );
					params[1] -= 20;
				}
				if( params[0] == 'full' && params[1] == 'full' )
				{
					params[1] = y - 70;	
					params[0] = x - 55;
				}	
				
				piro_close.hide();
				div_reg.add(resize).animate({
					'height':+ (params[1]) +'px',
					'width':+ (params[0] + 5)+'px'
					},700).css('visibility','visible');
					
				wrapper.animate({
					height:+ (params[1])+ 20 +'px',
					width:+ (params[0]) + 20 +'px',
					left:  ((x/2)-((params[0])/2)) + 'px',
					top: parseInt($(document).scrollTop())+(y-params[1])/2-20
					},700 ,function(){
						piro_next.add(piro_prev).css({'height':'20px','width':'20px'});
						piro_next.add(piro_prev).add(piro_prev_fake).add(piro_next_fake).css('visibility','visible');
						$('.nav_container').show();
						piro_close.show();
				});
			}
			
			div_reg.css('overflow', 'hidden');
			resize.css('overflow', 'hidden');
			$('.my_frame').remove();
			piro_close.add(btn_info).add(piro_capt).hide();
			$("#div_reg").addClass('immagine');
			animate_html();
			$('.immagine').live('click',function(){
				piro_capt.slideToggle(200);
			});	
		}
		
		piro_close.add(piro_loader).add(piro_bg).bind('click',function(y){y.preventDefault(); close_all(); });			
	});
})

$(window).bind('popstate', function() {
	$('#np-popup-container').html('');
	window.history.pushState({path:main_url},'',main_url);	
});