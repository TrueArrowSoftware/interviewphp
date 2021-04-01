<?php
require ("./../template.php");
require ("./include.php");
if (isset($_GET['imageid']) && is_numeric($_GET['imageid']) && (int) $_GET['imageid'] > 0) {
    $imageid = (int) $_GET['imageid'];
    $D = $GLOBALS['imagefile']->GetImage($imageid);
    if (count($D) > 0) {
        foreach ($D as $firstid => $firstImage)
            $pageParse['Content'] .= '<div class="col-md-12 p-0"> <div class="card card-body card-radius">
                    <h2 class="borderbottom-set">URL for Image</h2>';
        $pageParse['Content'] .= '<div class="px-3 py-2">' . \TAS\Core\UI::UIMessageDisplay($messages) . '</div>
        <fieldset class="generalform">
			<legend></legend>
			<div class="formfield">
				<label for="height" class="formlabel">Original</label>
				<div class="forminputwrapper">
					<input type="text" name="original" id="original" size="50" class="form-control" value="' . $firstImage['url'] . '" />					
				</div>
			<div class="clear"></div></div>';
        if (is_array($firstImage['thumbnails']) && count($firstImage['thumbnails']) > 0) {
            foreach ($firstImage['thumbnails'] as $size => $path) {
                $pageParse['Content'] .= '<div class="formfield">
						<label for="height" class="formlabel"> Thumbnail (' . str_replace(array(
                    "w",
                    "h"
                ), "", str_replace(".", " x ", $size)) . ')</label>
						<div class="forminputwrapper">
							<input type="text" name="height" id="height" size="50" class="form-control" value="' . $firstImage['baseurl'] . "/" . $path . '" />
						</div>
					<div class="clear"></div></div>';
            }
        }

        $pageParse['Content'] .= '
		<div class="px-3 py-2"><h2>Image</h2></div>
		<div class="px-3 py-2"><img src="' . $firstImage['url'] . '" width="150px" /></div>
		</fieldset></div></div></div>';
        $pageParse['MetaExtra'] .= '<script type="text/javascript">
		$(function(){
		
		});
		</script>';
    } else {
        $pageParse['Content'] = 'Image Not Found';
    }
} else {
    $pageParse['Content'] = "Invalid access to page";
}
echo \TAS\Core\TemplateHandler::TemplateChooser("popup");