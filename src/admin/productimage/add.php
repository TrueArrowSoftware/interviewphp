<?php
namespace Framework;
require ("../template.php");
require_once ("./include.php");
if (! $permission->CheckOperationPermission("product", "add", $GLOBALS['user']->UserRoleID)) {
    \TAS\Core\Web::Redirect($GLOBALS['AppConfig']['AdminURL'] . "/product/index.php");
}

if (isset($_GET['productid']) && is_numeric($_GET['productid'])) {
    $productid = (int) $_GET['productid'];
} else {
    \TAS\Core\Web::Redirect($GLOBALS['AppConfig']['AdminURL'] . "/product/index.php");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && count($_FILES) > 0) {
    parse_str($_POST['formdata'], $formData);
    if ($_FILES['imagefile']['error'] == 0) {
        $file['imagefile']['name'] = $_FILES['imagefile']['name'];
        $file['imagefile']['type'] = $_FILES['imagefile']['type'];
        $file['imagefile']['tmp_name'] = $_FILES['imagefile']['tmp_name'];
        $file['imagefile']['error'] = $_FILES['imagefile']['error'];
        $file['imagefile']['size'] = $_FILES['imagefile']['size'];
        $file['imagefile']['isdefault'] = 1;
        $file['imagefile']['caption'] = '';
        $file['imagefile']['tag'] = $formData['productcode'];
        $file['imagefile']['status'] = 1;
        $imageFile->LinkerType = 'product';

        $filename = $imageFile->Upload($file, true, $productid);
        $imageuploaderror = $imageFile->LastErrors();
        if (count($imageuploaderror) > 0) {
            $_SESSION['check'] = 2;
            $_SESSION['errorarray'] = $imageuploaderror;
            foreach ($imageuploaderror as $i => $v) {
                $messages[] = array(
                    "message" => $v,
                    "level" => 10
                );
            }
            
            $Check = 2;
        } else {
            $_SESSION['check'] = 1;
            $Check = 1;
        }
    }
} else {
    \TAS\Core\Web::Redirect("index.php");
}

