<?php
namespace Framework;
class OrderHelper extends Order
{
    /**
     * Initaite the Order Helper
     */
    public function __construct($obj = null)
    {
        parent::__construct($obj);
        if ($obj != null && is_numeric($obj) && (int) $obj > 0) {
            $this->OrderID = (int) $obj;
            $this->Load((int) $obj);
        }
    }
    
    
    public function Invoice()
    {
        
        $country = new Country($this->Address['billing']->Country);
        $country2 = new Country($this->Address['shipping']->Country);
        
        $HTML = '';
        $HTML .= '
            <div class = "table-responsive">
        	   <table class="orderviewtable table table-bordered" cellpadding="0" cellspacing="0">
        	        <tr>
        	       	     <td  class="order_invoiceno"><b>Order #</b> '.$this->OrderID.'</td>
        		         <td  colspan="3" style="text-align:right"><b>Date #</b> '.\TAS\Core\DataFormat::DBToDateTimeFormat($this->OrderDate).'</td>
        	        </tr>
                    <tr>
               <td><b>Customer Details (Billing) </b></td>
                <td colspan="3"><b>Customer Details (Shipping)</b></td>
            </tr>
            <tr>
               <td>' . ucfirst($this->Address['billing']->Title) . ' ' . ucfirst($this->Address['billing']->SubTitle) . '<br>
                ' . $this->Address['billing']->Email . '<br>
                ' . $this->Address['billing']->Phone . '<br>
                ' . $this->Address['billing']->Address1 . '<br>
                ' . (isset($this->Address['billing']->Address2) && $this->Address['billing']->Address2!=''?$this->Address['billing']->Address2.'<br>':'').$this->Address['billing']->City . '' .($this->Address['billing']->Zipcode!=''?'-'.$this->Address['billing']->Zipcode.'':'')  . '<br>' . $country->CountryName . '<br>';
        $HTML .= '<br>
               </td>';
        if ($this->Address['shipping']->Address1 == '') {
            $this->Address['shipping'] = $this->Address['billing'];
        }
        if ($this->Address['shipping']->Address1) {
            $HTML .= '<td colspan="3">' . ucfirst($this->Address['shipping']->Title) . ' ' . ucfirst($this->Address['shipping']->SubTitle) . '<br>
                    ' . $this->Address['shipping']->Email . '<br>
                    ' . $this->Address['shipping']->Phone . '<br>
                    ' . $this->Address['shipping']->Address1 . '<br>' . (isset($this->Address['shipping']->Address2) && $this->Address['shipping']->Address2!=''?$this->Address['shipping']->Address2.'<br>':'').$this->Address['shipping']->City . '' .($this->Address['billing']->Zipcode!=''?'-'.$this->Address['billing']->Zipcode.'':''). '<br>' . $country2->CountryName . '<br>';
            $HTML .= '<br>
                </td>';
        } else {
            $HTML .= '<td colspan="3">No Details</td>';
        }
        
        $HTML .= '</tr>
            <tr>
		      <th scope="col" class="fontbold">Product Name</th>
              <th scope="col" class="fontbold">Amount</th>
              <th scope="col" class="fontbold">Quantity</th>
              <th scope="col" class="fontbold">Total Amount</th>';
        
        $i = 1;
        $total = 0;
        foreach ($this->Products as $value) {
            $extrainfo = '';
            if($value['optiontag']!='')
            {
                $extra = json_decode($value['optiontag'],true);
                if(isset($extra['variationcode']))
                {
                    $extrainfo = $extra['variationcode'];
                }
            }
            
            $HTML .= '<tr>
        		<td>
                    <span class="product">'.$i.'. '.ucwords($value['productname']).'('.$value['productcode'].')</span>
                    <p>'.$extrainfo.'</p>
                </td>
                <td>'.$GLOBALS['AppConfig']['Currency'].number_format(($value['itemprice']), 2).'</td>
                <td>'.$value['quantity'].'</td>
                <td>'.$GLOBALS['AppConfig']['Currency'].number_format(($value['itemtotal']), 2).'<br>
               </tr>';
            $total += $value['itemtotal'];
            
            ++$i;
        }
        
        $HTML .= '
            <tr>
                <td colspan="3" class="text-right"><b>Total : </b></td>
                <td><b>'.$GLOBALS['AppConfig']['Currency'].''.number_format(($this->Total), 2).'</b></td>
            </tr>';
        
        if($this->Tax > 0)
        {
            $HTML .= '
            <tr>
                <td colspan="3" class="text-right"><b>Vat('.$this->TaxP.'%) : </b></td>
                <td><b>'.$GLOBALS['AppConfig']['Currency'].''.number_format(($this->Tax), 2).'</b></td>
            </tr>';
        }
        
        if($this->ShippingPrice > 0)
        {
            $HTML .= '
            <tr>
                <td colspan="3" class="text-right"><b>Delivery Fee : </b></td>
                <td><b>'.$GLOBALS['AppConfig']['Currency'].''.number_format(($this->ShippingPrice), 2).'</b></td>
            </tr>';
        }
        
        $HTML .= '
            <tr>
                <td colspan="3" class="text-right"><b>Grand Total : </b></td>
                <td><b>'.$GLOBALS['AppConfig']['Currency'].'<span class="ordergrandtotal">'.number_format(($this->OrderTotal), 2).'</span></b></td>
            </tr>
        </table>';
        
        
        return $HTML;
    }
    
