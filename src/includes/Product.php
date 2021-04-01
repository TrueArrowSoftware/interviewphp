<?php
namespace Framework;
/**
 * Product managable.
 */
class Product extends \TAS\Core\Entity
{
    
    public $ProductID, $ProductName, $ProductSlug, $Description, $ShortDescription, $BrandID, $SinglePrice;
    
    public  $ProductCode, $IsFeatured, $AddDate, $EditDate, $Status;
    
    private $Variations;
    
    public function __construct($id = 0)
    {
        parent::__construct();
        $this->_tablename = $GLOBALS['Tables']['product'];
        $this->_isloaded = false;
        if (is_numeric($id) && $id > 0) {
            $this->Load($id);
        } else if (is_array($id)) {
            $this->LoadFromRecordSet($id);
            $this->ProductID = (int) $id['productid'];
            $this->_isloaded = true;
        }
    }
    
    public function Load($id = 0)
    {
        if (! is_numeric($id) || (int) $id <= 0) {
            if ($this->ProductID > 0) {
                $id = $this->ProductID;
            } else {
                return false;
            }
        }
        $rs = $GLOBALS['db']->Execute("Select * from " . $this->_tablename . " where productid=" . (int) $id . " limit 1");
        if (\TAS\Core\DB::Count($rs) > 0) {
            $this->LoadFromRecordSet($rs);
            $this->_isloaded = true;
        } else {
            $this->_isloaded = false;
        }
        return $this->_isloaded;
    }
    
