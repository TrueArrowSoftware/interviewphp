<?php
require("../template.php");
require_once("./include.php");
if (! $permission->CheckOperationPermission('order', 'access', $GLOBALS['user']->UserRoleID)) {
    \TAS\Core\Web::Redirect ( "../index.php" );
}

if (isset ( $_GET ['mode'] ) && $_GET ['mode'] == 'clearfilter') {
    setcookie ( 'admin_order_filter', '', (time () - 25292000) );
    \TAS\Core\Web::Redirect ( "index.php" );
}

if (isset ( $_COOKIE ['admin_order_filter'] ) && $_SERVER ['REQUEST_METHOD'] == 'GET') {
    $filterOptions = json_decode ( $_COOKIE ['admin_order_filter'], true );
} else {
    $filterOptions = $_REQUEST;
}

$pageParse['Content'] .= '<div class="col-md-12 pt-3"> <div class="card card-body card-radius">
<h2 class="borderbottom-set">Order Management</h2>';

$pageParse ['Content'] .= '
<ul class="filterul d-flex p-3">
<li class="mr-2"><input type="button" name="filter" id="filter" class="btn primary-color primary-bg-color py-2" value="Show Filters"/></li>
<li><a href="index.php?mode=clearfilter" class="btn primary-color btn-dark py-2"> Clear Filter</a></li>
</ul>
	<div id="filterbox" class="filter-form-setting"><form method="post" action="index.php">
	<div class="form-row">
		<div class="formfield form-group col-md-6">
			<label class="formlabel" for="firstname">Order ID</label>
			<div class="forminputwrapper">
				<input type="text" name="orderid" id="orderid" class="form-control" value="' . (isset ($filterOptions ['orderid'] ) ? $filterOptions ['orderid'] : '') . '" />
			</div>
			<div class="clear"></div>
		</div>
		<div class="formfield form-group col-md-6">
			<label class="formlabel" for="firstname">Email</label>
			<div class="forminputwrapper">
				<input type="text" name="email" id="email" class="form-control" value="' . (isset ($filterOptions ['email'] ) ? $filterOptions ['email'] : '') . '" />
			</div>
			<div class="clear"></div>
		</div>
		<div class="formfield form-group col-md-6">
			<label class="formlabel" for="firstname">First Name</label>
			<div class="forminputwrapper">
				<input type="text" name="firstname" id="firstname" class="form-control" value="' . (isset ($filterOptions ['firstname'] ) ? $filterOptions ['firstname'] : '') . '" />
			</div>
		<div class="clear"></div></div>
		<div class="formfield form-group col-md-6">
			<label class="formlabel" for="lastname">Last Name</label>
			<div class="forminputwrapper">
				<input type="text" name="lastname" id="lastname" class="form-control" value="' . (isset ($filterOptions ['lastname'] ) ? $filterOptions ['lastname'] : '') . '" />
			</div>
		<div class="clear"></div></div>
		<div class="formfield form-group col-md-6">
			<label class="formlabel" for="search_lastname">Phone</label>
			<div class="forminputwrapper">
			<input type="text" name="phone" id="phone" class="form-control" value="' . (isset ($filterOptions ['phone'] ) ? $filterOptions ['phone'] : '') . '" />
			</div>
		<div class="clear"></div></div>		    
		<div class="formfield form-group col-md-6">
			<label class="formlabel" for="search_lastname">Order Status</label>
			<div class="forminputwrapper">
			<input type="text" name="orderstatus" id="orderstatus" class="form-control" value="' . (isset ($filterOptions ['orderstatus'] ) ? $filterOptions ['orderstatus'] : '') . '" />
			</div>
		<div class="clear"></div></div>
	</div>
				    
	<ul class="filterul d-flex w-100">
        <li><button type="submit" name="submit" class="btn primary-color primary-bg-color py-2" id="filtersubmit">Filter Report</button><li>
    </ul>
				    
	</form></div></div></div><br />';

$pageParse ['Content'] .= DisplayGrid ();
echo \TAS\Core\TemplateHandler::TemplateChooser("admin");
