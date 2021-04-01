<?php
namespace Framework;
require "./configure.php";
require ("./template.php");
$pageParse['PageTitle'] = 'Sign Up | '.$GLOBALS['AppConfig']['SiteName'];
$messages =array();

/*  BreadCrumb*/
/* $headerimage = $GLOBALS['db']->Fetch($GLOBALS['db']->Execute("Select * from " . $GLOBALS['Tables']['pages'] . " where page= 'Online Shop'"));
 $headerimage = $headerimage['headerimage'];
 $pageParse['PageName'] = 'Login';
 $pageParse['BreadCrumb'] = BreadCrumb('Login', '', $headerimage); */

UserLogin();

/* New User Register Process */

if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
    if (Captcha::ValidateCaptcha()) {
        $d = array();
        $d['firstname'] = \TAS\Core\DataFormat::DoSecure($_POST['firstname']);
        $d['lastname'] = (isset($_POST['firstname'])?\TAS\Core\DataFormat::DoSecure($_POST['lastname']):'');
        $d['email'] = \TAS\Core\DataFormat::DoSecure($_POST['email']);
        $d['username'] = \TAS\Core\DataFormat::DoSecure($_POST['email']);
        $d['password'] = password_hash(\TAS\Core\DataFormat::DoSecure($_POST['password']), PASSWORD_DEFAULT);;
        $d['phone'] = str_replace("+", "", \TAS\Core\DataFormat::DoSecure($_POST['phone']));
        $d['userroleid'] = -1;
        $d['status'] = '1';
        $d['allowlogin'] = '1';
        $d['verifyemail'] = '0';
        $d['adddate'] = date('Y-m-d H:i:s');
        if (strlen($d['phone']) == '12') {
            $userID = User::Add($d);
            if($userID > 0)
            {
                $_SESSION['userid'] = $userID;
                UserLog::AddEvent('New User Created', 'User Registered', $userID);
                /* Send Verification Email */
                User::SendVerificationEmail($userID);
                
                \TAS\Core\Web::Redirect($GLOBALS['AppConfig']['HomeURL'] . "/registeration-thanks");
            }
            else
            {
                if (count(User::GetErrors()) > 0) {
                    $a = User::GetErrors();
                    foreach ($a as $i => $v) {
                        $messages[] = $v;
                    }
                } else {
                    $messages[] = array(
                        "message" => _("Unable to create user at this moment. Please try again."),
                        "level" => 10
                    );
                }
            }
        }
        else
        {
            $messages[] = array(
                "message" => _("Please enter 10 digit valid phone number."),
                "level" => 10
            );
        }
    }
    else 
    {
        $messages[] = array("message"=>_("Human Verification Failed"), "level"=>10);
    }
}



$pageParse['Content']='<section class="login-head common-section myaccount-section">
	<div class="container py-7">
		<div class="col-xl-8 col-lg-10 col-md-12 col-sm-8 mx-auto">
		    <div class="row">
		        <div class="col-md-6 col-sm-12 myaccount-box mt-md-0 mt-5 px-0">
		            <div class="card card--account-card">
		            	<h4 class="heading">Create an account to</h4>
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
	                    ' . \TAS\Core\UI::UIMessageDisplay($messages) . '
			            <form  id="sellerform" action=""  method="post" class="validate questionmarkform">
                            <div class="form-group requiredfield">
                                <div class="inputfield">
		                        <input type="text" class="form-control" name="firstname" value="'.(isset($_POST['firstname'])?$_POST['firstname']:'').'" placeholder="First Name*" required/>
                                </div>
                             </div>
                            <div class="form-group requiredfield">
                                <div class="inputfield">
		                          <input type="text" class="form-control" name="lastname" value="'.(isset($_POST['lastname'])?$_POST['lastname']:'').'" placeholder="Last Name"/>
                                </div>
		                    </div>
                            <div class="form-group requiredfield">
                                <div class="inputfield">
		                        <input type="email" class="form-control unique-email" data-rel="" id="email" name="email" value="'.(isset($_POST['email'])?$_POST['email']:'').'" placeholder="Email*" required/>
                                </div>
                             </div>
                            <div class="form-group requiredfield">
                                <div class="inputfield">
		                        <input type="text" class="form-control phone validate-phone" data-rel="" id="phone" name="phone" value="'.(isset($_POST['phone'])?$_POST['phone']:'').'" placeholder="Mobile Number*" required/>
                                </div>
                             </div>
		                    <div class="form-group password-field requiredfield">
                                <div class="inputfield">
		                        <input type="password" class="form-control mb-2 validatepassword" name="password" placeholder="Password*" required/>
                                </div>
                            </div>
                            <div class="captcha">'.Captcha::GetCaptcha().'</div>
		                    <div class="form-group">
		                        <input type="submit" name="submit" id="submitform" value="Sign Up" class="btn btn--primary fullBtn">
		                    </div>
			            </form>
			            <h6 class="text-center mb-4">Already have an account? <a href="{HomeURL}/login.php">Log In</a></h6>
			            <p>By signing up I agree to Shopping cart <a href="#">Terms of Use</a> and <a href="#">Privacy Policy</a> and I consent to receiving marketing from Subs and third party offers.</p>
		        	</div>
		        </div>
		    </div>
		</div>
	</div>
</section>';
$pageParse['FooterInclusion'].='<script type="text/javascript" src="{HomeURL}/theme/scripts/TAS/common.js"></script>';
echo \TAS\Core\TemplateHandler::TemplateChooser("single");