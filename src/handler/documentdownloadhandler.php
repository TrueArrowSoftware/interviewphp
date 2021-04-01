<?php
namespace TAS;
require ("../configure.php");
use \TAS\Core\DocumentFile;
use \TAS\Core\Utility;

if(!isset($_GET['documentid']) && !is_numeric($_GET['documentid']))  Core\Web::Redirect($GLOBALS['AppConfig']['HomeURL']."/404");
$documentid= (int)$_GET['documentid'];
$docname= Core\DataFormat::DoSecure($_GET['docname']);

$documentfile = new Core\DocumentFile();
$documentfile->LinkerType = 'cms';

$documentDetail = $documentfile->GetDocument ( $documentid );
if (count ( $documentDetail) > 0) {
    foreach($documentDetail as $data)
    {
        $firstDocument[]=$data;
    }
    
    Core\Web::DownloadHeader($firstDocument[0]['filename']);
    readfile($firstDocument[0]['physicalpath']);
}
