<?php
require ("../template.php");
if (! $permission->CheckOperationPermission('log', 'access', $GLOBALS['user']->UserRoleID)) {
    \TAS\Core\Web::Redirect("../index.php");
}

function DisplayLogReport()
{
    $queryoptions = \TAS\Core\Grid::DefaultQueryOptions();
	$queryoptions ['basicquery'] = 'select * from ' . $GLOBALS ['Tables'] ['log'];
    $filterOptions = array();

    if (isset($_COOKIE['admin_log_filter']) && $_SERVER['REQUEST_METHOD'] == 'GET') {
        $filterOptions = json_decode($_COOKIE['admin_log_filter'], true);
    } else {
        $filterOptions = $_POST;
    }
    $filter = array();
    if (isset($filterOptions['eventlevel']) && $filterOptions['eventlevel'] != "") {
        $filter[] = " eventlevel = '" . $filterOptions['eventlevel'] . "'";
    }
    if (isset($filterOptions['startdate']) && $filterOptions['startdate'] != '') {
        $sdate = new DateTime($filterOptions['startdate']);
        $filter[] = " eventdate >=  '" . $sdate->format("Y-m-d 00:00:01") . "'";
    }
    if (isset($filterOptions['enddate']) && $filterOptions['enddate'] != '') {
        $sdate = new DateTime($filterOptions['enddate']);
        $filter[] = " eventdate <=  '" . $sdate->format("Y-m-d 23:59:59") . "'";
    }

    if (count ( $filter ) > 0) {
		$queryoptions['whereconditions']  = ' where ' . implode ( ' and ', $filter ) . ' ';
	} else {
		$queryoptions['whereconditions']  = '';
	}

    $_COOKIE['admin_log_filter'] = json_encode($filterOptions);
    setcookie('admin_log_filter', json_encode($filterOptions), (time() + 25292000));

    $options = \TAS\Core\Grid::DefaultOptions();
    $options['gridurl'] = $GLOBALS['AppConfig']['AdminURL'] . '/log/index.php';
    $options['gridid'] = 'logid';
    $options['allowsorting'] = true;
    $options['allowpaging'] = true;
    $options['showtotalrecord'] = true;
    $options['allowselection'] = false;
       $options ['fields'] = array (
               'logid' => array (
                       'type' => 'string',
                       'name' => 'Log #' 
               ),
               'eventdate' => array (
                       'type' => 'datetime',
                       'name' => 'Event Date' 
               ),
               'eventlevel' => array (
                       'type' => 'string',
                       'name' => 'Level' 
               ),
               'message' => array (
                       'type' => 'string',
                       'name' => 'Message' 
               ) 
       );
       $queryoptions['defaultorderby'] = 'logid';
       $queryoptions['defaultsortdirection'] = 'desc';
       $queryoptions['indexfield'] = 'logid';
       $queryoptions['tablename'] = $GLOBALS['Tables']['log'];
       
   
      /* for extra icon */
      $options['option']['image'] = array(
       'link' => $GLOBALS['AppConfig']['AdminURL'] . '/log/detail.php',
       'iconclass' => 'fa-folder-open',
       'tooltip' => 'Log View',
       'tagname' => 'colorboxpopup',
       'paramname' => 'id',
       'iconparent' => 'fa',
       'color' => 'danger colorboxpopup',
   );
       
   $grid = new \TAS\Core\Grid($options, $queryoptions);
   return $grid->Render();
   }

if (isset($_COOKIE['admin_log_filter']) && $_SERVER['REQUEST_METHOD'] == 'GET') {
    $filterOptions = json_decode($_COOKIE['admin_log_filter'], true);
} else {
    $filterOptions = $_REQUEST;
}
if (isset($_GET['mode']) && $_GET['mode'] == 'clearfilter') {
    setcookie('admin_log_filter', '', (time() - 25292000));
    \TAS\Core\Web::Redirect("index.php");
}

$pageParse['Content'] .= '<div class="col-md-12 pt-3"> <div class="card card-body card-radius p-0">
<h2 class="borderbottom-set">Error Log</h2>';
$pageParse['Content'] .= '
	<ul class="filterul d-flex p-3">
    <li><input type="button" name="filter" id="filter" class="btn primary-color primary-bg-color py-2 mr-2" value="Show Filters"/></li>
    <li><a href="index.php?mode=clearfilter" class="btn primary-color btn-dark py-2"> Clear Filter</a></li>
    </ul>
	<div id="filterbox">
    <form method="post" action="index.php">
	<fieldset class="shortfields">
		
		<div class="formfield">
			<label class="formlabel" for="startdate">Start Date</label>
			<div class="forminputwrapper">
				<input name="startdate" class="form-control date" id="startdate"  value="' . (isset($filterOptions['startdate']) ? $filterOptions['startdate'] : '') . '" autocomplete="off"/>
			</div>
		<div class="clear"></div></div>
		
		<div class="formfield">
			<label class="formlabel" for="enddate">End Date</label>
			<div class="forminputwrapper">
				<input name="enddate" id="enddate" class="form-control date" value="' . (isset($filterOptions['enddate']) ? $filterOptions['enddate'] : '') . '" autocomplete="off"/>
			</div>
		<div class="clear"></div></div>
		
		<div class="formfield">
			<label class="formlabel" for="eventlevel">Status</label>
			<div class="forminputwrapper">
				<select name="eventlevel" id="eventlevel" class="form-control">' . \TAS\Core\UI::RecordSetToDropDown($db->Execute("Select distinct(eventlevel) as eventlevel from " . $GLOBALS['Tables']['log']), (isset($filterOptions['eventlevel']) ? $filterOptions['eventlevel'] : ''), 'eventlevel', 'eventlevel') . '</select>
			</div>
		<div class="clear"></div></div>
		
		<ul class="filterul d-flex w-100 p-3">
        <li><button type="submit" name="submit" class="btn primary-color primary-bg-color py-2" id="filtersubmit">Filter Report</button><li>
       </ul>				    

	</fieldset>
	</form></div></div></div><br />';
$pageParse['Content'] .= DisplayLogReport();
echo \TAS\Core\TemplateHandler::TemplateChooser("admin");