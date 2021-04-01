<?php
namespace Framework;
require("./../template.php");
require("./include.php");
$messages = array ();
$orderID = isset($_GET['orderid'])?(int)$_GET['orderid']:0;
if (! $permission->CheckOperationPermission('order', 'access', $GLOBALS['user']->UserRoleID)) {
    \TAS\Core\Web::Redirect("index.php");
}

$order = new Order($orderID);
if(!$order->IsLoaded() || $orderID <=0)
{
    \TAS\Core\Web::Redirect("index.php");
}

$orderdata =array();
$orderstatus = $GLOBALS['db']->Execute("Select ekey,value from " . $GLOBALS['Tables']['enumeration'] . " where type = 'orderstatus' order by enumid");
if (\TAS\Core\DB::Count($orderstatus) > 0) {
    while ($row = $GLOBALS['db']->Fetch($orderstatus)) {
        $orderdata[$row['ekey']] = $row['value'];
    }
}

if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
		 $orderStatus = \TAS\Core\DataFormat::DoSecure($_POST['orderstatus']);
		 $rs = $GLOBALS['db']->Execute("Update " . $GLOBALS['Tables']['orders'] . " set orderstatus='".$orderStatus."' where orderid='".$orderID."'");
		 if ($rs) {
		    $messages [] = array (
					"message" => "Order status has been updated successfully.",
					"level" => 1 
			);
		 }else {
		    $messages [] = array (
					"message" => "Unable to update order status at this moment. Please try again.",
					"level" => 10 
			);
		}
}

$Order = new Order($orderID);
$pageParse ['Content'] .= '<div class="col-md-12 p-0"> <div class="card card-body card-radius">';
$pageParse['Content'] .= '<div class="px-3 py-2">' .\TAS\Core\UI::UIMessageDisplay ( $messages ) . '</div>';

$pageParse['Content'] .= '<form method="post" id="customdelete" action="edit.php?orderid=' . $orderID . '" class="validate" style="padding-top:10px">
		<fieldset class="generalform">
		<legend></legend>
			<legend>Order ID # ' . $orderID . '</legend>
			<div class="formfield">
				<table class="orderviewtable" cellpadding="2" cellspacing="0" width="100%">
				<div class="forminputwrapper">	
				<tr>
						<td class="labeltext pl-4" valign="top">Order Date:<span class="pl-1">' . $order->OrderDate . '</span></td>
						<td class="labeltext" valign="top">Order Total:<span class="pl-1"> '.$GLOBALS['AppConfig']['Currency'].'' . $order->OrderTotal . '</span></td>
					</tr>
					<tr>
						<td colspan="12"><hr /></td>
					</tr></div>
				</table>
			<div class="clear"></div></div>
			
			<div class="formfield">
				<label class="formlabel">Order Status</label>
				<div class="forminputwrapper">
					<select name="orderstatus" id="orderstatus" class="forminput required">
					' . \TAS\Core\UI::ArrayToDropDown ( $orderdata, $Order->OrderStatus ) . '
					</select>
				</div>
			<div class="clear"></div></div>';

$pageParse ['Content'] .= '	<div class="formbutton text-center">
<li><button type="submit" name="submit" class="btn primary-color primary-bg-color py-2 m-0" id="filtersubmit">Update Order</button><li>
</div>
		</fieldset>	
	</form></div></div>';

echo \TAS\Core\TemplateHandler::TemplateChooser ("popup");
