<?php
function DisplayForm($edit = 0) {
	$formtitle = ($edit > 0) ? "Edit Product Variation" : "Add Product Variation";
	
	$fields = array ();
	$fields = \Framework\ProductVariation::GetFields ( $edit );
	$param ['Fields'] = $fields;
	$param ['Group'] = array (
			'basic' => array (
					'legend' => '' 
			) 
	);
	
	$form = '<div class="col-md-12 pt-3"> <div class="card card-body card-radius">
<h2 class="borderbottom-set">' . _ ( $formtitle ) . '</h2><div class="px-3 mt-3 display-messages">' . \TAS\Core\UI::UIMessageDisplay ( $GLOBALS ['messages'] ) . '</div>
<form action="" method="post" class="validate">
<fieldset class="generalform">
	<legend></legend>
	' . \TAS\Core\UI::GetFormHTML ( $param ) . '
	<div class="formbutton">
		<input name="btnsubmit" id="btnsubmit" class="btn primary-color primary-bg-color py-2" value="Submit" type="submit">			
	</div>
</fieldset>
</form></div></div>';
	
	$GLOBALS['pageParse']['FooterInclusion'] .= '<script>
        $(function(){
            $("#productid").change(function(){
    var productid = $(this).val();
    $.post(HomeURL+"/handler/checkproduct.php",{ productid: productid }, function(data){
        data = JSON.parse(data);
        if( data.result != undefined && data.result == "success" ) {
            $(".variations").select2("destroy"); 
            $(".variations").removeAttr("disabled");
            if(data.painttype == "paint") {
                $(".variations").attr("disabled", "disabled");
                $("#option-can-size").removeAttr("disabled");    
            } 
            $(".variations").select2();
        }
    });
});
        });
    </script>';
	
	return $form;
}

function DisplayGrid() {
	$queryoptions = \TAS\Core\Grid::DefaultQueryOptions();
	$queryoptions ['basicquery'] = "select pv.*, p.productname from " . $GLOBALS ['Tables'] ['productvariation'] . " pv left join ". 
		$GLOBALS['Tables']['product'] . " p on pv.productid=p.productid ";
	$filterOptions = array ();
	if (isset ( $_COOKIE ['admin_productvariation_filter'] ) && $_SERVER ['REQUEST_METHOD'] == 'GET') {
		$filterOptions = json_decode ( stripslashes ( $_COOKIE ['admin_productvariation_filter'] ), true );
	} else {
		$filterOptions = $_POST;
	}
	$filter = array ();
	
	if( isset($filterOptions['productname']) && $filterOptions['productname'] != '' ){
	    $filter[] = "p.productname LIKE '%" . \TAS\Core\DataFormat::DoSecure($filterOptions['productname']) . "%'";
	}
	if( isset($filterOptions['productcode']) && $filterOptions['productcode'] != '' ){
	    $filter[] = "pv.productcode LIKE '%" . \TAS\Core\DataFormat::DoSecure($filterOptions['productcode']) . "%' or p.productcode LIKE '%" . \TAS\Core\DataFormat::DoSecure($filterOptions['productcode']) . "%'";
	}
	
	if (isset($filterOptions['producttype']) && $filterOptions['producttype'] != '') {
	    $filter[] = "p.producttype LIKE '%" . \TAS\Core\DataFormat::DoSecure($filterOptions['producttype']) . "%'";
	}
	
	if (count($filter) > 0) {
        $queryoptions['whereconditions'] = ' where ' . implode(' and ', $filter) . ' ';
    } else {
        $queryoptions['whereconditions'] = ' ';
    }
	
	$queryoptions ['pagingquery'] = "select count(*) from " . $GLOBALS ['Tables'] ['productvariation'];
 	//print_r($SQLQuery);
	$_COOKIE ['admin_productvariation_filter'] = json_encode ( $filterOptions );
	setcookie ( 'admin_productvariation_filter', json_encode ( $filterOptions ), (time () + 25292000) );
	
	$options = \TAS\Core\Grid::DefaultOptions();
	$options['gridurl'] = $GLOBALS['AppConfig']['AdminURL'] . '/variation/index.php';
	$options['gridid'] = 'variationid';
	$options['allowsorting'] = true;
	$options['allowpaging'] = true;
	$options['showtotalrecord'] = true;
	$options['allowselection'] = false;

	/* load default icon */
	$icon = new \TAS\Core\Grid();
	$options['option'] = $icon->DefaultIcon();
	$options['option']['edit']['link'] = $GLOBALS['AppConfig']['AdminURL'] . '/variation/edit.php';
	$options ['fields'] = array (
			'variationid' => array (
					'type' => 'string',
					'name' => '#' 
			),
			'productname' => array (
					'type' => 'string',
					'name' => 'Product Name' 
			),
			'productcode' => array (
					'type' => 'string',
					'name' => 'Product Code'
			)

	);
	$queryoptions['defaultorderby'] = 'pv.variationid';
    $queryoptions['defaultsortdirection'] = 'desc';
    $queryoptions['indexfield'] = 'variationid';
	$queryoptions['tablename'] = $GLOBALS['Tables']['enumeration'];
	    
    $grid = new \TAS\Core\Grid($options, $queryoptions);
    return $grid->Render();
}