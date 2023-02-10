/*!
	* Dhaka Solution CRM
	* Copyright 2018 Md Jahid Khan Limon
	* Licensed under Dhaka Solution (http://dhakasolution.com/Privacy-Policy.html)
*/
$(function() {
		$('#side-menu').metisMenu();
		/* G-From plugin developed by limon */
		if($(".g-form").length) {
			$(".g-form").each(function(){
				var _g_f_v = $(this).find('input, textarea').val();
				if(_g_f_v)
					$(this).find("label").addClass("label-top");
			});
		} 
		$(".g-form input, .g-form textarea").on('focus blur', function(e){
			$(this).parent().find('label').addClass('label-top');
			if(e.type == 'blur') {
				if($(this).val() == '' || $(this).val() ==  null)
					$(this).parent().find('label').removeClass('label-top');
			}
		});
		/* G-From plugin developed by limon */
	
    $(window).bind("load resize", function() {
        var topOffset = 50;
        var width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
        if (width < 768) {
            $('div.navbar-collapse').addClass('collapse');
            topOffset = 100; // 2-row-menu
        } else {
            $('div.navbar-collapse').removeClass('collapse');
        }

        var height = ((this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height) - 1;
        height = height - topOffset;
        if (height < 1) height = 1;
        if (height > topOffset) {
            $("#page-wrapper").css("min-height", (height) + "px");
        }
    });
		
		$('.clickable-row').click(function(){
			var href= $(this).attr('data-href');
			window.location.href = href;
		}).on('click', 'a, input, label', function(event){
			event.stopPropagation();
		});
		
		//customer list checkboxes
		$('#select-cus').click(function(){
			$('.clickable-row').unbind('click');
			$('.clickable-row').click(function(e) {
				$this_checkbox = $(this).find('input[type="checkbox"]');
				$this_checkbox.prop('checked', !$this_checkbox.prop('checked'));
			}).on('click', 'input[type="checkbox"]', function(){
				$(this).prop('checked', !$(this).prop('checked'));
			});
			$(this).hide(); $('.hidden-btns').removeClass('hidden-md hidden-lg');
			$('.table').find('thead tr').prepend('<th><a href="javascript:void(0)" id="check_all"><i class="fa fa-check"></i></a><th>');
			$('table tbody tr').each(function(e){
				$(this).prepend('<td><input type="checkbox" name="cutomers[]" /><td>');
			});
		});
		all_selected = false;
		$('table').on('click', '#check_all', function(){
			$(this).find('i').toggleClass('fa-times');
			var times = $(this).find('i.fa-times');
			$('tbody tr').each(function(){
				var checkbox = $(this).find('input[type="checkbox"]');							
				(times.length) ? checkbox.prop('checked', true) : checkbox.prop('checked', false);			
			});
		});
		$('.send-remove').click(function(){
			var checked = [];
			$('input[type="checkbox"]:checked').each(function(){checked.push($(this).closest('.clickable-row').attr('data-userid'));});
			if(typeof(selected_customers)!="undefined" && selected_customers!==null) {
				var sc_array = selected_customers.split(','); 
				for(sc_i = 0; sc_i < sc_array.length; sc_i++){
					if($('[data-userid="'+sc_array[sc_i]+'"]').length && !$('[data-userid="'+sc_array[sc_i]+'"]').find('input[type="checkbox"]').is(':checked')){
						checked = jQuery.grep(checked, function(value) {return value != sc_array[sc_i];});
						continue;
					}
					if($.inArray(sc_array[sc_i], checked) !== -1) {} else checked.push(sc_array[sc_i]);
				}
			}
			
			if($(this).attr('data-type') == 'send'){
				var msg_to = (all_selected) ? 'all' : encodeURIComponent(checked.join(','));
				window.location.href = "admin/messages/?new_message="+msg_to;
			}
			else if($(this).attr('data-type') == 'remove') {
				var ref = (typeof($(this).attr('data-ref')) != "undefined" && $(this).attr('data-ref') !== null)
										? $(this).attr('data-ref') : "admin/customer-list/";
				window.location.href = ref+"?delete="+encodeURIComponent(checked.join(','));
			}
			else if($(this).attr('data-type') == 'paging'){
				var newpage = encodeURIComponent($(this).attr('data-pn')); var user_type =  encodeURIComponent($(this).attr('data-ut'));
				var ref = (typeof($(this).attr('data-ref')) != "undefined" && $(this).attr('data-ref') !== null)
										? $(this).attr('data-ref') : "admin/customer-list/";
				window.location.href = ref+"?user_type="+user_type+"&page="+newpage+"&s_c="+encodeURIComponent(checked.join(','));
			}
		});
		
		
    var url = window.location; var url_s = url.toString();
    var element = $('ul.nav a').filter(function() {
			var this_href = this.href; 
			return (url_s.indexOf(this_href) >= 0) ? true : false;
    }).addClass('active').parent();
    while(true) {
			if (element.is('li')) element = element.parent().addClass('in').parent();
			else break;
    }
});
