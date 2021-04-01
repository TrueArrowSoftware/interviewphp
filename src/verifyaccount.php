<?php
namespace Framework;
require './configure.php';
require './template.php';
if(isset($_REQUEST['email']) &&isset($_REQUEST['hash'])) {
    if (User::VerifyAccount(\TAS\Core\DataFormat::DoSecure($_REQUEST['email']), \TAS\Core\DataFormat::DoSecure($_REQUEST['hash'])) ) {
        
        \TAS\Core\Web::Redirect($GLOBALS['AppConfig']['HomeURL']."/account-verified");
    }
    else
    {
        \TAS\Core\Web::Redirect($GLOBALS['AppConfig']['HomeURL']."/account-verification-failed");
    }
    
}
else
{
    \TAS\Core\Web::Redirect($GLOBALS['AppConfig']['HomeURL']."/account-verification-failed");
}