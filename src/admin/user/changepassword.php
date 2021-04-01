<?php
require ("./../template.php");
require_once './include.php';
require_once './../include.php';
$messages = array();
$userid = isset($_GET['userid']) ? (int) $_GET['userid'] : 0;
if ($userid <= 0 || ! $permission->CheckOperationPermission("user", "edit", $GLOBALS['user']->UserRoleID)) {
    \TAS\Core\Web::Redirect("index.php");
}

$user = new \Framework\User($userid);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $npassword = \TAS\Core\DataFormat::DoSecure($_POST['newpassword']);
    $cpassword = \TAS\Core\DataFormat::DoSecure($_POST['confirmpassword']);
    if ($npassword === $cpassword) {
        $cpassword = password_hash($cpassword, PASSWORD_DEFAULT);
        $GLOBALS['db']->Execute("update " . $GLOBALS['Tables']['user'] . " set password= '" . $cpassword . "'  where userid=" . \TAS\Core\DataFormat::DoSecure($userid));
        $messages[] = array(
            "message" => _("Password has been updated successfully."),
            "level" => 1
        );
        $userlog = \Framework\UserLog::AddEvent('Existing User Change login password', 'Password change', $userid);
    } else {
        $messages[] = array(
            "message" => _("Unable to update password record at this moment. Please try again."),
            "level" => 10
        );
    }
}

$pageParse['Content'] .= '<div class="col-md-12 p-0"> <div class="card card-body card-radius p-0">
<h2 class="borderbottom-set">Reset Password for (' . ucwords($user->FirstName) . ' ' . ucwords($user->LastName) . ')</h2>
<div class="px-3 py-2">' . \TAS\Core\UI::UIMessageDisplay($messages) . '</div>';
$pageParse['Content'] .= ChangePasswordForm();
$pageParse['Content'] .= "</div>";
echo \TAS\Core\TemplateHandler::TemplateChooser("popup");
