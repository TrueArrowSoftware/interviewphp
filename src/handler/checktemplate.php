<?php

include "../configure.php";

$ekey = trim($_POST['val']);

$ekey = $GLOBALS['db']->Execute("Select * FROM " . $GLOBALS['Tables']['enumeration'] ." WHERE ekey = '$ekey'");

if( $ekey->num_rows > 0 ){
    $result = 1;
} else {
    $result = 0;
}

echo $result;exit;