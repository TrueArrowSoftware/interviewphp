<!DOCTYPE html>
<html lang="en">
<head>
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
	{MetaExtra}
</head>

<body>
  	<div class="container-fluid fixed-top section-1 primary-bg-color">
            <div class="row">
                <div class="col-md-12 top-bar">
                    <nav class="navbar navbar-expand-lg bg-custom p-0">
                        <div class="col-lg-3 col-md-4 px-0 logo-trigger-btn">
                            <button type="button" id="leftsideCollapse" class="btn btn-custom primary-bg-color-dark p-0">
                                <span class="menu-icon leftmenutrigger text-white right-side"><i class="fas fa-bars"></i></span>
                            </button>
                            <a class="navbar-brand logo text-white py-0" href="{AdminURL}">{AdminTop}</a>
                        </div>
                        <div class="col-lg-9 col-md-8 px-0 topright-menu">
                            <div class="navbar-collapse" id="navbarText">
                                <ul class="navbar-nav ml-md-auto d-md-flex right-menu">
                                    <!--li class="nav-item py-1 text-sm-right">
                                        <a class="nav-link btn text-white cursor-default">Hi, {UserName}</a>
                                    </li-->
                                    <li class="nav-item py-1 text-sm-right">
                                        <a class="nav-link btn btn-custom-dashboard text-white" href="{AdminURL}/logout.php"><i class="fas fa-sign-out-alt mr-2"></i>Logout</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>

        <div class="container-fluid px-0 section-2">
            <div class="row m-0">
                <div class="content-left primary-bg-color px-0" id="accordion">
                    <div class="sticky-use primary-bg-color">
                        {Navigation}
                        <div class="d-md-none left-menu-logout-btn card-header primary-bg-color">
                            <a class="d-flex card-link text-white align-items-center" href="{AdminURL}/logout.php"> <i class="fas fa-sign-out-alt iconsize mr-2" aria-hidden="true"></i> Logout </a>
                        </div>
                    </div>
                </div>
                <div class="content-right px-0" id="rightside">
                    <div class="content-area">
                        {Content}
                    </div>
                </div>
            </div>
        </div>
	      
	<script type="text/javascript">
		var HomeURL = '{HomeURL}';
	</script>
	   
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
	<script type="text/javascript" src="{HomeURL}/node_modules/jquery-sortable/source/js/jquery-sortable.js"></script>
	<script type="text/javascript" src="{HomeURL}/node_modules/chart.js/dist/Chart.min.js"></script>
	<script type="text/javascript" src="{HomeURL}/theme/scripts/TAS/common.admin.js"></script>
	<script type="text/javascript" src="{HomeURL}/theme/scripts/TAS/common.js"></script>
	<script type="text/javascript" src="{HomeURL}/theme/scripts/custom.admin.js"></script>
	
	{FooterInclusion}
	
</body>
</html>
