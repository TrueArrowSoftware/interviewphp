<?php
namespace Framework;

require ("../template.php");
require_once ("./include.php");
$messages = array();
if (! $permission->CheckOperationPermission('category', 'access', $GLOBALS['user']->UserRoleID)) {
    \TAS\Core\Web::Redirect("../index.php");
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['delete']) && is_numeric($_GET['delete']) && $permission->CheckOperationPermission('category', 'delete', $GLOBALS['user']->UserRoleID)) {
        if (Category::Delete((int) $_GET['delete'])) {
            $messages[] = array(
                "message" => _("Category has been deleted successfully."),
                "level" => 1
            );
        } else {
            $messages[] = array(
                "message" => _("Unable to delete category at this moment. Please try again later."),
                "level" => 10
            );
        }
    }

    if (isset($_GET['mode']) && $_GET['mode'] == 'clearfilter') {
        setcookie('admin_category_filter', '', (time() - 25292000));
        \TAS\Core\Web::Redirect("index.php");
    }

    if (isset($_GET['type']) && is_numeric($_GET['id'])) {
        if ($_GET['type'] == 'status') {
            $u = new Category((int) $_GET['id']);
            if ($u->IsLoaded()) {
                $s = (($u->Status == 1) ? 0 : 1);
                $GLOBALS['db']->Execute("update " . $GLOBALS['Tables']['category'] . " set status=" . $s . " where categoryid=" . $u->CategoryID);
                \TAS\Core\Web::Redirect("index.php?status=1&page=" . $_GET['page']);
            } else {
                $messages[] = array(
                    "message" => _("No category found to update status"),
                    "level" => 10
                );
            }
        }

        if ($_GET['type'] == 'showinmenu') {
            $u = new Category((int) $_GET['id']);
            if ($u->IsLoaded()) {
                $s = (($u->ShowInMenu == 1) ? 0 : 1);
                $GLOBALS['db']->Execute("update " . $GLOBALS['Tables']['category'] . " set showinmenu=" . $s . " where categoryid=" . $u->CategoryID);
                \TAS\Core\Web::Redirect("index.php?status=2&page=" . $_GET['page']);
            } else {
                $messages[] = array(
                    "message" => _("No category found to update status"),
                    "level" => 10
                );
            }
        }
    }
    if (isset($_GET['status']) && $_GET['status'] == '1') {
        $messages[] = array(
            "message" => _("Category status has been updated successfully."),
            "level" => 1
        );
    }
    if (isset($_GET['status']) && $_GET['status'] == '2') {
        $messages[] = array(
            "message" => _("Show In Menu status has been updated successfully."),
            "level" => 1
        );
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'order') {
        $ids = array();
        $page = ((int) $GLOBALS['AppConfig']['PageSize']) * (isset($_POST['page']) ? ((int) $_POST['page'] - 1) : 0);

        foreach ($_POST['data'][0] as $data) {
            $ids[$data['id']] = ++ $page;
        }

        foreach ($ids as $id => $value) {
            $GLOBALS['db']->Execute("update " . $GLOBALS['Tables']['category'] . " set displayorder=" . (int) $value . " where categoryid=" . (int) $id);
        }
    }
}

$pageParse['Content'] .= '<div class="col-md-12 pt-3"> <div class="card card-body card-radius">
<h2 class="borderbottom-set">Category Management</h2>';
$pageParse['Content'] .= '<div class="px-3 mt-3 display-messages">' . \TAS\Core\UI::UIMessageDisplay($messages) . '</div>';
$pageParse['Content'] .= '<h6 class="px-3 pt-3 m-0"><a href="add.php">Add New Category</a></h6>';

if (isset($_COOKIE['admin_category_filter']) && $_SERVER['REQUEST_METHOD'] == 'GET') {
    $filterOptions = json_decode($_COOKIE['admin_category_filter'], true);
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
	<div class="">
        <div class="form-row">
		<div class="formfield form-group col-md-4">
			<label class="formlabel" for="search_catname">Category Name</label>
			<div class="forminputwrapper">
				<input type="text" name="search_catname" id="search_catname" class="form-control" value="' . (isset($filterOptions['search_catname']) ? $filterOptions['search_catname'] : '') . '" />
			</div>
		<div class="clear"></div></div>
				    
		<div class="formfield form-group col-md-2">
			<label class="formlabel" for="search_showtop">Show Top level</label>
			<div class="forminputwrapper">
				<input type="checkbox" class="" name="search_showtop" id="search_showtop" ' . (isset($filterOptions['search_showtop']) ? 'checked="checked"' : '') . '" />
			</div>
		<div class="clear"></div></div>
        <div class="formfield form-group col-md-4">
			<label class="formlabel" for="search_childof">Child Of</label>
			<div class="forminputwrapper">
				<select id="search_childof" name="search_childof" class="form-control">
                    <option value="">All</option>
                ' . \TAS\Core\UI::ArrayToDropDown(Category::GetCategoryTreeForDropDown(), (isset($filterOptions['search_childof']) ? $filterOptions['search_childof'] : '')) . '
            </select>
			</div>
		<div class="clear"></div></div>
    </div> 
    	
				    
	</div>
		 <ul class="filterul d-flex w-100">
            <li><button type="submit" name="submit" class="btn primary-color primary-bg-color py-2 m-0" id="filtersubmit">Filter Report</button><li>
        </ul>				    

	</form></div></div></div>';

$pageParse['Content'] .= DisplayGrid();
echo \TAS\Core\TemplateHandler::TemplateChooser("admin");
