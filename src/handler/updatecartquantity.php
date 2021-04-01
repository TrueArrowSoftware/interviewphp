<?php
namespace Framework;
require ("../configure.php");
header('Content-Type: application/json', true);
if ($_SERVER ['REQUEST_METHOD'] == 'POST' && $_POST['itemid'] > 0 && $_POST['quantity'] > 0 && isset($_POST['type']) && $_POST['type']=='quantity') {
    $quantity = (int) \TAS\Core\DataFormat::DoSecure($_POST['quantity']);
    $itemid = (int) \TAS\Core\DataFormat::DoSecure($_POST['itemid']);
    $GLOBALS['db']->Execute('update '.$GLOBALS['Tables']['cart'].' SET quantity="'.$quantity.'" where itemid="'.$itemid.'"');
    echo json_encode(array(
        'action' => 'success'
    ));
}
