<?php
namespace Framework;
require ("../template.php");
require_once ("./include.php");
$messages = array();
if (! $permission->CheckOperationPermission("customer", "add", $GLOBALS['user']->UserRoleID)) {
    \TAS\Core\Web::Redirect("index.php");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $d = array();
    $d = \TAS\Core\Entity::ParsePostToArray(User::GetFields());
    $d['adddate'] = date("Y-m-d h:i:s");
    $d['phone'] = str_replace("+", "", $d['phone']);
    $d['userroleid'] = '-1';
    $d['verifyemail'] = '1';
    $d['password'] = password_hash($d['password'], PASSWORD_DEFAULT);
    if (strlen($d['phone']) == '12') {
        $userID = User::Add($d);
        if ($userID > 0) {
            $userlog = UserLog::AddEvent('New Customer Created', 'Customer Registered', $userID);
            $messages[] = array(
                "message" => _("User has been added successfully."),
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
                    "message" => _("Unable to create customer at this moment. Please try again."),
                    "level" => 10
                );
            }
        }
    }
    else
    {
        $messages[] = array(
            "message" => _("Please enter valid phone number."),
            "level" => 10
        );
    }
    
}

$pageParse['Content'] = DisplayForm();
echo \TAS\Core\TemplateHandler::TemplateChooser("admin");
