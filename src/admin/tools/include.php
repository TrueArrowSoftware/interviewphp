<?php
namespace Framework;

function DisplayForm($edit = 0)
{
    $formtitle = ($edit > 0) ? "Edit Product" : "Add Product";

    $fields = array();
    $fields = Product::GetFields($edit);

    $param['Fields'] = $fields;
    $param['Group'] = array(
        'basic' => array(
            'legend' => ''
        )
    );

    $form = '<h2>' . _($formtitle) . '</h2>' . \TAS\Core\UI::UIMessageDisplay($GLOBALS['messages']) . '
<form action="" method="post" class="validate">
<fieldset class="generalform">
	<legend></legend>
	' . \TAS\Core\UI::GetFormHTML($param) . '
    <div class="formbutton shnote" style="display:none;">
        <div class="clr">Note* : You need to add variation before you can use this.</div> 
    </div> 
	<div class="formbutton">
		<input type="submit" name="btnsubmit" id="btnsubmit" class="ui-button ui-state-default ui-corner-all" value="' . _("Submit") . '" />			
	</div>
</fieldset>
</form>';
    return $form;
}

function DisplayGrid()
{
    $queryoptions = \TAS\Core\Grid::DefaultQueryOptions();
    $queryoptions['basicquery'] = "select p.*,c.companyname from " . $GLOBALS['Tables']['product'] . " as p left join " . $GLOBALS['Tables']['company'] . " as c on p.brandid = c.companyid";
    $filterOptions = array();
    if (isset($_COOKIE['admin_product_filter']) && $_SERVER['REQUEST_METHOD'] == 'GET') {
        $filterOptions = json_decode(stripslashes($_COOKIE['admin_product_filter']), true);
    } else {
        $filterOptions = $_POST;
    }
    $filter = array();

    if (isset($filterOptions['productname']) && $filterOptions['productname'] != '') {
        $filter[] = "productname LIKE '%" . \TAS\Core\DataFormat::DoSecure($filterOptions['productname']) . "%'";
    }
    if (isset($filterOptions['productcode']) && $filterOptions['productcode'] != '') {
        $filter[] = "productcode LIKE '%" . \TAS\Core\DataFormat::DoSecure($filterOptions['productcode']) . "%'";
    }

    if (isset($filterOptions['producttype']) && $filterOptions['producttype'] != '') {
        $filter[] = "producttype LIKE '%" . \TAS\Core\DataFormat::DoSecure($filterOptions['producttype']) . "%'";
    }
    if (isset($filterOptions['brand']) && $filterOptions['brand'] != '') {
        $filter[] = "companyname LIKE '%" . \TAS\Core\DataFormat::DoSecure($filterOptions['brand']) . "%'";
    }

    if (count($filter) > 0) {
        $queryoptions['whereconditions'] = ' where ' . implode(' and ', $filter) . ' ';
    } else {
        $queryoptions['whereconditions'] = ' ';
    }
   
    $queryoptions['pagingquery'] = "select count(*) from " . $GLOBALS['Tables']['product'];

    $_COOKIE['admin_product_filter'] = json_encode($filterOptions);
    setcookie('admin_product_filter', json_encode($filterOptions), (time() + 25292000));

    $options = \TAS\Core\Grid::DefaultOptions();
        $options['gridurl'] = $GLOBALS['AppConfig']['AdminURL'] . '/product/index.php';
        $options['gridid'] = 'productid';
        $options['allowsorting'] = true;
        $options['allowpaging'] = true;
        $options['showtotalrecord'] = true;
        $options['allowselection'] = false;

        /* load default icon */
        $icon = new \TAS\Core\Grid();
        $options['option'] = $icon->DefaultIcon();
        $options['option']['edit']['link'] = $GLOBALS['AppConfig']['AdminURL'] . '/product/edit.php';
        $options['fields'] = array(
        'productid' => array(
            'type' => 'string',
            'name' => '#'
        ),
        'productname' => array(
            'type' => 'string',
            'name' => 'Product Name'
        ),
        'companyname' => array(
            'type' => 'string',
            'name' => 'Brand'
        ),
        'productcode' => array(
            'type' => 'string',
            'name' => 'Product Code'
        ),
        'producttype' => array(
            'type' => 'globalarray',
            'name' => 'Product Type',
            'arrayname' => 'producttype'
        ),
        'singleprice' => array(
            'type' => 'currency',
            'name' => 'Price'
        ),
        'status' => array(
            'type' => 'onoff',
            'name' => 'Status',
            'mode' => 'fa'
        )
    );
    $queryoptions['defaultorderby'] = 'productid';
    $queryoptions['defaultsortdirection'] = 'desc';
    $queryoptions['indexfield'] = 'productid';
	$queryoptions['tablename'] = $GLOBALS['Tables']['product'];
        
    $options['option']['image'] = array(
        'link' => $GLOBALS['AppConfig']['AdminURL'] . '/productimage/index.php',
        'iconclass' => 'fa-image',
        'tooltip' => 'Product Images',
        'tagname' => 'productimage',
        'paramname' => 'productid',
    );
    
    $grid = new \TAS\Core\Grid($options, $queryoptions);
    return $grid->Render();
    
}
