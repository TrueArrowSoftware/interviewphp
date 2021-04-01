<?php
require ("../template.php");
require_once ("./include.php");
$msg = array();
if (! $permission->CheckOperationPermission('docmanager', 'access', $GLOBALS['user']->UserRoleID)) {
    \TAS\Core\Web::Redirect("../index.php");
}
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['delete']) && is_numeric($_GET['delete']) && $permission->CheckOperationPermission('docmanager', 'delete', $GLOBALS['user']->UserRoleID)) {
        if ($documentfile->DeleteDocument((int) $_GET['delete'])) {
            $msg[] = array(
                "message" => "Document has been deleted successfully.",
                "level" => 10
            );
        } else {
            $msg[] = array(
                "message" => "Unable to delete this document at this moment. Please try again.",
                "level" => 10
            );
        }
    }

    if (isset($_GET['mode']) && $_GET['mode'] == 'clearfilter') {
        setcookie('admin_document_filter', '', (time() - 25292000));
        \TAS\Core\Web::Redirect("index.php");
    }
}

$pageParse['Content'] .= '<div class="col-md-12 pt-3"> <div class="card card-body card-radius p-0">
<h2 class="borderbottom-set">Document Management</h2>';
$pageParse['Content'] .= '<div class="px-3 py-2">' . \TAS\Core\UI::UIMessageDisplay($messages) . '</div>';
$pageParse['Content'] .= '<h6 class="pl-3 py-3"><a href="add.php">Add New Document</a></h6><p>
	<div class="pl-3"><b>Instructions</b>
	<ul>
		<li>Add Document using Add Document function</li>
		<li>Click Get Code button to get URL of document</li>
		<li>You can link document directly by taking URL from GET CODE</li>
	</ul>
	</p></div></div></div>';
$pageParse['Content'] .= DisplayGrid();
echo \TAS\Core\TemplateHandler::TemplateChooser("admin");