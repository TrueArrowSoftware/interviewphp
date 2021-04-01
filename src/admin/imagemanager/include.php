<?php
$imagefile = new \TAS\Core\ImageFile();
$imagefile->LinkerType = 'cms';
$imagefile->ThumbnailSize = $GLOBALS['ThumbnailSize'];

function DisplayForm($imageid = 0)
{
    $formtitle = ($imageid > 0) ? "Edit Image" : "Add Image";
    if ($imageid > 0) {
        $D = $GLOBALS['imagefile']->GetImage($imageid);
        if (count($D) > 0) {
            list($firstid, $firstImage) = each($D);
            $D = $imageObj['caption'];
        } else {
            $D = '';
        }
    } else {
        $D = '';
    }

    $form = '<div class="col-md-12 pt-3"> <div class="card card-body card-radius">
<h2 class="borderbottom-set">' . $formtitle . '</h2>
   <form action="" method="post" class="validate"  data-toggle="validator" novalidate="novalidate"  enctype="multipart/form-data">
	<fieldset class="generalform">
		<legend></legend>
		<div class="formfield">
			<label for="title" class="formlabel requiredfield">Caption</label>
			<div class="forminputwrapper">
				<input type="text" name="title" id="title" size="32" maxlength="75" class="form-control required error"   aria-required="true" aria-invalid="true" value="' . $D . '" />
			</div>
		<div class="clear"></div></div>
		
		<div class="formfield">	
			<label for="image" class="formlabel requiredfield">Image</label>
			<div class="forminputwrapper">
				<input type="file" name="image" id="image" class="form-control required" aria-required="true" />
			</div> 
		<div class="clear"></div></div>

        <input type="hidden" name="imagetype" value="cms" />
         
		<div class="clear"></div>

		<div class="formbutton">
			<input name="btnsubmit" id="btnsubmit" class="btn primary-color primary-bg-color py-2" value="Submit" type="submit">
		</div>
</div>
	</fieldset>
	</form></div></div>';
    return $form;
}

function DisplayGrid()
{
    $queryoptions = \TAS\Core\Grid::DefaultQueryOptions();
    $queryoptions['basicquery'] = 'select * from ' . $GLOBALS['Tables']['images'];
    $filterOptions = array();
    if (isset($_COOKIE['admin_image_filter']) && $_SERVER['REQUEST_METHOD'] == 'GET') {
        $filterOptions = json_decode(stripslashes($_COOKIE['admin_image_filter']), true);
    } else {
        $filterOptions = $_POST;
    }
    $filter = array();
    $filter[] = " linkertype ='cms'";

    if (count($filter) > 0) {
        $queryoptions['whereconditions'] = ' where ' . implode(' and ', $filter) . ' ';
    } else {
        $queryoptions['whereconditions'] = ' ';
    }

    $_COOKIE['admin_image_filter'] = json_encode($filterOptions);
    setcookie('admin_image_filter', json_encode($filterOptions), (time() + 25292000));

    $options = \TAS\Core\Grid::DefaultOptions();
    $options['gridurl'] = $GLOBALS['AppConfig']['AdminURL'] . '/imagemanager/index.php';
    $options['gridid'] = 'pageid';
    $options['allowsorting'] = true;
    $options['allowpaging'] = true;
    $options['showtotalrecord'] = true;
    $options['allowselection'] = false;
     
    
    $options['option']['delete'] = array(
        'link' => $GLOBALS['AppConfig']['AdminURL'] . '/imagemanager/index.php',
        'iconclass' => 'fa-trash',
        'tooltip' => 'Delete',
        'tagname' => 'delete btn-outline-danger',
        'paramname' => 'delete',
    );
    
    $options['fields'] = array(
        'imagecaption' => array(
            'type' => 'string',
            'name' => 'Name'
        ),
        'linkertype' => array(
            'type' => 'string',
            'name' => 'Type'
        )
    );
    $queryoptions['defaultorderby'] = 'imageid';
    $queryoptions['defaultsortdirection'] = 'desc';
    $queryoptions['indexfield'] = 'imageid';
    $queryoptions['tablename'] = $GLOBALS['Tables']['images'];
    

   /* for extra icon */
   $options['option']['image'] = array(
    'link' => $GLOBALS['AppConfig']['AdminURL'] . '/imagemanager/getcode.php',
	'iconclass' => 'fa-external-link-alt',
    'tooltip' => 'Get Image URL',
    'tagname' => 'colorboxpopup',
	'paramname' => 'imageid',
);
	
$grid = new \TAS\Core\Grid($options, $queryoptions);
return $grid->Render();

}