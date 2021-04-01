<?php
namespace Framework;

require ("../template.php");
require_once ("./include.php");
$messages = array();
if (! $permission->CheckOperationPermission('company', 'add', $GLOBALS['user']->UserRoleID)) {
    \TAS\Core\Web::Redirect("index.php");
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $d = \TAS\Core\Entity::ParsePostToArray(Company::GetFields());
    $companyid = Company::Add($d);
    if ($companyid > 0) {
        $messages[] = array(
            "message" => _("Brand has been added successfully."),
            "level" => 1
        );
    } else {
        if (count(Company::GetErrors()) > 0) {
            $a = Company::GetErrors();
            foreach ($a as $i => $v) {
                $messages[] = $v;
            }
        } else {
            $messages[] = array(
                "message" => _("Unable to create brand at this moment. Please try again later."),
                "level" => 10
            );
        }
    }
}
$pageParse['Content'] .= DisplayForm();
echo \TAS\Core\TemplateHandler::TemplateChooser("admin");