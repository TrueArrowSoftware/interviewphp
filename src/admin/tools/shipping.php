<?php
require ("./../template.php");
$pageParse['PageTitle'] = "Shipping Configuration - " . $GLOBALS['AppConfig']['SiteName'];
$fields = array();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    unset($_POST['btnsubmit']);
    foreach ($_POST as $key => $value) {
        $GLOBALS['db']->Execute("UPDATE " . $GLOBALS['Tables']['configuration'] . " set settingvalue = '" . \TAS\Core\DataFormat::DoSecure($value) . "' where settingkey = '" . \TAS\Core\DataFormat::DoSecure($key) . "'");
    }
    $messages[] = array(
        'level' => 1,
        'message' => 'Update Successfully'
    );
}

$query = "Select * FROM " . $GLOBALS['Tables']['configuration'] . " where settingkey IN('shipping','minimumorderamount')";
$sql = $GLOBALS['db']->Execute($query);

$displayorder = 1;
if (\TAS\Core\DB::Count($sql)) {
    while ($row = $GLOBALS['db']->Fetch($sql)) {
        $fields[$row['settingkey']] = array(
            'field' => $row['settingkey'],
            'id' => $row['settingkey'],
            'type' => 'number',
            'label' => $row['displayname'] . ' ' . $GLOBALS['AppConfig']['Currency'],
            'displayorder' => $displayorder ++,
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['settingkey']) && isset($_POST['settingvalue']) && isset($GLOBALS['Configuration'][$_POST['settingkey']])) {
        $GLOBALS['db']->Execute("Update " . $GLOBALS['Tables']['configuration'] . " set settingvalue='" . \TAS\Core\DataFormat::DoSecure($_POST['settingvalue']) . "' where settingkey='" . \TAS\Core\DataFormat::DoSecure($_POST['settingkey']) . "' limit 1 ");
        \TAS\Core\Web::Redirect("configuration.php?key=". $_POST['settingkey']);
    }
}

if(isset($_GET['key'])) {
    $messages[]=array('level'=>1, "message"=>'Configuration updated successfully.');
}

$pageParse['Content'] .= '<div class="col-md-12 pt-3"> <div class="card card-body card-radius">
<h2 class="borderbottom-set">Shipping Management</h2><div class="px-3 mt-3 display-messages">' . \TAS\Core\UI::UIMessageDisplay($messages) . '</div>
	<form action="" method="post" class="validate">
	<fieldset class="generalform">
		<legend></legend>
		' . \TAS\Core\UI::GetFormHTML($param) . '
		<div class="formbutton">
			<input name="btnsubmit" id="btnsubmit" class="btn primary-color primary-bg-color py-2" value="Submit" type="submit">
		</div>
	</fieldset>
	</form></div></div>
<script type="text/javascript">
$(function(){
    var config = ' . json_encode($GLOBALS['Configuration']) . ';
$("#settingkey").change(function(){
    if($(this).val() != "") {
        $("#settingvalue").val(config[$(this).val()]);
    }
        
});
        
});
</script>';
echo \TAS\Core\TemplateHandler::TemplateChooser("admin");
