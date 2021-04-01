<?php

namespace Framework;

/**
 * Handles all sort of Address here.
 */
class Address extends \TAS\Core\Entity
{
    public $AddressID,$OwnerID,$OwnerType,$AddressType,$Title,$SubTitle,$Address1,$Address2,$City,$State,$ZipCode;
    
    public $Email,$Phone,$Country,$AddDate,$EditDate,$Tag;
    
    /**
     * Default Constructor for Address.
     */
    public function __construct($id = 0)
    {
        $this->Title = '';
        $this->SubTitle = '';
        $this->Address1 = '';
        $this->Address2 = '';
        $this->City = '';
        $this->Phone = '';
        $this->Email = '';
        $this->State = '';
        $this->Zipcode = '';
        $this->Country == '';
        if (is_numeric($id) && $id > 0) {
            $this->Load($id);
        }
    }

    public function Load($id)
    {
        if (!is_numeric($id) || (int) $id <= 0) {
            if ($this->AddressID > 0) {
                $id = $this->AddressID;
            } else {
                return false;
            }
        } else {
            $rs = $GLOBALS['db']->Execute('Select * from '.$GLOBALS['Tables']['address'].' where addressid='.$id.' limit 1');
            if (\TAS\Core\DB::Count($rs) > 0) {
                $row = $GLOBALS['db']->Fetch($rs);
                $this->AddressID = (int) $id;
                $this->LoadJSON(json_encode(array(
                    'address1' => $row['address1'],
                    'address2' => $row['address2'],
                    'city' => $row['city'],
                    'email' => $row['email'],
                    'phone' => $row['phone'],
                    'state' => $row['state'],
                    'zipcode' => $row['zipcode'],
                    'country' => $row['country'],
                )));
                $this->Title = ucwords($row['title']);
                $this->SubTitle = $row['subtitle'];
                $this->Email = $row['email'];
                $this->Phone = $row['phone'];
                $this->AddDate = \TAS\Core\DataFormat::DBToDateTimeFormat($row['adddate']);
                $this->EditDate = \TAS\Core\DataFormat::DBToDateTimeFormat($row['editdate']);
                $this->AddressType = $row['addresstype'];
                $this->OwnerID = (int) $row['ownerid'];
                $this->OwnerType = $row['ownertype'];
                $this->Tag = $row['tag'];
                $this->isLoad = true;
            } else {
                return false;
            }
        }
    }

