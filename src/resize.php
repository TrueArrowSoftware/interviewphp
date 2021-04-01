<?php
use TAS\Core\DB;
require ("./configure.php");
require_once './template.php';
if (! isset ( $_SESSION [$_GET ['path']] )) {
   
    header ( "Content-Type: image/png" );
	// No Image is present throw holder image
	
	$width = ((isset ( $_GET ['width'] ) && is_numeric ( $_GET ['width'] )) ? $_GET ['width'] : 0);
	$height = ((isset ( $_GET ['height'] ) && is_numeric ( $_GET ['height'] )) ? $_GET ['height'] : 0);
	if ($width == 0 && $height == 0) {
		$width = 400;
		$height = 300;
	} else if ($width == 0 && $height > 0) {
		$width = floor ( ($height * (4 / 3)) );
	} else {
		$height = floor ( ($width * (3 / 4)) );
	}
	$img = imagecreatetruecolor ( $width, $height );
	imagepng ( $img );
	imagedestroy ( $img );
} else {
	$width = ((isset ( $_GET ['width'] ) && is_numeric ( $_GET ['width'] )) ? $_GET ['width'] : 0);
	$height = ((isset ( $_GET ['height'] ) && is_numeric ( $_GET ['height'] )) ? $_GET ['height'] : 0);
	Resize ( $_SESSION [$_GET ['path']], $width, $height );
}
?>
