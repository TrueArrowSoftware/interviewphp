<?php
namespace Framework;
class ProductVariation extends \TAS\Core\Entity
{

    public $VariationID, $ProductID, $SinglePrice, $ProductCode;

    public $AddDate, $EditDate, $Status;

    public function __construct($id = 0)
    {
        parent::__construct();
        $this->_tablename = $GLOBALS['Tables']['productvariation'];
        $this->_isloaded = false;
        if (is_numeric($id) && $id > 0) {
            $this->Load($id);
        }
    }

    public function Load($id = 0)
    {
        if (! is_numeric($id) || (int) $id <= 0) {
            if ($this->VariationID > 0) {
                $id = $this->VariationID;
            } else {
                return false;
            }
        }
        $rs = $GLOBALS['db']->Execute("Select * from " . $this->_tablename . " where variationid=" . (int) $id . " limit 1");
        if (\TAS\Core\DB::Count($rs) > 0) {
            $this->LoadFromRecordSet($rs);
        }
    }

    public static function Add($values = array())
    {
        if (! self::Validate($values, 'productvariation')) {
            return false;
        } elseif (isset($values['productcode']) && empty($values['productcode'])) {
            self::SetError("Product Code is required.", "10");
            return false;
        } else if (!Product::UniqueProductCode($values)) {
            self::SetError("Product Code should be unique.", "10");
        } else {
            if ($GLOBALS['db']->Insert($GLOBALS['Tables']['productvariation'], $values)) {
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
        
        $newProductID = $this->ProductID . '-' . $this->VariationID;
        
        if (! self::Validate($values, 'productvariation') || $this->VariationID == 0) {
            return false;
        } elseif (isset($values['productcode']) && empty($values['productcode'])) {
            self::SetError("Product Code is required", "10");
            return false;
        } else if (!Product::UniqueProductCode($values, $newProductID)) {
            self::SetError("Product Code should be unique.", "10");
            return false;
        } else {
            if ($GLOBALS['db']->Update($this->_tablename, $values, $this->VariationID, 'variationid')) {
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
        
        $delete = $GLOBALS['db']->Execute("Delete from " . $GLOBALS['Tables']['productvariation'] . " where variationid=" . (int) $id . " limit 1");
        $delete = $GLOBALS['db']->Execute("Delete from " . $GLOBALS['Tables']['productvariationoption'] . " where variationid=" . (int) $id . " ");
        return true;
    }

    public static function OptionTable()
    {
        $out = array();
        $rs = $GLOBALS['db']->Execute("Select * from " . $GLOBALS['Tables']['attributeoption'] . " where optionname != '' order by displayorder, optionname");
        
        if (\TAS\Core\DB::Count($rs) > 0) {
            while ($row = $GLOBALS['db']->Fetch($rs)) {
                $out[$row['attribute']][$row['optionid']] = array(
                    'name' => $row['optionname'],
                    'tag' => $row['tag']
                );
            }
        }
        return $out;
       
    }

    /**
     * Returns the HTML for option and their variation as Template.
     */
    public static function OptionHTML($d)
    {
        $HTML = '';
        $options = ProductVariation::OptionTable();
        if (isset($GLOBALS['attribute']) && count($GLOBALS['attribute']) > 0) {
            foreach ($GLOBALS['attribute'] as $attribute => $attrName) {
                if (isset($options[$attribute])) {
                    $newoption = array();
                    foreach ($options[$attribute] as $optid => $option) {
                        $newoption[$optid] = $option['name'];
                    }
                    $HTML .= '<div class="formfield clearfix">
				<label class="formlabel">' . $GLOBALS['attribute'][$attribute] . '</label><div class="forminputwrapper">
					<select name="options[' . $attribute . ']" id="option-' . $attribute . '" class="forminput variations">
						<option value="">Select</option>' . \TAS\Core\UI::ArrayToDropDown($newoption, (isset($d['value'][$attribute]) ? $d['value'][$attribute][0] : '')) . '</select>
				</div><div class="clear"></div>
			</div>';
                }
            }
        }
        return $HTML;
    }

    public static function GetFields($id = 0)
    {
        $fields = \TAS\Core\Entity::GetFieldsGeneric($GLOBALS['Tables']['productvariation']);
        
        $product = new ProductVariation();
        
        $fields['status']['type'] = 'checkbox';
        
        $fields['productid']['type'] = 'select';
        $fields['productid']['selecttype'] = 'query';
        $fields['productid']['query'] = "select *, concat(productname,' (', productcode , ')') as productnamecode  from " . $GLOBALS['Tables']['product'] . " where status=1 order by productname";
        $fields['productid']['dbID'] = 'productid';
        $fields['productid']['dbLabelField'] = 'productnamecode';
        $fields['productid']['label'] = 'Product';
        
        $fields['singleprice']['label'] = 'Price';
        $fields['productcode']['label'] = 'Product Code';
        $fields['productcode']['css'] = 'form-control unique-productcode';
        $fields['options']['type'] = 'cb';
        $fields['options']['function'] = array(
            '\Framework\ProductVariation',
            "OptionHTML"
        );
        $fields['options']['Field'] = 'options';
        $fields['options']['id'] = 'options';
        $fields['options']['DoLabel'] = false;
        $fields['options']['group'] = 'basic';
        $fields['options']['DoWrapper'] = false;
        
        $fields['productid']['displayorder'] = 1;
        $fields['options']['displayorder'] = 2;
        $fields['productcode']['displayorder'] = 3;
        $fields['singleprice']['displayorder'] = 6;
        $fields['status']['displayorder'] = 10;
        $fields['adddate']['displayorder'] = 11;
        $fields['editdate']['displayorder'] = 12;
        
        $fields['productid']['required'] = true;
        $fields['productcode']['required'] = true;
        $fields['singleprice']['required'] = true;
        
        if ($id > 0) {
            $product = new ProductVariation($id);
            $fields['productcode']['additionalattr'] = (($id > 0) ? 'data-rel="' . $product->ProductID . '-'.$product->VariationID.'"' : "");
            $a = $product->ObjectAsArray();
            foreach ($a as $i => $v) {
                if (isset($fields[strtolower($i)])) {
                    $fields[strtolower($i)]['value'] = $v;
                }
            }
            
            $rs = $GLOBALS['db']->Execute("Select * from " . $GLOBALS['Tables']['productvariationoption'] . " where variationid=" . (int) $id);
            if (\TAS\Core\DB::Count($rs) > 0) {
                while ($row = $GLOBALS['db']->Fetch($rs)) {
                    $fields['options']['value'][$row['attribute']][] = $row['optionid'];
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
        
        $fields['status']['value'] = $product->Status == 1 ? true : false;
        unset($fields['variationid']);
        return $fields;
    }


    /**
     * Return HTML for Filter.
     */
    public static function GetFilterHTMLForOption($catid = 0)
    {
        $options = ProductVariation::OptionTable();
        if ($catid > 0) {
            $optionids = $GLOBALS['db']->FirstColumnArray("Select distinct(pvo.optionid) from " . $GLOBALS['Tables']['productvariationoption'] . " pvo where pvo.variationid in 
	                    (Select variationid from " . $GLOBALS['Tables']['productvariation'] . " pv left join " . $GLOBALS['Tables']['product'] . " p  on pv.productid= p.productid 
                        left join " . $GLOBALS['Tables']['productcategory'] . " pc on p.productid=pc.productid  where ( pc.categoryid=" . $catid . " or pc.categoryid in 
                        (select categoryid from " . $GLOBALS['Tables']['category'] . " where parentid=" . $catid . ") ))");
            
            foreach ($options['size'] as $index => $vale) {
                if (! in_array($index, $optionids)) {
                    unset($options['size'][$index]);
                }
            }
            
            foreach ($options['colour'] as $index => $vale) {
                if (! in_array($index, $optionids)) {
                    unset($options['colour'][$index]);
                }
            }
        } else {
            $optionids = null;
        }
        
        $output = '';
        $combineFilter = array();
        $colors = array();
        $sizefilter = array();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['color'])) {
            $colors = array_filter(explode(",", $_POST['color']));
            $sizefilter = array_filter(explode(",", $_POST['size']));
        }
        
        if (count($options['size']) > 0) {
            $output .= '<div class="size-select-wrap">
                	<div class="sidebar-title"><h3>Select your size</h3></div>
                    	<div class="size-list">
                    	<ul>';
            foreach ($options['size'] as $id => $size) {
                $output .= '<li class="size-box"><a href="" data-filterid="' . $id . '" title="' . ($size['tag']) . '" class="sizefilter' . (in_array($id, $combineFilter) ? ' active' : '') . '" data-filter="' . ($size['tag']) . '">' . (trim($size['tag']) == '' ? $size['name'] : $size['tag']) . '</a></li>';
            }
            $output .= '</ul></div></div>';
        }
        
        if (count($options['size']) > 0 || count($options['colour']) > 0) {
            $output .= '<div><form method="post">
				<input type="hidden" name="color" value="' . implode(",", $colors) . '" id="colorvalues"/>
				<input type="hidden" name="size" value="' . implode(",", $sizefilter) . '" id="sizevalues"/>
				<button id="filterbutton" type="submit" data-color="' . implode(",", $colors) . '" data-size="' . implode(",", $sizefilter) . '" class="btnfilter button">Filter</button>
				<button id="clearfilter" class="btnfilter">Clear Filter</button></form></div>';
        }
        return $output;
    }
}
