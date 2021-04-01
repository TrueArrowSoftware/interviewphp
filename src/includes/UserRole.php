<?php
namespace Framework;
class UserRole extends \TAS\Core\Entity
{

    public $UserRoleID, $AddDate, $EditDate, $RoleName, $Permission, $Role;

    public function __construct($id = 0)
    {
        parent::__construct();
        $this->_tablename = $GLOBALS['Tables']['userrole'];
        $this->_isloaded = false;
        if (is_numeric($id) && $id > 0) {
            $this->Load($id);
        } else if (is_array($id)) {
            $this->LoadFromRecordSet($id);
            $this->UserRoleID = (int) $id['userroleid'];
            $this->_isloaded = true;
        }
    }

    public function Load($id = 0)
    {
        if (!is_numeric($id) || (int) $id <= 0) {
            if ($this->UserRoleID > 0) {
                $id = $this->UserRoleID;
            } else {
                return false;
            }
        }
        $rs = $GLOBALS['db']->Execute("Select * from " . $this->_tablename . " where userroleid=" . (int) $id . " limit 1");
        if (\TAS\Core\DB::Count($rs) > 0) {
            $this->LoadFromRecordSet($rs);
            $this->_isloaded = true;
        } else {
            $this->_isloaded = false;
        }
        return $this->_isloaded;
    }

