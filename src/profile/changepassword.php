<?php
namespace Framework;
require "./../configure.php";
require ("./../template.php");
$messages =array();
$pageParse['PageTitle'] = 'Change Password | '.$GLOBALS['AppConfig']['SiteName'];

if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
    if( $_POST['password'] ==  $_POST['confirm-password']) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $update = $GLOBALS['db']->Execute("Update " . $GLOBALS['Tables']['user'] . " set password = '" . $password . "' where userid = '" .$_SESSION['userid']. "'");
        if ($update) {
            $messages[] = array(
                'message' =>'Password has been updated successfully.',
                'level' => 1,
            );
        }
        else
        {
            $messages[] = array(
                'message' =>'Unable to update password at this moment. Please try again.',
                'level' => 10,
            );
        }
        
    } else {
        $messages[] = array(
            'message' => 'Confirm password not match.',
            'level' => 10,
        );
    }
}



//$pageParse['BreadCrumb'] = BreadCrumb('Change Password','', '');
$pageParse['Content'] ='
<section class="contentarea">
  <div class="container padding70">
    <div class="row dashboard-page">
        <div class="col-md-3">
            '.SideBarHeader().'
        </div>
                
        <div class="col-md-9 pl-lg-5">
            <div class="d-flex align-items-center justify-content-between">
                <h2 class="heading mb-0">Change Password</h2>
            </div><hr>
            ' . \TAS\Core\UI::UIMessageDisplay($messages) . '
          <form action="" method="post" class="validate">
            <div class="row">
               <div class="row col-md-6">
                    <div class="form-group col-md-12">
                        <input type="password" class="form-control validatepassword" name="password" placeholder="Password*" required/>
                    </div>
                    <div class="form-group col-md-12">
                        <input type="password" class="form-control validatepassword" name="confirm-password" placeholder="Confirm Password*" required/>
                    </div>
                    <div class="form-group col">
                       <button class="btn btn--primary commonBtn w-auto" name="login" type="submit">Update Password</button> 
                    </div>
                </div>
            </div>
        </form>
    </div>
  </div>
</div>
</section>';
$pageParse['FooterInclusion']='<script type="text/javascript" src="{HomeURL}/theme/scripts/TAS/common.js"></script>';
echo \TAS\Core\TemplateHandler::TemplateChooser("single");