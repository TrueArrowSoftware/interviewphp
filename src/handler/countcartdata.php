<?php
namespace Framework;
require_once "./../configure.php";
$cart = new Cart();
$cartid = $cart->CartSettings['CartSession'];
echo $count = $GLOBALS['db']->ExecuteScalar("select count(*) from " . $GLOBALS['Tables']['cart'] . " where cartid= '".$cartid."'");
