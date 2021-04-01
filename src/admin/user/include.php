<?php
namespace Framework;
function DisplayForm($edit = 0)
{
    $formtitle = ($edit > 0) ? "Edit User" : "Add User";
    $fields = array();
    $fields = User::GetFields($edit);
    if ($edit > 0) {
        $user = new User($edit);
    } 
    else {
        $user = new User();
    }
    $param['Fields'] = $fields;
    $param['Fields']['userroleid'] = array(
        'field' => 'userroleid',
        'id' => 'userroleid',
        'type' => 'select',
        'selecttype'=>'query',
        'query' =>'Select userroleid, rolename from ' . $GLOBALS['Tables']['userrole'] . " WHERE role='admin' order by rolename ASC",
        'dbID'=>'userroleid',
        'dbLabelField'=>'rolename',
        'label' => 'User Role',
        'displayorder' => 4,
        'group' => 'basic',
        'value' =>$user->UserRoleID,
        'required' =>true,
    );
    
    $param['Group'] = array(
        'basic' => array(
            'legend' => ''
        )
    );

$form = '<div class="col-md-12 pt-3"> 
            <div class="card card-body card-radius">
                <h2 class="borderbottom-set">' .$formtitle. '</h2>
                <div class="px-3 py-2">' .\TAS\Core\UI::UIMessageDisplay($GLOBALS['messages']) . '</div>
                <form action="" method="post" class="validate">
                    <fieldset class="generalform">
	                   ' .\TAS\Core\UI::GetFormHTML($param) . '
	                   <div class="formbutton">
		                  <input name="btnsubmit" id="btnsubmit" class="btn primary-color primary-bg-color py-2" value="Submit" type="submit">			
	                   </div>
                    </fieldset>
                </form>
            </div>
        </div>';

        return $form;
    }

function DisplayGrid()
{
    $queryoptions = \TAS\Core\Grid::DefaultQueryOptions();
    $queryoptions['basicquery'] = "select u.*, concat(u.firstname, ' ', u.lastname) as fullname, ur.rolename as rolename from " . $GLOBALS['Tables']['user'] . " AS u INNER JOIN " . $GLOBALS['Tables']['userrole'] . " AS ur ON u.userroleid = ur.userroleid";
    $filterOptions = array();
    
    if (isset($_COOKIE['admin_user_filter']) && $_SERVER['REQUEST_METHOD'] == 'GET') {
        $filterOptions = json_decode(stripslashes($_COOKIE['admin_user_filter']), true);
    } else {
        $filterOptions = $_POST;
    }
    
    $filter = array();
    
    if (isset($filterOptions['search_email']) && $GLOBALS['db']->Escape($filterOptions['search_email']) != '') {
        $filter[] = " u.email like '%" . $GLOBALS['db']->Escape($filterOptions['search_email']) . "%'";
    }
    if (isset($filterOptions['search_phone']) && $GLOBALS['db']->Escape($filterOptions['search_phone']) != '') {
        $filter[] = " u.phone like '%" . $GLOBALS['db']->Escape($filterOptions['search_phone']) . "%'";
    }
    if (isset($filterOptions['search_lastname']) && $GLOBALS['db']->Escape($filterOptions['search_lastname']) != '') {
        $filter[] = " u.lastname like '%" . $GLOBALS['db']->Escape($filterOptions['search_lastname']) . "%'";
    }
    if (isset($filterOptions['search_firstname']) && $GLOBALS['db']->Escape($filterOptions['search_firstname']) != '') {
        $filter[] = " u.firstname like '%" . $GLOBALS['db']->Escape($filterOptions['search_firstname']) . "%'";
    }
    if (isset($filterOptions['search_username']) && $GLOBALS['db']->Escape($filterOptions['search_username']) != '') {
        $filter[] = " u.username like '%" . $GLOBALS['db']->Escape($filterOptions['search_username']) . "%'";
    }
   
    $queryoptions['whereconditions'] = ' where u.userroleid > 0';
    if (count($filter) > 0) {
        $queryoptions['whereconditions'] .= ' and '.implode(' and ', $filter).' ';
    }
    
    $queryoptions['pagingquery'] = "select count(*) from " . $GLOBALS['Tables']['user']. "";
    
    $_COOKIE['admin_user_filter'] = json_encode($filterOptions);
    setcookie('admin_user_filter', json_encode($filterOptions), (time() + 25292000));

    $options = \TAS\Core\Grid::DefaultOptions();
    $options['gridurl'] = $GLOBALS['AppConfig']['AdminURL'] . '/user/index.php';
    $options['gridid'] = 'userid';
    $options['allowsorting'] = true;
    $options['allowpaging'] = true;
    $options['showtotalrecord'] = true;
    $options['allowselection'] = false;

    $icon = new \TAS\Core\Grid();
    $options['option'] = $icon->DefaultIcon();
    $options['option']['edit']['link'] = $GLOBALS['AppConfig']['AdminURL'] . '/user/edit.php';
    
   
    $param['defaultorder'] = 'userid';
    $param['defaultsort'] = 'desc';
    $param['indexfield'] = 'userid';
    $param['tablename'] = $GLOBALS['Tables']['user'];
    $options['fields'] = array(
        'userid' => array(
            'type' => 'string',
            'name' => '#'
        ),
        'username' => array(
            'type' => 'string',
            'name' => 'User Name'
        ),
        'email' => array(
            'type' => 'string',
            'name' => 'Email'
        ),
        'rolename' => array(
            'type' => 'string',
            'name' => 'Role'
        ),
        'fullname' => array(
            'type' => 'string',
            'name' => 'Name'
        ),
        'phone' => array(
            'type' => 'string',
            'name' => 'Phone'
        ),
        'status' => array(
            'type' => 'onoff',
            'name' => 'Status',
            'mode' => 'fa'
        )
    );
    $queryoptions['defaultorderby'] = 'userid';
    $queryoptions['defaultsortdirection'] = 'desc';
    $queryoptions['indexfield'] = 'userid';
    $queryoptions['tablename'] = $GLOBALS['Tables']['user'];
    
   
/* for extra icon */
     $options['option']['changepassword'] = array(
        'link' => $GLOBALS['AppConfig']['AdminURL'] . '/user/changepassword.php',
        'iconclass' => 'fa-key',
        'tooltip' => 'Change Password',
        'tagname' => 'changepassword colorboxpopup',
        'paramname' => 'userid',
    );
    $grid = new \TAS\Core\Grid($options, $queryoptions);

    if (isset($_GET['download']) && $_GET['download'] == "true") {
        \TAS\Core\Utility::CreateCSV($queryoptions, "user-" . date("d-m-Y") . ".csv", 'user', $options);
    }

    return $grid->Render();
}



