<?php
require("./../template.php");
require("./include.php");
if (! $permission->CheckOperationPermission('order', 'access', $GLOBALS['user']->UserRoleID)) {
    TAS\Core\Web::Redirect("index.php");
}
$OrderID = isset($_GET['orderid'])?(int)$_GET['orderid']:0;
$orderHelper = new \Framework\OrderHelper($OrderID);
if(!$orderHelper->IsLoaded() || $OrderID <=0)
{
    TAS\Core\Web::Redirect("index.php");
}

$pageParse['Content'] .= '
<div class="col-md-12 pt-3 pb-4"> 
    <div class="card card-body card-radius">
        <h2 class="borderbottom-set">View Order</h2>
        <div class="col-md-12 mt-3">';
$pageParse['Content'] .=$orderHelper->Invoice();
$pageParse['Content'] .='</div><br><hr>
        <div class="text-center">
            <a href="'.$GLOBALS['AppConfig']['HomeURL'] .'/handler/printorder.php?orderid='.$OrderID.'" target="_" class="btn btn-dark printbtntext">Print Invoice</a>
        </div>';
$pageParse['Content'] .='<div class="pl-3">';
$pageParse['Content'] .= $orderHelper->OrderPaymentHistory();
$pageParse['Content'] .='</div>';
$pageParse['Content'] .='<hr><div class="pl-3">';
$pageParse['Content'] .= $orderHelper->OrderHistory();
$pageParse['Content'] .='</div>';
$pageParse['Content'] .='</div>
</div>';
echo \TAS\Core\TemplateHandler::TemplateChooser ("admin");

