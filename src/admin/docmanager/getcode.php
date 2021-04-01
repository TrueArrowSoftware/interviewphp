<?php
require ("../template.php");
require_once ("./include.php");
if (isset($_GET['documentid']) && is_numeric($_GET['documentid']) && (int) $_GET['documentid'] > 0) {
    $documentid = (int) $_GET['documentid'];
    $D = $GLOBALS['documentfile']->GetDocument($documentid);
    if (count($D) > 0) {
        $firstid = key($D);
        $firstDocument = current($D);

        $impersonPath = \TAS\Core\DocumentFile::DownloadURL(array(
            'name' => $firstDocument['name'],
            'id' => $firstid
        ));
        $pageParse['Content'] .= '<div class="col-md-12 p-0"> <div class="card card-body card-radius">
<h2 class="borderbottom-set">URL for Document</h2>';
        $pageParse['Content'] .= '<div class="px-3 py-2">' . \TAS\Core\UI::UIMessageDisplay($messages) . '</div>  
<fieldset class="generalform">
			<legend></legend>
			<div class="formfield">
				<label for="height" class="formlabel">Original URL</label>
				<div class="forminputwrapper">
					<input type="text" name="original" id="original" size="50" class="form-control" value="' . $firstDocument['url'] . '" />					
				</div>	
			<div class="clear"></div></div>
							
			<div class="formfield">
				<label for="height" class="formlabel">Public URL (impersonated & nice URL)</label>
				<div class="forminputwrapper">
					<input type="text" name="imperson" id="imperson" class="form-control" value="' . $impersonPath . '" />					
				</div>	
			<div class="clear"></div></div>';

        $pageParse['Content'] .= '	
	        <ul class="filterul d-flex w-100"><li class="mx-auto">

			<a href="' . $impersonPath . '" class="btn primary-color primary-bg-color py-2 text-center" target=_blank>Download Document Here</a></li></ul>
		</fieldset></div></div></div>';
    } else {
        $pageParse['Content'] = 'Document Not Found';
    }
} else {
    $pageParse['Content'] = "Invalid access to page";
}
echo \TAS\Core\TemplateHandler::TemplateChooser("popup");