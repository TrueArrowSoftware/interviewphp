<?php
namespace Framework;
require "./configure.php";
require ("./template.php");
$pageParse['PageTitle'] = 'Checkout | '.$GLOBALS['AppConfig']['SiteName'];

/*  BreadCrumb*/
/* $headerimage = $GLOBALS['db']->Fetch($GLOBALS['db']->Execute("Select * from " . $GLOBALS['Tables']['pages'] . " where page= 'Online Shop'"));
 $headerimage = $headerimage['headerimage'];
 $pageParse['PageName'] = 'Login';
 $pageParse['BreadCrumb'] = BreadCrumb('Login', '', $headerimage); */

$cart = new Cart();
$cartContent = Cart::GetCartContent ( $cart->CartSettings ['CartSession'] );

/*************************************** Start Check Post Data ***************************************************/

function ProcessAddress($OrderID)
{
    
    $dbilling['ownerid'] = $OrderID;
    $dbilling['addresstype'] = 'billing';
    $dbilling['title'] = \TAS\Core\DataFormat::DoSecure($_POST['firstname']);
    $dbilling['subtitle'] = (isset($_POST['lastname'])?\TAS\Core\DataFormat::DoSecure($_POST['lastname']):'');
    $dbilling['email'] = \TAS\Core\DataFormat::DoSecure($_POST['email']);
    $dbilling['phone'] = \TAS\Core\DataFormat::DoSecure($_POST['phone']);
    $dbilling['address1'] = \TAS\Core\DataFormat::DoSecure($_POST['address1']);
    $dbilling['address2'] = (isset($_POST['address2'])?\TAS\Core\DataFormat::DoSecure($_POST['address2']):'');
    $dbilling['city'] = \TAS\Core\DataFormat::DoSecure($_POST['city']);
    $dbilling['state'] = (isset($_POST['state'])?\TAS\Core\DataFormat::DoSecure($_POST['state']):'');
    $dbilling['country'] = (int)\TAS\Core\DataFormat::DoSecure($_POST['country']);
    $dbilling['zipcode'] = (isset($_POST['zipcode'])?\TAS\Core\DataFormat::DoSecure($_POST['zipcode']):'');
    $dbilling['adddate'] = date("Y-m-d H:i:s");
    $dbilling['ownertype'] = 'order';
    if($_POST['shippingemail']=='')
    {
        $dshipping = $dbilling;
        $dshipping['addresstype'] = 'shipping';
        
    }
    else
    {
        
        $dshipping['ownerid'] = $OrderID;
        $dshipping['addresstype'] = 'shipping';
        $dshipping['title'] = \TAS\Core\DataFormat::DoSecure($_POST['shippingfirstname']);
        $dshipping['subtitle'] = (isset($_POST['shippinglastname'])?\TAS\Core\DataFormat::DoSecure($_POST['shippinglastname']):'');
        $dshipping['email'] = \TAS\Core\DataFormat::DoSecure($_POST['shippingemail']);
        $dshipping['phone'] = \TAS\Core\DataFormat::DoSecure($_POST['shippingphone']);
        $dshipping['address1'] = \TAS\Core\DataFormat::DoSecure($_POST['shippingaddress1']);
        $dshipping['address2'] = (isset($_POST['shippingaddress2'])?\TAS\Core\DataFormat::DoSecure($_POST['shippingaddress2']):'');
        $dshipping['city'] = \TAS\Core\DataFormat::DoSecure($_POST['shippingcity']);
        $dshipping['state'] = (isset($_POST['shippingstate'])?\TAS\Core\DataFormat::DoSecure($_POST['shippingstate']):'');
        $dshipping['country'] = (int)\TAS\Core\DataFormat::DoSecure($_POST['shippingcountry']);
        $dshipping['zipcode'] = (isset($_POST['shippingzipcode'])?\TAS\Core\DataFormat::DoSecure($_POST['shippingzipcode']):'');
        $dshipping['adddate'] = date("Y-m-d H:i:s");
        $dshipping['ownertype'] = 'order';
    }
    
    
    Address::Add($dbilling);
    Address::Add($dshipping);
    
    if(isset($_SESSION['userid']))
    {
        $ddefault = $dbilling;
        $ddefault['ownertype'] = 'user';
        $ddefault['ownerid'] = $_SESSION['userid'];
        $ddefault['addresstype'] = 'default';
        $addressID = $GLOBALS['db']->ExecuteScalar("Select addressid from " . $GLOBALS['Tables']['address'] . " where addresstype='default' and ownertype='user' and ownerid= '" . $_SESSION['userid'] . "' limit 1");
        if ($addressID > 0) {
            
            $address = new Address($addressID);
            $ddefault['addressid'] = $addressID;
            $address->Update($ddefault);
        }else{
            Address::Add($ddefault);
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cartcontent = Cart::GetCartContent($cart->CartSettings['CartSession']);
    $OrderID = $cart->Checkout($cartcontent);
    
    if ($OrderID > 0) {
        ProcessAddress($OrderID);
        $_SESSION['SetForBrowseCheck']=1;
        \TAS\Core\Web::Redirect($GLOBALS['AppConfig']['HomeURL']."/handlepayment.php?orderid=". $OrderID);
    }
    else {
        \TAS\Core\Web::Redirect($GLOBALS['AppConfig']['HomeURL']);
    }
}

/*************************************** End Check Post Data ***************************************************/


/************************************ Start Invoice Tables Data ************************************************/

$HTML = '';
$finalTotal=0;
if (!empty($cartContent))
{
    
    
        $HTML .= '<div class="table-responsive cart-page-table mb-3">
	                <table class="table">
	                    <thead class="carttablebg text-white thead-dark">
	                        <tr>
	                            <th class="product_name_table">Product Name</th>
	                            <th class="price_table">Price</th>
                                <th class="price_table">Quantity</th>
                                <th class="price_table">Total</th>
	                        </tr>
	                    </thead>
	                    <tbody>';
        
        $finalTotal=0;
        
        $imageFile = new \TAS\Core\ImageFile();
        $imageFile->ThumbnailSize = $GLOBALS['ThumbnailSize'];
        $imageFile->LinkerType = 'product';
        
        foreach ( $cartContent as $cartproduct ) {
            $images = $imageFile->GetImageOnLinker($cartproduct['productid'],true,'displayorder asc,imageid asc');
            $imgURL = $GLOBALS['AppConfig']['HomeURL'].'/theme/images/noimage.png';
            if($images['url']!='')
            {
                $imgURL = $images['url'];
            }
            
            $variation = '';
            if($cartproduct['extrainfo']!='')
            {
                $extraInfo = json_decode($cartproduct['extrainfo'],true);
                if(isset($extraInfo['variationcode']))
                {
                    $variation = $extraInfo['variationcode'];
                }
            }
            
            $HTML .= '<tr class="productrow">
                    <td class="d-flex product_name_table">
                        <div class="cartimg" style="background: url('.$imgURL.') no-repeat;background-size: cover;background-position: center;"></div>
                        <div class="pl-3 cart-product-detail">
                            <h4 class="mb-0">'.ucwords($cartproduct['productname']).'('.$cartproduct['productcode'].')</h4>
                            <p>'.$variation.'</p>
                        </div>
                    </td>
                    <td class="price_table">'.$GLOBALS['AppConfig']['Currency'].'
                        <span class="unitprice"> '.number_format($cartproduct['price'],2).'</span>
                    </td>
                    <td class="price_table quantity-box">
                        '.$cartproduct['quantity'].'
                    </td>
                    <td class="price_table">'.$GLOBALS['AppConfig']['Currency'].'
                        <span class="price"> '.number_format(($cartproduct['price'] * $cartproduct['quantity']),2).'</span>
                    </td>
			</tr>';
            
            $finalTotal += ($cartproduct['price'] * $cartproduct['quantity']);
            
        }
        
        
        $tax = ($finalTotal * $GLOBALS['Configuration']['tax'])/100;
        $shippingPrice = 0;
        if(isset($GLOBALS['Configuration']['shipping']) && $GLOBALS['Configuration']['shipping'] > 0)
        {
            $shippingPrice = $GLOBALS['Configuration']['shipping'];
        }
        
        
        $grandTotal = $finalTotal + $tax + $shippingPrice;
        $HTML .= '<tr class="cartTotalheight">
                <td class="text-right" colspan="3">Total Price</td>
                <td class="finaltotal">
                    <b>'.$GLOBALS['AppConfig']['Currency'].'</b>
                    <b class="grandtotal">'.number_format($finalTotal,2).'</b></td>
              </tr>
              <tr class="cartTotalheight">
                <td class="text-right" colspan="3">Vat ('.$GLOBALS['Configuration']['tax'].'%)</td>
                <td class="tax">
                    <b>'.$GLOBALS['AppConfig']['Currency'].'</b>
                    <b class="grandtotal">'.number_format($tax,2).'</b></td>
              </tr> 
               '.($shippingPrice > 0 ? '
                <tr class="cartTotalheight">
                <td class="text-right" colspan="3">Delivery Fee</td>
                <td class="tax">
                    <b>'.$GLOBALS['AppConfig']['Currency'].'</b>
                    <b class="grandtotal">'.number_format($shippingPrice,2).'</b></td>
              </tr>
               ':'').' 
                <tr class="cartTotalheight">
                <td class="text-right" colspan="3">Grand Total</td>
                <td class="finaltotal">
                    <b>'.$GLOBALS['AppConfig']['Currency'].'</b>
                    <b class="grandtotal">'.number_format($grandTotal,2).'</b></td>
              </tr>
            </tbody>
        </table>
    </div>';
}
else
{
    \TAS\Core\Web::Redirect($GLOBALS['AppConfig']['HomeURL'].'/productsearch.php');
}

/************************************ End Invoice Tables Data ************************************************/

/********************************** Start Get User Default Address ****************************************/
if(isset($_SESSION['userid']))
{
    $address= Address::GetDefaultAddress($_SESSION['userid'], 'user', 'default');
}


/********************************** End Get User Default Address ****************************************/

$pageParse['Content'] .='<section class="common-section myaccount-section">
	<div class="container py-7">
		<div class="col-xl-11 col-lg-12 mx-auto checkoutpage">
		   <form action="" method="post" class="validate checkoutform" novalidate="novalidate">
			    <div class="row mb-5">
			        <div class="col-md-12">
			            <h2 class="heading">Your Order</h2>
			            <hr>
			            '.$HTML.'
			        </div>
			    </div>
			    <div class="row">
			        <div class="col-lg-6 col-md-12 pr-lg-5 mb-lg-0 mb-3">
			            <h2 class="heading">Billing Details</h2>
			            <hr>
			            <div class="row">
			                <div class="col-md-6 form-group">
			                    <label class="col-form-label">First Name</label>
			                    <input type="text" name="firstname" class="form-control rounded-0" placeholder="First Name*" value="'.(isset($address) && $address['title']!=''?$address['title']:'').'" required/>
			                </div>
			                <div class="col-md-6 form-group">
			                    <label class="col-form-label">Last Name</label>
			                    <input type="text" name="lastname" class="form-control rounded-0" placeholder="Last Name" value="'.(isset($address) && $address['subtitle']!=''?$address['subtitle']:'').'"/>
			                </div>
			            </div>
			            <div class="row">
			                <div class="col-md-6 form-group">
			                    <label class="col-form-label">Email</label>
			                    <input type="email" name="email" class="form-control rounded-0" placeholder="Email*" value="'.(isset($address) && $address['email']!=''?$address['email']:'').'" required/>
			                </div>
			                <div class="col-md-6 form-group">
			                    <label class="col-form-label">Phone</label>
			                    <input type="text" name="phone" class="form-control rounded-0 phone" placeholder="Phone*" value="'.(isset($address) && $address['phone']!=''?$address['phone']:'').'" required/>
			                </div>
			            </div>
			            <div class="row">
			                <div class="col-md-12 form-group">
			                    <label class="col-form-label">Address</label>
			                    <input type="text" name="address1" class="form-control rounded-0" placeholder="Address1*" value="'.(isset($address) && $address['address1']!=''?$address['address1']:'').'" required/>
			                    <input type="text" name="address2" class="form-control rounded-0 mt-3" placeholder="Address2" value="'.(isset($address) && $address['address2']!=''?$address['address2']:'').'"/>
			                </div>
			            </div>
			            <div class="row">
			                <div class="col-md-6 form-group select2error">
			                    <label for="Select" class="col-form-label">Country</label>
			                    <select class="form-control custom-select2 select2" name="country" required>
		                             '.\TAS\Core\UI::RecordSetToDropDown($GLOBALS['db']->Execute("Select * from " . $GLOBALS['Tables']['countries']),(isset($address) && $address['country']!=''?$address['country']:''), 'countryid', 'countryname').'
		                        </select>
			                </div>
			                <div class="col-md-6 form-group">
			                    <label class="col-form-label">City</label>
			                    <input type="text" name="city" class="form-control rounded-0" placeholder="City*" value="'.(isset($address) && $address['city']!=''?$address['city']:'').'" required/>
			                </div>
			            </div>
			            <div class="row">
			                <div class="col-md-6 form-group">
			                    <label class="col-form-label">State</label>
			                    <input type="text" name="state" class="form-control rounded-0" placeholder="State" value="'.(isset($address) && $address['state']!=''?$address['state']:'').'"/>
			                </div>
                            <div class="col-md-6 form-group">
			                    <label class="col-form-label">Post Code</label>
			                    <input type="text" name="zipcode" class="form-control rounded-0" placeholder="Post Code" value="'.(isset($address) && $address['zipcode']!=''?$address['zipcode']:'').'"/>
			                </div>
			            </div>
			        </div>
			        <div class="col-lg-6 col-md-12 mb-lg-0 mb-3">
			            <div class="row">
			                <div class="col-md-12 d-sm-flex align-items-center justify-content-between">
			                    <h2 class="heading mb-2 mb-sm-0">Delivery Details</h2>
			                    <div class="d-inline-block" data-toggle="collapse" data-target="#shippingdetails">
			                    	<label class="custom-checkbox mb-0"><input type="checkbox" class="form-control">
			                          	<span class="checkmark-box"></span>
			                          	<span>Ship to a different address?</span>
		                          	</label>
			                    </div>
			                </div>
			            </div>
			            <hr>
			            <div id="shippingdetails" class="collapse">
			                <div class="row">
			                    <div class="col-md-6 form-group">
			                        <label class="col-form-label">First Name</label>
			                        <input type="text" name="shippingfirstname" class="form-control rounded-0" placeholder="First Name*" required/>
			                    </div>
			                    <div class="col-md-6 form-group">
			                        <label class="col-form-label">Last Name</label>
			                        <input type="text" name="shippinglastname" class="form-control rounded-0" placeholder="Last Name"/>
			                    </div>
			                </div>
			                <div class="row">
			                    <div class="col-md-6 form-group">
			                        <label class="col-form-label">Email</label>
			                        <input type="email" name="shippingemail" class="form-control rounded-0" placeholder="Email*" required/>
			                    </div>
			                    <div class="col-md-6 form-group">
			                        <label class="col-form-label">Phone</label>
			                        <input type="text" name="shippingphone" class="form-control rounded-0 phone" placeholder="Phone*" required/>
			                    </div>
			                </div>
			                <div class="row">
			                    <div class="col-md-12 form-group">
			                        <label class="col-form-label">Address</label>
			                        <input type="text" name="shippingaddress1" class="form-control rounded-0" placeholder="Address1*" required/>
			                        <input type="text" name="shippingaddress2" class="form-control rounded-0 mt-3" placeholder="Address2"/>
			                    </div>
			                </div>
			                <div class="row">
			                    <div class="col-md-6 form-group select2error">
			                        <label for="Select2" class="col-form-label d-block">Country</label>
			                        <select class="form-control custom-select2 select2" name="shippingcountry" required>
			                             '.\TAS\Core\UI::RecordSetToDropDown($GLOBALS['db']->Execute("Select * from " . $GLOBALS['Tables']['countries']),'', 'countryid', 'countryname').'
			                        </select>
			                    </div>
			                    <div class="col-md-6 form-group">
			                        <label class="col-form-label">City</label>
			                        <input type="text" name="shippingcity" class="form-control rounded-0" placeholder="City*" required/>
			                    </div>
			                </div>
                            <div class="row">
			                    <div class="col-md-6 form-group">
			                        <label class="col-form-label">State</label>
			                        <input type="text" name="shippingstate" class="form-control rounded-0" placeholder="State"/>
			                    </div>
                                <div class="col-md-6 form-group">
			                        <label class="col-form-label">Post Code</label>
			                        <input type="text" name="shippingzipcode" class="form-control rounded-0" placeholder="Post Code"/>
			                    </div>
			                </div>
			            </div>
			        </div>
			    </div>
			    <div class="row">
			        <div class="col-md-12">
	                	<label class="custom-checkbox"><input type="checkbox" class="form-control">
	                      	<span class="checkmark-box"></span>
	                      	<span>I have read and agree to the website terms and conditions.</span>
	                  	</label>
			            <div class="mt-4">
			                <button class="btn btn--primary commonBtn placeorder" type="submit">Place Order</button>
			            </div>
			        </div>
			    </div>
			</form>
		</div>
	</div>
</section>';

echo \TAS\Core\TemplateHandler::TemplateChooser("single");