<?php 
namespace Framework;
require "./configure.php";
require ("./template.php");
$pageParse['PageTitle'] = 'Product Detail | '.$GLOBALS['AppConfig']['SiteName'];
$messages =array();

/*  BreadCrumb*/
/* $headerimage = $GLOBALS['db']->Fetch($GLOBALS['db']->Execute("Select * from " . $GLOBALS['Tables']['pages'] . " where page= 'Online Shop'"));
 $headerimage = $headerimage['headerimage'];
 $pageParse['PageName'] = 'Login';
 $pageParse['BreadCrumb'] = BreadCrumb('Login', '', $headerimage); */

$product = (isset($_GET['product']) ?$_GET['product']:'');

if($product=='')
{
    \TAS\Core\Web::Redirect($GLOBALS['AppConfig']['HomeURL']);
}

/********************************************** Start Product Detail Process  *********************************************/

$productID = Search::GetProductID($product);
$productDetail = new Product($productID);
if($productID <=0 || !$productDetail->IsLoaded())
{
    \TAS\Core\Web::Redirect($GLOBALS['AppConfig']['HomeURL']);
}


/******************* Start Add To Cart Scrip *************************/
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cart = new Cart();
    $cartid = $cart->CartSettings['CartSession'];
    $variationid = 0;
    if(isset($_POST['variationid']) && $_POST['variationid'] > 0)
    {
        $variationid = (int) \TAS\Core\DataFormat::DoSecure($_POST['variationid']);
    }
    
    $countAddedItem= $GLOBALS['db']->ExecuteScalar('select count(*) from '.$GLOBALS['Tables']['cart'].' where productid="'.$productID.'" and variationid="'.$variationid.'" and cartid="'.$cartid.'"');
    
    $variationValHTML = '';
    $newproductcode = $productDetail->ProductCode;
    $newprice = $productDetail->SinglePrice;
    if($variationid > 0)
    {
        $newVariation = new ProductVariation($variationid);
        $newproductcode = $newVariation->ProductCode;
        $newprice = $newVariation->SinglePrice;
        $getNewVar = $GLOBALS['db']->Execute('select ao.*,e.value as value from '.$GLOBALS['Tables']['productvariationoption'].' as pvo left join '.$GLOBALS['Tables']['attributeoption'].' as ao on pvo.optionid=ao.optionid left join '.$GLOBALS['Tables']['enumeration'].' as e on pvo.attribute=e.ekey and e.type="attribute" where pvo.variationid="'.$variationid.'"');
        if(\TAS\Core\DB::Count($getNewVar) > 0)
        {
            $newCountVar = 1;
            while($rowVariationVal = $GLOBALS['db']->Fetch($getNewVar))
            {
                if($newCountVar=='1')
                {
                    $variationValHTML .= $rowVariationVal['value'].':'.$rowVariationVal['optionname'];
                }
                elseif($newCountVar > 1)
                {
                    $variationValHTML .= ', '.$rowVariationVal['value'].':'.$rowVariationVal['optionname'];
                }
                $newCountVar++;
            }
        }
    }
    
    if($countAddedItem > 0)
    {
        $messages[] = array(
            "message" => _($productDetail->ProductName."(".$newproductcode."). has been already added into cart."),
            "level" => 10
        );
    }
    else
    {
        $itemid = Cart::SetCartProduct(array(
            'cartid' => $cartid,
            'productid' => $productID,
            'variationid'=>$variationid,
            'productcode' => $newproductcode,
            'productname' => $productDetail->ProductName,
            'price' => $newprice,
            'quantity' => (int)\TAS\Core\DataFormat::DoSecure($_POST['quantity']),
            'extrainfo' => ($variationValHTML!=''?json_encode(array(
                'variationcode' => $variationValHTML,
            )):''),
            
        ));
        if($itemid > 0)
        {
            \TAS\Core\Web::Redirect($GLOBALS['AppConfig']['HomeURL'].'/cart.php');
        }
    }
}

/******************* End Add To Cart Script  ************************/


/******************* Start Slider Script  ************************/

$imageFile = new \TAS\Core\ImageFile();
$imageFile->ThumbnailSize = $GLOBALS['ThumbnailSize'];
$imageFile->LinkerType = 'product';
$images = $imageFile->GetImageOnLinker($productID,false,'displayorder asc,imageid asc');

$leftImage = '';
$mainSlider = '';
$count = 1;

if(!empty($images))
{
    foreach($images as $img)
    {
        $imgURL = \TAS\Core\UI::ImageURLFinder($img, 136, 168);
        $leftImage .='<li class="list-inline-item '.($count=='1'?'active':'').'">
                          <a id="carousel-selector-'.($count - 1).'" data-slide-to="'.($count - 1).'" data-target="#product-slider">
                            <img src="'.$imgURL.'" class="img-fluid" />
                              <div class="thumb-img" style="background: url('.$imgURL.') no-repeat;"></div>
                          </a>
                      </li>';
        
        $mainSlider .= '<div class="'.($count=='1'?'active':'').' carousel-item" data-slide-number="'.$count.'" style="background: url('.$img['url'].') no-repeat;"></div>';
        $count++;
    }
}
else
{
    $mainSlider .= '<div class="'.($count=='1'?'active':'').' carousel-item noimage" data-slide-number="'.$count.'" style="background: url({HomeURL}/theme/images/noimage.png) no-repeat;"></div>';
}

/******************* End Slider Script  ************************/


/******************* Start Variation Script ********************/

$variationHTML = Search::CheckProductVariation($productID);

/******************* End Variation Script ********************/

/********************************************** End Product Detail Process  ********************************************/

$pageParse['Content']='
<section class="common-section single-product-page pb-5">
    <div class="container">
        <div class="col-xl-11 col-lg-12 mx-auto">
            <div class="mt-3">' . \TAS\Core\UI::UIMessageDisplay ($messages) . '</div>
            <div class="card mb-4 single-product-section border-0">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-3 -mb-lg-0">
                        <div class="row single-product-section-inner p-3">
                            <div class="col-md-5 single-product-image">
                                <div id="product-slider" class="carousel slide">
                                    <div class="carousel-inner">
                                        '.$mainSlider.'
                                    </div>

                                    <ul class="carousel-indicators list-inline mx-auto border px-2">
                                        '.$leftImage.'
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-7 single-product-detail">
                                <form action="{HomeURL}/productdetail.php?product='.$product.'" class="validate productform" method="post">
                                    <h2 class="heading">'.ucwords($productDetail->ProductName).' (<span id="pcode">'.$productDetail->ProductCode.'</span>)</h2>
                                    <p>'.$productDetail->Description.'</p>
                                    <h4>Price : '.$GLOBALS['AppConfig']['Currency'].'<span id="pprice">'.number_format($productDetail->SinglePrice,2).'</span></h4>
                                    <div class="variationhtml">
                                            '.($variationHTML!=''?'<div class="d-flex"><span class="">Variation :</span> <span class="w-50 pl-2">'.$variationHTML.'</span></div>':'').'
                                    </div>
                                    <div class="d-flex pt-3">
                                        <label class="col-form-label">Quantity : </label>
                                        <div class="quantity ml-4">
                                            <span class="quantity-minus cursor-pointer">-</span>
                                            <input type="number" min="1" class="qty" name="quantity" value="1" title="Qty" readonly/>
                                            <span class="quantity-plus cursor-pointer">+</span>
                                        </div>
                                    </div>
                                    <button id="addtocart" type="button" class="btn btn--primary commonBtn mt-4">Add to cart</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>';

echo \TAS\Core\TemplateHandler::TemplateChooser("single");