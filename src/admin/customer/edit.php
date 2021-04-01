<?php
namespace Framework;
require ("../template.php");
require_once ("./include.php");
$messages = array();
$userid = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$users = new User($userid);
if ($userid <= 0 || ! $permission->CheckOperationPermission("customer", "edit", $GLOBALS['user']->UserRoleID)) {
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
    $d['userroleid'] = '-1';
    $d['phone'] = str_replace("+", "", $d['phone']);
    if (strlen($d['phone']) == '12') {
        $isupdated = $users->Update($d);
        if ($isupdated) {
            $userlog = UserLog::AddEvent('Existing Customer Updated', 'Updated Customer', $userid);
            $messages[] = array(
                "message" => _("Customer has been updated successfully."),
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
                    "message" => _("Unable to update customer at this moment. Please try again later."),
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
