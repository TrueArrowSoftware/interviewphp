<?php
namespace Framework;
require ("../template.php");
require_once ("./include.php");
$messages = array();
if (! $permission->CheckOperationPermission('product', 'add', $GLOBALS['user']->UserRoleID)) {
   \TAS\Core\Web:: Redirect("index.php");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $d = \TAS\Core\Entity::ParsePostToArray(Product::GetFields());
    $d['adddate'] = date("Y-m-d H:i:s");
    $d['brandid'] = $d['brandid'] == '' ? 0 : $d['brandid'];
    
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
    $productid = Product::Add($d);
    if ($productid > 0) {
        if (isset($_POST['categories']) && is_array($_POST['categories']) && count($_POST['categories']) > 0) {
            foreach ($_POST['categories'] as $catid) {
                $GLOBALS['db']->Insert("productcategory", array(
                    'productid' => $productid,
                    'categoryid' => (int) $catid
                ));
            }
        }
        $messages[] = array(
            "message" => _("Product has been added successfully."),
            "level" => 1
        );
    } else {
        if (count(Product::GetErrors()) > 0) {
            $a = Product::GetErrors();
            foreach ($a as $i => $v) {
                $messages[] = $v;
            }
        }
        else {
            $messages[] = array(
                "message" => _("Unable to add product at this moment. Please try again later."),
                "level" => 10
            );
        }
        
    }
}

$pageParse['Content'] .= DisplayForm();
echo \TAS\Core\TemplateHandler::TemplateChooser("admin");