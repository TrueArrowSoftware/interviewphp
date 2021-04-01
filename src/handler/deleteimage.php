<?php
require "./configure.php";
if (isset ( $_POST['delete'] ) && is_numeric ( $_POST ['delete'] ) && ( int ) $_POST['delete'] > 0) {
	$image = new \TAS\Core\ImageFile ();
	$image->ThumbnailSize = $GLOBALS['ThumbnailSize'];
	$image->DeleteImage((int)$_POST['delete']);
	echo 1;
} else {
	echo 0;
}
