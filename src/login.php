<?php
namespace Framework;
require("./configure.php");
require("./template.php");
$messages = array();
$pageParse['PageTitle'] = 'Login | '.$GLOBALS['AppConfig']['SiteName'];

/*  BreadCrumb*/
/* $headerimage = $GLOBALS['db']->Fetch($GLOBALS['db']->Execute("Select * from " . $GLOBALS['Tables']['pages'] . " where page= 'Online Shop'"));
$headerimage = $headerimage['headerimage'];
$pageParse['PageName'] = 'Login';
$pageParse['BreadCrumb'] = BreadCrumb('Login', '', $headerimage); */


UserLogin();

if($_SERVER['REQUEST_METHOD']=='POST' )
{
    
    if (Captcha::ValidateCaptcha()) {
        $userid = User::AuthenticateUser(\TAS\Core\DataFormat::DoSecure($_POST['email']), \TAS\Core\DataFormat::DoSecure($_POST['password']));
        if(is_bool($userid) && $userid === false) {
            $messages[] = array("message"=>_("Invalid email or password."), "level"=>10);
        } else {
            session_regenerate_id ( true );
            $_SESSION['userid'] = $userid;
            \TAS\Core\Web::Redirect( $GLOBALS['AppConfig']['HomeURL'].'/profile/index.php');
        }
    } else {
        $messages[] = array("message"=>_("Human Verification Failed"), "level"=>10);
    }
}

$pageParse['Content'] .='
    
<section class="login-head common-section myaccount-section">
	<div class="container py-7">
		<div class="col-xl-8 col-lg-10 col-md-12 col-sm-8 mx-auto">
		    <div class="row">
		        <div class="col-md-6 col-sm-12 myaccount-box mt-md-0 mt-5 px-0">
		            <div class="card card--account-card">
		            	<h4 class="heading">Log in to</h4>
		            	<ul class="list-style">
		            		<li>List items for sale</li>
		            		<li>Get price drop notifications for items you like</li>
		            		<li>Set up deal alerts</li>
		            		<li>Message buyers and sellers</li>
		            	</ul>
		            </div>
		        </div>
		        <div class="col-md-6 col-sm-12 myaccount-box px-0">
		        	<div class="card card--account-card login-box">
                        '.\TAS\Core\UI::UIMessageDisplay($messages).'
                        <form action="" method="post" class="validate" novalidate="novalidate">
		                    <div class="form-group">
		                        <input type="email" class="form-control" name="email" placeholder="Email*" required/>
		                    </div>
		                    <div class="form-group">
		                        <input type="password" class="form-control" name="password" placeholder="Password*" required/>
		                    </div>
                            <div class="captcha">'.Captcha::GetCaptcha().'</div>
		                    <div class="form-group">
		                        <input type="submit" name="submit" value="Log In" class="btn btn--primary fullBtn">
		                    </div>
			            </form>
			            <h6 class="text-center">Don\'t have an account? <a href="{HomeURL}/register.php">Signup Here!</a></h6>
			            <a class="text-center" href="{HomeURL}/forgotpassword.php">Forgot password?</a>
		        	</div>
		        </div>
		    </div>
		</div>
	</div>
</section>';
echo \TAS\Core\TemplateHandler::TemplateChooser("single");