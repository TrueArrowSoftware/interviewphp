<?php
namespace Framework;
require "./configure.php";
if ($_SERVER ['REQUEST_METHOD'] != 'GET') {
    \TAS\Core\Web::Redirect ( "404.php" );
}

if (isset ( $_GET ['id'] ) && is_numeric ( $_GET ['id'] ) && ( int ) $_GET ['id'] > 0) {
    $image = new \TAS\Core\ImageFile ();
	$image->ThumbnailSize = $GLOBALS['ThumbnailSize'];
	$image->LinkerType = 'product'; 
	$imageData= $image->GetImage((int)$_GET['id']);
	
	$NoImage = $GLOBALS['AppConfig']['PhysicalPath'] . DIRECTORY_SEPARATOR . "theme". DIRECTORY_SEPARATOR. "images" . DIRECTORY_SEPARATOR . "noimage.png";
	
	if ($imageData != null ) {		
		$imageData = array_pop($imageData);
		if (file_exists($imageData['physicalpath'])) {
		  $image->DoResize($imageData['physicalpath'], (int)$_GET['w'], (int)$_GET['h'], '');
		} else {
		    $image->DoResize($NoImage, (int)$_GET['w'], (int)$_GET['h'], '');
		}
	} else {
	    \TAS\Core\Web::Redirect ( "404.php" );
	}	
} else {
    \TAS\Core\Web::Redirect ( "404.php" );
}
