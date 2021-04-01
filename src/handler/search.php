<?php
require ("../configure.php");
header('Content-Type: application/json', true);
if ($_SERVER ['REQUEST_METHOD'] == 'POST'){
    $options = array(
        'productname' => (isset($_POST['productname']) ? $_POST['productname']:''),
        'guid' => (isset($_POST['category']) ? $_POST['category']:''),
        'minprice' => (isset($_POST['minprice']) ? $_POST['minprice']:''),
        'maxprice' => (isset($_POST['maxprice']) ?$_POST['maxprice']:''),
        'orderby' => (isset($_POST['orderby']) ? $_POST['orderby']:'')
    );
    
    $searchQuery = \Framework\Search::GetProductSearchQuery($options);
    $productHTML = \Framework\Search::GetProductSearchHTML($searchQuery);
    echo json_encode(array(
        'action' => 'success',
        'productHTML'=>$productHTML
    ));
}else {
    echo json_encode(array(
        'action' => 'fail'
    ));
}