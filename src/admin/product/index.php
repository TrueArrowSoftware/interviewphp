<?php
namespace Framework;
require("../template.php");
require_once("./include.php");
$messages= array ();
if (! $permission->CheckOperationPermission('product', 'access', $GLOBALS['user']->UserRoleID)) {
    \TAS\Core\Web::Redirect("../index.php");
}
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['delete']) && is_numeric($_GET['delete']) && $permission->CheckOperationPermission('product', 'delete', $GLOBALS['user']->UserRoleID)) {
        Product::DeleteProductVariation((int) $_GET['delete']);
        if (Product::Delete((int) $_GET['delete'])) {
            $messages[] = array(
                "message" => _("Product has been deleted successfully."),
                "level" => 1
            );
        } else {
            $messages[] = array(
                "message" => _("Unable to delete product at this moment. Please try again later."),
                "level" => 10
            );
        }
    }
    
    
    if (isset($_GET['mode']) && $_GET['mode'] == 'clearfilter') {
        setcookie('admin_product_filter', '', (time() - 25292000));
        \TAS\Core\Web::Redirect("index.php");
    }
    
    if (isset($_GET['type']) && is_numeric($_GET['id'])) {
        if ($_GET['type'] == 'status') {
            $u = new Product((int) $_GET['id']);
            if ($u->IsLoaded()) {
                $s = (($u->Status == 1) ? 0 : 1);
                $GLOBALS['db']->Execute("update " . $GLOBALS['Tables']['product'] . " set status=" . $s . " where productid=" . $u->ProductID);
                \TAS\Core\Web::Redirect ( "index.php?status=1?page=".$_GET['page'] );
            } else {
                $messages[] = array(
                    "message" => _("No product found to update status."),
                    "level" => 10
                );
            }
        }
    }
}
if (isset ( $_GET ['status'] ) && $_GET ['status'] == '1') {
    $messages [] = array (
        "message" => _ ( "Product status has been updated successfully." ),
        "level" => 1
    );
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' ) {
    $filterOptions = $_POST;
} else {
    $filterOptions = (isset($_COOKIE['admin_product_filter']) ? json_decode(stripslashes($_COOKIE['admin_product_filter']), true) : array());
}


$pageParse['Content'] .= '<div class="col-md-12 pt-3"> <div class="card card-body card-radius">
<h2 class="borderbottom-set">Product Management</h2>';
$pageParse['Content'] .= '<div class="px-3 mt-3 display-messages">'.\TAS\Core\UI::UIMessageDisplay($messages).'</div>';
$pageParse['Content'] .= '<h6 class="px-3 pt-3 m-0"><a href="add.php">Add New Product</a></h6>';

$pageParse['Content'] .= '
	<ul class="filterul d-flex p-3">
<li class="mr-2"><input type="button" name="filter" id="filter" class="btn primary-color primary-bg-color py-2" value="Show Filters"/></li>
<li><a href="index.php?mode=clearfilter" class="btn primary-color btn-dark py-2"> Clear Filter</a></li>
</ul>
    
<div id="filterbox" class="filter-form-setting">
<form method="post" action="index.php">
	<div class="form-row">
				    
		<div class="formfield form-group col-md-6">
			<label class="formlabel" for="name"> Product Name </label>
			<div class="forminputwrapper">
				<input type="text" name="productname" id="name" class="form-control" value="' . (isset($filterOptions['productname']) ? $filterOptions['productname'] : '') . '" />
			</div>
		<div class="clear"></div></div>
				    
		<div class="formfield form-group col-md-6">
			<label class="formlabel" for="search_quote"> Product Code </label>
			<div class="forminputwrapper">
				<input type="text" name="productcode" id="code" class="form-control numeric" value="' . (isset($filterOptions['productcode']) ? $filterOptions['productcode'] : '') . '" />
			</div>
		<div class="clear"></div></div>

    	<div class="formfield form-group col-md-6">
			<label class="formlabel" for="search_quote"> Brand </label>
			<div class="forminputwrapper">
				<input type="text" name="brand" id="brand" class="form-control numeric" value="' . (isset($filterOptions['productcode']) ? $filterOptions['productcode'] : '') . '" />
			</div>
		<div class="clear"></div></div>
	</div>
		<ul class="filterul d-flex w-100">
            <li><button type="submit" name="submit" class="btn primary-color primary-bg-color py-2 m-0" id="filtersubmit">Filter Report</button><li>
        </ul>				    

	</form></div></div></div>';
$pageParse['Content'] .= '<br>'.DisplayGrid() ;
echo \TAS\Core\TemplateHandler::TemplateChooser("admin");