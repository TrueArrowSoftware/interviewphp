<?php
require ("../template.php");
require_once ("./include.php");
$msg = array();
if (! $permission->CheckOperationPermission('imagemanager', 'add', $GLOBALS['user']->UserRoleID)) {
    \TAS\Core\Web::Redirect("index.php");
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data['title'] = ((isset($_POST['title'])) ? \TAS\Core\DataFormat::DoSecure($_POST['title']) : '');

    if ($_FILES['image']['error'] == 0) {
        $file['image']['name'] = $_FILES['image']['name'];
        $file['image']['type'] = $_FILES['image']['type'];
        $file['image']['tmp_name'] = $_FILES['image']['tmp_name'];
        $file['image']['error'] = $_FILES['image']['error'];
        $file['image']['size'] = $_FILES['image']['size'];
        $file['image']['isdefault'] = 1;
        $file['image']['caption'] = $data['title'];
        $file['image']['status'] = 1;

        $imagefile->LinkerType = \TAS\Core\DataFormat::DoSecure($_POST['imagetype']);
        $filename = $imagefile->Upload($file, true, 1);
        $imageuploaderror = $imagefile->LastErrors();
        if (count($imageuploaderror) > 0) {
            foreach ($imageuploaderror as $i => $v) {
                $msg[] = array(
                    "message" => $v,
                    "level" => 10
                );
            }
            $msg[] = array(
                "message" => "Unable to upload your image. Try again later",
                "level" => 10
            );
        } else {
            $msg[] = array(
                "message" => 'Image has been uploaded successfully.',
                "level" => 1
            );
        }
    }
}

if(!empty($msg)){
    $pageParse['Content'] = '
<div class="col-md-12 pt-3">
    <div class="card card-body card-radius">
        <div class="borderbottom-set">'.TAS\Core\UI::UIMessageDisplay($msg).'</div>
    </div>
</div>';
}
$pageParse['Content'] .= DisplayForm();
echo \TAS\Core\TemplateHandler::TemplateChooser("admin");