<!DOCTYPE html>
<html lang="en">
<head>
    <!--- Basic Page Needs  -->
    <meta charset="utf-8">
    <title>{PageTitle}</title>
    <meta name="description" content="{MetaDescription}">    
    <meta name="keywords" content="{MetaKeyword}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
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
	<script type="text/javascript">
		var HomeURL = '{HomeURL}';
    </script>
		
	{MetaExtra}
	
</head>
<body>
<div id="iframepage">    	
	{Content}
</div>
   
<script type="text/javascript" src="{HomeURL}/node_modules/jquery/dist/jquery.min.js"></script>
	<script type="text/javascript" src="{HomeURL}/node_modules/popper.js/dist/umd/popper.min.js"></script>
	<script type="text/javascript" src="{HomeURL}/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="{HomeURL}/node_modules/select2/dist/js/select2.min.js"></script>
	<script type="text/javascript" src="{HomeURL}/node_modules/jquery-confirm/dist/jquery-confirm.min.js"></script>
	<script type="text/javascript" src="{HomeURL}/node_modules/jquery-validation/dist/jquery.validate.min.js"></script>
	<script type="text/javascript" src="{HomeURL}/node_modules/jquery-colorbox/jquery.colorbox-min.js"></script>
	<script type="text/javascript" src="{HomeURL}/node_modules/jquery-mask-plugin/dist/jquery.mask.min.js"></script>
	<script type="text/javascript" src="{HomeURL}/node_modules/dropzone/dist/min/dropzone.min.js"></script>
	<script type="text/javascript" src="{HomeURL}/node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
	<script type="text/javascript" src="{HomeURL}/node_modules/chart.js/dist/Chart.min.js"></script>
	<script type="text/javascript" src="{HomeURL}/theme/scripts/TAS/common.admin.js"></script>
	<script type="text/javascript" src="{HomeURL}/theme/scripts/TAS/common.js"></script>
	<script type="text/javascript" src="{HomeURL}/theme/scripts/custom.admin.js"></script>
{FooterInclusion}

</body>
</html>