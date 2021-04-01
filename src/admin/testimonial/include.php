<?php

function DisplayForm($edit = 0)
{
    $formtitle = ($edit > 0) ? "Edit Testimonial" : "Add Testimonial";
    
    $fields = array();
    $fields = \Framework\Testimonial::GetFields($edit);
    
    $param['Fields'] = $fields;
    $param['Group'] = array(
        'basic' => array(
            'legend' => ''
        )
    );
    
    $form = '<div class="col-md-12 pt-3"> <div class="card card-body card-radius">
<h2 class="borderbottom-set">' . _($formtitle) . '</h2><div class="px-3 mt-3 display-messages">' . \TAS\Core\UI::UIMessageDisplay($GLOBALS['messages']) . '</div>
<form action="" method="post" class="validate">
<fieldset class="generalform">' . \TAS\Core\UI::GetFormHTML($param) . '
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
    $queryoptions['basicquery'] = "select * from " . $GLOBALS['Tables']['testimonial'];
    
    $queryoptions['where'] = '';
    
    $queryoptions['pagingQuery'] = "select * from " . $GLOBALS['Tables']['testimonial'];
    $options = \TAS\Core\Grid::DefaultOptions();
    $options['gridurl'] = $GLOBALS['AppConfig']['AdminURL'] . '/testimonial/index.php';
    $options['gridid'] = 'testimonialid';
    $options['allowsorting'] = true;
    $options['allowpaging'] = true;
    $options['showtotalrecord'] = true;
    $options['allowselection'] = false;

    /* load default icon */
    $icon = new \TAS\Core\Grid();
    $options['option'] = $icon->DefaultIcon();
    $options['option']['edit']['link'] = $GLOBALS['AppConfig']['AdminURL'] . '/testimonial/edit.php';    
    $options['fields'] = array(
        'testimonialid' => array(
            'type' => 'string',
            'name' => '#'
        ),
        'testimonialname' => array(
            'type' => 'string',
            'name' => 'Name'
        ),
        'company' => array(
            'type' => 'string',
            'name' => 'Company'
        ),
        'displayorder' => array(
            'type' => 'number',
            'name' => 'Order'
        ),
        'status' => array(
            'type' => 'onoff',
            'name' => 'Status',
            'mode' => 'fa'
        )
    );
    
    $queryoptions['defaultorderby'] = 'testimonialid';
    $queryoptions['defaultsortdirection'] = 'desc';
    $queryoptions['indexfield'] = 'testimonialid';
    $queryoptions['tablename'] = $GLOBALS['Tables']['testimonial'];
    
    $grid = new \TAS\Core\Grid($options, $queryoptions);
    return $grid->Render();
}