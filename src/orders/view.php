<?php
namespace Framework;
require './../configure.php';
require './../template.php';
$messages =array();
$pageParse['PageTitle'] = 'View Order | '.$GLOBALS['AppConfig']['SiteName'];
//$pageParse['BreadCrumb'] = BreadCrumb('View Booking','', '');
$orderID = isset($_GET['orderid']) ? (int) $_GET['orderid'] : 0;
if($orderID <=0 || !isset($_SESSION['userid']))
{
    \TAS\Core\Web::Redirect("index.php");
}
$order = new Order($orderID);
if($order->UserID!=$_SESSION['userid'])
{
    \TAS\Core\Web::Redirect("index.php");
}

$orderHelper = new OrderHelper($orderID);

$pageParse['Content'] ='
<section class="contentarea">
  <div class="container padding70">
    <div class="row dashboard-page">
        <div class="col-md-3">
            '.SideBarHeader().'
        </div>
                
        <div class="col-md-9 pl-lg-5">
            <div class="d-flex align-items-center justify-content-between">
            <h2 class="heading mb-0">Orders Details</h2>
        </div><hr>
           '.$orderHelper->Invoice().'
    </div>
    <div class="col-lg-4 col-md-6 px-0 mt-3">
        <a href="'.$GLOBALS['AppConfig']['HomeURL'] .'/handler/printorder.php?orderid='.$orderID.'" target="_" class="btn btn--primary commonBtn">Print Order Details</a>
    </div>
  </div>
</section>';

echo \TAS\Core\TemplateHandler::TemplateChooser("single");
