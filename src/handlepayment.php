<?php
namespace Framework;
require_once './configure.php';
require_once './template.php';
\TAS\Core\Web::NoBrowserCache();

$pageParse['PageTitle'] = 'Payment | '.$GLOBALS['AppConfig']['SiteName'];

/*  BreadCrumb*/
/* $headerimage = $GLOBALS['db']->Fetch($GLOBALS['db']->Execute("Select * from " . $GLOBALS['Tables']['pages'] . " where page= 'Online Shop'"));
 $headerimage = $headerimage['headerimage'];
 $pageParse['PageName'] = 'Login';
 $pageParse['BreadCrumb'] = BreadCrumb('Login', '', $headerimage); */

$orderID = (isset($_GET['orderid']) ? (int) $_GET['orderid'] : 0);

if($_SESSION['SetForBrowseCheck']!='1' || $orderID < 0){
    \TAS\Core\Web::Redirect($GLOBALS['AppConfig']['HomeURL'] . "/orderfail");
}

$order = new Order($orderID);

/* 
 * @TO-DO
 * 
 * payment gateway apply as per requirement
 *  */
 
 /* remove this when you apply payment gateway */
 \TAS\Core\Web::Redirect($GLOBALS['AppConfig']['HomeURL'] . '/executepayment.php?mode=noamount&success=true&order=' . $order->OrderID);
 
if ($order->OrderStatus == 'success') {
    \TAS\Core\Web::Redirect($GLOBALS['AppConfig']['HomeURL']);
}

if ($order->OrderTotal == 0) {
    \TAS\Core\Web::Redirect($GLOBALS['AppConfig']['HomeURL'] . '/executepayment.php?mode=noamount&success=true&order=' . $order->OrderID);
}

$pageParse['Content'].='
    <div class="container py-5">
        <h4 class="text-center font-weight-bold pb-4">Enter your credit card details</h4>
        <hr>
        <div class="col-md-12 f-size">
        <div class="row">
        <div class="col-md-5 mx-auto card card-header card--payment">
            <form class="validate" method="post" action="{HomeURL}/executepayment.php?order='.$orderID.'&mode=securetrading">
            <div class="row">
                <div class="col-md-7 form-group">
                    <label for="cardnumber" class="control-label font-weight-bold paymentfont mb-1">Card Number</label>
                    <input id="cardnumber" name="cardnumber" type="tel" class="form-control required" autocomplete="off"/>
                    <label id="invalidcardnumber" class="d-none error">Invalid Card Number</label>
                </div>
                <div class="col-md-5 form-group">
                    <label for="expirydate" class="control-label paymentfont font-weight-bold mb-1">Expiry Date(mm/yyyy)</label>
                    <input id="expirydate" name="expirydate" type="text" class="form-control required" placeholder="mm/yyyy" data-mask="00/0000" autocomplete="off">
                    <label id="invalidexpiry" class="d-none error">Invalid Expiry Date</label>
                    <input type="hidden" id="validatefield" name="validatefield" value="0">
                    <input type="hidden" id="mode" name="mode" value="securetrading">
                </div>
            </div>
            <div class="row mt-0">
                <div class="col-md-7 form-group">
                    <label for="cardholdername" class="control-label paymentfont mb-1 font-weight-bold">Card Holder</label>
                    <input id="cardholdername" name="cardholdername" type="text" class="form-control required" autocomplete="off">
                </div>
                <div class="col-md-5 form-group">
                    <label for="cvv" class="control-label paymentfont mb-1 font-weight-bold">CVV</label>
                    <input id="cvv" name="cvv" type="text"  data-mask="0000"  maxlength="4" class="form-control number required" autocomplete="off">
                    <label id="invalidcvv" for="invalidcvv"  class="d-none error">Invalid CVV Number</label>
                </div>
            </div>
            <div class="text-right mt-2">
                <button id="payment-button" type="submit" class="btn btn--primary commonBtn placeorder"><span id="payment-button-amount" class="paymentprocess">Pay Now</span>
                </button>
            </div>
            </form>
            </div>
        </div>
        </div>
    </div>';

$pageParse['FooterInclusion'] = '
<script type="text/javascript">
$(function(){
    $("#validatefield").val("1");
});
    
document.getElementById("cardnumber").addEventListener("input", function (e) {
  e.target.value = e.target.value.replace(/[^\dA-Z]/g, "").replace(/(.{4})/g, "$1 ").trim();
});
    
    
$("#expirydate").on("change", function () {
    var expiry = $(this).val();
    validateExpiry(expiry);
    
});
    
$("#cvv").on("change", function () {
    var cvv = $(this).val().length;
    validateCVV(cvv);
    
});
    
$("#payment-button").click(function(e){
    
    if($(".validate").valid())
    {
        var expirydate = $("#expirydate").val();
        if(validateExpiry(expirydate))
        {
            var cvv = $("#cvv").val().length;
            if(validateCVV(cvv))
            {
                return true;
            }
            else
            {
                e.preventDefault();
            }
        }
        else
        {
            e.preventDefault();
        }
    }
});
    
    
function validateCVV(cvv)
{
   if(cvv < 3)
    {
        $("#invalidcvv").removeClass("d-none");
        return false;
    }
    else
    {
        $("#invalidcvv").addClass("d-none");
        return true;
    }
}
    
    
function validateExpiry(value)
{
    var expiryDate = value.split("/");
    var d = new Date();
    var currentYear = d.getFullYear();
    if(value!="")
    {
        if(expiryDate[1]!==undefined && expiryDate[1]!=="")
        {
    
            var extDateLength = expiryDate[1].toString().length;
            if(extDateLength=="4")
            {
                if(expiryDate[0] > "12")
                {
                    $("#invalidexpiry").removeClass("d-none");
                    return false;
                }
                else if(expiryDate[1]>=currentYear)
                {
                    $("#invalidexpiry").addClass("d-none");
                    return true;
                }
                else
                {
                    $("#invalidexpiry").removeClass("d-none");
                    return false;
                }
            }
            else
            {
                $("#invalidexpiry").removeClass("d-none");
                return false;
            }
        }
        else
        {
            $("#invalidexpiry").removeClass("d-none");
            return false;
        }
    
    }
}
</script>';

echo \TAS\Core\TemplateHandler::TemplateChooser("single");
