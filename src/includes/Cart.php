<?php
namespace Framework;
/**
 * Handle the Shopping Cart functionality
 *
 */
class Cart
{

    /**
     * Cart Settings are stored in this variable.
     */
    public $CartSettings;

    public static $CartContent;

    public function __construct()
    {
        $this->CartSettings = null;
        
        $this->GetCart();
        if ($this->CartSettings == null) {
            
            $this->CartSettings = array(
                'ConsumerID' => - 1,
                'CartSession' => md5(uniqid())
            );
        }
        
        if (! headers_sent()) {
            if (! isset($_COOKIE['cart']) || $_COOKIE['cart'] == null)
                setcookie('cart', json_encode($this->CartSettings), time() + 40000, "/");
        } else {
            if ((! isset($_COOKIE['cart']) || $_COOKIE['cart'] == null) && (! isset($_SESSION['cart']) || $_SESSION['cart'] == null))
                $_SESSION['cart'] = json_encode($this->CartSettings);
        }
    }

    /**
     * Load Cart in object from session or Cookie
     */
    public function GetCart()
    {
        if (isset($_SESSION['cart']) && $_SESSION['cart'] != null) {
            $this->CartSettings = json_decode(stripslashes($_SESSION['cart']), true);
        } else if ((isset($_COOKIE['cart']) && $_COOKIE['cart'] != null) && (! isset($_SESSION['cart']) || $_SESSION['cart'] == null)) {
            $this->CartSettings = json_decode(stripslashes($_COOKIE['cart']), true);
        } else {
            return $this->CartSettings;
        }
    }

    /**
     * Set cart in session.
     */
    public function SetCart()
    {
        if (! headers_sent()) {
            setcookie('cart', json_encode($this->CartSettings), time() + 40000);
            $_SESSION['cart'] = json_encode($this->CartSettings);
        } else {
            $_SESSION['cart'] = json_encode($this->CartSettings);
        }
    }

    /**
     * Set Product in Cart.
     */
    
    public static function SetCartProduct($CartParam)
    {
        
        if (! empty($CartParam)) {
            
            $product = new Product($CartParam['productid']);
            if (! $product->IsLoaded())
                return false;
                
                $d = array(
                    'cartid' => $CartParam['cartid'],
                    'productid' => $CartParam['productid'],
                    'variationid' => $CartParam['variationid'],
                    'productcode' => $CartParam['productcode'],
                    'productname' => $CartParam['productname'],
                    'quantity' => $CartParam['quantity'],
                    'price' => (float)$CartParam['price'],
                    'extrainfo' => $CartParam['extrainfo'],
                    'adddate' => date("Y-m-d H:i:s")
                );
               
                $GLOBALS['db']->Insert($GLOBALS['Tables']['cart'], $d);
                $cartid = $GLOBALS['db']->GeneratedID();
               
                if ($cartid > 0) {
                    return $cartid;
                } else {
                    return false;
                }
        }
        return false;
    }
    
   

    public static function GetCartContent($cartid)
    {
        $rs = $GLOBALS['db']->Execute("Select * from " . $GLOBALS['Tables']['cart'] . " where cartid='" . $cartid . "'");
        if (\TAS\Core\DB::Count($rs) > 0) {
            $o = array();
            while ($row = $GLOBALS['db']->Fetch($rs)) {
                $o[] = $row;
            }
            self::$CartContent = $o;
            return $o;
        } else {
            return null;
        }
    }

    public static function isEmpty($cartid = null)
    {
        if ($cartid == null) {
            $cart = new Cart();
            $cartid = $cart->CartSettings['CartSession'];
        }
        $p = Cart::GetCartContent($cartid);
        
        return (is_array($p) && count($p) > 0) ? false : true;
    }

    /**
     * Delete all cart entries older than one day
     */
    public static function AbandonedOldCart()
    {
        $yesterday = date('Y-m-d H:i:s', strtotime("-2days"));
        $sql = "DELETE FROM " . $GLOBALS['Tables']['cart'] . " WHERE adddate < '$yesterday'";
        $GLOBALS['db']->Execute($sql);
    }

