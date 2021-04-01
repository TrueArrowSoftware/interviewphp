<?php

namespace Framework;

class Country extends \TAS\Core\Entity
{
	public $CountryID, $CodeNumber, $CountryCode2, $CountryCode3, $CountryName, $Nationality;

	public function __construct($id = 0)
	{
		parent::__construct();
		$this->_tablename = $GLOBALS['Tables']['countries'];
		$this->_isloaded = false;
		if (!empty($id)) {
			$this->Load($id);
		}
	}

	public function Load($id = 0)
	{
		if (empty($id)) {
			if (!empty($this->CountryID)) {
				$id = $this->CountryID;
			} else {
				return false;
			}
		}
		$rs = $GLOBALS['db']->Execute("Select * from " . $this->_tablename . " where countryid='" . $id . "' limit 1");
		if (\TAS\Core\DB::Count($rs) > 0) {
			$this->LoadFromRecordSet($rs);
			$this->isLoad = true;
			return true;
		} else {
			return false;
		}
	}
	public static function GetFields($id = 0)
	{
		$fields = \TAS\Core\Entity::GetFieldsGeneric($GLOBALS['Tables']['countries']);
		$country = new Country();
		$fields['countryname']['label'] = 'Country Name';
		$fields['countryname']['required'] = true;
		$fields['countryname']['displayorder'] = 1;

		$fields['countrycode2']['label'] = 'Country Code2';
		
		$fields['countrycode2']['displayorder'] = 3;

		$fields['countrycode3']['label'] = 'Country Code3';
		$fields['countrycode3']['displayorder'] = 4;

		$fields['nationality']['label'] = 'Nationality';
		$fields['nationality']['required'] = true;
		$fields['nationality']['displayorder'] = 2;


		if ($id > 0) {
			$country = new Country($id);
			$a = $country->ObjectAsArray();
			foreach ($a as $i => $v) {
				if (isset($fields[strtolower($i)])) {
					$fields[strtolower($i)]['value'] = $v;
				}
			}
		}

		unset($fields['countryid']);
		unset($fields['codenumber']);
		return $fields;
	}
	public static function UniqueCountry($d, $CountryID = 0)
	{
		if ($CountryID == 0) {
			$count = $GLOBALS['db']->ExecuteScalar("select count(*) from " . $GLOBALS['Tables']['countries'] . " where countryname='" . $d['countryname'] . "'");
		} else {
			$count = $GLOBALS['db']->ExecuteScalar("select count(*) from " . $GLOBALS['Tables']['countries'] . " where countryname='" . $d['countryname'] . "'
				and countryid!= '" . $CountryID . "' ");
		}
		if ($count > 0) {
			return false;
		} else {
			return true;
		}
	}
	public static function Add($values = array())
	{
		if (!self::Validate($values, $GLOBALS['Tables']['countries'])) {
			return false;
		} else if (!Country::UniqueCountry($values)) {
			self::SetError("Please use unique country name", "10");
			return false;
		} else {
			if ($GLOBALS['db']->Insert($GLOBALS['Tables']['countries'], $values)) {
				$id = $GLOBALS['db']->GeneratedID();
				return ($id);
			} else {
				return false;
			}
		}
	}
	public function Update($values = array())
	{
		if (is_null($values) || count($values) == 0) {
			$tv = json_decode($this->ToJson(), true);
			foreach ($tv as $k => $v) {
				$values[strtolower($k)] = $v;
			}
		}
		if (!self::Validate($values, $GLOBALS['Tables']['countries']) || $this->CountryID == 0) {
			return false;
		} else if (!Country::UniqueCountry($values, $this->CountryID)) {
			self::SetError("Please use unique country name", "10");
			return false;
		} else {
			if ($GLOBALS['db']->Update($GLOBALS['Tables']['countries'], $values, $this->CountryID, 'countryid')) {
				return true;
			} else {
				return false;
			}
		}
	}


	public static function Delete($id)
	{
		if (!is_numeric($id) || (int) $id <= 0) {
			return false;
		}
		$id = floor((int) $id);
		$delete = $GLOBALS['db']->Execute("Delete from " . $GLOBALS['Tables']['countries'] . " where countryid=" . (int) $id . " limit 1");
		return true;
	}
}
