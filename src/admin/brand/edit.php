<?php
namespace Framework;

require ("../template.php");
require_once ("./include.php");
$messages = array();
$companyid = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($companyid <= 0 || ! $permission->CheckOperationPermission("company", "edit", $GLOBALS['user']->UserRoleID)) {
    \TAS\Core\Web::Redirect("index.php");
}
$company = new Company($companyid);

if($companyid > 0)
{
    if(!$company->IsLoaded())
    {
        \TAS\Core\Web::Redirect("index.php");
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $d = array();
    $d = \TAS\Core\Entity::ParsePostToArray(Company::GetFields($companyid));
    $isupdated = $company->Update($d);
    if ($isupdated) {
        $messages[] = array(
            "message" => _("Brand has been updated successfully."),
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
                "message" => _("Unable to update brand at this moment. Please try again later."),
                "level" => 10
            );
        }
    }
}

$pageParse['Content'] = DisplayForm($companyid);
echo \TAS\Core\TemplateHandler::TemplateChooser("admin");