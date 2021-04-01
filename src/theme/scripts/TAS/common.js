$(document).ready(function(){
	/*Global set get value*/
	var $GET = new Object();
	function parseQueryVars() {
		window.location.search.replace(new RegExp("([^?=&]+)(=([^&]*))?", "g"), function($0, $1, $2, $3) {
			$GET[$1] = $3;
		});
	}
	parseQueryVars();
	
	/*for tooltip*/
	$('[data-toggle="tooltip"]').tooltip();
	
	/*datepicker using by class*/
	$('.date').datepicker({
		uiLibrary: 'bootstrap4',
		iconsLibrary: 'fontawesome',
		format: 'mm/dd/yyyy',
	});
	
	/*admin navigation toggle*/
	$('#leftsideCollapse').on('click', function () {
	    $(this).toggleClass('close-icon');
	    $('#accordion').toggleClass('active');
	    $('#rightside').toggleClass('active');
	    $('.collapse.in').toggleClass('in');
	    $('a[aria-expanded=true]').attr('aria-expanded', 'false');
	});

	/*phone number input masking*/
	$(".phone").mask("999-999-9999");
	
	/*form validation*/
	$(".validate").each(function() {
		$(this).validate();
	});
	
	/*auto hide message*/
	$(".hidemessage").delay(20000).hide("slow");
	
	/*Zip code validation*/
	$(".zipcode").mask("99999");
	
	/*dropdown select2 jquery*/
	$('select').select2();
	$("select").not(".noautoselect2").not("[multiple]").not("select2min").select2({ selectOnClose: true});
    $(".select2min").not(".noautoselect2").not("[multiple]").select2({ minimumInputLength: 2, selectOnClose: true});	
	
	
	/*Admin Navigation ID Store in local storage*/
	var getid = localStorage.getItem("adminnavigationid");
	if(getid!='null'){
		
		 $('.card-header a[href="#' + getid + '"]').click();
	}

	$(".nav-item").click(function(){
		var id = $(this).closest('#accordion').find('div .collapse.show').attr('id');
		localStorage.setItem("adminnavigationid", id);
	});

	$(".static-link").click(function(){
		localStorage.setItem("adminnavigationid", '');
	});		
	/*admin filter show and hide*/
	$("#filter").click(function(){
		$("#filterbox").slideToggle().queue(function(){;
			if($("#filterbox").is(":visible")) {
				$("#filter").val("Hide Filters");
			} else {
				$("#filter").val("Show Filters");
			}
			$(this).dequeue();
		});
	});
	$("#filterbox").hide();

	/*checkbox select all and deselect*/
	
	$(".checkall").click(function() {
		if ($(this).is(":checked")) {
			$(".checkall_child").prop("checked",true);
		} else {
			$(".checkall_child").prop("checked",false);
		}
	});
	
	$(".checkall_child").click(function() {
		if (!$(this).is(":checked")) {
			$(".checkall").prop("checked",false);
		}
	});	

});

/***
 * Attach first combo to another dropdown to fill in the values. 
 * Combo2 can be list of multiple combo using CSS Selector separated by comma
 * field should be in order of <value>|<displaytext> ex. "id|{location_code (state)}" 
 */

//AttachCombos("#retailerid", "#userid", "user", "userid|{firstname}", "retailerid", "Select", "##userid##");

function AttachCombos(combo1, combo2, tabletosearch, field, valuename, firstcombo, selectedvalue) {
	var allcombos = combo2.split(",");
	$(combo1).on('change', function( event, hasCallBack){
		$.getJSON(HomeURL + '/handler/combo.php', 
				{field: field, table: tabletosearch, value: $(this).val(), valuefield: valuename}, 
				function(data){
					var opts = [];					
					if(firstcombo != "") opts.push('<option value="">' + firstcombo + '</option>');
					$.each(data, function(i, item) {
						opts.push('<option value="' + i + '">' + item + '</option>');
					});
					
					for(index in allcombos){
						$(allcombos[index]).html(opts.join(''));
						$(allcombos[index]).val(selectedvalue);
					}

					if (hasCallBack !== undefined) hasCallBack();
				});
	});
}


