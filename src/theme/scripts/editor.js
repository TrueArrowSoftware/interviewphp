$(function(){
	tinymce.init({
   	 	selector: ".editor",
   	 	document_base_url : "{HomeURL}/",
   	 	relative_urls : false,
        remove_script_host : false,
        convert_urls : false,
        height: "500px",
    	plugins: [
        	"advlist autolink lists link image charmap preview anchor",
        	"searchreplace visualblocks code fullscreen",
        	"insertdatetime media table contextmenu paste"
    	],
    	toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
	});
});