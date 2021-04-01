$(document).ready(function(){

	$('.datetime').datepicker({
		format : 'yyyy-mm-dd',
		clearBtn : true,
		autoclose : true
	});

	/*for user role check and uncheck*/
	$('#b1').click(function(){
		var roletype = $("#role").val();
		if(roletype=='admin')
		{
			$(this).closest('table').find('.adminmodule td .action').prop('checked', 'checked');
		}
		else if(roletype=='user')
		{
			$(this).closest('table').find('.usermodule td .action').prop('checked', 'checked');
		}
	});
	
	$('#b2').click(function(){
		$('.action').prop('checked', false);  
	});
	
	$('.getclass').click(function(){
		var mod=$(this).closest('tr').attr('id');
		$('.'+mod+' .action').prop('checked','checked');  
	});
	
	$('.removeclass').click(function(){
		var mod=$(this).closest('tr').attr('id');
		$('.'+mod+' .action').prop('checked',false);  
	});
	
	/*mobile responsive code*/
	$('.mobile-menu-btn').on('click', function () {
	    $('.mobile-bar-icon').toggleClass('open');
	    $('.bg-custom').toggleClass('menusetting');
	});

	if ($(window).width() <= 991) {
	    $('.dropdown-setting').removeClass('dropdown-menu');
	    $('.dropdown-setting').addClass('collapse');
	    $('.dropdown-toggle').on('click', function () {  
	    	$('.dropdown-setting.collapse.show').removeClass('show');
	    });
	}

});