/* Email Unique Validation*/
$.validator.addMethod("unique-username", function(value, element) {
	var isunique = false;
	var rel = $(element).data("rel");
	$.ajax({
		url : HomeURL + "/handler/unique.php",
		type : 'POST',
		dataType : 'jsonp',
		data : {
			
			data : value,
			type : "username",
			rel : rel
		},
		async : false,
		success : function(data) {
			
			isunique = ((data == 1) ? true : false);
		}
	});
	return this.optional(element) || isunique;
}, "The username you have entered is already registered.");

/* Phone no 10 digit validation check */
$.validator.addMethod("validate-phone", function(value, element) {
	var isunique = false;
	var rel = $(element).data("rel");
	$.ajax({
		url : HomeURL + "/handler/unique.php",
		type : 'POST',
		dataType : 'jsonp',
		data : {
			
			data : value,
			type : "validatephone",
			rel : rel
		},
		async : false,
		success : function(data) {
			isunique = ((data == 1) ? true : false);
		}
	});
	return this.optional(element) || isunique;
}, "Please enter 10 digit valid mobile number.");


/* Global unique check */
$.validator.addMethod("unique", function(value, element) {
	var isunique = false;
	var rel = $(element).data("rel");
	var relfield=$(element).data('field');
	var reltype=$(element).data("uniquetype");
	var relidfield=$(element).data("id");
	
	$.ajax({
		url : HomeURL + "/handler/unique.php",
		type : 'POST',
		dataType : 'jsonp',
		data : {
			data : value,
			type : reltype,
			field: relfield,
			idfield: relidfield,
			rel : rel
		},
		async : false,
		success : function(data) {
			isunique = ((data == 1) ? true : false);
		}
	});
	return this.optional(element) || isunique;
}, "Already in use. Choose unique data!!!");

/*Global handle*/
$(".handler").click(function() {
	var src = $(this).attr("href");
	$.ajax({
		url : src,
		type : 'POST',
		success : function(data) {
			if (data == 1)
				window.location.href = window.location.href;
			else
				alert(data);
		}
	});
	return false;
});


/* Password Validation */
$.validator.addMethod("validatepassword", function(value, element) {
	var isunique = false;
	var rel = $(element).data("rel");
	$.ajax({
		url : HomeURL + "/handler/unique.php",
		type : 'POST',
		dataType : 'jsonp',
		data : {
			
			data : value,
			type : "password",
			rel : rel
		},
		async : false,
		success : function(data) {
			isunique = ((data == 1) ? true : false);
		}
	});
	return this.optional(element) || isunique;
}, "Password should be at least 8 characters in length and should include at least one upper case letter, one lower case letter, one number and one special character.");


/* Email Unique Validation*/
$.validator.addMethod("unique-email", function(value, element) {
	var isunique = false;
	var rel = $(element).data("rel");
	$.ajax({
		url : HomeURL + "/handler/unique.php",
		type : 'POST',
		dataType : 'jsonp',
		data : {
			
			data : value,
			type : "email",
			rel : rel
		},
		async : false,
		success : function(data) {
			isunique = ((data == 1) ? true : false);
		}
	});
	return this.optional(element) || isunique;
}, "The email address you have entered is already registered.");


/* Email Unique Validation*/
$.validator.addMethod("unique-email", function(value, element) {
	var isunique = false;
	var rel = $(element).data("rel");
	$.ajax({
		url : HomeURL + "/handler/unique.php",
		type : 'POST',
		dataType : 'jsonp',
		data : {
			
			data : value,
			type : "email",
			rel : rel
		},
		async : false,
		success : function(data) {
			isunique = ((data == 1) ? true : false);
		}
	});
	return this.optional(element) || isunique;
}, "The email address you have entered is already registered.");

/* Product Code Unique Validation*/
$.validator.addMethod("unique-productcode", function(value, element) {
	var isunique = false;
	var rel = $(element).data("rel");
	$.ajax({
		url : HomeURL + "/handler/unique.php",
		type : 'POST',
		dataType : 'jsonp',
		data : {
			
			data : value,
			type : "productcode",
			rel : rel
		},
		async : false,
		success : function(data) {
			isunique = ((data == 1) ? true : false);
		}
	});
	return this.optional(element) || isunique;
}, "Please use unique product code.");

