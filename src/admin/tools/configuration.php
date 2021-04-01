<?php
require("../template.php");
$pageParse['PageTitle'] = "Configuration - " . $GLOBALS['AppConfig']['SiteName'];
$fields = array();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    unset($_POST['btnsubmit']);
    foreach ($_POST as $key => $value) {
        $GLOBALS['db']->Execute("UPDATE " . $GLOBALS['Tables']['configuration'] . " set settingvalue = '" . TAS\Core\DataFormat::DoSecure($value) . "' where settingkey = '" . TAS\Core\DataFormat::DoSecure($key) . "'");
    }
    $messages[] = array(
        'level' => 1,
        'message' => 'Update Successfully'
    );
}

$query = "Select * FROM " . $GLOBALS['Tables']['configuration'] . " where settingkey='tax'";
$sql = $GLOBALS['db']->Execute($query);

$displayorder = 1;
if (\TAS\Core\DB::Count($sql)) {
    while ($row = $GLOBALS['db']->Fetch($sql)) {
        $fields[$row['settingkey']] = array(
            'field' => $row['settingkey'],
            'id' => $row['settingkey'],
            'type' => 'varchar',
            'label' => $row['displayname'],
            'displayorder' => $displayorder++,
            'value' => $row['settingvalue'],
            'required' => true,
            'group' => 'basic'
        );
    }
}

$param['Fields'] = $fields;
$param['Group'] = array(
    'basic' => array(
        'legend' => ''
    )
);

$pageParse['Content'] .= '<div class="w-100 d-flex align-items-center pt-5">
<div class="col-md-5 mx-auto card card-body">
   <h2 class="text-center">Configuration</h2>' . TAS\Core\UI::UIMessageDisplay($messages) . '
	<form action="" method="post" class="validate">
	<fieldset class="generalform">
		<legend></legend>
		' . TAS\Core\UI::GetFormHTML($param) . '
		<div class="formbutton">
			<input type="submit" name="btnsubmit" id="btnsubmit" class="btn btn-default" value="Submit" />
		</div>
	</fieldset>
	</form></div></div>';

\TAS\Core\TemplateHandler::TemplateChooser("admin");
