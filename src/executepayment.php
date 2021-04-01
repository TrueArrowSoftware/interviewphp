<?php
namespace Framework;
require_once './configure.php';
\TAS\Core\Web::NoBrowserCache();
if (isset($_REQUEST['order']) && is_numeric($_REQUEST['order'])) {
    $orderID = (int) $_REQUEST['order'];
    $order = new Order($orderID);
    $ordersuccess = false;
    $transactionID = '';
    
    \TAS\Core\Log::AddEvent(array(
        'message' => 'Payment Execution IPN ',
        'order' => print_r($order, true),
        'post' => print_r($_POST, true),
        'get' => print_r($_GET, true),
        'session' => print_r($_SESSION, true),
        'isdebumode' => ($GLOBALS['AppConfig']['DebugMode'] ? 'yes' : 'no'),
    ), 'debug');
    
    if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'noamount') {
        $transactionID = 'NoAmount';
        $ordersuccess = true;
        unset($_SESSION['code']);
    } elseif (isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'securetrading') {
        /* @TO-DO
         * 
         * payment gateway apply as per requirement
         * 
         *  */
        
        $response = array();
        $transactionID = '';
        \TAS\Core\Log::AddEvent(array(
            'message' => 'Payment verification Data for securetrading',
            'paymentverification' => print_r($response, true),
        ), 'debug');
        
        
        $ordersuccess = true;
    }
    
    
    $orderTotal = $order->OrderTotal;
    
    if ($ordersuccess) {
        $d = array(
            'orderstatus' => 'paid',
            'paymentstatus' => 'paid',
        );
        
        $paymentDetails = array(
            'orderid' => $orderID,
            'paymentdate' => date('Y-m-d h:i:s'),
            'paymentmode' => 'securetrading',
            'transactionid' => $transactionID,
            'price' => $orderTotal,
            'status' => 1
        );
        
        $GLOBALS['db']->Insert('payment', $paymentDetails);
        
        OrderLog::Add(array(
            'orderid' => $orderID,
            'logmessage' => 'Payment Successfully received through securetrading with transition id :'.$transactionID.' and amount is '.$orderTotal,
            'eventtag' => 'order',
            'sourcetag' => 'online',
            'eventtime' => date('Y-m-d H:i:s'),
        ));
        
        
        $GLOBALS['db']->Update($GLOBALS['Tables']['orders'], $d, $orderID, 'orderid');
        $orderHelper = new OrderHelper($orderID);
        
        $clientData = array(
            'message' => $orderHelper->InvoiceForMail(),
            'orderid' => $order->OrderID,
        );
        
        
        // mail to customer
        \TAS\Core\Utility::DoEmail(3, $clientData, $order->Address['billing']->Email, $GLOBALS['AppConfig']['SenderEmail']);
        
        //mail to admin
        \TAS\Core\Utility::DoEmail(2, $clientData, $GLOBALS['AppConfig']['AdminMail'], $GLOBALS['AppConfig']['SenderEmail']);
        
        \TAS\Core\Web::Redirect($GLOBALS['AppConfig']['HomeURL'].'/paymentsuccess.php?orderid='.$orderID);
    } else {
        $GLOBALS['db']->Execute('Update '.$GLOBALS['Tables']['orders']." set orderstatus='failed',paymentstatus='failed' where orderid=".(int) $orderID);
        
        OrderLog::Add(array(
            'orderid' => $orderID,
            'logmessage' => 'Payment has been failed due to '.$response['responses'][0]['errormessage'].'. Order Amount :'.$GLOBALS['AppConfig']['Currency'].$orderTotal,
            'eventtag' => 'order',
            'sourcetag' => ' ',
            'eventtime' => date('Y-m-d H:i:s'),
        ));
        
        
        \TAS\Core\Log::AddEvent(array(
            'message' => 'Secure Trading  order payment failed. ',
            'securetrading_reply' => print_r($response, true),
            'order' => print_r($order, true),
        ), 'high');
        
        \TAS\Core\Web::Redirect($GLOBALS['AppConfig']['HomeURL'].'/order-fail');
    }
} else {
    \TAS\Core\Utility::Redirect('404');
}