    public function LoadCode($code)
    {
        $rs = $GLOBALS['db']->ExecuteScalar("Select distinct(productid) as productid  from " . $GLOBALS['Tables']['product'] . " p	where productcode='" . $code . "'
				union
				select distinct(productid) as productid from " . $GLOBALS['Tables']['productvariation'] . " pv where productcode='" . $code . "'");
        
        if (is_bool($rs) && $rs==false) {
            return false;
        } else {
            
            return $this->Load((int)$rs);
        }
    }
    
    public function LoadVendorCode($code)
    {
        $rs = $GLOBALS['db']->Execute("Select distinct(productid) as productid  from " . $GLOBALS['Tables']['product'] . " p
				where lower(vendorcode)=lower('" . $code . "')
				union
				select distinct(productid) as productid from " . $GLOBALS['Tables']['productvariation'] . " pv where lower(vendorcode)=lower('" . $code . "')");
        
        if (\TAS\Core\DB::Count($rs) > 0) {
            $row = $GLOBALS['db']->Fetch($rs);
            $this->Load($row['productid']);
        } else {
            $this->_isloaded = false;
        }
    }
    
    /**
     * Get variation of product.
     */
    public function GetVariation()
    {
        $this->Variations = array();
        if ($this->IsLoaded() == true) {
            $rsVariation = $GLOBALS['db']->Execute("Select * from " . $GLOBALS['Tables']['productvariation'] . " where productid=" . $this->ProductID);
            
            if (\TAS\Core\DB::Count($rsVariation) > 0) {
                while ($rowVariation = $GLOBALS['db']->Fetch($rsVariation)) {
                    $this->Variations[$rowVariation['productcode']] = $rowVariation;
                }
            }
        }
        return $this->Variations;
    }
    
    /**
     * Get Variation.
     */
    public function PrepareVariation()
    {
        $variations = $this->GetVariation();
        $variationids = array();
        foreach ($variations as $code => $info) {
            $variationids[$code] = $info['variationid'];
        }
        
        $OptionName = array();
        $rsAttributeOption = $GLOBALS['db']->Execute("Select * from " . $GLOBALS['Tables']['attributeoption'] . " order by displayorder");
        if (\TAS\Core\DB::Count($rsAttributeOption) > 0) {
            while ($rowAttributeOption = $GLOBALS['db']->Fetch($rsAttributeOption)) {
                $OptionName[$rowAttributeOption['attribute']][$rowAttributeOption['optionid']] = array(
                    'Name' => $rowAttributeOption['optionname'],
                    'Tag' => $rowAttributeOption['tag'],
                    'DisplayOrder'=>$rowAttributeOption['displayorder']
                );
            }
        }
        
        $rsVariations = $GLOBALS['db']->Execute("Select * from " . $GLOBALS['Tables']['productvariationoption'] . " pvo where variationid in (" . implode(",", $variationids) . ") order by attribute");
        if (\TAS\Core\DB::Count($rsVariations) > 0) {
            while ($rowVariation = $GLOBALS['db']->Fetch($rsVariations)) {
                $vcode = array_search($rowVariation['variationid'], $variationids);
                if (! isset($this->Variations[$vcode]['Options'])) {
                    $this->Variations[$vcode]['Options'] = array();
                }
                $this->Variations[$vcode]['Options'][$rowVariation['variationid'] . "-" . $rowVariation['optionid']] = array(
                    'OptionID' => $rowVariation['optionid'],
                    'Attribute' => $rowVariation['attribute'],
                    'OptionName' => $OptionName[$rowVariation['attribute']][$rowVariation['optionid']]['Name'],
                    'Tag' => $OptionName[$rowVariation['attribute']][$rowVariation['optionid']]['Tag'],
                    'DisplayOrder' => $OptionName[$rowVariation['attribute']][$rowVariation['optionid']]['DisplayOrder']
                );
            }
        }
        foreach ($this->Variations as $code => $info) {
            $t = array();
            if (isset($info['Options']) && is_array($info['Options']) && count($info['Options']) > 0) {
                foreach ($info['Options'] as $index => $optioninfo) {
                    $t[] = $GLOBALS['attribute'][$optioninfo['Attribute']] . ': ' . $optioninfo['OptionName'];
                }
            }
            $this->Variations[$code]['OptionName'] = implode(", ", $t);
        }
        return $this->Variations;
    }
    
    /**
     * Update Price on Vendor.
     *
     * @param string $vendorCode
     * @param float $bulkprice
     * @param float $singleprice
     * @return boolean
     */
   
    
    /**
     * Add a new Product in database
     *
     * @param array $values
     * @return boolean|int
     */
    public static function Add($values = array())
    {
        if (! self::Validate($values, 'product')) {
            
            return false;
        } else if (! Product::UniqueProductCode($values)) {
            self::SetError("Please use unique Product Code", "10");
            return false;
        } else {
            
            if ($GLOBALS['db']->Insert($GLOBALS['Tables']['product'], $values)) {
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
        
        if (! self::Validate($values, 'product') || $this->ProductID == 0) {
            
            return false;
        } else if (!Product::UniqueProductCode($values, $this->ProductID)) {
            self::SetError("Please use unique Product Code", "10");
            return false;
        } else {
            
            if ($GLOBALS['db']->Update($this->_tablename, $values, $this->ProductID, 'productid')) {
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
        
        $delete = $GLOBALS['db']->Execute("Delete from " . $GLOBALS['Tables']['product'] . " where productid=" . (int) $id . " limit 1");
        self::DeleteProductVariation($id);
        self::DeleteProductCategory($id);
        $imageFile = new \TAS\Core\ImageFile();
        $imageFile->LinkerType = 'product';
        $imageFile->DeleteImageOnLinker($id);
        return true;
    }
    
    public static function GetFields($id = 0)
    {
        $fields = \TAS\Core\Entity::GetFieldsGeneric($GLOBALS['Tables']['product']);
        
        $product = new Product();
        $fields['productname']['label'] = 'Product Name';
        $fields['status']['type'] = 'checkbox';
        $fields['shortdescription']['label'] = 'Short Description';
        $fields['brandid']['label'] = 'Brand';
        $fields['singleprice']['label'] = 'Price';
        $fields['productcode']['label'] = 'Product Code';
        $fields['isfeatured']['label'] = 'Is featured';
        $fields['isfeatured']['type'] = 'checkbox';
        $fields['shortdescription']['type'] = 'text';
        $fields['shortdescription']['shortnote'] = '(limit 250 character)';
        $fields['productcode']['css'] = 'form-control unique-productcode';
        
        $fields['productname']['required'] = true;
        $fields['productcode']['required'] = true;
        $fields['singleprice']['required'] = true;
        
        $fields['brandid']['type'] = 'select';
        $fields['brandid']['selecttype'] = 'query';
        $fields['brandid']['query'] = 'Select * from ' . $GLOBALS['Tables']['company'];
        $fields['brandid']['dbLabelField'] = 'companyname';
        $fields['brandid']['dbID'] = 'companyid';
        $fields['brandid']['showSelect'] = true;
        $fields['brandid']['required'] = true;
        
        // Category selector
        $fields['categories']['type'] = 'select';
        $fields['categories']['multiple'] = true;
        $fields['categories']['required'] = true;
        $fields['categories']['selecttype'] = 'array';
        $fields['categories']['arrayname'] = Category::GetCategoryTreeForDropDown();
        // $fields['categories']['arrayname'] = array();
        $fields['categories']['additionalattr'] = 'placeholder="Select Category"';
        $fields['categories']['showSelect'] = false;
        $fields['categories']['label'] = 'Category';
        $fields['categories']['id'] = 'categories';
        $fields['categories']['Field'] = 'categories';
        $fields['categories']['group'] = 'basic';
        
        /* $fields['producttype']['displayorder'] = 1; */
        $fields['categories']['displayorder'] = 2;
        $fields['productname']['displayorder'] = 3;
        $fields['productcode']['displayorder'] = 4;
        $fields['brandid']['displayorder'] = 5;
        $fields['singleprice']['displayorder'] = 6;
        $fields['shortdescription']['displayorder'] = 7;
        $fields['description']['displayorder'] = 8;
        $fields['isfeatured']['displayorder'] = 9;
        $fields['status']['displayorder'] = 10;
        $fields['adddate']['displayorder'] = 11;
        $fields['editdate']['displayorder'] = 12;
        
        if ($id > 0) {
            $product = new Product($id);
            $fields['productcode']['additionalattr'] = (($id > 0) ? 'data-rel="' . $product->ProductID . '"' : "");
            $a = $product->ObjectAsArray();
            if($a){
                foreach ($a as $i => $v) {
                    if (isset($fields[strtolower($i)])) {
                        $fields[strtolower($i)]['value'] = $v;
                    }
                }
            }
            
            $row = $GLOBALS['db']->FirstColumnArray("Select categoryid from " . $GLOBALS['Tables']['productcategory'] . " where productid=" . $id);
            
            $fields['categories']['value'] = $row;
            $fields['adddate']['type'] = 'readonly';
            $fields['editdate']['type'] = 'readonly';
            $fields['adddate']['label'] = 'Add Date';
            $fields['editdate']['label'] = 'Edit Date';
            
        } else {
            unset($fields['adddate']);
            unset($fields['editdate']);
        }
        
        unset($fields['productslug']);
        unset($fields['productid']);
        unset($fields['adddate']);
        unset($fields['editdate']);
        return $fields;
    }
    
    /**
     * Test is given productname and email is unique in system
     *
     * @param array $d
     *            An Array with key as productname and email.
     */
    public static function UniqueProductCode($d, $productid = 0)
    {
        if ($productid == 0) {
            $count = $GLOBALS['db']->ExecuteScalar("select count(*) from " . $GLOBALS['Tables']['productlist'] . " where lower(productcode)='" . strtolower($d['productcode']) . "'");
        } else {
            $count = $GLOBALS['db']->ExecuteScalar("select count(*) from " . $GLOBALS['Tables']['productlist'] . " where lower(productcode)='" . strtolower($d['productcode']) . "'
				and productid != '" .$productid . "' ");
        }
        return ($count > 0) ? false : true;
    }
    
    /**
     * for delete Product variation
     */
    
    public static function DeleteProductVariation($id) {
        $Variations = array();
        if (! is_numeric($id) || (int) $id <= 0) {
            return false;
        }
        $id = floor((int) $id);
        
        $rsVariation = $GLOBALS['db']->Execute("Select * from " . $GLOBALS['Tables']['productvariation'] . " where productid=" . (int) $id . " ");
        if (\TAS\Core\DB::Count($rsVariation) > 0) {
            while ($rowVariation = $GLOBALS['db']->Fetch($rsVariation)) {
                $Variations[] = $rowVariation['variationid'];
            }
        }
        
        $productvariationdelete = $GLOBALS['db']->Execute("Delete from " . $GLOBALS['Tables']['productvariation'] . " where productid=" . (int) $id . " ");
        foreach($Variations as $val){
            $GLOBALS['db']->Execute("Delete from " . $GLOBALS['Tables']['productvariationoption'] . " where variationid=" . (int) $val);
        }
        
        return true;
    }
    
    /**
     * Delete Product Category 
     * @param unknown $id
     * @return boolean
     */
    public static function DeleteProductCategory($id) {
        
        if (! is_numeric($id) || (int) $id <= 0) {
            return false;
        }
        $id = floor((int) $id);
        
        $productcategorydelete = $GLOBALS['db']->Execute("Delete from " . $GLOBALS['Tables']['productcategory'] . " where productid=" . (int) $id . " ");
        return true;
    }
    
}
