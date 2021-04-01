<?php
namespace Framework;

class Search extends \TAS\Core\Entity
{
    /* Product search final query with search filter */
    public static function GetProductSearchQuery($param)
    {
        $filter = array();
        if (isset($param['productname']) && $param['productname']!='') {
            $filter[] = ' p.productname like "%' . ($param['productname']) . '%"';
        }
        
        if (isset($param['guid']) && $param['guid']!='') {
            if($param['guid']!='all')
            {
                $categoryID = self::GetCategoryID($param['guid']);
                $checkCategory = self::CheckCategory($categoryID);
                if(empty($checkCategory))
                {
                    $filter[] = ' pc.categoryid="' . $categoryID . '"';
                }
                else 
                {
                    $filter [] = " categoryid IN (".implode(',',$checkCategory).")";
                }
            }
        }
        
        
        if(isset($param['minprice']) && isset($param['maxprice']) && $param['minprice']!='' && $param['maxprice'] !='')
        {
            if($param['minprice'] > 0 || $param['maxprice']!=100)
            {
                $filter[]= ' p.price BETWEEN '.$param['minprice'].' AND '.$param['maxprice'].'';
            }
        }

        $orderby = ' p.mainid desc';
        if(isset($param['orderby']) && $param['orderby']!='')
        {
            switch($param['orderby']){
                case 'lowtohigh' : $orderby = ' p.price asc'; break;
                case 'hightolow' : $orderby = ' p.price desc'; break;
            }
        }
        
       return 'select p.* from ' . $GLOBALS['Tables']['productlist'] . ' as p where p.mainid IN (select distinct(p.mainid) from ' . $GLOBALS['Tables']['productlist'] . ' as p left join ' . $GLOBALS['Tables']['productcategory'] . ' as pc on p.mainid=pc.productid where p.status="1" and p.type="mainproduct" ' . (! empty($filter) ? 'and' . implode(' and ', $filter) : '') . ' order by '.$orderby . ') and p.status="1" and p.type="mainproduct" order by '.$orderby.'';
    }
    
    /* Product search final html with search filter */
    public static function GetProductSearchHTML($query)
    {
        $product = $GLOBALS['db']->Execute($query);
        $productHTML = '';
        if(\TAS\Core\DB::Count($product) > 0)
        {
            $imageFile = new \TAS\Core\ImageFile();
            $imageFile->ThumbnailSize = $GLOBALS['ThumbnailSize'];
            $imageFile->LinkerType = 'product';
            $imgURL = $GLOBALS['AppConfig']['HomeURL'].'/theme/images/noimage.png';
            while($row = $GLOBALS['db']->Fetch($product))
            {
                $images = $imageFile->GetImageOnLinker($row['productid'],true,'displayorder asc,imageid asc');
                if($images['url']!='')
                {
                    $imgURL = $images['url'];
                }
                $productHTML .='<div class="col-xl-3 col-md-3 col-sm-4 py-3">
                            <div class="card card--product">
                                <a href="'.$GLOBALS['AppConfig']['HomeURL'].'/productdetail.php?product='.$row['productslug'].'">
                                    <div class="product-image" style="background: url('.$imgURL.') no-repeat;background-size: cover; background-position: center;height: 150px;">
                                    </div>
                                    <h4 class="heading">
                                        '.$row['productname'].'<br><br>
                                        Price : '.$GLOBALS['AppConfig']['Currency'].number_format($row['price'],2).'
                                    </h4>
                                    
                                </a>
                                <a href="'.$GLOBALS['AppConfig']['HomeURL'].'/productdetail.php?product='.$row['productslug'].'" class="detail-link">View</a>
                            </div>
                        </div>';
            }
        }
        else 
        {
            $productHTML .='<div class="col-md-12">No search product found.</div>';
        }
        
        return $productHTML;
    }

    /* get category id by guid */
    public static function GetCategoryID($guid)
    {
        return $GLOBALS['db']->ExecuteScalar('select categoryid from '.$GLOBALS['Tables']['category'].' where guid="'.$guid.'"');
    }
    
