<?php
require ("../configure.php");
header ( "Content-Type: application/json", true );
$json = array ();
if (isset ( $_GET ['callback'] ))
    echo $_GET ['callback'] . "(";
    if ($_SERVER ['REQUEST_METHOD'] != "POST" || ! isset ( $_POST ['type'] )) {
        echo json_encode ( array (
            "Error" => "Unauthorize access!!!"
        ) );
    } else {
        $type = \TAS\Core\DataFormat::DoSecure ( $_POST ['type'] );
        $id = 0;
        $query='';
        switch ($type) {
            case "email" :
                if (isset ( $_POST ['rel'] ) && (is_numeric ( $_POST ['rel'] ) && $_POST ['rel'] > 0))
                {
                    $id = $GLOBALS['db']->ExecuteScalar("Select userid from " . $GLOBALS ['Tables'] ['user'] . " where email= '" . \TAS\Core\DataFormat::DoSecure ( $_POST ['data'] ) . "' and userid !=" . ( int ) $_POST ['rel']);
                }
                else
                {
                    $id = $GLOBALS['db']->ExecuteScalar("Select userid from " . $GLOBALS ['Tables'] ['user'] . " where email = '" . \TAS\Core\DataFormat::DoSecure ( $_POST ['data'] ) . "'");
                }
                break;
            case "username" :
                if (isset ( $_POST ['rel'] ) && (is_numeric ( $_POST ['rel'] ) && $_POST ['rel'] > 0))
                {
                    $id = $GLOBALS['db']->ExecuteScalar("Select userid from " . $GLOBALS ['Tables'] ['user'] . " where username= '" . \TAS\Core\DataFormat::DoSecure ( $_POST ['data'] ) . "' and userid !=" . ( int ) $_POST ['rel']);
                }
                else
                {
                    $id = $GLOBALS['db']->ExecuteScalar("Select userid from " . $GLOBALS ['Tables'] ['user'] . " where username = '" . \TAS\Core\DataFormat::DoSecure ( $_POST ['data'] ) . "'");
                }
                break;
            case "userphone" :
                if (isset ( $_POST ['rel'] ) && (is_numeric ( $_POST ['rel'] ) && $_POST ['rel'] > 0))
                {
                    $id = $GLOBALS['db']->ExecuteScalar("Select userid from " . $GLOBALS ['Tables'] ['user'] . " where phone = '" . \TAS\Core\DataFormat::DoSecure ( $_POST ['data'] ) . "' and userid !=" . ( int ) $_POST ['rel']);
                }
                else
                {
                    $id = $GLOBALS['db']->ExecuteScalar("Select userid from " . $GLOBALS ['Tables'] ['user'] . " where phone = '" . \TAS\Core\DataFormat::DoSecure ( $_POST ['data'] ) . "'");
                }
                break;
            case "password" :
                $password = \TAS\Core\DataFormat::DoSecure($_POST ['data']);
                $checkPassword = PasswordValidation($password);
                if ($checkPassword !='1') {
                    $id = 1;
                }
                break;
            case "validatephone" :
                $phone = \TAS\Core\DataFormat::DoSecure($_POST ['data']);
                if (strlen($phone) < '12') {
                    $id = 1;
                }
                break;
            case "productcode" :
                if (isset ( $_POST ['rel'] ) && $_POST ['rel'] != '')
                {
                    $id = $GLOBALS['db']->ExecuteScalar("select count(*) from " . $GLOBALS['Tables']['productlist'] . " where lower(productcode)='" . strtolower(\TAS\Core\DataFormat::DoSecure ( $_POST ['data'] )) . "' and productid != '" . $_POST ['rel'] . "' ");
                }
                else
                {
                    echo
                    $id = $GLOBALS['db']->ExecuteScalar("select count(*) from " . $GLOBALS['Tables']['productlist'] . " where lower(productcode)='" . strtolower(\TAS\Core\DataFormat::DoSecure ( $_POST ['data'] )) . "'");
                }
                break;
            default :
                if(isset($_POST['type']) && isset($_POST['field']) && isset($_POST['idfield']) ) {
                    if (isset($_POST['rel']) &&(is_numeric($_POST['rel']) && $_POST['rel'] > 0))
                    {
                        $id = $GLOBALS['db']->ExecuteScalar("Select count(*) from " . $GLOBALS['Tables'][$_POST['type']] . " where ".\TAS\Core\DataFormat::DoSecure($_POST['field'])." = '" . \TAS\Core\DataFormat::DoSecure($_POST['data'])."' and ".\TAS\Core\DataFormat::DoSecure($_POST['idfield'])." !=" .(int)$_POST['rel']);
                    }
                    else
                    {
                        $id = $GLOBALS['db']->ExecuteScalar("Select count(*) from " . $GLOBALS['Tables'][$_POST['type']] . " where ".\TAS\Core\DataFormat::DoSecure($_POST['field'])." = '" . \TAS\Core\DataFormat::DoSecure($_POST['data'])."'");
                    }
                } else {
                    $id= 0;
                }
                break;
        }
        
        echo ((is_numeric ( $id ) && $id > 0) ? "0" : "1");
    }
    if (isset ( $_GET ['callback'] ))
        echo ")";