<?php
namespace Framework;

class Category extends \TAS\Core\Entity
{

    public $CategoryID,$GUID, $CategoryName, $ParentID, $ShowInMenu, $DisplayOrder, $Breadcrumb;
    
    public $Status, $AddDate, $EditDate;

    public $ImageID;

    public function __construct($id = 0)
    {
        parent::__construct();
        $this->_tablename = $GLOBALS['Tables']['category'];
        $this->_isloaded = false;
        if (is_numeric($id) && $id > 0) {
            $this->Load($id);
        }
    }

    public function Load($id = 0)
    {
        if (! is_numeric($id) || (int) $id <= 0) {
            if ($this->CategoryID > 0) {
                $id = $this->CategoryID;
            } else {
                return false;
            }
        }
        $rs = $GLOBALS['db']->Execute("Select * from " . $this->_tablename . " where categoryid=" . (int) $id . " limit 1");
        if (\TAS\Core\DB::Count($rs) > 0) {
            $this->LoadFromRecordSet($rs);
            $rsImages = \TAS\Core\ImageFile::GetLinkerImage($this->CategoryID, 'category');
            if (count($rsImages) > 0) {
                $this->ImageID = array_keys($rsImages)[0];
            }
        }
    }

    public static function UniqueCat($d, $catid = 0)
    {
        if ($catid == 0) {
            $count = $GLOBALS['db']->ExecuteScalar("select count(*) from " . $GLOBALS['Tables']['category'] . " where categoryname='" . $d['categoryname'] . "'");
        } else {
            $count = $GLOBALS['db']->ExecuteScalar("select count(*) from " . $GLOBALS['Tables']['category'] . " where categoryname='" . $d['categoryname'] . "'
				and categoryid != '" . (int) $catid . "' ");
        }

        if ($count > 0) {
            return false;
        } else {
            return true;
        }
    }

    public static function Add($values = array())
    {
        if (! self::Validate($values, 'category')) {
            return false;
        } 
        else if (! \TAS\Core\Entity::InputValidate(self::GetFields(), $values)) {
            return false;
        } else if (! Category::UniqueCat($values)) {
            self::SetError("Please use unique Category Name", "10");
            return false;
        } 
        else {
            if ($GLOBALS['db']->Insert($GLOBALS['Tables']['category'], $values)) {
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
        } else if (! Category::UniqueCat($values, $this->CategoryID)) {
            self::SetError("Please use unique Category Name", "10");
            return false;
        } 
        else if (! self::Validate($values, 'category') || $this->CategoryID == 0) {
            return false;
        } else {
            return ($GLOBALS['db']->Update($this->_tablename, $values, $this->CategoryID, 'categoryid')) ? true : false;
        }
    }

    public static function Delete($id)
    {
        if (! is_numeric($id) || (int) $id <= 0) {
            return false;
        }
        $id = floor((int) $id);

        $delete = $GLOBALS['db']->Execute("Delete from " . $GLOBALS['Tables']['category'] . " where categoryid=" . (int) $id . " limit 1");
        $delete = $GLOBALS['db']->Execute("Delete from " . $GLOBALS['Tables']['productcategory'] . " where categoryid=" . (int) $id . "");
        return true;
    }

    public static function GetFields($id = 0)
    {
        $fields = \TAS\Core\Entity::GetFieldsGeneric($GLOBALS['Tables']['category']);

        $category = new Category();
        $fields['categoryname']['label'] = 'Category Name';

        $fields['status']['type'] = 'checkbox';
        $fields['showinmenu']['type'] = 'checkbox';
        $fields['displayorder']['label'] = 'Display Order ';
        $fields['displayorder']['shortnote'] = 'Least numeric number will come on top';
        $fields['showinmenu']['label'] = 'Show in Menu';
        $fields['showinmenu']['shortnote'] = 'tick to show this category in main menu. They will still be shown in Category page and filter everywhere else.';
        $fields['categoryname']['required'] = true;
        // Code change for feild name of Category

        $fields['parentid']['label'] = 'Parent Category';
        // Code change for feild name of Category

        $fields['parentid']['type'] = 'select';
        $fields['parentid']['selecttype'] = 'array';
        $select_field[] = '-- Select Category --';
        $fields['parentid']['arrayname'] = Category::GetCategoryTreeForDropDownCat();
        $fields['parentid']['arrayname'] = $select_field + $fields['parentid']['arrayname'];

        $fields['categoryname']['displayorder'] = 2;
        $fields['parentid']['displayorder'] = 1;
        $fields['status']['displayorder'] = 8;
        $fields['displayorder']['displayorder'] = 6;
        $fields['showinmenu']['displayorder'] = 7;
        $fields['adddate']['displayorder'] = 11;
        $fields['editdate']['displayorder'] = 10;

        if ($id > 0) {
            $category = new Category($id);
            $a = $category->ObjectAsArray();
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

        $fields['status']['value'] = (isset($category->Status) && $category->Status == 1) ? true : false;
        unset($fields['breadcrumb']);
        unset($fields['guid']);
        unset($fields['categoryid']);
        return $fields;
    }

    /**
     * *
     * Returns the an array with hierarchical data.
     * Incomplete function
     */
    public static function GetCategoryTree($formenu = false)
    {
        if ($formenu) {
            $rs = $GLOBALS['db']->Execute("Select * from " . $GLOBALS['Tables']['category'] . " where showinmenu =1 order by  parentid, categoryname");
        } else {
            $rs = $GLOBALS['db']->Execute("Select * from " . $GLOBALS['Tables']['category'] . " order by  parentid, categoryname");
        }
        $output = array();
        if (\TAS\Core\DB::Count($rs) > 0) {
            while ($row = $GLOBALS['db']->Fetch($rs)) {
                if ($row['parentid'] == 0) {
                    $output[$row['categoryid']] = array(
                        'Name' => htmlspecialchars_decode($row['categoryname']),
                        'child' => array()
                    );
                } else {
                    if (isset($output[$row['parentid']])) {
                        $output[$row['parentid']]['child'][$row['categoryid']] = array(
                            'Name' => htmlspecialchars_decode($row['categoryname']),
                            'child' => array()
                        );
                    }
                }
            }
        }

        return $output;
    }

    public static function GetCategoryTreeForDropDown()
    {
        $categories = Category::GetCategoryTree();
        $output = array();
        if (count($categories) > 0) {
            foreach ($categories as $catid => $catinfo) {
                $output[$catid] = $catinfo['Name'];
                if (is_array($catinfo['child']) && count($catinfo['child']) > 0) {
                    foreach ($catinfo['child'] as $subcatid => $subcatinfo) {
                        $output[$subcatid] = " - " . ucwords($subcatinfo['Name']);
                    }
                }
            }
        }

        return $output;
    }

    public static function GetCategoryTreeForDropDownCat()
    {
        $categories = Category::GetCategoryTree();
        $output = array();
        if (count($categories) > 0) {
            foreach ($categories as $catid => $catinfo) {
                $output[$catid] = $catinfo['Name'];
                /*
                 * if (is_array($catinfo['child']) && count($catinfo['child']) > 0) {
                 * foreach ($catinfo['child'] as $subcatid => $subcatinfo) {
                 * $output[$subcatid] = " - " . $subcatinfo['Name'];
                 * }
                 * }
                 */
            }
        }

        return $output;
    }

    public static function GetHeaderImage($catID)
    {
        $thisCategory = new Category($catID);

        $imageFile = new \TAS\Core\ImageFile();
        $imageFile->LinkerType = 'category';
        $images = $imageFile->GetImageOnLinker((int) $catID);

        $imagename = '';

        if ($images !== false && count($images) > 0) {
            foreach ($images as $image) {
                $imagename = $image['baseurl'] . $image['filename'];
            }
        }

        $output = '';
        if ($imagename) {
            $output = $imagename;
        }

        if ($output == "" && $thisCategory->ParentID != 0) {
            $output = self::GetHeaderImage($thisCategory->ParentID);
        }
        if ($thisCategory->ParentID == 0 && $output == "") {
            $output = $GLOBALS['AppConfig']['folderpath'] . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . "catimage/default.jpg";
        }
        return $output;
    }
}
