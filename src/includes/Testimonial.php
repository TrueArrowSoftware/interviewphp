<?php
namespace Framework;

class Testimonial extends \TAS\Core\Entity
{

    public $TestimonialID, $TestimonialName, $Company , $AddDate, $EditDate ,$DisplayOrder, $Message, $Status;
    
    public function __construct($id = 0)
    {
        parent::__construct();
        $this->_tablename = $GLOBALS['Tables']['testimonial'];
        $this->_isloaded = false;
        if (is_numeric($id) && $id > 0) {
            $this->Load($id);
        }
    }

    public function Load($id = 0)
    {
        if (! is_numeric($id) || (int) $id <= 0) {
            if ($this->TestimonialID > 0) {
                $id = $this->TestimonialID;
            } else {
                return false;
            }
        }
        $rs = $GLOBALS['db']->Execute("Select * from " . $this->_tablename . " where testimonialid=" . (int) $id . " limit 1");
        if (\TAS\Core\DB::Count($rs) > 0) {
            $this->_isloaded = true;
            $this->LoadFromRecordSet($rs);
        }
    }
    

    public static function Add($values = array())
    {
        if (empty($values['testimonialname'])) {
            self::SetError("Testimonial Name is required", "10");
            return false;
        } elseif (! self::Validate($values, 'testimonial')) {
            return false;
        } else {
            if ($GLOBALS['db']->Insert($GLOBALS['Tables']['testimonial'], $values)) {
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
        
        if (isset($values['testimonialname']) && empty($values['testimonialname'])){
            self::SetError("Testimonial Name is required", "10");
            return false;
        } elseif (! self::Validate($values, 'testimonial') || $this->TestimonialID == 0) {
            
            return false;
        } else {
            if ($GLOBALS['db']->Update($this->_tablename, $values, $this->TestimonialID, 'testimonialid')) {
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

        $delete = $GLOBALS['db']->Execute("Delete from " . $GLOBALS['Tables']['testimonial'] . " where testimonialid=" . (int) $id . " limit 1");
        return true;
    }

    public static function GetFields($id = 0)
    {
        $fields = \TAS\Core\Entity::GetFieldsGeneric($GLOBALS['Tables']['testimonial']);
       
        $fields['testimonialname']['label'] = 'Testimonial Name';
        $fields['testimonialname']['required'] = true;
        
        $fields['displayorder']['label'] = 'Display Order ';
        $fields['displayorder']['shortnote'] = 'Least numeric number will come on top';
       
        $fields['status']['type'] = 'checkbox';
       
        if ($id > 0) {
            $testimonial = new Testimonial($id);
            $a = $testimonial->ObjectAsArray();
            foreach ($a as $i => $v) {
                if (isset($fields[strtolower($i)])) {
                    $fields[strtolower($i)]['value'] = $v;
                }
            }
            
            $fields['adddate']['type'] = 'readonly';
            $fields['editdate']['type'] = 'readonly';
            $fields['adddate']['label'] = 'Add Date';
            $fields['editdate']['label'] = 'Edit Date';
        } else {
            unset($fields['adddate']);
            unset($fields['editdate']);
        }
        
        unset($fields['testimonialid']);
        return $fields;
    }
    
}
