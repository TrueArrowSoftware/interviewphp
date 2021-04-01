<?php
namespace Framework;
require_once ("./template.php");
require_once ("./include.php");

if (! isset($_SESSION['userid']) || ! is_numeric($_SESSION['userid'])) {
    \TAS\Core\Web::Redirect($GLOBALS['AppConfig']['AdminURL'] . "login.php");
} else {
    $GLOBALS['user'] = new User($_SESSION['userid']);
    if ($permission->CheckOperationPermission('coreadmin', 'access', $GLOBALS['user']->UserRoleID)) {
        $pageParse['MetaExtra'] .= '<meta http-equiv="refresh" content="300" />';
        $pageParse['Content'].=DisplayStats();
        echo \TAS\Core\TemplateHandler::TemplateChooser("admin");
    } else {
       \TAS\Core\Web::Redirect($GLOBALS['AppConfig']['AdminURL'] . "/login.php?dd=" . $GLOBALS['user']->UserRoleID);
    }
}