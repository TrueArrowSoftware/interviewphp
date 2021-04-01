<?php
namespace Framework;
require "./configure.php";
require ("./template.php");
$pageParse['PageTitle'] = 'Cart | '.$GLOBALS['AppConfig']['SiteName'];
$messages = array();

/*  BreadCrumb*/
/* $headerimage = $GLOBALS['db']->Fetch($GLOBALS['db']->Execute("Select * from " . $GLOBALS['Tables']['pages'] . " where page= 'Online Shop'"));
 $headerimage = $headerimage['headerimage'];
 $pageParse['PageName'] = 'Login';
 $pageParse['BreadCrumb'] = BreadCrumb('Login', '', $headerimage); */

$cart = new Cart();
$cartid = $cart->CartSettings['CartSession'];
$cartContent = Cart::GetCartContent ( $cart->CartSettings ['CartSession'] );
$HTML = '';
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
                        <div class="d-flex align-items-center mr-3">
                        <a data-cartid="'.$cartproduct['itemid'].'" href="javascript:void(0)" class="deletecartitem d-block">
                            <span class="fa-stack">
                                <i class="fas fa-circle fa-stack-2x text-danger"></i>
                                <i class="fas fa-times fa-stack-1x text-white"></i>
                            </span>
                        </a>
                        </div>
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
                        <input type="number" min="1" max="100" class="quantity" name="quantity" value="'.$cartproduct['quantity'].'" data-itemid="'.$cartproduct['itemid'].'" data-price="'.$cartproduct['price'].'" autocomplete="off"/>
                    </td>
                    <td class="price_table">'.$GLOBALS['AppConfig']['Currency'].'
                        <span class="price"> '.number_format(($cartproduct['price'] * $cartproduct['quantity']),2).'</span>
                    </td>
			</tr>';
        
        $finalTotal += ($cartproduct['price'] * $cartproduct['quantity']);
        
    }
    
    $HTML .= '<tr class="cartTotalheight">
                <td class="text-right" colspan="3">Total Price</td>
                <td class="finaltotal">
                    <b>'.$GLOBALS['AppConfig']['Currency'].'</b>
                    <b class="grandtotal">'.number_format($finalTotal,2).'</b></td>
              </tr>
            </tbody>
        </table>
    </div>


        <div class="d-flex justify-content-end">
            <a href="{HomeURL}/productsearch.php" class="btn btn--primary commonBtn">Continue Shopping</a>
            '.(isset($_SESSION['userid']) && $_SESSION['userid'] > 0 ?'
                <a href="{HomeURL}/checkout.php" class="btn btn--primary commonBtn ml-2">Proceed to Checkout</a>
                ':'
                <a href="{HomeURL}/checkout.php" class="btn btn--primary commonBtn ml-2">Checkout as Guest</a>
                <a href="{HomeURL}/login.php" class="btn btn--primary commonBtn ml-2">Login/Register</a>
            ').'
            
        </div>';
}
else
{
    $HTML .=' <div class="row">
                <div class="text-center col-md-4 offset-md-4 col-sm-12 col-xs-12">
                    <img src="{HomeURL}/theme/images/cart.png"  class="w-100">
                </div>
               </div>
                <div class="row my-5">
                    <div class="col-md-12 mt-3">
                        <div class="cartbuttons text-center">
        				    <a href="{HomeURL}/productsearch.php" class="btn btn--primary">Continue Shopping </a>
                         </div>
                    </div>
                 </div>';
}



$pageParse['Content'] .='<section class="common-section myaccount-section">
	<div class="container py-7">
	    <div class="row">
	        <div class="col-xl-11 col-lg-12 mx-auto">
	            '.$HTML.'
	        </div>
	    </div>
	</div>
</section>';
echo \TAS\Core\TemplateHandler::TemplateChooser("single");
