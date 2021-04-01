<?php
namespace Framework;
use DateTime;

class OrderLog extends \TAS\Core\Entity
{

    public $OrderLogID, $OrderID, $LogMessage, $EventTag, $SourceTag, $EventTime;

    public function __construct($id = 0)
    {
        $this->init();
        if (is_numeric($id) && (int) $id > 0) {
            $rs = $GLOBALS['db']->Execute("Select * from " . $GLOBALS['Tables']['orderlog'] . " where orderlogid=" . (int) $id);
            $this->LoadFromRecordSet($rs);
        }
    }

    private function init()
    {
        $this->OrderLogID = 0;
        $this->OrderID = 0;
        $this->LogMessage = "";
        $this->EventTag = "";
        $this->SourceTag = "";
        $this->EventTime = new DateTime();
    }

    public static function Add($values = array())
    {
        if (! self::Validate($values, 'orderlog')) {
            return false;
        } else {
            if ($GLOBALS['db']->Insert($GLOBALS['Tables']['orderlog'], $values)) {
                $id = $GLOBALS['db']->GeneratedID();
                return ($id);
            } else {
                return false;
            }
        }
    }
}
