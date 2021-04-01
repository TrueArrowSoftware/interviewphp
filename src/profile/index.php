<?php
namespace Framework;
require "./../configure.php";
require ("./../template.php");
$userDetail = new User($_SESSION['userid']);
$pageParse['PageTitle'] = 'Dashboard | '.$GLOBALS['AppConfig']['SiteName'];
//$pageParse['BreadCrumb'] = BreadCrumb('Profile','', '');
$messages = array();
if(isset($_GET['verification']) && $_GET['verification']=='1')
{
    User::SendVerificationEmail($_SESSION['userid']);
    \TAS\Core\Web::Redirect( $GLOBALS['AppConfig']['HomeURL'].'/profile/index.php?verification=true');
}

if(isset($_GET['verification']) && $_GET['verification']=='true')
{
    $messages[] = array(
        "message" =>"Account verification mail has been successfully send.",
        "level" => 1
    );
}
$pageParse['Content'] ='
<section class="contentarea">
  <div class="container padding70">
    <div class="row dashboard-page">
        <div class="col-md-3">
            '.SideBarHeader().'
        </div>
        <div class="col-md-9 pl-lg-5">
            '.($userDetail->VerifyEmail=='0'?'<div class="alert alert-danger">We have sent you an email with a link to verify your email.</div>':'').'
            ' . \TAS\Core\UI::UIMessageDisplay($messages) . '
            <div class="d-flex align-items-center justify-content-between">
                <h2 class="heading mb-0">Personal Details</h2>
                <div>
                '.($userDetail->VerifyEmail=='0'?'<a href="{HomeURL}/profile/index.php?verification=1">Send Verification Email</a> | ':'').'
                <a href="{HomeURL}/profile/edit.php">Edit Profile</a></div>
            </div><hr>  
          <table class="table table-striped">
                <tr>
                    <th>First Name</th>
                    <td>'.ucwords($userDetail->FirstName).'</td>
                </tr>
                <tr>
                    <th>Last Name</th>
                    <td>'.ucwords($userDetail->LastName).'</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>'.$userDetail->Email.'</td>
                </tr>
                <tr>
                    <th>Phone</th>
                    <td>'.$userDetail->Phone.'</td>
                </tr>
           </table> 
    </div>
  </div>
</div>
</section>';

echo \TAS\Core\TemplateHandler::TemplateChooser("single");