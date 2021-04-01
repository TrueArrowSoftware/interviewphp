<?php
namespace Framework; 
require("../template.php");
require_once("./include.php");
$messages= array();
$productid = isset($_GET['id'])?(int)$_GET['id']:0;
$product= new Product($productid );
if ($productid<= 0 || !$permission->CheckOperationPermission("product", "edit", $GLOBALS['user']->UserRoleID)) {
	\TAS\Core\Web::Redirect("index.php");
}

if($productid > 0)
{
    if(!$product->IsLoaded())
    {
        \TAS\Core\Web::Redirect("index.php");
    }
}

if($_SERVER['REQUEST_METHOD'] =='POST') {
	$d=array();
	$d=\TAS\Core\Entity::ParsePostToArray(Product::GetFields($productid));
    $d['editdate']= date("Y-m-d H:i:s");
	$d['brandid'] = $d['brandid'] ==''?0:$d['brandid'] ;
	$productslug = \TAS\Core\DataFormat::CreateSlug($_POST['productname']);
	/* check product slug unique */
	$checkProductSlug = $GLOBALS['db']->ExecuteScalar('select count(*) from '.$GLOBALS['Tables']['product'].' where productslug="'.$productslug.'"');
	if($checkProductSlug > 0)
	{
	    $productslug = \TAS\Core\DataFormat::CreateSlug($_POST['productname']).'-'.rand(1,1000);
	    $x1=1;
	    do {
	        $countCheck = $GLOBALS['db']->ExecuteScalar('select count(*) from '.$GLOBALS['Tables']['product'].' where productslug="'.$productslug.'"');
	        if($countCheck > 0)
	        {
	            $productslug = \TAS\Core\DataFormat::CreateSlug($_POST['productname']).'-'.rand(1,1000);
	        }
	        else
	        {
	            $x1=0;
	        }
	        
	    }while ($x1 < 0);
	}
	
	$d['productslug'] = $productslug;
	$isupdated= $product->Update($d);
	if($isupdated) {
	    $GLOBALS['db']->Execute("Delete from ". $GLOBALS['Tables']['productcategory']. " where productid=". $productid);
	    if(isset($_POST['categories']) && is_array($_POST['categories']) && count($_POST['categories'])>0) {
	        foreach($_POST['categories'] as $catid) {
	            
	            $GLOBALS['db']->Insert("productcategory", array('productid'=> $productid, 'categoryid'=> (int)$catid));
	        }
	    }
		$messages[] = array("message"=> _("Product has been updated successfully."), "level"=>1);
	} else {
		$messages[] = array("message"=> _("Unable to update product at this moment. Please try again later."), "level"=>10);
	}
}


$pageParse['Content'] = DisplayForm($productid);
echo \TAS\Core\TemplateHandler::TemplateChooser("admin");
