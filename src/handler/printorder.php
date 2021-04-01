<?php
namespace Framework ;
require("../configure.php");
 if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      
       $orderid = \TAS\Core\DataFormat::DoSecure($_GET['orderid']);
       $order = new OrderHelper($orderid);
       $pageParse['Content'] = $order->InvoiceForMail();
       $pageParse['MetaExtra'] ='';
       $pageParse['FooterInclusion'] ='';
 }
 
 echo \TAS\Core\TemplateHandler::TemplateChooser("printorder");