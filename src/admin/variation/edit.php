<?php
namespace Framework;
require ("../template.php");
require_once ("./include.php");
$messages = array();
$variationid = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($variationid <= 0 || ! $permission->CheckOperationPermission("variation", "edit", $GLOBALS['user']->UserRoleID)) {
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
        $d['editdate'] = date("Y-m-d H:i:s");
        
        $hasAttribute = false;
        reset($GLOBALS['attribute']);
        foreach ($GLOBALS['attribute'] as $attr => $attname) {
            
            if ($_POST['options'] != '')
                $hasAttribute = true;
        }
        if (! $hasAttribute) {
            $messages[] = array(
                "message" => _("Please select at least one attribute option to continue."),
                "level" => 10
            );
        } else {
            $variation = new ProductVariation($variationid);
            $isupdated = $variation->Update($d);
            if ($isupdated) {
                $messages[] = array(
                    "message" => _("Product varient has been updated successfully."),
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
                        "message" => _("Unable to update product variation. Please try again."),
                        "level" => 10
                    );
                }
            }
            $GLOBALS['db']->Execute("delete from " . $GLOBALS['Tables']['productvariationoption'] . " where variationid=" . $variation->VariationID);
            reset($GLOBALS['attribute']);
            foreach ($GLOBALS['attribute'] as $attr => $attname) {
                if (isset($_POST['options'][$attr])) {
                    $d = array(
                        'variationid' => $variationid,
                        'optionid' => (int) $_POST['options'][$attr],
                        'attribute' => $attr
                    );
                    $optionid = $GLOBALS['db']->Insert($GLOBALS['Tables']['productvariationoption'], $d);
                }
            }
        }
    }
}

$pageParse['Content'] = DisplayForm($variationid);
echo \TAS\Core\TemplateHandler::TemplateChooser("admin");