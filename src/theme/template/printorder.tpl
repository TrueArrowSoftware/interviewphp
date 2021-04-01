<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{PageTitle}</title>
    <title>{PageTitle}</title>
    <meta name="description" content="{MetaDescription}">    
    <meta name="keywords" content="{MetaKeyword}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Mobile Specific Meta  -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    
    <!-- CSS -->
	<link rel="stylesheet" type="text/css" href="{HomeURL}/node_modules/bootstrap/dist/css/bootstrap.min.css" >
	<link rel="stylesheet" type="text/css" href="{HomeURL}/node_modules/jquery-colorbox/example1/colorbox.css">
	<link rel="stylesheet" type="text/css" href="{HomeURL}/node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
	<link rel="stylesheet" type="text/css" href="{HomeURL}/node_modules/jquery-confirm/dist/jquery-confirm.min.css">
	<link rel="stylesheet" type="text/css" href="{HomeURL}/node_modules/dropzone/dist/min/dropzone.min.css">
	<link rel="stylesheet" type="text/css" href="{HomeURL}/node_modules/select2/dist/css/select2.min.css">
	<link rel="stylesheet" type="text/css" href="{HomeURL}/node_modules/@fortawesome/fontawesome-free/css/all.css">
	<link rel="stylesheet" type="text/css" href="{HomeURL}/node_modules/typeface-lato/index.css">
	<link rel="stylesheet" type="text/css" href="{HomeURL}/node_modules/intl-tel-input/build/css/intlTelInput.css">
	<link rel="stylesheet" type="text/css" href="{HomeURL}/theme/css/TAS/secure-admin.css">
	<link rel="stylesheet" type="text/css" href="{HomeURL}/theme/css/TAS/securelogin-color.css">
	<link rel="stylesheet" type="text/css" href="{HomeURL}/theme/css/custom.css">
	<link rel="stylesheet" type="text/css" href="{HomeURL}/theme/css/responsive.css">
	<link rel="shortcut icon" href="{HomeURL}/theme/images/favicon.png" type="image/x-icon"/> 
    
    <!-- JS -->
  	<style tyle="text/css">
  	@media print { 
		#header, #footer, #nav { display: none !important; } 
	}
  	
  	</style>
    
    <style type="text/css" media="print">
@page {
    size: auto;   /* auto is the initial value */
    margin: 0;  /* this affects the margin in the printer settings */
}
</style>
<script type="text/javascript">
var HomeURL = '{HomeURL}';
</script>
{MetaExtra}
</head>
<body>
	<div class="container">
	  	<div class="header py-4 col-md-12 text-center">
			<img src="{HomeURL}/theme/images/logo.png">
		</div>  
    	{Content}
	
	</div>
	<script type="text/javascript" src="{HomeURL}/node_modules/jquery/dist/jquery.min.js"></script>
	<script type="text/javascript" src="{HomeURL}/node_modules/popper.js/dist/umd/popper.min.js"></script>
	<script type="text/javascript" src="{HomeURL}/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
	<script type="text/javascript">
	$( document ).ready(function() {
	  window.print();
	  window.onmousemove = function()
	  {
	   window.close();
	  }
	});
	</script>
    {FooterInclusion}
</body>
</html>