    /* check if category is parent then fetch all child category ids */
    public static function CheckCategory($categoryid)
    {
        $categoryChild = $GLOBALS['db']->Execute('select categoryid from '.$GLOBALS['Tables']['category'].' where parentid="'.$categoryid.'" and status="1"');
        $categoryID = array();
        if(\TAS\Core\DB::Count($categoryChild) > 0)
        {
            $categoryID[] = $categoryid;
            while($row = $GLOBALS['db']->Fetch($categoryChild))
            {
                $categoryID[] = $row['categoryid'];
            }
        }
       
        return $categoryID;
    }
    
    /* Category HTML */
    public static function CategoryHTML()
    {
        $categoryHTML = '';
        $category = $GLOBALS['db']->Execute('select * from '.$GLOBALS['Tables']['category'].' where parentid="0" and status="1" order by displayorder asc');
        if(\TAS\Core\DB::Count($category) > 0)
        {
            $categoryHTML.='<li><a href="{HomeURL}/productsearch.php?category=all" class="'.(isset($_GET['category']) && $_GET['category']=='all'?'active':'').'">All Categories</a></li>';
            while($rowParent = $GLOBALS['db']->Fetch($category))
            {
                $categoryHTML.='<li><a href="{HomeURL}/productsearch.php?category='.$rowParent['guid'].'" class="'.(isset($_GET['category']) && $_GET['category']==$rowParent['guid']?'active':'').'">'.ucwords($rowParent['categoryname']).'</a></li>';
                $catChild = $GLOBALS['db']->Execute('select * from '.$GLOBALS['Tables']['category'].' where parentid="'.$rowParent['categoryid'].'" and status="1" order by displayorder asc');
                if(\TAS\Core\DB::Count($catChild) > 0)
                {
                    while($rowChild = $GLOBALS['db']->Fetch($catChild))
                    {
                        $categoryHTML.='<li class="pl-3"><a href="{HomeURL}/productsearch.php?category='.$rowChild['guid'].'" class="'.(isset($_GET['category']) && $_GET['category']==$rowChild['guid']?'active':'').'">'.ucwords($rowChild['categoryname']).'</a></li>';
                    }
                    
                }
            }
        }
        
        return $categoryHTML;
    }
    
    /* get product id by product slug */
    
    public static function GetProductID($product)
    {
        return $GLOBALS['db']->ExecuteScalar('select productid from '.$GLOBALS['Tables']['product'].' where productslug="'.$product.'"');
    }
    
    /* product variation dropdown */
    public static function CheckProductVariation($productID)
    {
        $productDetail = new Product($productID);
        $variation = $productDetail->GetVariation();
        $variationHTML = '';
        if(!empty($variation))
        {
            $count =1;
            foreach($variation as $variations)
            {
                if($variations['status']=='1')
                {
                    $getVariation = $GLOBALS['db']->Execute('select ao.*,e.value as value from '.$GLOBALS['Tables']['productvariationoption'].' as pvo left join '.$GLOBALS['Tables']['attributeoption'].' as ao on pvo.optionid=ao.optionid left join '.$GLOBALS['Tables']['enumeration'].' as e on pvo.attribute=e.ekey and e.type="attribute" where pvo.variationid="'.$variations['variationid'].'"');
                    if(\TAS\Core\DB::Count($getVariation) > 0)
                    {
                        if($count=='1'){
                            $variationHTML .='<select name="variationid" class="form-control variationid" data-variation="yes">
                                          <option value="" data-productcode="'.$productDetail->ProductCode.'" data-price="'.number_format($productDetail->SinglePrice,2).'">Select</option>';
                        }
                        $newCount = 1;
                        $variationVal = '';
                        while($rowVariation = $GLOBALS['db']->Fetch($getVariation))
                        {
                            if($newCount=='1')
                            {
                                $variationVal .= $rowVariation['value'].':'.$rowVariation['optionname'];
                            }
                            elseif($newCount > 1)
                            {
                                $variationVal .= ', '.$rowVariation['value'].':'.$rowVariation['optionname'];
                            }
                            $newCount++;
                        }
                        $variationHTML .=  '<option value="'.$variations['variationid'].'" data-productcode="'.$variations['productcode'].'" data-price="'.number_format($variations['singleprice'],2).'">'.$variationVal.'</option>';
                        $count++;
                    }
                }
            }
            
            if($variationHTML!='')
            {
                $variationHTML .='</select>';
            }
        }
        return $variationHTML;
    }
}