<?php
namespace Framework;

class Order extends \TAS\Core\Entity
{

    public $OrderID, $UserID, $Discount,$DiscountP, $Tax, $TaxP, $ShippingPrice, $Total, $OrderTotal;

    public $OrderStatus, $PaymentStatus, $OrderDate, $Tag, $ExtraInfo,$AddDate,$EditDate;
    
    public $Address,$Products,$Payment;

    private function _init()
    {
        $this->_tablename = $GLOBALS['Tables']['orders'];
        $this->_isloaded = false;
        $this->Address = array();
        $this->Address['shipping'] = new Address();
        $this->Address['billing'] = new Address();
        $this->Products = array();
        $this->Payment = array();
    }
    
    public function __construct($id = 0)
    {
        parent::__construct();
        $this->_init();
        if (is_numeric($id) && $id > 0) {
            $this->Load($id);
        }
    }
    
    public function Load($id = 0)
    {
        if (!is_numeric($id) || (int) $id <= 0) {
            if ($this->OrderID > 0) {
                $id = (int) $this->OrderID;
            } else {
                return false;
            }
        }
        $id = (int) $id;
        
        $rs = $GLOBALS['db']->Execute('Select * from ' . $this->_tablename . ' where orderid=' . (int) $id . ' limit 1');
        if (\TAS\Core\DB::Count($rs) > 0) {
            $this->LoadFromRecordSet($rs);
        }
        
        if (\TAS\Core\DB::Count($rs) > 0) {
            $this->LoadFromRecordSet($rs);
            $rs = $GLOBALS['db']->Execute("Select * from " . $GLOBALS['Tables']['orderitem'] . " where orderid=" . $id . " ");
            if (\TAS\Core\DB::Count($rs) > 0) {
                while ($row = $GLOBALS['db']->Fetch($rs)) {
                    $this->Products[$row['itemid']] = $row;
                }
            }
            
            $rs = $GLOBALS['db']->Execute('Select * from ' . $GLOBALS['Tables']['payment'] . ' where orderid="' . $id . '"');
            if (\TAS\Core\DB::Count($rs) > 0) {
                while ($row = $GLOBALS['db']->Fetch($rs)) {
                    $this->Payment[$row['paymentid']] = $row;
                }
            }
        }
        
        if (\TAS\Core\DB::Count($rs) > 0) {
            $this->LoadFromRecordSet($rs);
            $rs = $GLOBALS['db']->Execute("Select * from " . $GLOBALS['Tables']['payment'] . " where orderid=" . $id . " ");
            if (\TAS\Core\DB::Count($rs) > 0) {
                while ($row = $GLOBALS['db']->Fetch($rs)) {
                    $this->Payment[$row['paymentid']] = $row;
                }
            }
        }
        
        $billingAddressID = Address::GetAddressID($this->OrderID, 'order', 'billing');
        $this->Address['billing'] = new Address($billingAddressID);
        $addressid = Address::GetAddressID($this->OrderID, 'order', 'shipping');
        $this->Address['shipping'] = new Address($addressid);
    }
}
