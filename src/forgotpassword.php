<?php
namespace Framework;
require "./configure.php";
require ("./template.php");
$messages =array();
$pageParse['PageTitle'] = 'Forgot Password | '.$GLOBALS['AppConfig']['SiteName'];

/*  BreadCrumb*/
/* $headerimage = $GLOBALS['db']->Fetch($GLOBALS['db']->Execute("Select * from " . $GLOBALS['Tables']['pages'] . " where page= 'Online Shop'"));
 $headerimage = $headerimage['headerimage'];
 $pageParse['PageName'] = 'Login';
 $pageParse['BreadCrumb'] = BreadCrumb('Login', '', $headerimage); */

UserLogin();

/* Forgot Password Process */

if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
    if (\TAS\Core\DataValidate::ValidateEmail($_POST['email'])) {
        if (User::ResetPasswordMail(\TAS\Core\DataFormat::DoSecure($_POST['email']) ) ) {
            $messages [] = array (
                "message" => _ ("A Reset Password Link has been sent on your email address." ),
                "level" => 1
            );
            $pageParse['MetaExtra'] .= '<meta http-equiv="refresh" content="5; url={HomeURL}/login.php">';
        } else {
            $messages [] = array (
                "message" => _ ( "Incorrect account information. Please re-enter your account detail to reset the password" ),
                "level" => 10
            );
        }
    } else {
        $messages [] = array (
            "message" => _ ( "Fail to identify your account. Please check your details to reset password" ),
            "level" => 10
        );
    }
}


//$pageParse['BreadCrumb'] = BreadCrumb('Forgot Password','', '');
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
                                Enter the email address associated with your account, and we’ll email you a link to reset your password.
                            </div>
                            <div class="form-group col-md-12">
                                <input type="email" class="form-control" name="email" placeholder="Email*" required/>
                            </div>
                            <div class="form-group col mb-0">
                                <button class="btn btn--primary commonBtn" name="login" type="submit">Send Reset Link</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</section>';

echo \TAS\Core\TemplateHandler::TemplateChooser("single");