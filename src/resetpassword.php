<?php 
namespace Framework;
require "./configure.php";
require ("./template.php");
$messages =array();
$pageParse['PageTitle'] = 'Reset Password | '.$GLOBALS['AppConfig']['SiteName'];
/*  BreadCrumb*/
/* $headerimage = $GLOBALS['db']->Fetch($GLOBALS['db']->Execute("Select * from " . $GLOBALS['Tables']['pages'] . " where page= 'Online Shop'"));
 $headerimage = $headerimage['headerimage'];
 $pageParse['PageName'] = 'Login';
 $pageParse['BreadCrumb'] = BreadCrumb('Login', '', $headerimage); */
UserLogin();

if( ! isset($_GET['code']) || (trim($_GET['code']) == '') ) {
    \TAS\Core\Web::Redirect('index.php');
}

if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
    $code = trim(\TAS\Core\DataFormat::DoSecure($_GET['code']),'.');
    if( $_POST['password'] ==  $_POST['confirm-password']) {
        $a = User::ResetPassword($code, $_POST['password']);
        if(is_bool($a) && $a == true){
            $messages[] = array(
                'message' =>'Password has been updated successfully.',
                'level' => 1,
            );
            $pageParse['MetaExtra'] .= '<meta http-equiv="refresh" content="5; url={HomeURL}/login.php">';
        } else {
            $messages[] = array(
                'level' => 10,
                'message' => _($a)
            );
        }
    } else {
        $messages[] = array(
            'message' => 'Confirm password not match.',
            'level' => 10,
        );
    }
}


//$pageParse['BreadCrumb'] = BreadCrumb('Reset Account Password','', '');
$pageParse['Content']='
<section class="contentarea">
    <div class="container padding70">
        <div class="row">
            <div class="col-xl-5 col-md-8 mx-auto">
                <div class="card card-header border-0 p-sm-5 p-4">
                    <h2 class="heading mb-3">Reset Password</h2>
                    ' . \TAS\Core\UI::UIMessageDisplay($messages) . '
                    <form action="" method="post" class="validate login-form">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <input type="password" class="form-control validatepassword" name="password" placeholder="Password*" required/>
                            </div>
                            <div class="form-group col-md-12">
                                <input type="password" class="form-control validatepassword" name="confirm-password" placeholder="Confirm Password*" required/>
                            </div>
                            <div class="form-group col mb-0">
                                <button class="btn btn--primary commonBtn" name="login" type="submit">Update Password</button> 
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</section>';
$pageParse['FooterInclusion'].='<script type="text/javascript" src="{HomeURL}/theme/scripts/TAS/common.js"></script>';
echo \TAS\Core\TemplateHandler::TemplateChooser("single");