    public static function InsertUniqueTag($values)
    {
        $oldid = $GLOBALS['db']->ExecuteScalar('Select addressid from '.$GLOBALS['Tables']['address']." where
					ownerid='".$values['ownerid']."' and ownertype='".$values['ownertype']."'  and addresstype='".$values['addresstype']."' and tag='".$values['tag']."'");
        if ($oldid === false) {
            if ($GLOBALS['db']->InsertArray($GLOBALS['Tables']['address'], $values)) {
                $id = $GLOBALS['db']->GeneratedID();

                return array(
                    'status' => 'new',
                    'id' => $id,
                );
            } else {
                return array(
                    'status' => 'error',
                    'id' => false,
                );
            }
        } else {
            return array(
                'status' => 'old',
                'id' => (int) $oldid,
            );
        }
    }

    /**
     * Load the Address from json string.
     *
     * @param string $jsondata
     *                         a JSON String
     */
    public function LoadJSON($jsondata)
    {
        if (trim($jsondata) == '') {
            return false;
        }
        $data = json_decode($jsondata, true);

        foreach ($data as $key => $value) {
            $key = trim(strtolower($key));
            $value = ucwords(trim($value));
            switch ($key) {
                    case 'title':
                        $this->Title = $value;
                        break;
                    case 'subtitle':
                        $this->SubTitle = $value;
                        break;
                    case 'address1':
                    case 'streetaddress':
                    case 'add1':
                    case 'address':
                        $this->Address1 = $value;
                        break;
                    case 'address2':
                    case 'add2':
                        $this->Address2 = $value;
                        break;
                    case 'city':
                        $this->City = $value;
                        // no break
                    case 'state':
                    case 'st':
                        $this->State = $value;
                        break;
                    case 'zip':
                    case 'zipcode':
                    case 'zcode':
                        $this->Zipcode = $value;
                        break;
                    case 'country':
                        $this->Country = $value;
                }
        }

        return true;
    }

    public static function GetFields($id = 0)
    {
        $fields = \TAS\Core\Entity::GetFieldsGeneric($GLOBALS['Tables']['address']);
        $address = new Address();
        $fields['title']['label'] = 'First Name';
        $fields['title']['displayorder'] = 1;
        $fields['subtitle']['label'] = 'Last Name';
        $fields['subtitle']['displayorder'] = 2;
        $fields ['zipcode'] ['label'] = 'Post Code';
        $fields['email']['required'] = true;
        $fields['email']['type'] = 'email';
        $fields['phone']['type'] = 'number';
        $fields['phone']['required'] = true;
        $fields['title']['required'] = true;
        $fields['address1']['required'] = true;
       /*  $fields['city']['required'] = true;
        $fields['zipcode']['required'] = true; */
        
        
        
        if ($id > 0) {
            $address = new Address($id);
            $a = $address->ObjectAsArray();
            foreach ($a as $i => $v) {
                if (isset($fields[strtolower($i)])) {
                    $fields[strtolower($i)]['value'] = $v;
                }
            }
            $fields['adddate']['type'] = 'readonly';
            $fields['editdate']['type'] = 'readonly';
            $fields['adddate']['label'] = 'Date created';
            $fields['editdate']['label'] = 'Date last edit made';
        } else {
            unset($fields['lastlogin']);
        }
        unset($fields['adddate']);
        unset($fields['editdate']);
        unset($fields['addresstype']);
        unset($fields['ownerid']);
        unset($fields['ownertype']);
        unset($fields['country']);
        unset($fields['state']);
        unset($fields['tag']);
        unset($fields['addressid']);
        return $fields;
    }

    public static function Add($values = array())
    {
        if (!self::Validate($values, 'address')) {
            return false;
        } else {
            $required = array(
                'ownertype',
                'addresstype',
                'ownerid',
            );
            if (count(array_intersect($required, array_keys($values))) == count($required)) {
                
                if ($GLOBALS['db']->Insert($GLOBALS['Tables']['address'], $values)) {
                   
                    $id = $GLOBALS['db']->GeneratedID();
                    
                    return $id;
                } else {
                    return false;
                }
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
            unset($values['level']);
        }
        if (! self::Validate($values,'address') || $this->AddressID == 0) {
            return false;
        }else {
            if ($GLOBALS['db']->Update($GLOBALS['Tables']['address'], $values, $this->AddressID, 'addressid')) {
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
        $delete = $GLOBALS['db']->Execute('Delete from '.$GLOBALS['Tables']['address'].' where addressid='.(int) $id.' limit 1');

        return true;
    }

    /**
     * Get HTML of Address.
     */
    public function GetHTML($wrapper = 'p', $titleaddition = '')
    {
        return '<'.$wrapper.' class="address">'.($this->Title != '' ? '<span class="addresstitle">'.$this->Title.(($titleaddition != '') ? ' - '.$titleaddition : '').'</span><br />' : '').($this->SubTitle != '' ? '<span class="addresstitle">'.$this->SubTitle.'</span><br />' : '').$this->Address1.',<br />'.(($this->Address2 != '') ? $this->Address2.',<br />' : '').$this->City.', '.$this->State.' '.$this->Zipcode.'<br />'.'</'.$wrapper.'>';
    }

    /**
     * Returns the AddressID on given condition.
     *
     * @param string $ownerid
     * @param string $ownertype
     * @param string $addresstype
     */
    public static function GetAddressID($ownerid, $ownertype, $addresstype)
    {
        return $GLOBALS['db']->ExecuteScalar('Select addressid from '.$GLOBALS['Tables']['address']." where ownertype='".$ownertype."' and ownerid='".(int) $ownerid."' and addresstype='".$addresstype."' limit 1");
    }

    /**
     * Returns the array of all Address ID for given owner and type.
     *
     * @param string $ownerid
     * @param string $ownertype
     * @param string $addresstype
     */
    public static function GetAddressIDs($ownerid, $ownertype, $addresstype)
    {
        return $GLOBALS['db']->FirstColumnArray('Select addressid from '.$GLOBALS['Tables']['address']." where ownertype='".$ownertype."' and ownerid=".(int) $ownerid." and addresstype='".$addresstype."'");
    }
    
    public static function GetDefaultAddress($ownerid, $ownertype, $addresstype)
    {
        return $GLOBALS['db']->ExecuteScalarRow('Select * from '.$GLOBALS['Tables']['address']." where ownertype='".$ownertype."' and ownerid=".(int) $ownerid." and addresstype='".$addresstype."'");
    }
}
