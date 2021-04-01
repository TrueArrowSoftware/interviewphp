<?php
function DisplayForm($edit = 0) {
    $formtitle = ($edit > 0) ? "Edit Category" : "Add Category";
    
    if ($edit > 0) {
        $category = new \Framework\Category ( $edit );
    } else {
        $category = new \Framework\Category ();
    }
    
    $fields = array ();
    $fields = \Framework\Category::GetFields ( $edit );
    
    
    $param ['Fields'] = $fields;
    
    // code for adding image feild in Category form
    $param ['Fields'] ['categoryimage'] = array (
        'field' => 'categoryimage',
        'id' => 'categoryimage',
        'type' => 'file',
        'label' => 'Category Image',
        'displayorder' => 3,
        'group' => 'basic',
        'value' => $category->ImageID,
        'shortnote' => 'Please insert the image of size 1900 X 400px'
    );
    
    // code for adding image feild in Category form
    
    $param ['Group'] = array (
        'basic' => array (
            'legend' => ''
        )
    );
    
    $form = '<div class="col-md-12 pt-3"> <div class="card card-body card-radius">
<h2 class="borderbottom-set">' . $formtitle. '</h2><div class="px-3 mt-3 display-messages">' . \TAS\Core\UI::UIMessageDisplay ( $GLOBALS ['messages'] ) . '</div>
<form action="" method="post" class="validate" enctype="multipart/form-data">
<fieldset class="generalform">
	<legend></legend>
	' . \TAS\Core\UI::GetFormHTML ( $param ) . '
    <div class="formbutton">
		<input name="btnsubmit" id="btnsubmit" class="btn primary-color primary-bg-color py-2" value="Submit" type="submit">
	</div>
</fieldset>
</form></div></div>';
    return $form;
}

function DisplayGrid() {
    $queryoptions = \TAS\Core\Grid::DefaultQueryOptions();
    $queryoptions ['basicquery'] = "select c.*, (case when c.parentid!=0 then p.categoryname else 'na' end) as parentCategoryName
			from " . $GLOBALS ['Tables'] ['category'] . " c left join ". $GLOBALS['Tables']['category']. " p
			 on c.parentid=p.categoryid ";
    $filterOptions = array ();
    $filter = array ();
    
    if (isset ( $_COOKIE ['admin_category_filter'] ) && $_SERVER ['REQUEST_METHOD'] == 'GET') {
        $filterOptions = json_decode ( stripslashes ( $_COOKIE ['admin_category_filter'] ), true );
    } else {
        $filterOptions = $_POST;
    }
    
    if (isset($filterOptions['search_catname']) && !empty($filterOptions['search_catname']) ){
        $filter[] = " c.categoryname like '%". \TAS\Core\DataFormat::DoSecure($filterOptions['search_catname']). "%' ";
    }
    
    if (isset($filterOptions['search_showtop']) ){
        $filter[] = " c.parentid =0";
    } else if (isset($filterOptions['search_childof']) && is_numeric($filterOptions['search_childof']) && (int)$filterOptions['search_childof']>0 ){
        $filter[] = " c.parentid = ". (int)$filterOptions['search_childof'];
    }
    
    
    if (count($filter) > 0) {
        $queryoptions['whereconditions'] = ' where ' . implode(' and ', $filter) . ' ';
    } else {
        $queryoptions['whereconditions'] = ' ';
    }
    
    $queryoptions ['pagingquery'] = "select count(*) from " . $GLOBALS ['Tables'] ['category'];
    
    $_COOKIE ['admin_category_filter'] = json_encode ( $filterOptions );
    setcookie ( 'admin_category_filter', json_encode ( $filterOptions ), (time () + 25292000) );

    $options = \TAS\Core\Grid::DefaultOptions();
    $options['gridurl'] = $GLOBALS['AppConfig']['AdminURL'] . '/category/index.php';
    $options['gridid'] = 'categoryid';
    $options['allowsorting'] = true;
    $options['allowpaging'] = true;
    $options['showtotalrecord'] = true;
    $options['allowselection'] = false;
    $options['roworder'] = true;

     /* load default icon */
     $icon = new \TAS\Core\Grid();
     $options['option'] = $icon->DefaultIcon();
     $options['option']['edit']['link'] = $GLOBALS['AppConfig']['AdminURL'] . '/category/edit.php';
     
    $options ['fields'] = array (
        'categoryid' => array (
            'type' => 'string',
            'name' => '#'
        ),
        'categoryname' => array (
            'type' => 'string',
            'name' => 'Category'
        ),
        'parentCategoryName'=> array (
            'type' => 'string',
            'name' => 'Parent Category'
        ),
        'status' => array (
            'type' => 'onoff',
            'name' => 'Status',
            'mode' => 'fa'
        ) ,
        'showinmenu' => array (
            'type' => 'onoff',
            'name' => 'Show in Menu',
            'mode' => 'fa'
        ) ,
        'displayorder' => array (
            'type' => 'string',
            'name' => 'Display Order',
            'mode' => 'fa'
        )
    );
    $queryoptions['defaultorderby'] = 'c.displayorder';
    $queryoptions['defaultsortdirection'] = 'asc';
    $queryoptions['indexfield'] = 'categoryid';
    $queryoptions['tablename'] = $GLOBALS['Tables']['category'];
    
    $grid = new \TAS\Core\Grid($options, $queryoptions);
    
    return $grid->Render();
}
