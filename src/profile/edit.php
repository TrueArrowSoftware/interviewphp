<?php
namespace Framework;
require "./../configure.php";
require ("./../template.php");
$messages =array();
$pageParse['PageTitle'] = 'Edit Profile | '.$GLOBALS['AppConfig']['SiteName'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $d = array();
    $d['firstname'] = \TAS\Core\DataFormat::DoSecure($_POST['firstname']);
    $d['lastname'] = (isset($_POST['lastname'])?\TAS\Core\DataFormat::DoSecure($_POST['lastname']):'');
    $d['email'] = \TAS\Core\DataFormat::DoSecure($_POST['email']);
    $d['username'] = \TAS\Core\DataFormat::DoSecure($_POST['email']);
    $d['phone'] = \TAS\Core\DataFormat::DoSecure($_POST['phone']);
    $d['editdate'] = date('Y-m-d H:i:s');
    $userDetails = new User($_SESSION['userid']);
    $isupdated = $userDetails->Update($d);
    if ($isupdated) {
        $default =array();
        $default['title'] = \TAS\Core\DataFormat::DoSecure($_POST['firstname']);
        $default['subtitle'] = (isset($_POST['lastname'])?\TAS\Core\DataFormat::DoSecure($_POST['lastname']):'');
        $default['email'] = \TAS\Core\DataFormat::DoSecure($_POST['email']);
        $default['phone'] = \TAS\Core\DataFormat::DoSecure($_POST['phone']);
        $default['address1'] = \TAS\Core\DataFormat::DoSecure($_POST['address1']);
        $default['address2'] = (isset($_POST['address2'])?\TAS\Core\DataFormat::DoSecure($_POST['address2']):'');
        $default['city'] = \TAS\Core\DataFormat::DoSecure($_POST['city']);
        $default['state'] = (isset($_POST['state'])?\TAS\Core\DataFormat::DoSecure($_POST['state']):'');
        $default['country'] = \TAS\Core\DataFormat::DoSecure($_POST['country']);
        $default['zipcode'] = (isset($_POST['zipcode'])?\TAS\Core\DataFormat::DoSecure($_POST['zipcode']):'');
        $addressID = Address::GetAddressID($_SESSION['userid'], 'user', 'default');
        if($addressID > 0)
        {
            $default['editdate'] = date("Y-m-d H:i:s");
            $address = new Address($addressID);
            $default['addressid'] = $addressID;
            $address->Update($default);
        }
        else
        {
            $default['addresstype'] = 'default';
            $default['ownerid'] = $_SESSION['userid'];
            $default['ownertype'] = 'user';
            $default['adddate'] = date("Y-m-d H:i:s");
            Address::Add($default);
        }
        
    }else {
        if (count(User::GetErrors()) > 0) {
            $a = User::GetErrors();
            foreach ($a as $i => $v) {
                $messages[] = $v;
            }
        } else {
            $messages[] = array(
                "message" => _("Unable to update profile at this moment. Please try again."),
                "level" => 10
            );
        }
    }
}


$userDetails = new User($_SESSION['userid']);

$address= Address::GetDefaultAddress($_SESSION['userid'], 'user', 'default');

//$pageParse['BreadCrumb'] = BreadCrumb('Edit Profile','', '');
$pageParse['Content'] ='
<section class="contentarea">
  <div class="container padding70">
    <div class="row dashboard-page">
        <div class="col-md-3">
            '.SideBarHeader().'
        </div>
        
        <div class="col-md-9 pl-lg-5">
            <div class="d-flex align-items-center justify-content-between">
                <h2 class="heading mb-0">Edit Profile</h2>
            </div><hr> 
            ' . \TAS\Core\UI::UIMessageDisplay($messages) . ' 
          <form action="" method="post" class="validate career-form">
            <div class="row">
                <div class="form-group col-md-6">
                    <input type="text" class="form-control" name="firstname" placeholder="First Name*" value="'.ucwords($userDetails->FirstName).'" required/>
                </div>
                <div class="form-group col-md-6">
                    <input type="text" class="form-control" name="lastname" placeholder="Last Name" value="'.ucwords($userDetails->LastName).'"/>
                </div>
                <div class="form-group col-md-6">
                    <input type="email" class="form-control unique-email" name="email" placeholder="Email*" value="'.$userDetails->Email.'" required/>
                </div>
                <div class="form-group col-md-6">
                    <input type="text" class="form-control phone" name="phone" placeholder="Phone*" value="'.$userDetails->Phone.'" required/>
                </div>
                
                <div class="form-group col-md-6">
                    <input type="text" class="form-control" name="address1" placeholder="Address1*" value="'.(isset($address['address1'])?$address['address1']:'').'" required/>
                </div>
                <div class="form-group col-md-6">
                    <input type="text" class="form-control" name="address2" placeholder="Address2" value="'.(isset($address['address2'])?$address['address2']:'').'"/>
                </div>
                <div class="form-group col-md-6">
                    <input type="text" class="form-control" name="city" placeholder="City*" value="'.(isset($address['city'])?$address['city']:'').'" required/>
                </div>
                <div class="form-group col-md-6">
                    <input type="text" class="form-control" name="state" placeholder="State" value="'.(isset($address['state'])?$address['state']:'').'"/>
                </div>
                <div class="form-group col-md-6 select2error">
                    <select name="country" class="form-control select2" required>
                        '.\TAS\Core\UI::RecordSetToDropDown($GLOBALS['db']->Execute("Select * from " . $GLOBALS['Tables']['countries']), (isset($address['country'])?$address['country']:''), 'countryid', 'countryname',true,'Select Country').'    
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <input type="text" class="form-control" name="zipcode" placeholder="Post Code" value="'.(isset($address['zipcode'])?$address['zipcode']:'').'"/>
                </div>
            </div>
            <div class="row">
                <div class="form-group col mb-0">
                    <button class="btn btn--primary commonBtn w-auto" name="login" type="submit">Update Profile</button> 
                </div>
            </div>
        </form>
    </div>
  </div>
</div>
</section>';
$pageParse['FooterInclusion']='<script type="text/javascript" src="{HomeURL}/theme/scripts/TAS/common.js"></script>';
echo \TAS\Core\TemplateHandler::TemplateChooser("single");