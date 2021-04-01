<?php
namespace Framework;

require ("../template.php");
require_once ("./include.php");
$messages = array();
if (! $permission->CheckOperationPermission('testimonial', 'access', $GLOBALS['user']->UserRoleID)) {
    \TAS\Core\Web::Redirect("../index.php");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (trim(strtolower($_POST['action'])) == 'order') {
        $ids = array();
        $page = ((int) $GLOBALS['AppConfig']['PageSize']) * (isset($_POST['page']) ? ((int) $_POST['page'] - 1) : 0);

        foreach ($_POST['data'] as $data) {
            $ids[str_replace("row_", "", $data)] = ++ $page;
        }

        foreach ($ids as $id => $value) {
            $GLOBALS['db']->Execute("update " . $GLOBALS['Tables']['testimonial'] . " set displayorder=" . (int) $value . " where testimonialid=" . (int) $id);
        }
        exit();
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['delete']) && is_numeric($_GET['delete']) && $permission->CheckOperationPermission('testimonial', 'delete', $GLOBALS['user']->UserRoleID)) {
        if (Testimonial::Delete((int) $_GET['delete'])) {
            $messages[] = array(
                "message" => _("Testimonial has been deleted successfully."),
                "level" => 1
            );
        } else {
            $messages[] = array(
                "message" => _("Unable to delete testimonial at this moment. Please try again later."),
                "level" => 10
            );
        }
    }

    if (isset($_GET['type']) && is_numeric($_GET['id'])) {
        if ($_GET['type'] == 'status') {
            $u = new Testimonial((int) $_GET['id']);
            if ($u->IsLoaded()) {
                $s = (($u->Status == 1) ? 0 : 1);
                $GLOBALS['db']->Execute("update " . $GLOBALS['Tables']['testimonial'] . " set status=" . $s . " where testimonialid=" . $u->TestimonialID);
                \TAS\Core\Web::Redirect("index.php?status=1&page=" . $_GET['page']);
            } else {
                $messages[] = array(
                    "message" => _("No testimonial found to update status"),
                    "level" => 10
                );
            }
        }
    }
    if (isset($_GET['status']) && $_GET['status'] == '1') {
        $messages[] = array(
            "message" => _("Testimonial status has been updated successfully."),
            "level" => 1
        );
    }
}

$pageParse['Content'] .= '<div class="col-md-12 pt-3"> <div class="card card-body card-radius">
<h2 class="borderbottom-set">Testimonial Management</h2>';
$pageParse['Content'] .= '<div class="px-3 mt-3 display-messages">' . \TAS\Core\UI::UIMessageDisplay($messages) . '</div>';
$pageParse['Content'] .= '<h6 class="px-3 pt-3 m-0"><a href="add.php">Add New Testimonial</a></h6><p class="pl-3">You can change order of Testimonial by Drag and Drop. </p></div></div>';
$pageParse['Content'] .= DisplayGrid();
echo \TAS\Core\TemplateHandler::TemplateChooser("admin");