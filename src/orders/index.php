<?php
namespace Framework;
require './../configure.php';
require './../template.php';
$pageParse['PageTitle'] = 'Orders | '.$GLOBALS['AppConfig']['SiteName'];
function DisplayGrid()
{
    $queryoptions = \TAS\Core\Grid::DefaultQueryOptions();
    $queryoptions['basicquery'] = "select o.*,UPPER(concat(a.title , ' ', a.subtitle)) as name, a.email as email, a.phone as phone from ".$GLOBALS['Tables']['orders']." as o left join ".$GLOBALS['Tables']['address'].
    " as a on o.orderid = a.ownerid";
    
    $queryoptions['whereconditions'] = ' where a.addresstype="billing" and a.ownertype="order" and a.ownerid="'.$_SESSION['userid'].'" and o.userid="'.$_SESSION['userid'].'"';

    $queryoptions['pagingquery'] = "select count(*) from " . $GLOBALS['Tables']['orders'] . '';
    
    $options = \TAS\Core\Grid::DefaultOptions();
    $options['gridurl'] = $GLOBALS['AppConfig']['AdminURL'] . '/order/index.php';
    $options['gridid'] = 'orderid';
    $options['allowsorting'] = false;
    $options['allowpaging'] = true;
    $options['showtotalrecord'] = true;
    $options['allowselection'] = false;
    $options['norecordtext'] = 'No order found.';
    
    $options['fields'] = array(
        'orderid' => array(
            'type' => 'number',
            'name' => 'Order ID'
        ),
        'name' => array(
            'type' => 'string',
            'name' => 'Name'
        ),
        'email' => array(
            'type' => 'string',
            'name' => 'Email'
        ),
        'phone' => array(
            'type' => 'string',
            'name' => 'Phone'
        ),
        'ordertotal' => array(
            'type' => 'currency',
            'name' => 'OrderTotal'
        ),
        'orderstatus' => array(
            'type' => 'globalarray',
            'arrayname' =>'orderstatus',
            'name' => 'Order Status'
        ),
        
    );
    
    $queryoptions['defaultorderby'] = 'orderid';
    $queryoptions['defaultsortdirection'] = 'desc';
    $queryoptions['indexfield'] = 'orderid';
    $queryoptions['tablename'] = $GLOBALS['Tables']['orders'];
    
    $options['option']['orderview'] = array(
        'link' => $GLOBALS['AppConfig']['HomeURL'] . '/orders/view.php',
        'iconclass' => 'fa-eye',
        'tooltip' => 'Order detail view',
        'tagname' => '',
        'paramname' => 'orderid',
        'iconparent' => 'fa'
    );
    
    $grid = new \TAS\Core\Grid($options, $queryoptions);
    return $grid->Render();
}

//$pageParse['BreadCrumb'] = BreadCrumb('Booking', '', '');
$pageParse['Content'] = '
<section class="contentarea">
  <div class="container padding70">
    <div class="row dashboard-page">
        <div class="col-md-3">
            '.SideBarHeader().'
        </div>
        
        <div class="col-md-9 pl-lg-5">
            <div class="d-flex align-items-center justify-content-between">
            <h2 class="heading mb-0">Manage Orders</h2>
        </div><hr>
           '.DisplayGrid().'
    </div>
  </div>
</section>';
echo \TAS\Core\TemplateHandler::TemplateChooser('single');