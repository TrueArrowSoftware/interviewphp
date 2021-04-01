// sidebar height adjustment
var callback = function () {
	     var w = $('.main').height();
//	     var h = $('.navbar').height();
	     var h = $('.sidebar-bg').height();
	     if( w > h ) {
	    	 $('.sidebar-bg').height(w); 
	     }
	  };
$(document).ready(callback);
$(window).resize(callback);


$(function() {
	
	/*jquery confirm dailog box for delete*/
	$(".delete").click(function(e) {
		e.preventDefault();	
		var URL = $(this).prop("href");		
		$.confirm({
			title: 'Are you sure to delete?',
				content: '',
				type: 'red',
				buttons: {
					confirm: function () {
						window.location.href=URL;
					},
					cancel: function () {
					
					}
				}
		});
	});
	
	/*colorbox popup for iframe*/
	$(".colorboxpopup").colorbox({iframe:true, width:"80%", height: "80%"});
	$(".inlinepopup").colorbox({inline:true, width:"60%"});
});


Dropzone.autoDiscover = false;
$(document).ready(function(){
    	
	$('#v-sorting').change(function() {
    	var c = $(this).val();
    	window.location = HomeURL+"/listing.php?sortby=" + c;
    });
    
    $('.menu-button').click(function(){
    	$('.sidebar-bg').toggle();
    });
	    
	    
	/*Dropzone upload image using ajax*/
    Dropzone.autoDiscover = false;
    
    /*Dropzone for multiple image upload and page reload*/
    
    /*if ($("#myimageuploader").length > 0) {
		var dzImageUploader = new Dropzone("#myimageuploader", {
			url : $("#myimageuploader").data("dropzoneurl"),
			paramName : 'imagefile',
			queuecomplete : function() {
			    window.location.href = window.location.href;
		    }
		});
	}*/
    
    /*Dropzone for process 1 image*/
    if ($("#myimageuploader").length > 0) {
		var dzImageUploader = new Dropzone("#myimageuploader", {
			url : $("#myimageuploader").data("dropzoneurl"),
			paramName : 'imagefile',
			maxFiles : 1,
			autoProcessQueue : false,
			queuecomplete : function() {
				dzImageUploader.removeAllFiles(true);
			},
			success : function(file, response) {
					window.location.reload();
			},
			sending : function(file, xhr, formData) {
				var form = $("#myimageuploader").data("form");
				formData.append("formdata", $(form).serialize());
			}
		});
		
		$("#myimageuploader .submitbutton").click(function(ev){
			ev.preventDefault();
			dzImageUploader.processQueue();		
		});
	}
	
	// only numeri value with dot
	$(document).on('keydown', '.price', function(e){-1!==$.inArray(e.keyCode,[46,8,9,27,13,110])||(/65|67|86|88/.test(e.keyCode)&&(e.ctrlKey===true||e.metaKey===true))&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault()});

});

/*table sort by drag and drop*/
$('.tablesort').sortable({
	  containerSelector: 'table',
	  itemPath: '> tbody',
	  itemSelector: 'tr',
	  placeholder: '<tr class="placeholder"/>',
	  group: 'serialization',
	  onDrop:function ($item, container, _super, event) {
		  $item.removeClass(container.group.options.draggedClass).removeAttr("style");
		  $("body").removeClass(container.group.options.bodyClass);
		  
		  var data = $('.tablesort').sortable("serialize").get();
		  var url = $('.tablesort').data("url");
		  $.post(url, {
		        data : data,
		        action : "order",
		        success: function(){
			      
		        }
		    });  
	 }
});
