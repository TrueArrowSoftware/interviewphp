<?php
require_once ("./configure.php");
require_once ("./template.php");

if (! $GLOBALS['AppConfig']['DeveloperMode']) {
    date_default_timezone_set('UTC');
    $add_date = date('Y-m-d h:i:s');
    
    $extension = parse_url($_SERVER['REQUEST_URI']);
    $extension = $extension['path'];
    $extension = explode(".", $extension);
    $extension = end($extension);
    
    $extensions = array(
        'png',
        'jpg',
        'jpeg',
        'gif',
        'css'
    );
    
    if (! in_array($extension, $extensions)) {
        $get_var = (empty($_GET)) ? base64_encode(serialize($_GET)) : '';
        $post = (empty($_POST)) ? base64_encode(serialize($_POST)) : '';
        
        $proxy_ip = $_SERVER['REMOTE_ADDR'];
        $remote_addr = $_SERVER['REMOTE_ADDR'];
        
        if (isset($_SERVER['HTTP_X_REAL_IP']) || isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $proxy_ip = $_SERVER['HTTP_X_REAL_IP'];
            $remote_addr = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        
        $values = array(
            'request_url' => $GLOBALS['AppConfig']['HomeURL'] . $_SERVER['REQUEST_URI'],
            'http_referer' => (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : '',
            'get_var' => $get_var,
            'post' => $post,
            'proxy_ip' => $proxy_ip,
            'request_method' => $_SERVER['REQUEST_METHOD'],
            'remote_addr' => $remote_addr,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'add_date' => date('Y-m-d h:i:s')
        );
        
        $GLOBALS['db']->Insert($GLOBALS['Tables']['error_log'], $values);
    }
}

$pagename = (isset($_GET['pagename']) ? \TAS\Core\DataFormat::DoSecure($_GET['pagename']) : '');
if ($pagename == '')
    \TAS\Core\Web::Redirect("index.php");
    $headerimage = $GLOBALS['db']->Fetch($GLOBALS['db']->Execute("Select * from " . $GLOBALS['Tables']['pages'] . " where slug='" . $pagename . "'"));
    $page = $GLOBALS['db']->ExecuteScalarRow("select * from $Tables[pages] where slug='$pagename'");
    
    if($page == false || $page == '' || !is_array($page)) {
        if (file_exists($GLOBALS['AppConfig']['PhysicalPath'] . DIRECTORY_SEPARATOR . "static". DIRECTORY_SEPARATOR . $pagename. ".tpl.php")){
            $page['content'] = \TAS\Core\TemplateHandler::PrepareContent(file_get_contents("./static/".$pagename.".tpl.php"), array());
            $page['sidebar'] ='';
            $page['metakeyword']='';
            $page['metadescription']='';
            $page['pagetitle'] = $GLOBALS['AppConfig']['SiteName'];
        } else {
            $page = $db->ExecuteScalarRow("select * from $Tables[pages] where slug='404'");
        }
    }
    
    
    if (isset($page['headerimage']) && $page['headerimage'] != "") {
        $pageParse['HeaderImage'] = $page['headerimage'];
        $pageParse['AdditionalHeader'] = $pageParse['HeaderImage'];
    }
    $pagetitle = $page['pagetitle'];
    
    $contentAddon = '';
    if (isset($page['contentfunction']) && $page['contentfunction'] != '') {
        $contentAddon = call_user_func(array(
            '\Framework\CMSContent',
            'ContactAfterContent'
        ), $page);
    }
    
    $headerimage = $headerimage['headerimage'];
    
    $pageParse['PageName'] = $pagename;
    $pageParse['BreadCrumb'] = BreadCrumb($pagetitle,'', $headerimage);
    
    $pageParse['PageTitle'] = (isset($page['pagetitle']) && $page['pagetitle'] != '') ? $page['pagetitle'] : $GLOBALS['AppConfig']['SiteName'];
    $pageParse['MetaTitle'] = $pageParse['PageTitle'];
    $pageParse['MetaKeyword']= $page['metakeyword'];
    $pageParse['MetaDescription']= $page['metadescription'];
    
    $pageParse['Content'] .= stripslashes($page['content']) . $contentAddon;
    $pageParse['Sidebar'] .= stripslashes($page['sidebar']);
    echo TAS\Core\TemplateHandler::TemplateChooser(isset($page['template']) && $page['template']!='' ? $page['template'] : "single");
    