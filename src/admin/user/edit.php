<?php
namespace Framework;
require ("../template.php");
require_once ("./include.php");
$messages = array();
$userid = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$users = new User($userid);
if ($userid <= 0 || ! $permission->CheckOperationPermission("user", "edit", $GLOBALS['user']->UserRoleID)) {
    \TAS\Core\Web::Redirect("index.php");
}

if ($userid > 0) {
    if (! $users->IsLoaded()) {
        \TAS\Core\Web::Redirect("index.php");
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $d = array();
    $d = \TAS\Core\Entity::ParsePostToArray(User::GetFields($userid));
    $d['editdate'] = date("Y-m-d H:i:s");
    $d['phone'] = str_replace("+", "", $d['phone']);
    $d['userroleid'] = (int)\TAS\Core\DataFormat::DoSecure($_POST['userroleid']);
    if (strlen($d['phone']) == '12') {
        $isupdated = $users->Update($d);
        if ($isupdated) {
            $userlog = UserLog::AddEvent('Existing User Updated', 'Updated User', $userid);
            $messages[] = array(
                "message" => _("User has been updated successfully."),
                "level" => 1
            );
        } else {

            if (count(User::GetErrors()) > 0) {
                $a = User::GetErrors();
                foreach ($a as $i => $v) {
                    $messages[] = $v;
                }
            } else {
                $messages[] = array(
                    "message" => _("Unable to update user at this moment. Please try again later."),
                    "level" => 10
                );
            }
        }
    } else {
        $messages[] = array(
            "message" => _("Please enter valid phone number."),
            "level" => 10
        );
    }
}

$pageParse['Content'] = DisplayForm($userid);
echo \TAS\Core\TemplateHandler::TemplateChooser("admin");
