<?php
namespace Framework; 
require ("./../template.php");
require ("./include.php");
$messages = array();
if (! $permission->CheckOperationPermission('product', 'add', $GLOBALS['user']->UserRoleID)) {
    \TAS\Core\Web::Redirect("index.php");
}
if (! empty($_FILES['productcsv']['name'])) 
{
   
    $arrFileName = explode('.', $_FILES['productcsv']['name']);
    if ($arrFileName[1] == 'csv') 
    {
        $row = 1;
        
        if (($handle = fopen($_FILES['productcsv']['tmp_name'], "r")) !== FALSE) 
        {
            while (($data = fgetcsv($handle, 4096, ",")) !== FALSE) 
            {
                if($row == 1)
                {
                    $row++;
                    continue;
                }
                if(isset($data[3]))
                {
                   $categoryID = category(\TAS\Core\DataFormat::DoSecure($data[3]));
                  
                  if(!empty($categoryID))
                  {
                      $productID = product(\TAS\Core\DataFormat::DoSecure($data[0]),\TAS\Core\DataFormat::DoSecure($data[1]),\TAS\Core\DataFormat::DoSecure($data[4]),\TAS\Core\DataFormat::DoSecure($data[6]),\TAS\Core\DataFormat::DoSecure($data[7]),\TAS\Core\DataFormat::DoSecure($data[8]),\TAS\Core\DataFormat::DoSecure($data[9]),$categoryID);
                      if(isset($data[5]))
                      {
                          $optionid = attribute(\TAS\Core\DataFormat::DoSecure($data[5]),$productID,\TAS\Core\DataFormat::DoSecure($data[2]),\TAS\Core\DataFormat::DoSecure($data[4]));
                          
                      }
                    
                  }
                }
            }
            //Redirect ( "bulkproduct.php" );
            
        }
    }
}
function category($categoryname)
{
    
    if($categoryname!='')
    {
        
        $category = explode(",",$categoryname);
        $catID = array();
        foreach($category as $categorydetails)
        {
            $categoryDetials = explode("&gt;",$categorydetails);
            $parentCategoryID = $GLOBALS ['db']->ExecuteScalar ("Select categoryid from " . $GLOBALS ['Tables'] ['category'] . " where categoryname='" .$categoryDetials[0] . "' and parentid='0' and status='1'  LIMIT 1");
            
            if($parentCategoryID < 1)
            {
                $d['parentid'] = 0;
                $d['adddate'] = date("Y-m-d H:i:s");
                $d['status'] = 1;
                $d['showinmenu'] = 1;
                $d['categoryname']= $categoryDetials[0];
                $parentCategoryID = Category::Add($d);
                array_push($catID,$parentCategoryID);
            }
            if(isset($categoryDetials[1])!='')
            {
                $categoryID = $GLOBALS ['db']->ExecuteScalar ("Select categoryid from " . $GLOBALS ['Tables'] ['category'] . " where categoryname='" .$categoryDetials[1] . "' and parentid='".$parentCategoryID."' and status='1'  LIMIT 1");
                if($categoryID < 1 )
                {
                    $d['parentid'] = $parentCategoryID;
                    $d['adddate'] = date("Y-m-d H:i:s");
                    $d['status'] = 1;
                    $d['showinmenu'] = 1;
                    $d['categoryname']= $categoryDetials[1];
                    if ($GLOBALS['db']->Insert($GLOBALS['Tables']['category'], $d)) {
                        $categoryID = $GLOBALS['db']->GeneratedID();
                        array_push($catID,$categoryID);
                    }
                }
                
            }
          
            array_push($catID,$parentCategoryID);
        }
        return $catID;
    }
   
}

function product($productcode,$productname,$productprice,$description,$brand,$isfeatured,$shortdescription,$categoryID)
{
    
    $brandID = $GLOBALS ['db']->ExecuteScalar ("Select companyid from " . $GLOBALS ['Tables'] ['company'] . " where companyname='" .$brand. "' and status='1'  LIMIT 1");
    if($brandID < 1)
    {
        $d['companyname'] = $brand;
        $d['status'] = 1;
        $brandID= $GLOBALS['db']->Insert($GLOBALS['Tables']['company'], $d);
    }
    
    $product = array();
    $productID = $GLOBALS ['db']->ExecuteScalar ("Select productid from " . $GLOBALS ['Tables'] ['product'] . " where productcode='" .$productcode. "' and status='1'  LIMIT 1");
    
    if($productID < 1)
    {
        $detail['productname']=$productname;
        $detail['productcode']=$productcode;
        $detail['singleprice']=(float)$productprice;
        $detail['adddate'] = date("Y-m-d H:i:s");
        $detail['producttype']='product';
        $detail['status'] = 1;
        $detail['brandid'] =$brandID;
        $detail['description']= $description;
        $detail['shortdescription']= $shortdescription;
        $detail['isfeatured'] = ($isfeatured)== 'no' ? 0 : 1;
        $productID = Product::Add($detail);
        $_SESSION['product']= 'inserted';
    }
    array_push($product,$productID);
    if($productID > 0 )
    {
        foreach($categoryID as $category)
            $GLOBALS['db']->Insert("productcategory", array(
                'productid' => (int)$productID,
                'categoryid' => (int) $category
            ));
    }
    
    return $product;
}

