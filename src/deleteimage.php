<?php
namespace TAS\Core;
require "./configure.php";
if ($_SERVER ['REQUEST_METHOD'] != 'POST') {
    Web::Redirect ( "404.php" );
}

if (isset ( $_POST['delete'] ) && is_numeric ( $_POST ['delete'] ) && ( int ) $_POST['delete'] > 0) {
	$image = new ImageFile ();
	$image->ThumbnailSize = $GLOBALS['ThumbnailSize'];
	$image->DeleteImage((int)$_POST['delete']);
	echo 1;
} else {
	echo 0;
	Web::Redirect ( "404.php" );
}
