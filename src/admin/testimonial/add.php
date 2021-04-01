<?php
namespace Framework;

require ("../template.php");
require_once ("./include.php");
$messages = array();
if (! $permission->CheckOperationPermission('testimonial', 'add', $GLOBALS['user']->UserRoleID)) {
    \TAS\Core\Web::Redirect("index.php");
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $d = \TAS\Core\Entity::ParsePostToArray(Testimonial::GetFields());
    $d['adddate'] = date("Y-m-d H:i:s");
    $testimonialid = Testimonial::Add($d);
    if ($testimonialid > 0) {
        $messages[] = array(
            "message" => _("Testimonial has been added successfully."),
            "level" => 1
        );
    } else {
        $messages[] = array(
            "message" => _("Unable to add testimonial at this moment. Please try again later."),
            "level" => 10
        );
    }
}
$pageParse['Content'] .= DisplayForm();
echo \TAS\Core\TemplateHandler::TemplateChooser("admin");