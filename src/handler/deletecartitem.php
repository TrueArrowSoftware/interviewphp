<?php 
namespace Framework;
require("../configure.php");
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['cartid']) && is_numeric($_POST['cartid']) && $_POST['cartid'] > 0)
{
    $itemid = (int)\TAS\Core\DataFormat::DoSecure($_POST['cartid']);
    $id = $GLOBALS['db']->Execute("DELETE FROM " .$GLOBALS['Tables']['cart'] . " where itemid = '" . $itemid . "'");
    if( $id ) {
        echo 1;
    } else {
        echo 0;
    }
}
else
{
    echo 0;
}
 