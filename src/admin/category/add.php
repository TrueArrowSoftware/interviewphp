<?php
namespace Framework;

require ("../template.php");
require_once ("./include.php");
$messages = array();
if (! $permission->CheckOperationPermission('category', 'add', $GLOBALS['user']->UserRoleID)) {
    \TAS\Core\Web::Redirect("index.php");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $d = \TAS\Core\Entity::ParsePostToArray(Category::GetFields());
    if ($d['parentid'] == '') {
        $d['parentid'] = 0;
    }

    $d['adddate'] = date("Y-m-d H:i:s");
    $guid = \TAS\Core\Utility::CreateGUIDString();
    
    /* check guid unique */
    $checkGUID = $GLOBALS['db']->ExecuteScalar('select count(*) from '.$GLOBALS['Tables']['category'].' where guid="'.$guid.'"');
    if($checkGUID > 0)
    {
        $guid = \TAS\Core\Utility::CreateGUIDString();
        $x1=1;
        do {
            $countCheck = $GLOBALS['db']->ExecuteScalar('select count(*) from '.$GLOBALS['Tables']['category'].' where guid="'.$guid.'"');
            if($countCheck > 0)
            {
                $guid = \TAS\Core\Utility::CreateGUIDString();
            }
            else
            {
                $x1=0;
            }
            
        }while ($x1 < 0);
    }
    
    $d['guid'] = $guid;
    $categoryid = Category::Add($d);

    // Code For Inserting image for categories

    if (count($_FILES) > 0) {
        $imageFile = new \TAS\Core\ImageFile();
        $imageFile->ThumbnailSize = $GLOBALS['ThumbnailSize'];
        if ($_FILES['categoryimage']['error'] == 0) {
            $file['categoryimage']['name'] = $_FILES['categoryimage']['name'];
            $file['categoryimage']['type'] = $_FILES['categoryimage']['type'];
            $file['categoryimage']['tmp_name'] = $_FILES['categoryimage']['tmp_name'];
            $file['categoryimage']['error'] = $_FILES['categoryimage']['error'];
            $file['categoryimage']['size'] = $_FILES['categoryimage']['size'];
            $file['categoryimage']['isdefault'] = 1;
            $file['categoryimage']['caption'] = '';
            $file['categoryimage']['tag'] = $categoryid;
            $file['categoryimage']['status'] = 1;

            $imageFile->LinkerType = 'category';

            $filename = $imageFile->Upload($file, true, $categoryid);
            $imageuploaderror = $imageFile->LastErrors();
            if (count($imageuploaderror) > 0) {
                foreach ($imageuploaderror as $i => $v) {
                    $messages[] = array(
                        "message" => $v,
                        "level" => 10
                    );
                }
            }
        }
    }

    if ($categoryid > 0) {
        $messages[] = array(
            "message" => _("Category has been added successfully."),
            "level" => 1
        );
    } else {
        if (count(Category::GetErrors()) > 0) {
            $a = Category::GetErrors();
            foreach ($a as $i => $v) {
                $messages[] = $v;
            }
        } else {
            $messages[] = array(
                "message" => _("Unable to add category at this moment. Please try again later."),
                "level" => 10
            );
        }
    }
}

$pageParse['Content'] .= DisplayForm();
echo \TAS\Core\TemplateHandler::TemplateChooser("admin");