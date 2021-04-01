<?php

require("../template.php");

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
	$logid = (int) $_GET['id'];

	$rsLog = $db->ExecuteScalarRow("Select * from " . $GLOBALS['Tables']['log'] . " where logid=" . (int) $logid);
	if ($rsLog) {
		$pageParse['Content'] = '<fieldset class="generalform"><legend>Error Details</legend>
			<div class="formfield">
				<label class="formlabel">Event Date</label>
				<div class="inputwrapper">
					' . \TAS\Core\DataFormat::DBToDateTimeFormat($rsLog['eventdate']) . '
				</div>
			<div class="clear"></div></div>
			
			<div class="formfield">
				<label class="formlabel">Event Level</label>
				<div class="inputwrapper">
					' . strtoupper($rsLog['eventlevel']) . '
				</div>
			<div class="clear"></div></div>
			<div class="formfield">
				<label class="formlabel">Main Message</label>
				<div class="inputwrapper">
					' . $rsLog['message'] . '
				</div>
			<div class="clear"></div></div>
			';

		if ($rsLog['details'] != '') {
			$message = json_decode($rsLog['details'], true);
			foreach ($message as $i => $v) {
				$pageParse['Content'] .= '<div class="formfield">
						<label class="formlabel">' . ucwords($i) . '</label>
						<div class="inputwrapper">
							' . (is_array($v) ? print_r($v, true) : ucwords($v)) . '
						</div>
					<div class="clear"></div></div>';
			}
		}
		$pageParse['Content'] .= '<div class="formfield">
				<label class="formlabel">Debug Trace</label>
				<div class="inputwrapper">
					' . $rsLog['debugtrace'] . '
				</div>
			<div class="clear"></div></div>';

		$pageParse['Content'] .= '</fieldset>';
	}
	echo \TAS\Core\TemplateHandler::TemplateChooser("popup");
} else {

	\TAS\Core\Web::Redirect("index.php");
}
