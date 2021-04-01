<?php
namespace Framework;
class UserLog
{

    public static function AddEvent($message, $eventname, $userid, $extrainfo = array())
    {
        if (defined('SKIPAUTOLOADERROR')) {
            return true;
        }
        $extrainfodata = array(
            "IpAddress" => $_SERVER['REMOTE_ADDR'],
            "BrowserInfo" => $_SERVER['HTTP_USER_AGENT']
        );
        $extrainfo = json_encode(array_merge($extrainfo, $extrainfodata));
        $data = array(

            'userid' => $userid,
            'message' => $message,
            'eventname' => $eventname,
            'actionid' => $_SESSION['userid'],
            'extrainfo' => $extrainfo,
            'eventdate' => date("Y-m-d H:i:s")
        );
        $GLOBALS['db']->Insert($GLOBALS['Tables']['userlog'], $data);
    }
}
