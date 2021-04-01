<?php
namespace Framework;

class Company extends \TAS\Core\Entity
{

    public $CompanyID, $CompanyName, $CompanyType;

    public $Status;

    public function __construct($id = 0)
    {
        parent::__construct();
        $this->_tablename = $GLOBALS['Tables']['company'];
        $this->_isloaded = false;
        if (is_numeric($id) && $id > 0) {
            $this->Load($id);
        }
    }

    public function Load($id = 0)
    {
        if (! is_numeric($id) || (int) $id <= 0) {
            if ($this->CompanyID > 0) {
                $id = $this->CompanyID;
            } else {
                return false;
            }
        }
        $rs = $GLOBALS['db']->Execute("Select * from " . $this->_tablename . " where companyid=" . (int) $id . " limit 1");
        if (\TAS\Core\DB::Count($rs) > 0) {
            $this->LoadFromRecordSet($rs);
        }
    }

    // @todo This is for Unique Brand test
    /*
     * if Brand is existing in database then it will return false otherwise return true.
     */
    public static function UniqueBrand($d, $companyid = 0)
    {
        if ($companyid == 0) {
            $count = $GLOBALS['db']->ExecuteScalar("select count(*) from " . $GLOBALS['Tables']['company'] . " where companyname='" . $d['companyname'] . "'");
        } else {
            $count = $GLOBALS['db']->ExecuteScalar("select count(*) from " . $GLOBALS['Tables']['company'] . " where companyname='" . $d['companyname'] . "'
				and companyid != '" . (int) $companyid . "' ");
        }

        if ($count > 0) {
            return false;
        } else {
            return true;
        }
    }

    public static function Add($values = array())
    {
        if (empty($values['companyname'])) {
            self::SetError("Name is required", "10");
            return false;
        } elseif (! self::Validate($values, 'company')) {
            return false;
        } else if (!Company::UniqueBrand($values)) {
            self::SetError("Please use unique Brand Name", "10");
            return false;  
        } else {
            if ($GLOBALS['db']->Insert($GLOBALS['Tables']['company'], $values)) {
                $id = $GLOBALS['db']->GeneratedID();
                return ($id);
            } else {
                return false;
            }
        }
    }

    public function Update($values = array())
    {
        if (is_null($values)) {
            $tv = json_decode($this->ToJson(), true);
            foreach ($tv as $k => $v) {
                $values[strtolower($k)] = $v;
            }
        }
        
        if (isset($values['companyname']) && empty($values['companyname'])){
            self::SetError("Name is required", "10");
            return false;
        } elseif (! self::Validate($values, 'company') || $this->CompanyID == 0) {
            
            return false;
        } 
        else if (!Company::UniqueBrand($values ,$this->CompanyID)) {
            self::SetError("Please use unique Brand Name", "10");
            return false;
        }else {
            if ($GLOBALS['db']->Update($this->_tablename, $values, $this->CompanyID, 'companyid')) {
                return true;
            } else {
                return false;
            }
        }
    }

    public static function Delete($id)
    {
        if (! is_numeric($id) || (int) $id <= 0) {
            return false;
        }
        $id = floor((int) $id);

        $delete = $GLOBALS['db']->Execute("Delete from " . $GLOBALS['Tables']['company'] . " where companyid=" . (int) $id . " limit 1");
        return true;
    }

    public static function GetFields($id = 0)
    {
        $fields = \TAS\Core\Entity::GetFieldsGeneric($GLOBALS['Tables']['company']);
        $company = new Company();
        $fields['companyname']['label'] = 'Brand Name';

        $fields['status']['type'] = 'checkbox';

        $fields['companyname']['required'] = true;

        $fields['companyname']['displayorder'] = 1;
        $fields['status']['displayorder'] = 3;

        if ($id > 0) {
            $company = new Company($id);
            $a = $company->ObjectAsArray();
            foreach ($a as $i => $v) {
                if (isset($fields[strtolower($i)])) {
                    $fields[strtolower($i)]['value'] = $v;
                }
            }
        }

        $fields['status']['value'] = (isset($company->Status) && $company->Status == 1) ? true : false;

        unset($fields['companyid']);
        return $fields;
    }
}
