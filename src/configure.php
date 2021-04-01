<?php

/**
 * Site Configuration file:
 * This file is common to all module and all modules are expected to pick
 * there settings from here only.
 */
@session_start();
@ob_start();
date_default_timezone_set('Europe/Malta');

$GLOBALS['AppConfig']['PhysicalPath'] = dirname(__FILE__);
require_once $GLOBALS['AppConfig']['PhysicalPath'].'/includes/incfunctions.php';
require_once $GLOBALS['AppConfig']['PhysicalPath'].'/vendor/autoload.php';
spl_autoload_register([
    '\\TAS\\Core\\Utility',
    'AutoLoad',
], true);

$GLOBALS['AppConfig']['Domain'] = '';
if (file_exists($GLOBALS['AppConfig']['PhysicalPath'].'/configure.local.php')) {
    require_once $GLOBALS['AppConfig']['PhysicalPath'].'/configure.local.php';
} else {
    define('HOST', 'localhost');
    define('LOCAL_USER', 'root');
    define('LOCAL_PASSWORD', '');
    define('LOCAL_DB', 'testdb');
    define('URL_FOLDERPATH', '/');
    define('ADMIN_EMAIL', 'demo@example.com');
    $GLOBALS['AppConfig']['SenderEmail'] = 'noreply@'.$GLOBALS['AppConfig']['Domain'];
}

$GLOBALS['db'] = new \TAS\Core\DB(HOST, LOCAL_USER, LOCAL_PASSWORD, LOCAL_DB);
$GLOBALS['AppConfig']['folderpath'] = URL_FOLDERPATH;
$GLOBALS['AppConfig']['AdminMail'] = ADMIN_EMAIL;

$GLOBALS['AppConfig']['SiteName'] = 'Shopping Cart';
$GLOBALS['AppConfig']['LegalName'] = 'Shopping Cart';

