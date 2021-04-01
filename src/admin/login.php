<?php
namespace Framework;
require("./../configure.php");
require("./../template.php");
if($_SERVER['REQUEST_METHOD']=='POST' )
{
    if (Captcha::ValidateCaptcha()) {
        $userid = User::AuthenticateUser(\TAS\Core\DataFormat::DoSecure($_POST['username']), \TAS\Core\DataFormat::DoSecure($_POST['password']));
        
        if(is_bool($userid) && $userid=== false) {
            $messages[] = array("message"=>_("Authentication Failed !!! Please retry."), "level"=>10);
        } else {
            $GLOBALS['user'] = new User($userid);
            
            if ($permission->CheckModulePermission('coreadmin', $GLOBALS['user']->UserRoleID)) {
                $_SESSION['userid'] = $userid;
                UserLog::AddEvent('Existing User Login Success','User Logged in',$userid);
                \TAS\Core\Web::Redirect($GLOBALS['AppConfig']['AdminURL'] );
            } else {
                $messages[] = array("message"=>_("You do not have access rights. Permission Denied."), "level"=>10);
            }
        }
    } else {
        $messages[] = array("message"=>_("Human Verification Failed"), "level"=>10);
    }
}

$pageParse['Content'] .='<section class="container-fluid admin-login primary-bg-color">
    <div class="row">
       <div class="container page-body-wrapper">
        <form class="w-100 d-flex align-items-center validate loginform" id="user-form" method="POST">
    
                <div class="col-lg-5 col-md-10 col-sm-11 mx-auto content-wrapper">
                <h1 class="main-tile primary-text-color text-center pb-5">
                    <!--img src="{HomeURL}/theme/images/logo.jpg" class="img-responsive w-100"-->
                </h1>
    
	            <h2 class="main-tile primary-text-color-green">Secure Login</h2>
                  '.\TAS\Core\UI::UIMessageDisplay($messages).'
                    <div class="form-group username-box">
                    <label>Username</label>
                        <input type="text" name="username" class="form-control username primary-border-color required" placeholder="Username" value="" required />
                        <span class="primary-bg-color"><i class="fas fa-user"></i></span>
                    </div>
                    <div class="form-group password-box">
                    <label>Password</label>
                        <input type="password" name="password" class="form-control password primary-border-color" placeholder="**********" value="" required />
                        <span class="primary-bg-color"><i class="fas fa-lock"></i></span>
                    </div>
                    <div class="captcha">'.Captcha::GetCaptcha().'</div>
                    <div class="form-group sbmt-btn mt-4 mb-0">
                        <input href="#" class="btn primary-color primary-bg-color w-100 py-2 login" type="submit" value="Login" />
                    </div>
                </div>
            </div>
        </form></div></div></section>';

$pageParse['FooterInclusion'] = '<script type="text/javascript">
$(function(){
    $(".validate").validate({});
});
</script>';
echo \TAS\Core\TemplateHandler::TemplateChooser("login");