function attribute($attributevalue,$productid,$productcode,$price)
{
    $attributeDetails = explode(",",$attributevalue);
    $rsAttribute = array();
    foreach($attributeDetails as $attribute)
    {
        $attributeVal = explode("&gt;",$attribute);
        $countAttribute = $GLOBALS['db']->ExecuteScalar("select count(*) from " . $GLOBALS['Tables']['enumeration'] . " where ekey='" . $attributeVal[0] . "'");
        if($countAttribute < 1)
        {
            $d = array (
                'ekey' => \TAS\Core\DataFormat::DoSecure ( \TAS\Core\DataFormat::CreateSlug ( $attributeVal[0] ) ),
                'value' => \TAS\Core\DataFormat::DoSecure (ucfirst($attributeVal[0])),
                'displayorder' => 1,
                'type' => 'attribute'
            );
            $GLOBALS['db']->Insert($GLOBALS['Tables']['enumeration'], $d);
        }
        if(isset($attributeVal[0]) AND isset($attributeVal[1]))
        {
            $optionid = $GLOBALS['db']->ExecuteScalar("select optionid from " . $GLOBALS['Tables']['attributeoption'] . " where optionname='" . $attributeVal[1] . "'");
            
            if($optionid < 1)
            {
                $productVariation = array (
                    'optionname' => \TAS\Core\DataFormat::DoSecure ($attributeVal[1]),
                    'attribute' => \TAS\Core\DataFormat::DoSecure ($attributeVal[0]),
                    'tag' => '' ,
                    'displayorder' =>99
                );
                if ($GLOBALS['db']->Insert($GLOBALS['Tables']['attributeoption'], $productVariation)) {
                    $optionid = $GLOBALS['db']->GeneratedID();
                    
                }
                
            }
            
            foreach($productid as $productDetails)
            {
                if($productDetails == '')
                {
                    unset($productDetails);
                }
                else
                {
                    if($optionid > 0)
                    {
                        $variationid = $GLOBALS['db']->ExecuteScalar("select variationid from " . $GLOBALS['Tables']['productvariation'] . " where productcode='" . $productcode . "'");
                        if($variationid < 1)
                        {
                            $productvartiondata['productid'] = $productDetails;
                            $productvartiondata['productcode'] = $productcode;
                            $productvartiondata['singleprice'] = (float)$price;
                            $productvartiondata['adddate'] = date("Y-m-d H:i:s");
                            $productvartiondata['status'] = 1;
                            $variationid = ProductVariation::Add($productvartiondata);
                        }
                        if ($variationid > 0) {
                            $productvariationoption = array(
                                'variationid' => $variationid,
                                'optionid' => (int)$optionid,
                                'attribute' => $attributeVal[0]
                            );
                        $GLOBALS['db']->Insert($GLOBALS['Tables']['productvariationoption'], $productvariationoption);
                        
                        }
                    }
                }
            }
        }        
    }
}

if(isset($_SESSION['product'])!='')
{
    $messages [] = array (
        "message" => _ ( "Product has been updated successfully." ),
        "level" => 1
    );
    unset($_SESSION['product']);
}

$pageParse['Content'] .= '<h2>Import Bulk Product</h2>';
$pageParse['Content'] .= \TAS\Core\UI::UIMessageDisplay($messages);

$pageParse['Content'] .= '<div id="filterbox">
    <a href="{HomeURL}/handler/download.php?file=product.csv">Download File Format</a>
   <form method="post" enctype="multipart/form-data" class="validate">
	<fieldset class="shortfields">
    
		<div class="formfield">
			<label class="formlabel" for="productcsv">Product CSV</label>
			<div class="forminputwrapper">
				<input type="file" name="productcsv" id="productcsv" class="forminput" value="" required/>
			</div>
		<div class="clear"></div></div>
		
				    
		<p>&nbsp;</p>
		<div class="formfield"><br /><br />
			<button type="submit" name="submit" id="import">Import Product</button> <br />
		<div class="clear"></div></div>
	</fieldset>
	</form></div><br />';

echo \TAS\Core\TemplateHandler::TemplateChooser("admin");