    public function InvoiceForMail()
    {
        $country = new Country($this->Address['billing']->Country);
        $country2 = new Country($this->Address['shipping']->Country);
        
        $HTML = '';
        $HTML = '
        	<table style="width:100%; border: 1px solid; border-collapse: collapse;">
        	<tr style="border: 1px solid;height:50px;background:#ddd;">
        		<td style="padding: 15px;"><b>Order  #</b> '.$this->OrderID.'</td>
        		<td colspan="3"  style="padding: 15px;text-align:right"><b>Date #</b> '.\TAS\Core\DataFormat::DBToDateTimeFormat($this->OrderDate).'</td>
        	</tr>
            <tr style="border: 1px solid; height:40px;">
               <td style="border: 1px solid; padding:15px;"><b>Customer Details (Billing) </b></td>
                <td colspan="3" style=" padding:15px;"><b>Customer Details (Shipping)</b></td>
            </tr>
            <tr  style="border: 1px solid;">
               <td colspan="1"  style="border: 1px solid; padding:15px;">' . ucfirst($this->Address['billing']->Title) . ' ' . ucfirst($this->Address['billing']->SubTitle) . '<br>
               ' . $this->Address['billing']->Email . '<br>
                ' . $this->Address['billing']->Phone . '<br>
                ' . $this->Address['billing']->Address1 . '<br>' . (isset($this->Address['billing']->Address2) && $this->Address['billing']->Address2!=''?$this->Address['billing']->Address2.'<br>':'').$this->Address['billing']->City . '' . ($this->Address['billing']->Zipcode!=''?'-'.$this->Address['billing']->Zipcode.'':'')  . '<br>' . $country->CountryName . '<br>';
        $HTML .= '<br>
               </td>';
        if ($this->Address['shipping']->Address1 == '') {
            $this->Address['shipping'] = $this->Address['billing'];
        }
        if ($this->Address['shipping']->Address1) {
            $HTML .= '<td colspan="3" style="padding:15px;">' . ucfirst($this->Address['shipping']->Title) . ' ' . ucfirst($this->Address['shipping']->SubTitle) . '<br>
                    ' . $this->Address['shipping']->Email . '<br>
                    ' . $this->Address['shipping']->Phone . '<br>
                  ' . $this->Address['shipping']->Address1 . '<br>' . (isset($this->Address['shipping']->Address2) && $this->Address['shipping']->Address2!=''?$this->Address['shipping']->Address2.'<br>':'').$this->Address['shipping']->City . '' . ($this->Address['billing']->Zipcode!=''?'-'.$this->Address['billing']->Zipcode.'':'')  . '<br>' . $country2->CountryName . '<br>';
            $HTML .= '<br>
                </td>';
        } else {
            $HTML .= '<td colspan="3" valign="top" style="padding:15px;">No Details</td>';
        }
        
        $HTML .= '</tr>
            <tr  style="border: 1px solid;">
                <td  style="border: 1px solid; padding:15px; width:56%;"><b>Products Name</b></td>
                <td  style="border: 1px solid; padding:15px;"><b>Amount</b></td>
                <td  style="border: 1px solid;padding:15px;"><b>Quantity</b></td>
                <td  style="border: 1px solid; padding:15px;"><b>Total Amount</b></td>
            </tr>';
        
        $i = 1;
        $total = 0;
        foreach ($this->Products as $value) {
            $extrainfo = '';
            if($value['optiontag']!='')
            {
                $extra = json_decode($value['optiontag'],true);
                if(isset($extra['variationcode']))
                {
                    $extrainfo = $extra['variationcode'];
                }
            }
            
            $HTML .= '<tr  style="border: 1px solid;">
        		<td style="padding:15px;">
                    <span class="product">'.$i.'. '.ucwords($value['productname']).'('.$value['productcode'].')</span>
                    <p style="margin-left:12px;">'.$extrainfo.'</p>
                </td>
                        
        		<td  style="border: 1px solid;padding:15px;">'.$GLOBALS['AppConfig']['Currency'].number_format(($value['itemprice']), 2).'</td>
                <td  style="border: 1px solid;padding:15px;">'.$value['quantity'].'</td>
                <td  style="border: 1px solid;padding:15px;">'.$GLOBALS['AppConfig']['Currency'].number_format(($value['itemtotal']), 2).'<br>
               </tr>';
            $total += $value['itemtotal'];
            
            ++$i;
        }
        
        $HTML .= '
            <tr>
                <td colspan="3" style="border-bottom: 1px solid;text-align:right"><b>Total : </b></td>
                <td style="padding:15px;border-bottom: 1px solid;"><b>'.$GLOBALS['AppConfig']['Currency'].''.number_format(($this->Total), 2).'</b></td>
            </tr>';
        
        if($this->Tax > 0)
        {
            $HTML .= '
            <tr>
                <td colspan="3" style="border-bottom: 1px solid;text-align:right"><b>Vat('.$this->TaxP.'%) : </b></td>
                <td style="padding:15px;border-bottom: 1px solid;"><b>'.$GLOBALS['AppConfig']['Currency'].''.number_format(($this->Tax), 2).'</b></td>
            </tr>';
        }
        
        if($this->ShippingPrice > 0)
        {
            $HTML .= '
            <tr>
                <td colspan="3" style="border-bottom: 1px solid;text-align:right"><b>Delivery Fee : </b></td>
                <td style="padding:15px;border-bottom: 1px solid;"><b>'.$GLOBALS['AppConfig']['Currency'].''.number_format(($this->ShippingPrice), 2).'</b></td>
            </tr>';
        }
        
        $HTML .= '
            <tr>
                <td colspan="3" style="text-align:right"><b>Grand Total : </b></td>
                <td style="padding:15px;"><b>'.$GLOBALS['AppConfig']['Currency'].''.number_format(($this->OrderTotal), 2).'</b></td>
            </tr>
        </table>';
        
        return $HTML;
    }
    
    
    public function OrderPaymentHistory()
    {
        $HTML ='<h3>Payment History</h3>';
        $result = $GLOBALS['db']->Execute("SELECT * FROM " . $GLOBALS['Tables']['payment'] . " where orderid = '" . $this->OrderID . "' order by paymentid desc");
        if (\TAS\Core\DB::Count($result) > 0) {
            while ($row = $GLOBALS['db']->Fetch($result)) {
                $userDetails = new User($row['orderid']);
                $HTML .= "<p class='adminorderlogfont'>" . $row['paymentdate'] . ' <span class="pl-1"> Payment Successfully received by</span> <b>' . $userDetails->FirstName .' '.$userDetails->LastName. '</b> with Transation id : '.$row['transactionid']. ' and Amount is '.$GLOBALS['AppConfig']['Currency'].''.$row['price']."</p>";
            }
        }
        return $HTML;
    }
    
    public function OrderHistory()
    {
        $HTML = "<h3>Order History</h3>
            <div class='orderlog'>";
        $result = $GLOBALS['db']->Execute("SELECT * FROM " . $GLOBALS['Tables']['orderlog'] . " where orderid = '" . $this->OrderID . "' order by orderlogid desc");
        
        if (\TAS\Core\DB::Count($result) > 0) {
            while ($row = $GLOBALS['db']->Fetch($result)) {
                $HTML .= "<p class='adminorderlogfont'>" . $row['eventtime'] . '<span class="pl-1">' . $row['logmessage'] . "</p></span>";
            }
        }
        $HTML .= "</div>";
        
        return $HTML;
    }
}
