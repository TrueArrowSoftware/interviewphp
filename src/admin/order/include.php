<?php
function DisplayGrid() {
    $queryoptions = \TAS\Core\Grid::DefaultQueryOptions();
    $queryoptions['basicquery'] = "select o.*,UPPER(concat(a.title , ' ', a.subtitle)) as name, a.email as email, a.phone as phone from ".$GLOBALS['Tables']['orders']." as o left join ".$GLOBALS['Tables']['address'].
    " as a on o.orderid = a.ownerid";

     $filterOptions = array();
    $filter = array();
    
    if (isset($_COOKIE['admin_order_filter']) && $_SERVER['REQUEST_METHOD'] == 'GET') {
        $filterOptions = json_decode(stripslashes($_COOKIE['admin_order_filter']), true);
    } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $filterOptions = $_POST;
    }
    if (isset($filterOptions['orderid']) && ! empty($filterOptions['orderid'])) {
        $filter[] = " o.orderid= '" . \TAS\Core\DataFormat::DoSecure($filterOptions['orderid']) . "'";
    }
    if (isset($filterOptions['email']) && ! empty($filterOptions['email'])) {
        $filter[] = " a.email like '%" . \TAS\Core\DataFormat::DoSecure($filterOptions['email']) . "%'";
    }
    if (isset($filterOptions['firstname']) && ! empty($filterOptions['firstname'])) {
        $filter[] = " a.title like '%" . \TAS\Core\DataFormat::DoSecure($filterOptions['firstname']) . "%'";
    }
    if (isset($filterOptions['lastname']) && ! empty($filterOptions['lastname'])) {
        $filter[] = " a.subtitle like '%" . \TAS\Core\DataFormat::DoSecure($filterOptions['lastname']) . "%'";
    }
    if (isset($filterOptions['phone']) && ! empty($filterOptions['phone'])) {
        $filter[] = " a.phone like '%" . \TAS\Core\DataFormat::DoSecure($filterOptions['phone']) . "%'";
    }
    if (isset($filterOptions['orderstatus']) && ! empty($filterOptions['orderstatus'])) {
        $filter[] = " o.orderstatus like '%" . \TAS\Core\DataFormat::DoSecure($filterOptions['orderstatus']) . "%'";
    }

    $queryoptions['whereconditions'] = ' where a.addresstype="billing" and a.ownertype="order"';
    if (count($filter) > 0) {
        $queryoptions['whereconditions'] .= ' and '.implode(' and ', $filter).' ';
    }
    
    
    $_COOKIE['admin_order_filter'] = json_encode($filterOptions);
    setcookie('admin_order_filter', json_encode($filterOptions), (time() + 25292000));

   
    $queryoptions['pagingquery'] = "select count(*) from " . $GLOBALS['Tables']['orders'] . '';
    $options = \TAS\Core\Grid::DefaultOptions();
    $options['gridurl'] = $GLOBALS['AppConfig']['AdminURL'] . '/order/index.php';
    $options['gridid'] = 'orderid';
    $options['allowsorting'] = false;
    $options['allowpaging'] = true;
    $options['showtotalrecord'] = true;
    $options['allowselection'] = false;

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
        'link' => $GLOBALS['AppConfig']['AdminURL'] . '/order/view.php',
        'iconclass' => 'fa-eye',
        'tooltip' => 'View Order Detail',
        'tagname' => '',
        'paramname' => 'orderid',
        'iconparent' => 'fa'
    );

    
    $options['option']['order'] = array(
        'link' => $GLOBALS['AppConfig']['AdminURL'] . '/order/edit.php',
        'iconclass' => 'fa-edit',
        'tooltip' => 'Edit Order',
        'tagname' => 'colorboxpopup',
        'paramname' => 'orderid',
        'iconparent' => 'fa'
    );    
   
    $grid = new \TAS\Core\Grid($options, $queryoptions);
    return $grid->Render();
}
