<?php
namespace Framework;
require ("../template.php");
require_once ("./include.php");
$messages = array();
if (! $permission->CheckOperationPermission("variation", "add", $GLOBALS['user']->UserRoleID)) {
    \TAS\Core\Web::Redirect("index.php");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (! isset($_POST['options']) || ! is_array($_POST['options'])) {
        $messages[] = array(
            "message" => _("Please select at least one attribute option to continue."),
            "level" => 10
        );
    } else {
        $d = \TAS\Core\Entity::ParsePostToArray(ProductVariation::GetFields());
        $d['adddate'] = date("Y-m-d H:i:s");
        
        $hasAttribute = false;
        reset($GLOBALS['attribute']);
        foreach ($GLOBALS['attribute'] as $attr => $attname) {
            if (isset($_POST['options'][$attr]) && $_POST['options'][$attr] != '') {
                $hasAttribute = true;
            }
        }
        if (! $hasAttribute) {
            $messages[] = array(
                "message" => _("Please select at least one attribute option to continue."),
                "level" => 10
            );
        } else {
            $variationid = ProductVariation::Add($d);
            
            if ($variationid > 0) {
                reset($GLOBALS['attribute']);
                foreach ($GLOBALS['attribute'] as $attr => $attname) {
                    if (isset($_POST['options'][$attr]) && $_POST['options'][$attr] != '') {
                        $d = array(
                            'variationid' => $variationid,
                            'optionid' => (int) $_POST['options'][$attr],
                            'attribute' => $attr
                        );
                        
                        $optionid = $GLOBALS['db']->Insert($GLOBALS['Tables']['productvariationoption'], $d);
                    }
                }
                $messages[] = array(
                    "message" => _("Product variation has been added successfully."),
                    "level" => 1
                );
            } else {
                if (count(ProductVariation::GetErrors()) > 0) {
                    $a = ProductVariation::GetErrors();
                    foreach ($a as $i => $v) {
                        $messages[] = $v;
                    }
                } else {
                    $messages[] = array(
                        "message" => _("Unable to add product variation at this moment. Please try again later."),
                        "level" => 10
                    );
                }
            }
        }
    }
}
$pageParse['Content'] .= DisplayForm();
echo \TAS\Core\TemplateHandler::TemplateChooser("admin");