    /**
     * Delete Cart from database only.
     * To clear session or reset cart use object.
     *
     * @param string $cartid
     */
    public static function DeleteCart($cartid)
    {
        $GLOBALS['db']->Execute("Delete from " . $GLOBALS['Tables']['cart'] . " where cartid='" . $cartid . "'");
        return true;
    }

    
    public static function GetCartTotal()
    {
        $orderTotals = array(
            'ordertotal' => 0,
            'tax' => 0,
            'taxp'=>(isset($GLOBALS['Configuration']['tax']) ? $GLOBALS['Configuration']['tax'] : 0),
            'total' => 0,
            'shippingprice' => (isset($GLOBALS['Configuration']['shipping']) ? $GLOBALS['Configuration']['shipping'] : 0)
        );
        
        
        if (is_array(self::$CartContent) && count(self::$CartContent) > 0) {
            foreach (self::$CartContent as $row) {
                $orderTotals['total'] += floatval($row['price'] * $row['quantity']);
            }
        }
        
        $totaltax = 0;
        if($orderTotals['taxp'] > 0)
        {
            $totaltax = ($orderTotals['total'] * $orderTotals['taxp'])/100;
        }
        
        $shippingPrice = $orderTotals['shippingprice'];
        
        $grandtotal = $orderTotals['total'] + $totaltax + $shippingPrice;
        
        $orderTotal =  array( 
            'total' => $orderTotals['total'],
            'tax' => $totaltax,
            'taxp'=>(isset($GLOBALS['Configuration']['tax']) ? $GLOBALS['Configuration']['tax'] : 0),
            'shippingprice' => (isset($GLOBALS['Configuration']['shipping']) ? $GLOBALS['Configuration']['shipping'] : 0),
            'ordertotal' => $grandtotal
        );
        return $orderTotal;
    }

   
    public function Checkout($param = array())
    {
        $cartid = $this->CartSettings['CartSession'];
        $cartContent = Cart::GetCartContent($cartid);
        
        if ($cartContent == null || count($cartContent) == 0)
            return false;
            $orderTotals = Cart::GetCartTotal();
            
            if(isset($_SESSION['userid']) && $_SESSION['userid'] > 0){
               $OrderData = array(
                    'userid' => (int)$_SESSION['userid'],
                    'discount'=> 0,
                    'discountp' => 0,
                    'taxp' => (float)$orderTotals['taxp'],
                    'tax' => (float)$orderTotals['tax'],
                    'shippingprice'=>(float)$orderTotals['shippingprice'],
                    'total'=>(float)$orderTotals['total'],
                    'ordertotal' => $orderTotals['ordertotal'],
                    'orderstatus' => 'queue',
                    'paymentstatus' => 'pending',
                    'orderdate' => date("Y-m-d H:i:s"),
                    'tag' => 'online',
                    'extrainfo' => '{}',
                    'adddate'=>date("Y-m-d H:i:s"),
                );
            }
            else{
                $OrderData = array(
                    'userid' => 0,
                    'discount'=> 0,
                    'discountp' => 0,
                    'taxp' => (float)$orderTotals['taxp'],
                    'tax' => (float)$orderTotals['tax'],
                    'shippingprice'=>(float)$orderTotals['shippingprice'],
                    'total'=>(float)$orderTotals['total'],
                    'ordertotal' => $orderTotals['ordertotal'],
                    'orderstatus' => 'queue',
                    'paymentstatus' => 'pending',
                    'orderdate' => date("Y-m-d H:i:s"),
                    'tag' => 'online',
                    'extrainfo' => '{}',
                    'adddate'=>date("Y-m-d H:i:s"),
                );
              }
                
            if ($GLOBALS['db']->Insert($GLOBALS['Tables']['orders'], $OrderData)) {
                $OrderID = $GLOBALS['db']->GeneratedID();
                
                $_SESSION['orderid'] = $OrderID;
               
                foreach ($cartContent as $index => $product) {
                    $Item = array(
                        'orderid' => $OrderID,
                        'productid' => $product['productid'],
                        'productcode' => $product['productcode'],
                        'productname' => $product['productname'],
                        'optiontag' => $product['extrainfo'],
                        'quantity' => $product['quantity'],
                        'itemprice' => $product['price'],
                        'itemtotal' => $product['price'] * $product['quantity'],
                        'adddate' => date("Y-m-d H:i:s")
                    );
                    
                    if (! $GLOBALS['db']->Insert($GLOBALS['Tables']['orderitem'], $Item)) {
                        \TAS\Core\Log::AddEvent(json_encode(array(
                            "message" => "Product Insert fails",
                            "query" => print_r($Item, true),
                            "orderid" => $OrderID
                        )), 'high');
                    }
                }
              
                OrderLog::Add(array(
                    'orderid' => $OrderID,
                    'logmessage' => 'New Order Placed',
                    'eventtag' => 'order',
                    'sourcetag' => '',
                    'eventtime' => date("Y-m-d H:i:s")
                ));
                Cart::DeleteCart($this->CartSettings['CartSession']);
                return $OrderID;
            } else {
                if (isset($GLOBALS['ApplicationSettings']['Debug']) && $GLOBALS['ApplicationSettings']['Debug']) {
                    print_r($GLOBALS['db']->LastErrors());
                }
                \TAS\Core\Log::AddEvent(array(
                    "message" => "Order Save Failed",
                    "Order Details" => print_r($OrderData, true),
                    "SESSION" => print_r($_SESSION, true),
                    "DB Error" => print_r($GLOBALS['db']->LastErrors(), true)
                ), 'high');
                return false;
            }
    }
}
