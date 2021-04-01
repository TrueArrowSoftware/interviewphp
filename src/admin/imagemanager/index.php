<?php

require("../template.php");
require_once("./include.php");
$msg = array();
if (!$permission->CheckOperationPermission('imagemanager', 'access', $GLOBALS['user']->UserRoleID)) {
    \TAS\Core\Web::Redirect("../index.php");
}
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['delete']) && is_numeric($_GET['delete']) && $permission->CheckOperationPermission('imagemanager', 'delete', $GLOBALS['user']->UserRoleID)) {
        if ($imagefile->DeleteImage((int) $_GET['delete'])) {
            $msg[] = array(
                "message" => "Image has been deleted successfully.",
                "level" => 10
            );
        } else {
            $msg[] = array(
                "message" => "Unable to delete this image at this moment. Please try again.",
                "level" => 10
            );
        }
    }

    if (isset($_GET['mode']) && $_GET['mode'] == 'clearfilter') {
        setcookie('admin_image_filter', '', (time() - 25292000));
        \TAS\Core\Web::Redirect("index.php");
    }
}

$pageParse['Content'] .= '<div class="col-md-12 pt-3"> <div class="card card-body card-radius p-0">
<h2 class="borderbottom-set">Image Manager</h2>';
$pageParse['Content'] .= '<div class="px-3 py-2">' . \TAS\Core\UI::UIMessageDisplay($messages) . '</div>';
$pageParse['Content'] .= '<h6 class="pl-3 py-3"><a href="add.php">Add New Image</a></h6><p>
	<div class="pl-3"><p>
	<b>Instructions</b>
	<ul>
		<li>Add Image using Add Image function</li>
		<li>Click Get Code button to get URL of image or thumbnail of it</li>
		<li>Paste the URL in CMS using IMAGE Option</li>
	</ul>
	</p></div></div></div>';
$pageParse['Content'] .= DisplayGrid();
echo \TAS\Core\TemplateHandler::TemplateChooser("admin");
