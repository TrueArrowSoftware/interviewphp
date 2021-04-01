<?php
require("./../template.php");
require("./include.php");
$msg = array();
if (!$permission->CheckOperationPermission('docmanager', 'add', $GLOBALS['user']->UserRoleID)) {
    \TAS\Core\Web::Redirect("index.php");
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data['title'] = ((isset($_POST['title'])) ? \TAS\Core\DataFormat::DoSecure($_POST['title']) : '');

    if ($_FILES['document']['error'] == 0) {
        $file['document']['name'] = $_FILES['document']['name'];
        $file['document']['type'] = $_FILES['document']['type'];
        $file['document']['tmp_name'] = $_FILES['document']['tmp_name'];
        $file['document']['error'] = $_FILES['document']['error'];
        $file['document']['size'] = $_FILES['document']['size'];
        $file['document']['isdefault'] = 1;
        $file['document']['caption'] = $data['title'];
        $file['document']['status'] = 1;

        $filename = $documentfile->Upload($file, true, 1); // LinkerID is 1 as it is generic CMS Document and not related to any Entity
        $documentuploaderror = $documentfile->LastErrors();
        if (count($documentuploaderror) > 0) {
            foreach ($documentuploaderror as $i => $v) {
                $msg[] = array(
                    "message" => $v,
                    "level" => 10
                );
            }
            $msg[] = array(
                "message" => "Unable to upload your document. Try again later.",
                "level" => 10
            );
        } else {

            $d = new TAS\Core\DocumentFile();
            $d->LinkerType = 'cms';
            $abc = $d->GetDocument($filename['document']['ID']);
            $firstid = key($abc);
            $firstDocument = current($abc);
            $impersonPath = TAS\Core\DocumentFile::DownloadURL(array(
                'name' => $firstDocument['name'],
                'id' => $firstid
            ));
            $msg[] = array(
                "message" => _('Document has been saved successfully. <p>URL from last Upload : ' . $impersonPath . '</p>'),
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