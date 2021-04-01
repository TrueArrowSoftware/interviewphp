<?php
namespace Framework;
require ("../template.php");
require_once ("./include.php");
$messages = array();
if (! $permission->CheckOperationPermission("customer", "access", $GLOBALS['user']->UserRoleID)) {
    \TAS\Core\Web::Redirect("../index.php");
}
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['delete']) && is_numeric($_GET['delete']) && $permission->CheckOperationPermission("customer", "delete", $GLOBALS['user']->UserRoleID)) {
        if (\TAS\Core\DataFormat::DoSecure($_GET['delete']) == 1) {
            $messages[] = array(
                "message" => _("Default customer can not be deleted."),
                "level" => 10
            );
        } else {
            $id = (int) \TAS\Core\DataFormat::DoSecure($_GET['delete']);
            $rs = $GLOBALS['db']->Execute("Select * from " . $GLOBALS['Tables']['user'] . " where userid=" . (int) $id . " limit 1");
            $row = $GLOBALS['db']->Fetch($rs);

            if (User::Delete((int) $_GET['delete'])) {
                $userlog = UserLog::AddEvent('User(' . $row['firstname'] . ' ' . $row['lastname'] . ') Deleted', ' User Deleted', $id);
                $messages[] = array(
                    "message" => _("Customer has been deleted successfully."),
                    "level" => 1
                );
            } else {
                $messages[] = array(
                    "message" => _("Unable to delete Customer at this moment. Please try again."),
                    "level" => 10
                );
            }
        }
    }

    if (isset($_GET['type']) && $_GET['type'] == 'status') {
        if ($_GET['type'] == 'status') {
            $u = new User((int) $_GET['id']);
            if ($u->IsLoaded()) {
                $s = (($u->Status == 1) ? 0 : 1);
                $GLOBALS['db']->Execute("update " . $GLOBALS['Tables']['user'] . " set status=" . $s . " where userid=" . $u->UserID);
                \TAS\Core\Web::Redirect("index.php?status=1&page=" . $_GET['page']);
            } else {
                $messages[] = array(
                    "message" => _("Unable to update user status at this moment. Please try again."),
                    "level" => 10
                );
            }
        }
    }

    if (isset($_GET['status']) && $_GET['status'] == '1') {
        $messages[] = array(
            "message" => _("Customer status has been updated successfully."),
            "level" => 1
        );
    }
    if (isset($_GET['mode']) && $_GET['mode'] == 'clearfilter') {
        setcookie('admin_customer_filter', '', (time() - 25292000));
        \TAS\Core\Web::Redirect("index.php");
    }
}

$pageParse['Content'] .= '<div class="col-md-12 pt-3"> <div class="card card-body card-radius p-0">
<h2 class="borderbottom-set">Customer Management</h2>';
$pageParse['Content'] .= '<div class="px-3 py-2">' . \TAS\Core\UI::UIMessageDisplay($messages) . '</div>';
$pageParse['Content'] .= '<h6 class="pl-3 py-3"><a href="add.php">Add New Customer</a> / <a href="index.php?download=true">Download Now (Current filter)</a></h6>';

if (isset($_COOKIE['admin_customer_filter']) && $_SERVER['REQUEST_METHOD'] == 'GET') {
    $filterOptions = json_decode($_COOKIE['admin_customer_filter'], true);
} else {
    $filterOptions = $_REQUEST;
}

$_COOKIE['admin_customer_filter'] = json_encode($filterOptions);
setcookie('admin_customer_filter', json_encode($filterOptions), time() + 3600000);

$pageParse['Content'] .= '
	<ul class="filterul d-flex p-3">
    <li><input type="button" name="filter" id="filter" class="btn primary-color primary-bg-color py-2 mr-2" value="Show Filters"/></li>
    <li><a href="index.php?mode=clearfilter" class="btn primary-color btn-dark py-2"> Clear Filter</a></li>
    </ul>
	<div id="filterbox">
    <form method="post" action="index.php">
	<fieldset class="shortfields m-0">
        
        <div class="formfield mb-3 m-0 px-3">
          <label class="formlabel" for="search_email">Email</label>
			<div class="forminputwrapper">
				<input type="text" name="search_email" id="search_email" class="form-control" value="' . (isset($filterOptions['search_email']) ? $filterOptions['search_email'] : '') . '" />
			</div>
        <div class="clear"></div></div>
	    
        <div class="formfield mb-3 m-0 px-3">
          <label class="formlabel" for="search_username">Username</label>
			<div class="forminputwrapper">
				<input type="text" name="search_username" id="search_username" class="form-control" value="' . (isset($filterOptions['search_username']) ? $filterOptions['search_username'] : '') . '" />
			</div>
        <div class="clear"></div></div>

	    <div class="formfield mb-3 m-0 px-3">
          <label class="formlabel" for="search_firstname">First Name</label>
			<div class="forminputwrapper">
				<input type="text" name="search_firstname" id="search_firstname" class="form-control" value="' . (isset($filterOptions['search_firstname']) ? $filterOptions['search_firstname'] : '') . '" />
			</div>
        <div class="clear"></div></div>
	    <div class="formfield mb-3 m-0 px-3">
          <label class="formlabel" for="search_lastname">Last Name</label>
			<div class="forminputwrapper">
				<input type="text" name="search_lastname" id="search_lastname" class="form-control" value="' . (isset($filterOptions['search_lastname']) ? $filterOptions['search_lastname'] : '') . '" />
			</div>
		<div class="clear"></div></div>
		
		<div class="formfield mb-3 m-0 px-3">
		<label class="formlabel" for="search_phone">Phone</label>
		  <div class="forminputwrapper">
			  <input type="text" name="search_phone" id="search_phone" class="form-control" value="' . (isset($filterOptions['search_phone']) ? $filterOptions['search_phone'] : '') . '" />
		  </div>
	  <div class="clear"></div></div>
       <ul class="filterul d-flex w-100 p-3">
        <li><button type="submit" name="submit" class="btn primary-color primary-bg-color py-2 m-0" id="filtersubmit">Filter Report</button><li>
       </ul>
              
	</fieldset>
	</form></div></div></div>';

$pageParse['Content'] .= DisplayGrid();
echo \TAS\Core\TemplateHandler::TemplateChooser("admin");
