<?php
$documentfile = new \TAS\Core\DocumentFile();
$documentfile->LinkerType = 'cms';

function DisplayForm($documentid = 0)
{
    $formtitle = ($documentid > 0) ? "Edit Document" : "Add Document";
    return \TAS\Core\DocumentFile::DocumentForm('',$formtitle, $documentid);
}

function DisplayGrid()
{
    return '' . \TAS\Core\DocumentFile::DocumentGrid('cms', array(), array(
        'gridpage' => $GLOBALS['AppConfig']['AdminURL'] . '/docmanager/index.php',
        'delete' => $GLOBALS['AppConfig']['AdminURL'] . '/docmanager/index.php',
        'getcode' => $GLOBALS['AppConfig']['AdminURL'] . '/docmanager/getcode.php'
    ));
}