    // @todo This is for Unique Brand test
    /*
     * if RoleName is existing in database then it will return false otherwise return true.
     */
    public static function UniqueUserRole($d, $userroleid = 0)
    {
        if ($userroleid == 0) {
            $count = $GLOBALS['db']->ExecuteScalar("select count(*) from " . $GLOBALS['Tables']['userrole'] . " where rolename='" . $d['rolename'] . "'");
        } else {
            $count = $GLOBALS['db']->ExecuteScalar("select count(*) from " . $GLOBALS['Tables']['userrole'] . " where rolename='" . $d['rolename'] . "'
				and userroleid != '" . (int) $userroleid . "' ");
        }

        if ($count > 0) {
            return false;
        } else {
            return true;
        }
    }

    public static function GetFields($id = 0)
    {
        $fields = \TAS\Core\Entity::GetFieldsGeneric($GLOBALS['Tables']['userrole']);
        unset($fields['userroleid']);
        $fields['rolename']['label'] = 'Role Name';
        $fields['rolename']['displayorder'] = '1';
        $fields['rolename']['required'] = true;

        $fields['permission']['type'] = 'cb';
        $fields['permission']['function'] = array(
            'Framework\UserRole',
            "OptionHTML"
        );
        $fields['permission']['arrayname'] = 'array';
        $fields['permission']['required'] = true;
        $fields['permission']['DoLabel'] = false;
        $fields['permission']['DoWrapper'] = false;

        $fields['role']['label'] = 'Role Type';
        $fields['role']['type'] = 'select';
        $fields['role']['selecttype'] = 'globalarray';
        $fields['role']['arrayname'] = 'roletype';
        $fields['role']['showSelect'] = 'true';
        $fields['role']['required'] = true;

        if ($id > 0) {
            $userrole = new UserRole($id);
            $fields['rolename']['additionalattr'] = (($id > 0) ? 'data-rel="' . $userrole->UserRoleID . '"' : "");
            $a = $userrole->ObjectAsArray();
            foreach ($a as $i => $v) {
                if (isset($fields[strtolower($i)])) {
                    $fields[strtolower($i)]['value'] = $v;
                }
            }

            $fields['adddate']['type'] = 'readonly';
            $fields['editdate']['type'] = 'readonly';

            $fields['adddate']['label'] = 'Add Date';
            $fields['editdate']['label'] = 'Edit Date';
            $fields['permission']['value'] = json_decode($userrole->Permission, true);
        }

        unset($fields['userroleid']);
        unset($fields['editdate']);
        unset($fields['adddate']);
        return $fields;
    }

    /**
     * Check if user is referred by someone, if yes return UserID, else return bool false.
     *
     * @param unknown $userid
     *            User ID to check if he is referred to us
     * @return number|boolean false is not, else numeric userid of who refer them.
     */
    public static function Add($values = array())
    {
        if (empty($values['rolename'])) {
            self::SetError("Role Name is required", "10");
            return false;
        } elseif (!self::Validate($values, 'userrole')) {
            return false;
        } else if (!UserRole::UniqueUserRole($values)) {
            self::SetError("Please use unique User Role Name", "10");
            return false;
        } else {
            if ($GLOBALS['db']->Insert($GLOBALS['Tables']['userrole'], $values)) {
                $id = $GLOBALS['db']->GeneratedID();
                return ($id);
            } else {
                return false;
            }
        }
    }

    public function Update($values = array())
    {
        /*
         * if (is_null ( $values ) || count ( $values ) == 0) {
         * $tv = json_decode ( $this->ToJson (), true );
         * foreach ( $tv as $k => $v ) {
         * $values [strtolower ( $k )] = $v;
         * }
         * unset ( $values ['level'] );
         * $values['editdate']= Date("Y-m-d H:i:s");
         * }
         *
         * if (! isset ( $values ['username'] ))
         * $values ['username'] = $this->Username;
         */
         if (!self::Validate($values, 'userrole') || $this->UserRoleID == 0) {
            return false;
         } else if (!UserRole::UniqueUserRole($values, $this->UserRoleID)) {
            return false;
        } else {
            if ($GLOBALS['db']->Update($GLOBALS['Tables']['userrole'], $values, $this->UserRoleID, 'userroleid')) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * Delete the User
     *
     * @param unknown $id
     * @return boolean
     */
    public static function Delete($id)
    {
        if (!is_numeric($id) || (int) $id <= 0) {
            return false;
        }
        $id = floor((int) $id);

        $delete = $GLOBALS['db']->Execute("Delete from " . $GLOBALS['Tables']['userrole'] . " where userroleid=" . (int) $id . " limit 1");
        // $delete = $GLOBALS ['db']->Execute ( "Delete from " . $GLOBALS ['Tables'] ['userlocation'] . " where roleid=" . ( int ) $id . "" );
        return true;
    }

    public static function OptionHTML($d)
    {

        $HTML = '';
        $HTML .= '<div class="formfield mt-4 m-0 px-3 clearfix" id="mainmodule">
            <div class="table-responsive">
                <table class="custom table-striped col-md-12 border-0 tableuserrole">
                <tr class="custom modules">
                    <th class="custom px-2">Modules</th>
	               <th><small>
                           <input type="button" name="b1" value="Check"  id="b1" class="btn-xs btn-success px-2"> 
	                       <input type="button" name="b2" value="Uncheck" id="b2" class="btn-xs btn-danger px-2">
                    </small>
                    </th>';

        foreach ($GLOBALS['action'] as $key => $value) {
            $HTML .= "<th class='custom p-2'>" . $value . "</th>";
        }
        $HTML .= "</tr>
            <tr class='custom adminmodule'>
                <td colspan='7' class='text-center p-2 font-weight-bold'>Admin Module</td>
            </tr>";
        $queryAdmin = $GLOBALS['db']->Execute("select * from " . $GLOBALS['Tables']['module'] . " where tags='admin' ORDER BY `displayorder` ASC");
        if (\TAS\Core\DB::Count($queryAdmin) > 0) {
            while ($rsquery = $GLOBALS['db']->Fetch($queryAdmin)) {
                $key = $rsquery['slug'];
                $HTML .= "<tr class='custom$key adminmodule' id='custom$key'>
                            <td class='custom p-2'> " . $rsquery['modulename'] . "</td>
                            <td><small>
                                <input type='button' name='b3' value='Check' id='b3' class='getclass btn-xs btn-success px-2'>
                                <input type='button' name='b4' value='Uncheck' id='b4' class='removeclass btn-xs btn-danger px-2'>
                            </small></td>";

                foreach ($GLOBALS['action'] as $k => $v) {
                    $check = '';
                    if (isset($d['value'][$key][$k]) && $d['value'][$key][$k] > 0) {
                        $check = 'checked=checked';
                    }
                    $HTML .= "<td class='custom '><input type='checkbox' " . $check . " class='action' value='1' name='permission[" . $key . "][" . $k . "]'></td>";
                }
                $HTML .= "</tr>";
            }
        } else {
            $HTML .= '<tr><td colspan=7 class="text-center pt-2 pb-2">No Admin Modules Found</td></tr>';
        }

        $HTML .= "</tr><tr class='custom retailermodule'><td colspan='7' class='text-center p-2 font-weight-bold'>Retailer Admin Module</td></tr>";
        $queryWebsite = $GLOBALS['db']->Execute("select * from " . $GLOBALS['Tables']['module'] . " where tags='retaileradmin' ORDER BY `displayorder` ASC");

        if (\TAS\Core\DB::Count($queryWebsite) > 0) {
            while ($rsquery = $GLOBALS['db']->Fetch($queryWebsite)) {

                $key = $rsquery['slug'];
                $HTML .= "<tr class='custom$key retailermodule' id='custom$key'>
                    <td class='custom p-2'> " . $rsquery['modulename'] . " </td>
                <td><small><input type='button' name='b3' value='Check' id='b3' class='getclass btn-xs btn-success px-2'>
                 <input type='button' name='b4' value='Uncheck' id='b4' class='removeclass btn-xs btn-danger px-2'></small></td>";

                foreach ($GLOBALS['action'] as $k => $v) {
                    $check = '';
                    if (isset($d['value'][$key][$k]) && $d['value'][$key][$k] > 0) {
                        $check = 'checked=checked';
                    }

                    $HTML .= "<td class='custom'><input type='checkbox' " . $check . " class='action' value='1' name='permission[" . $key . "][" . $k . "]'></td>";
                }
                $HTML .= "</tr>";
            }
        } else {
            $HTML .= '<tr class="retailermodule"><td colspan=7 class="text-center pt-2 pb-2">No Retailer Admin Modules Found</td></tr>';
        }

        $HTML .= "</tr><tr class='custom locationexecutive'><td colspan='7' class='text-center p-2 font-weight-bold'>Location Executive Module</td></tr>";
        $queryWebsite = $GLOBALS['db']->Execute("select * from " . $GLOBALS['Tables']['module'] . " where tags='locationexecutive' ORDER BY `displayorder` ASC");

        if (\TAS\Core\DB::Count($queryWebsite) > 0) {
            while ($rsquery = $GLOBALS['db']->Fetch($queryWebsite)) {

                $key = $rsquery['slug'];
                $HTML .= "<tr class='custom$key locationexecutive' id='custom$key'>
                    <td class='custom p-2'> " . $rsquery['modulename'] . " </td>
                <td><small><input type='button' name='b3' value='Check' id='b3' class='getclass btn-xs btn-success px-2'>
                 <input type='button' name='b4' value='Uncheck' id='b4' class='removeclass btn-xs btn-danger px-2'></small></td>";

                foreach ($GLOBALS['action'] as $k => $v) {
                    $check = '';
                    if (isset($d['value'][$key][$k]) && $d['value'][$key][$k] > 0) {
                        $check = 'checked=checked';
                    }

                    $HTML .= "<td class='custom'><input type='checkbox' " . $check . " class='action' value='1' name='permission[" . $key . "][" . $k . "]'></td>";
                }
                $HTML .= "</tr>";
            }
        } else {
            $HTML .= '<tr class="locationexecutive"><td colspan=7 class="text-center pt-2 pb-2">No Location Executive Modules Found</td></tr>';
        }

        $HTML .= "</tr><tr class='custom superretailermodule'>
                <td colspan='7' class='text-center p-2 font-weight-bold'>Super Retailer Module</td></tr>";

        $queryWebsite = $GLOBALS['db']->Execute("select * from " . $GLOBALS['Tables']['module'] . " where tags='superretailer' ORDER BY `displayorder` ASC");

        if (\TAS\Core\DB::Count($queryWebsite) > 0) {
            while ($rsquery = $GLOBALS['db']->Fetch($queryWebsite)) {

                $key = $rsquery['slug'];
                $HTML .= "<tr class='custom$key superretailermodule' id='custom$key'>
                <td class='custom p-2'> " . $rsquery['modulename'] . " </td>
                    <td><small><input type='button' name='b3' value='Check'  id='b3' class='getclass btn-xs btn-success px-2'>
                    <input type='button' name='b4' value='Uncheck' id='b4' class='removeclass btn-xs btn-danger px-2'></small>
                </td>";

                foreach ($GLOBALS['action'] as $k => $v) {
                    $check = '';
                    if (isset($d['value'][$key][$k]) && $d['value'][$key][$k] > 0) {
                        $check = 'checked=checked';
                    }

                    $HTML .= "<td class='custom'><input type='checkbox' " . $check . " class='action' value='1' name='permission[" . $key . "][" . $k . "]'></td>";
                }
                $HTML .= "</tr>";
            }
        } else {
            $HTML .= '<tr class="superretailermodule"><td colspan=7 class="text-center pt-2 pb-2">No Super Retailer Modules Found</td></tr>';
        }

        $HTML .= '</tr></table></div></div>';
        return $HTML;
    }
}
