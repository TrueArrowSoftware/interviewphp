<?php
function DisplayForm($edit = 0)
{
    $formtitle = ($edit > 0) ? "Edit Brand" : "Add Brand";
    $fields = array();
    $fields = FrameWork\Company::GetFields($edit);
    $param['Fields'] = $fields;
    $param['Group'] = array(
        'basic' => array(
            'legend' => ''
        )
    );

    $form = '<div class="col-md-12 pt-3"> <div class="card card-body card-radius">
<h2 class="borderbottom-set">' . _($formtitle) . '</h2><div class="px-3 mt-3 display-messages">' . \TAS\Core\UI::UIMessageDisplay($GLOBALS['messages']) . '</div>
<form action="" method="post" class="validate">
<fieldset class="generalform">
	<legend></legend>
	'. \TAS\Core\UI::GetFormHTML($param) . '
	<div class="formbutton">
		<input name="btnsubmit" id="btnsubmit" class="btn primary-color primary-bg-color py-2" value="Submit" type="submit">			
	</div>	
</fieldset>
</form></div></div>';
    return $form;
}

function DisplayGrid()
{
    $queryoptions = \TAS\Core\Grid::DefaultQueryOptions();
    $queryoptions['basicquery'] = "select * from " . $GLOBALS['Tables']['company'];
    $filterOptions = array();
    if (isset($_COOKIE['admin_company_filter']) && $_SERVER['REQUEST_METHOD'] == 'GET') {
        $filterOptions = json_decode(stripslashes($_COOKIE['admin_company_filter']), true);
    } else {
        $filterOptions = $_POST;
    }
    $filter = array();
    
    if (isset ( $filterOptions ['companyname'] ) && $filterOptions ['companyname'] != "") {
        $filter [] = " companyname LIKE '%" . $filterOptions ['companyname'] . "%'";
       
    }
    
    if (count($filter) > 0) {
        $queryoptions['whereconditions'] = ' where ' . implode(' and ', $filter) . ' ';
    } else {
        $queryoptions['whereconditions'] = ' ';
    }
    
    $queryoptions['pagingquery'] = "select count(*) from " . $GLOBALS['Tables']['company'];
    
    $_COOKIE['admin_company_filter'] = json_encode($filterOptions);
    setcookie('admin_company_filter', json_encode($filterOptions), (time() + 25292000));
   
    $options = \TAS\Core\Grid::DefaultOptions();
    $options['gridurl'] = $GLOBALS['AppConfig']['AdminURL'] . '/brand/index.php';
    $options['gridid'] = 'companyid';
    $options['allowsorting'] = true;
    $options['allowpaging'] = true;
    $options['showtotalrecord'] = true;
    $options['allowselection'] = false;

     /* load default icon */
     $icon = new \TAS\Core\Grid();
     $options['option'] = $icon->DefaultIcon();
     $options['option']['edit']['link'] = $GLOBALS['AppConfig']['AdminURL'] . '/brand/edit.php';

    $options['fields'] = array(
        'companyid' => array(
            'type' => 'string',
            'name' => '#'
        ),
        'companyname' => array(
            'type' => 'string',
            'name' => 'Name'
        ),
        'status' => array(
            'type' => 'onoff',
            'name' => 'Status',
            'mode' => 'fa'
        )
    );
    $queryoptions['defaultorderby'] = 'companyid';
    $queryoptions['defaultsortdirection'] = 'desc';
    $queryoptions['indexfield'] = 'companyid';
    $queryoptions['tablename'] = $GLOBALS['Tables']['company'];
    
    $grid = new \TAS\Core\Grid($options, $queryoptions);
    
    return $grid->Render(); 
}
