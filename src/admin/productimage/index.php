<?php
namespace Framework;
require ("../template.php");
require_once ("./include.php");
$messages = array();
$check = 0;
if (! $permission->CheckOperationPermission('product', 'access', $GLOBALS['user']->UserRoleID)) {
    \TAS\Core\Web::Redirect("../index.php");
}
if (isset($_GET['productid']) && is_numeric($_GET['productid'])) {
    $productid = (int) $_GET['productid'];
} else {
    \TAS\Core\Web::Redirect($GLOBALS['AppConfig']['AdminURL'] . "/product/index.php");
}

if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $imageFile = new \TAS\Core\ImageFile();
    $imageFile->DeleteImage((int) $_GET['delete']);
    $messages[] = array(
        'message' => 'Image has been deleted successfully.',
        "level" => 1
    );
}

$product = new Product($productid);
if (isset($_SESSION['check'])) {
    switch ($_SESSION['check']) {
        case 2:
            if (count($_SESSION['errorarray']) > 0) {
                foreach ($_SESSION['errorarray'] as $i => $v) {
                    $messages[] = array(
                        "message" => $v,
                        "level" => 10
                    );
                }
            }

            break;
        case 1:
            $messages[] = array(
                "message" => _("Image has been updated successfully."),
                "level" => 1
            );
            break;
    }
    unset($_SESSION['check']);
    unset($_SESSION['errorarray']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'order') {
        $ids = array();
        $page = ((int) $GLOBALS['AppConfig']['PageSize']) * (isset($_POST['page']) ? ((int) $_POST['page'] - 1) : 0);
        
        foreach ($_POST['data'][0] as $data) {
            $ids[$data['id']] = ++ $page;
        }
        
        foreach ($ids as $id => $value) {
            $GLOBALS['db']->Execute("update " . $GLOBALS['Tables']['images'] . " set displayorder=" . (int) $value . " where imageid=" . (int) $id);
        }
    }
}

$pageParse['Content'] .= '
<div class="col-md-12 pt-3"> 
    <div class="card card-body card-radius">
        <h2 class="borderbottom-set">Product Image Management</h2>
        <div class="message px-3 mt-3 display-messages product-upload-msg">'.\TAS\Core\UI::UIMessageDisplay($messages).'</div>
    </div>
</div>';

$pageParse['Content'] .= DisplayGrid();
echo \TAS\Core\TemplateHandler::TemplateChooser("admin");