<?php
$imageFile = new \TAS\Core\ImageFile();
$imageFile->ThumbnailSize = $GLOBALS['ThumbnailSize'];
$imageFile->LinkerType = 'product';

function DisplayGrid()
{
    global $product, $imageFile;

    $HTML = '';
    $productVairations = $product->PrepareVariation();
    $options = array(
        'mainproduct' => 'Main Product'
    );
    foreach ($productVairations as $code => $info) {
        $options[$code] = $info['OptionName'];
    }

    $filter = array();
    $filter[] = ' linkerid=' . $product->ProductID;

    $HTML .= '<div class="col-md-12 pt-3 content-area"><div class="card card-body card-radius"><div class="dropzone mb-5" id="myimageuploader" data-form="#restofinfo" data-dropzoneurl="{AdminURL}/productimage/add.php?productid=' . $product->ProductID . '" >' . \TAS\Core\ImageFile::ImageGrid('product', $filter, array(
        'gridpage' => $GLOBALS['AppConfig']['AdminURL'] . '/productimage/index.php?productid=' . $product->ProductID . '',
        'delete' => $GLOBALS['AppConfig']['AdminURL'] . '/productimage/index.php?productid=' . $product->ProductID,
		'tagname' => 'productimage',
		'gridid'=>'productimage',
		'defaultorderby' => 'displayorder',
        'defaultsortdirection'=>'asc',
		'roworder' =>true,
		'fields' => array (
			'imageid' => array (
				'name' => 'ID #',
				'type' => 'numeric'
			),
			'imagefile' => array (
				'name' => 'Image',
				'type' => 'callback',
				'function' => array (
					'\TAS\Core\ImageFile',
					'CallBackImageUrl'
				)
			),
			'displayorder'=>array (
				'name' => 'Display Order',
				'type' => 'numeric'
			),
			)
		)) . '
		
			<ul class="nolist">
				<li>Drag and drop your image within this box to auto upload.</li>
				<li>Once all queued file are uploaded, page will auto refresh to add them in Table above.</li>
				<!--li>Double click a Table Row to open edit mode for that photo.</li>
				<li>Once you are done with editing, press save or double click again on row to save them</li-->
			</ul>
			<form id="restofinfo" action="add.php">
			<fieldset class="generalform">
				<div class="formfield">
					<label class="formlabel requiredfield" for="productcode">Product Option</label>
					<div class="forminputwrapper">
						<select name="productcode" id="productcode" style="width:200px;">
						' . \TAS\Core\UI::ArrayToDropDown($options, '') . '
						</select>
					</div><div class="clear"></div>
				</div>
			
				<div class="dz-message" data-dz-message>
                    <span>Click or Drag your Image file here to upload.<b>Please refresh the page to see the effect</b></span>
                </div>
								
				<div class="formbutton">
					<input type="button" name="btnsubmit" id="btnsubmit" class="submitbutton btn primary-color primary-bg-color py-2" value="Submit" />			
				</div>
								
			</fieldset>
			</form>
			</div></div></div>';

    return $HTML;
}
