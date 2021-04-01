<?php
namespace Framework;
require "./configure.php";
require ("./template.php");
$pageParse['PageTitle'] = 'Payment Success | '.$GLOBALS['AppConfig']['SiteName'];

/*  BreadCrumb*/
/* $headerimage = $GLOBALS['db']->Fetch($GLOBALS['db']->Execute("Select * from " . $GLOBALS['Tables']['pages'] . " where page= 'Online Shop'"));
 $headerimage = $headerimage['headerimage'];
 $pageParse['PageName'] = 'Login';
 $pageParse['BreadCrumb'] = BreadCrumb('Login', '', $headerimage); */

$orderid = isset($_GET['orderid']) ? (int) $_GET['orderid'] : 0;
if($orderid<=0)
{
    \TAS\Core\Web::Redirect($GLOBALS['AppConfig']['HomeURL']);
}

$order = new OrderHelper($orderid);
if(!$order->IsLoaded())
{
    \TAS\Core\Web::Redirect($GLOBALS['AppConfig']['HomeURL']);
}

$pageParse['Content'] .='<section class="section-md bg-default paymentsuccess-page">
            <div class="container" style="width:80%">
			 <h4 class="text-center"><i>Thank you for your purchase.</i></h4><br>
                <center> <h3>Order Details</h3></center>
                '.$order->Invoice().'
                <div class="custom-btns d-flex">
                    <div class="col-lg-4 blank-col">
                </div>
                <div class="col-lg-4 col-md-6 px-0 text-center mt-3">
                    <a href="'.$GLOBALS['AppConfig']['HomeURL'] .'/handler/printorder.php?orderid='.$orderid.'" target="_" class="btn btn--primary commonBtn">Print Order Details</a>
                </div>
                <div class="col-lg-4 col-md-6 px-0 text-right"></div>
            </div>
        </section>';
echo \TAS\Core\TemplateHandler::TemplateChooser("single");