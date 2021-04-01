<?php
require ("./../template.php");
require ("./include.php");
$messages = array();
if (! $permission->CheckOperationPermission("slidemanager", "add", $GLOBALS['user']->UserRoleID)) {
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
        $file['image']['tag'] = json_encode(array(
            'title' => \TAS\Core\DataFormat::DoSecure($_POST['hometitle']),
            'desc' => \TAS\Core\DataFormat::DoSecure($_POST['homedescription']),
            'link' => \TAS\Core\DataFormat::DoSecure($_POST['linkimage']),
            'btntext' => \TAS\Core\DataFormat::DoSecure($_POST['buttontext'])
        ));

        $imagefile->LinkerType = 'homeslider';
        $filename = $imagefile->Upload($file, true, 1);
        $imageuploaderror = $imagefile->LastErrors();
        if (count($imageuploaderror) > 0) {
            foreach ($imageuploaderror as $i => $v) {
                $messages[] = array(
                    "message" => $v,
                    "level" => 10
                );
            }
            $messages[] = array(
                "message" => "Unable to upload home page slider image at this moment. Please try again later .",
                "level" => 10
            );
        } else {
            $messages[] = array(
                "message" => 'Home page slider image has been uploaded successfully.',
                "level" => 1
            );
        }
    }
}


$pageParse['Content'] .= DisplayForm();
echo \TAS\Core\TemplateHandler::TemplateChooser("admin");