<?php
require ("../template.php");
require_once ("./include.php");
if (! $permission->CheckOperationPermission("slidemanager", "access", $GLOBALS['user']->UserRoleID)) {
    \TAS\Core\Web::Redirect("../index.php");
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && ! isset($_POST['mode'])) {

    if ($_POST['action'] == 'order') {
        $ids = array();
        $page = ((int) $GLOBALS['AppConfig']['PageSize']) * (isset($_POST['page']) ? ((int) $_POST['page'] - 1) : 0);

        foreach ($_POST['data'] as $data) {
            $ids[str_replace("row_", "", $data)] = ++ $page;
        }

        foreach ($ids as $id => $value) {
            $GLOBALS['db']->Execute("update " . $GLOBALS['Tables']['images'] . " set displayorder=" . (int) $value . " where imageid=" . (int) $id);
        }
    }
    exit();
}

$messages = array();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['delete']) && is_numeric($_GET['delete']) && $permission->CheckOperationPermission("slidemanager", "delete", $GLOBALS['user']->UserRoleID)) {
        if ($imagefile->DeleteImage((int) $_GET['delete'])) {
            $messages[] = array(
                "message" => 'Home page slider image has been successfully deleted .',
                "level" => 1
            );
        } else {
            $messages[] = array(
                "message" => 'Unable to delete home page slider image at this moment . Please try aganin later .',
                "level" => 10
            );
        }
    }

    if (isset($_GET['mode']) && $_GET['mode'] == 'clearfilter') {
        unset($_SESSION['slidemanager_orderby']);
        unset($_SESSION['slidemanager_direction']);
        setcookie('admin_slideimage_filter', '', (time() - 25292000));
        \TAS\Core\Web::Redirect("index.php");
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['mode'] == 'filter') {
    $filterOption = $_POST;
} else {
    $filterOption = (isset($_COOKIE['admin_slideimage_filter']) ? json_decode(stripslashes($_COOKIE['admin_slideimage_filter']), true) : array(
        'imagetype' => ''
    ));
}

$pageParse['Content'] .= '<div class="col-md-12 pt-3"> <div class="card card-body card-radius p-0">
<h2 class="borderbottom-set">Home Slider Management</h2>';
$pageParse['Content'] .= '<div class="px-3 py-2">' . \TAS\Core\UI::UIMessageDisplay($messages) . '</div>';
$pageParse['Content'] .= '<h6 class="pl-3 py-3"><a href="add.php">Add New Slider Image</a></h6><p>
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