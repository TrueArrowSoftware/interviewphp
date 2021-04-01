<?php
use TAS\ImageFile;
$imagefile = new \TAS\Core\ImageFile();
$imagefile->LinkerType = 'homeslider';
$imagefile->ThumbnailSize = $GLOBALS['ThumbnailSize'];

function DisplayForm($imageid = 0)
{
    $actionurl = ($imageid > 0) ? "{AdminURL}/slidemanager/edit.php?id=" . $imageid : "{AdminURL}/slidemanager/add.php";
    $formtitle = ($imageid > 0) ? "Edit Home Page Slider Image" : "Add Home Page Slider Image";
    if ($imageid > 0) {
        $D = $GLOBALS['imagefile']->GetImage($imageid);
        if (count($D) > 0) {
            foreach ($D as $image) {
                $tag = json_decode($image['tag']);
                $caption = $image['caption'];
                $title = $tag->title;
                $description = $tag->desc;
                $buttontext = $tag->btntext;
                $link = '';
                if (isset($tag->link)) {
                    $link = $tag->link;
                }
            }
        } else {
            $caption = '';
            $title = '';
            $description = '';
            $link = '';
            $buttontext = '';
        }
    } else {
        $caption = '';
        $title = '';
        $description = '';
        $link = '';
        $buttontext = '';
    }

    $form = '<div class="col-md-12 pt-3"> <div class="card card-body card-radius">
<h2 class="borderbottom-set">' . $formtitle . '</h2>
<div class="p-3 display-messages">' . \TAS\Core\UI::UIMessageDisplay($GLOBALS['messages']) . '</div>
<form action="' . $actionurl . '" method="post" class="validate" enctype="multipart/form-data">
	<fieldset class="generalform">
		<legend></legend>
		<div class="formfield">
			<label for="title" class="formlabel requiredfield">Caption</label>
			<div class="forminputwrapper">
				<input type="text" name="title" id="title" size="32" maxlength="75" class="form-control required" value="' . $caption . '" />
			</div>
		<div class="clear"></div></div>


        

        <div class="formfield">
			<label for="title" class="formlabel ">Upper Small Text</label>
			<div class="forminputwrapper">
				<textarea class="form-control" row="5" cols="60" name="homedescription" id="homedescription">' . $description . '</textarea>
			</div>
		<div class="clear"></div></div>


<div class="formfield">
			<label for="title" class="formlabel requiredfield">Lower Big Text</label>
			<div class="forminputwrapper">
				<input type="text" name="hometitle" id="hometitle" size="32" maxlength="140" class="form-control required" value="' . $title . '" />
			</div>
		<div class="clear"></div></div>

        <div class="formfield">
			<label for="linkimage" class="formlabel requiredfield">Link</label>
			<div class="forminputwrapper">
				<input type="text" name="linkimage" size="40" id="linkimage" class="form-control required" value="' . $link . '" />
			</div>
		<div class="clear"></div></div>

        <div class="formfield">
			<label for="buttontext" class="formlabel requiredfield">Button Text </label>
			<div class="forminputwrapper">
				<input type="text" name="buttontext" size="40" id="buttontext" class="form-control required" value="' . $buttontext . '" />
			</div>
		<div class="clear"></div></div>';

    if ($imageid == 0) {
        $form .= '<div class="formfield">
			<label for="image" class="formlabel requiredfield">Image</label>
			<div class="forminputwrapper">
				<input type="file" name="image" id="image" class="form-control required" />
			</div>
		<div class="clear"></div></div>
            
        <div class="formbutton">
            <ul>
                <li>Home Slider recommended size : 1800x950px </li>
            </ul>
        </div>';
    }

    $form .= '<div class="formbutton">
			<input name="btnsubmit" id="btnsubmit" class="btn primary-color primary-bg-color py-2" value="Submit" type="submit">
		</div>
	</fieldset>
	</form>';
    return $form;
}

function DisplayGrid()
{
    $queryoptions = \TAS\Core\Grid::DefaultQueryOptions();
    $queryoptions['basicquery'] = 'select * from ' . $GLOBALS['Tables']['images'];

    $filterOptions = array();
    if (isset($_COOKIE['admin_slideimage_filter']) && $_SERVER['REQUEST_METHOD'] == 'GET') {
        $filterOptions = json_decode(stripslashes($_COOKIE['admin_slideimage_filter']), true);
    } else {
        $filterOptions = $_POST;
    }
    $filter = array();

    if (isset($filterOptions['imagetype']) && $filterOptions['imagetype'] != '') {
        $filter[] = "linkertype = '" . \TAS\Core\DataFormat::DoSecure($filterOptions['imagetype']) . "'";
    } else {
        $filter[] = "linkertype ='homeslider'";
    }

    if (count($filter) > 0) {
        $queryoptions['whereconditions'] = ' where ' . implode(' and ', $filter) . ' ';
    } else {
        $queryoptions['whereconditions'] = ' ';
    }

    $queryoptions['pagingquery'] = "select count(*) from " . $GLOBALS['Tables']['images'];
    $_COOKIE['admin_image_filter'] = json_encode($filterOptions);
    setcookie('admin_image_filter', json_encode($filterOptions), (time() + 25292000));

    $options = \TAS\Core\Grid::DefaultOptions();
    $options['gridurl'] = $GLOBALS['AppConfig']['AdminURL'] . '/slidemanager/index.php';
    $options['gridid'] = 'imageid';
    $options['allowsorting'] = true;
    $options['allowpaging'] = true;
    $options['showtotalrecord'] = true;
    $options['allowselection'] = false;
    
    $icon = new \TAS\Core\Grid();
    $options['option'] = $icon->DefaultIcon();
    $options['option']['edit']['link'] = $GLOBALS['AppConfig']['AdminURL'] . '/slidemanager/edit.php';
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
    $queryoptions['defaultorderby'] = 'imagecaption';
    $queryoptions['defaultsortdirection'] = 'desc';
    $queryoptions['indexfield'] = 'imageid';
    $queryoptions['tablename'] = $GLOBALS['Tables']['images'];
    

   /* for extra icon */
   $options['option']['slidemanager'] = array(
    'link' => $GLOBALS['AppConfig']['AdminURL'] . '/slidemanager/getcode.php',
	'iconclass' => 'fa-external-link-alt',
    'tooltip' => 'Get Image URL',
    'tagname' => 'colorboxpopup',
	'paramname' => 'imageid',
);
	
$grid = new \TAS\Core\Grid($options, $queryoptions);
return $grid->Render();
}