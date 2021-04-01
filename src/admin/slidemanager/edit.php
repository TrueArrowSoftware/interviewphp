<?php
require ("./../template.php");
require ("./include.php");
$messages = array();
$imageid = isset($_GET['id']) ? $_GET['id'] : 0;

if (! $permission->CheckOperationPermission("slidemanager", "edit", $GLOBALS['user']->UserRoleID)) {
    \TAS\Core\Web::Redirect("index.php");
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $data['imagecaption'] = \TAS\Core\DataFormat::DoSecure($_POST['title']);
    $data['tag'] = json_encode(array(
        'title' => \TAS\Core\DataFormat::DoSecure($_POST['hometitle']),
        'desc' => \TAS\Core\DataFormat::DoSecure($_POST['homedescription']),
        'link' => \TAS\Core\DataFormat::DoSecure($_POST['linkimage']),
        'btntext' => \TAS\Core\DataFormat::DoSecure($_POST['buttontext'])
    ));

    $GLOBALS['db']->Update($GLOBALS['Tables']['images'], $data, $imageid, 'imageid');
    $imageuploaderror = $GLOBALS['db']->lastError;

    if (count($imageuploaderror) > 0) {
        foreach ($imageuploaderror as $i => $v) {
            $messages[] = array(
                "message" => $v,
                "level" => 10
            );
        }
        $messages[] = array(
            "message" => "Unable to update home page slider details at this moment. Please try again later .",
            "level" => 10
        );
    } else {
        $messages[] = array(
            "message" => 'Home page slider image details has been  successfully updated .',
            "level" => 1
        );
    }
}


$pageParse['Content'] .= DisplayForm($imageid);
echo \TAS\Core\TemplateHandler::TemplateChooser("admin");