$domain = (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost');
$GLOBALS['AppConfig']['NonSecureURL'] = 'http://'.$domain.$GLOBALS['AppConfig']['folderpath'];
$GLOBALS['AppConfig']['SecureURL'] = 'http://'.$domain.$GLOBALS['AppConfig']['folderpath'];
$GLOBALS['AppConfig']['HomeURL'] = (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == 'off') ? $GLOBALS['AppConfig']['NonSecureURL'] : $GLOBALS['AppConfig']['SecureURL'];

$GLOBALS['AppConfig']['Language'] = 'en_US'; // use english for windows and en_US for linux based server.
$GLOBALS['AppConfig']['ImageURL'] = $GLOBALS['AppConfig']['HomeURL'].'/images';

$GLOBALS['AppConfig']['UploadPath'] = $GLOBALS['AppConfig']['PhysicalPath'].'/assets';
$GLOBALS['AppConfig']['UserFileURL'] = $GLOBALS['AppConfig']['HomeURL'].'/assets';
$GLOBALS['AppConfig']['UploadURL'] = $GLOBALS['AppConfig']['UserFileURL'];
$GLOBALS['AppConfig']['TemplatePath'] = $GLOBALS['AppConfig']['PhysicalPath'].'/theme/template';
$GLOBALS['AppConfig']['cache'] = $GLOBALS['AppConfig']['PhysicalPath'].DIRECTORY_SEPARATOR.'cache';

$GLOBALS['AppConfig']['Currency'] = '&euro;';

$GLOBALS['AppConfig']['UseSMTPAuth'] = false;
$GLOBALS['AppConfig']['SMTPServer'] = '';
$GLOBALS['AppConfig']['SMTPUsername'] = '';
$GLOBALS['AppConfig']['SMTPPassword'] = '';
$GLOBALS['AppConfig']['SMTPServerPort'] = 587;
$GLOBALS['AppConfig']['SMTP-TLS'] = true;

$GLOBALS['AppConfig']['AdminURL'] = $GLOBALS['AppConfig']['HomeURL'].'/admin';
$GLOBALS['AppConfig']['AdminTemplate'] = $GLOBALS['AppConfig']['PhysicalPath'].'/theme/template';

$GLOBALS['AppConfig']['DeveloperMode'] = true;
$GLOBALS['AppConfig']['DebugMode'] = false; // Can be true or false only
$GLOBALS['AppConfig']['PageSize'] = 50;
$GLOBALS['AppConfig']['ProductListingPageSize'] = 12;
$GLOBALS['AppConfig']['DeveloperEmail'] = 'demo@7archers.com';

//Local Captha
$GLOBALS['AppConfig']['CaptchaSiteKey'] = '';
$GLOBALS['AppConfig']['CaptchaSecretKey'] = '';

$GLOBALS['AppConfig']['NoImage_Listing'] = $GLOBALS['AppConfig']['HomeURL'].'/theme/images/noimage.png';
$GLOBALS['AppConfig']['GoogleMapServerKey'] = '';

// Language file loading
putenv('LANG='.$GLOBALS['AppConfig']['Language']);
setlocale(LC_COLLATE, $GLOBALS['AppConfig']['Language']); // LC_ALL only if you want currency and number format as well
bindtextdomain('lang', $GLOBALS['AppConfig']['PhysicalPath'].'/languages');
bind_textdomain_codeset('lang', 'UTF-8');
set_include_path(get_include_path().PATH_SEPARATOR.$GLOBALS['AppConfig']['PhysicalPath']);

// Table names
$GLOBALS['Tables'] = [
    'address' => 'address',
    'attribute' => 'attribute',
    'attributeoption' => 'attributeoption',
    'configuration' => 'configuration',
    'company' => 'company',
    'category' => 'category',
    'cart' => 'cart',
    'countries' => 'countries',
    'document' => 'document',
    'enumeration' => 'enumeration',
    'emailcms' => 'emailcms',
    'error_log' => 'error_log',
    'images' => 'images',
    'log' => 'log',
    'module' => 'module',
    'orders' => 'orders',
    'orderitem' => 'orderitem',
    'orderlog' => 'orderlog',
    'product' => 'product',
    'productcategory' => 'productcategory',
    'productvariation' => 'productvariation',
    'productvariationoption' => 'productvariationoption',
    'pages' => 'pages',
    'payment' => 'payment',
    'passwordverification' => 'passwordverification',
    'testimonial' => 'testimonial',
    'user' => 'user',
    'userrole' => 'userrole',
    'userlog' => 'userlog',

    //view
    'productlist' => 'productlist',
];

$prefix = '';
if ($prefix != '') {
    foreach ($GLOBALS['Tables'] as $k => $v) {
        $GLOBALS['Tables'][$k] = $prefix.$v; // Add prefix to table here.
    }
}

$GLOBALS['db']->Debug = $GLOBALS['AppConfig']['DebugMode'];

if (!$GLOBALS['db']->Connect()) {
    TAS\Core\Web::Redirect($GLOBALS['AppConfig']['HomeURL'].'/dbfail.html');
}

$rs = $GLOBALS['db']->Execute('Select * From '.$GLOBALS['Tables']['enumeration'].'  order by type, displayorder ASC');

if ($GLOBALS['db']->RowCount($rs) > 0) {
    while ($row = $GLOBALS['db']->FetchArray($rs)) {
        $GLOBALS[$row['type']][$row['ekey']] = $row['value'];
        if ($GLOBALS['AppConfig']['DeveloperMode'] == true) {
            $GLOBALS['types'][$row['type']][$row['ekey']] = $row['value'];
        }
    }
}

$GLOBALS['Configuration'] = [];
$rsConfiguration = $GLOBALS['db']->Execute('Select * from '.$GLOBALS['Tables']['configuration'].' order by displayname');
if (\TAS\Core\DB::Count($rsConfiguration) > 0) {
    while ($rowConfiguration = $GLOBALS['db']->Fetch($rsConfiguration)) {
        $GLOBALS['Configuration'][$rowConfiguration['settingkey']] = $rowConfiguration['settingvalue'];
    }
}
$GLOBALS['module'] = [];
$rsModule = $GLOBALS['db']->Execute('Select * from '.$GLOBALS['Tables']['module'].'');
if (\TAS\Core\DB::Count($rsModule) > 0) {
    while ($rsModuleGet = $GLOBALS['db']->Fetch($rsModule)) {
        $GLOBALS['module'][$rsModuleGet['slug']] = $rsModuleGet['modulename'];
    }
}

$rsUserRole = $GLOBALS['db']->Execute('Select * from '.$GLOBALS['Tables']['userrole'].' order by rolename ');
if (\TAS\Core\DB::Count($rsUserRole) > 0) {
    while ($rowset = $GLOBALS['db']->Fetch($rsUserRole)) {
        $roles = json_decode($rowset['permission'], true);
        foreach ($GLOBALS['module'] as $mkey => $mval) {
            foreach ($GLOBALS['action'] as $akey => $aval) {
                if (isset($roles[$mkey][$akey])) {
                    $GLOBALS['permissions'][$rowset['userroleid']][$mkey][$akey] = $roles[$mkey][$akey];
                } else {
                    $GLOBALS['permissions'][$rowset['userroleid']][$mkey][$akey] = false;
                }
            }
        }
    }
}

 \TAS\Core\TemplateHandler::$TemplateName = [
    'single' => 'single.tpl',
    'home' => 'home.tpl',
    'admin' => 'admin.tpl',
    'login' => 'login.tpl',
    'contact' => 'contact.tpl',
    'popup' => 'popup.tpl',
    'printorder' => 'printorder.tpl',
];

$permission = new \TAS\Core\Permission();
$permission->usertype = $GLOBALS['db']->FirstColumnArray('select userroleid from '.$GLOBALS['Tables']['userrole'].'');
$permission->modules = $GLOBALS['module'];
$permission->action = $GLOBALS['action'];
$permission->permissions = $GLOBALS['permissions'];
$permission->Reload();

$GLOBALS['ThumbnailSize'] = [
    0 => [
        'width' => 120,
        'height' => 90,
    ],
    1 => [
        'width' => 136,
        'height' => 168,
    ],
    2 => [
        'width' => 260,
        'height' => 280,
    ],
    3 => [
        'width' => 372,
        'height' => 280,
    ],
    4 => [
        'width' => 520,
        'height' => 750,
    ],
];

if ($GLOBALS['AppConfig']['DeveloperMode']) {
    error_reporting(E_ALL);
    ini_set('display_errors', 'On');
}
