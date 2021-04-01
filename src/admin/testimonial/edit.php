<?php
namespace Framework;

require ("../template.php");
require_once ("./include.php");
$messages = array();
$testimonialid = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($testimonialid <= 0 || ! $permission->CheckOperationPermission("testimonial", "edit", $GLOBALS['user']->UserRoleID)) {
    \TAS\Core\Web::Redirect("index.php");
}
$testimonial = new Testimonial($testimonialid);
if ($testimonialid > 0) {
    if (! $testimonial->IsLoaded()) {
        \TAS\Core\Web::Redirect("index.php");
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $d = array();
    $d = \TAS\Core\Entity::ParsePostToArray(Testimonial::GetFields($testimonialid));
    $d['editdate'] = date("Y-m-d H:i:s");
    $isupdated = $testimonial->Update($d);
    if ($isupdated) {
        $messages[] = array(
            "message" => _("Testimonial has been updated successfully."),
            "level" => 1
        );
    } else {
        $messages[] = array(
            "message" => _("Unable to update testimonial at this moment. Please try again later."),
            "level" => 10
        );
    }
}

$pageParse['Content'] = DisplayForm($testimonialid);
echo \TAS\Core\TemplateHandler::TemplateChooser("admin");