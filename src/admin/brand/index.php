<?php
namespace Framework;

require_once ("./include.php");
require ("../template.php");
$messages = array();
if (! $permission->CheckOperationPermission('company', 'access', $GLOBALS['user']->UserRoleID)) {
    \TAS\Core\Web::Redirect("../index.php");
}
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['delete']) && is_numeric($_GET['delete']) && $permission->CheckOperationPermission('company', 'delete', $GLOBALS['user']->UserRoleID)) {
        if (Company::Delete((int) $_GET['delete'])) {
            $messages[] = array(
                "message" => _("Brand has been deleted successfully."),
                "level" => 1
            );
        } else {
            $messages[] = array(
                "message" => _("Fail to delete this record"),
                "level" => 10
            );
        }
    }

    if (isset($_GET['mode']) && $_GET['mode'] == 'clearfilter') {
        setcookie('admin_company_filter', '', (time() - 25292000));
        \TAS\Core\Web::Redirect("index.php");
    }

    if (isset($_GET['type']) && is_numeric($_GET['id'])) {
        if ($_GET['type'] == 'status') {
            $id = $_GET['id'];
            $page = $_GET['page'];
            $u = new Company((int) $_GET['id']);
            if ($u->IsLoaded()) {
                $s = (($u->Status == 1) ? 0 : 1);
                $GLOBALS['db']->Execute("update " . $GLOBALS['Tables']['company'] . " set status=" . $s . " where companyid=" . $u->CompanyID);
                \TAS\Core\Web::Redirect("index.php?status=1&page=" . $_GET['page']);
            } else {
                $messages[] = array(
                    "message" => _("No user found to update status"),
                    "level" => 10
                );
            }
        }
    }
}

if (isset($_GET['status']) && $_GET['status'] == '1') {
    $messages[] = array(
        "message" => _("Brand status has been updated successfully."),
        "level" => 1
    );
}

$pageParse['Content'] .= '<div class="col-md-12 pt-3"> <div class="card card-body card-radius">
<h2 class="borderbottom-set">Brand Management</h2>';
$pageParse['Content'] .= '<div class="px-3 mt-3 display-messages">' . \TAS\Core\UI::UIMessageDisplay($messages) . '</div>';
$pageParse['Content'] .= '<h6 class="px-3 pt-3 m-0"><a href="add.php">Add New Brand</a></h6>';

if (isset($_COOKIE['admin_company_filter']) && $_SERVER['REQUEST_METHOD'] == 'GET') {
    $filterOptions = json_decode($_COOKIE['admin_company_filter'], true);
} else {
    $filterOptions = $_REQUEST;
}

$pageParse['Content'] .= '
<ul class="filterul d-flex p-3">
<li class="mr-2"><input type="button" name="filter" id="filter" class="btn primary-color primary-bg-color py-2" value="Show Filters"/></li>
<li><a href="index.php?mode=clearfilter" class="btn primary-color btn-dark py-2"> Clear Filter</a></li>
</ul>
  <div id="filterbox" class="filter-form-setting">
  <form method="post" action="index.php">
  <div class="form-row">
        
  <div class="formfield form-group col-md-4">
  <label class="formlabel" for="companyname">Name</label>
  <div class="forminputwrapper">
  <input type="text" name="companyname" id="companyname" class="form-control" value="' . (isset($filterOptions['companyname']) ? $filterOptions['companyname'] : '') . '" />
  </div>
  <div class="clear"></div></div>
      
	</div>
  <ul class="filterul d-flex w-100">
            <li><button type="submit" name="submit" class="btn primary-color primary-bg-color py-2 m-0" id="filtersubmit">Filter Report</button><li>
        </ul>				    

	</form></div></div></div>';

$pageParse['Content'] .= DisplayGrid();
echo \TAS\Core\TemplateHandler::TemplateChooser("admin");