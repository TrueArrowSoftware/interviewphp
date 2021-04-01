<?php
namespace Framework;

use TAS\Core\TemplateHandler;
$currentdirectory = getcwd();
chdir(dirname(__FILE__));
require ("./../configure.php");
chdir($currentdirectory);
$messages = array();
if ((! isset($_SESSION['userid'])) && str_replace("/", '\\', $_SERVER['SCRIPT_FILENAME']) != str_replace("/", '\\', $GLOBALS['AppConfig']['PhysicalPath'] . '\\' . "securelogin" . '\\' . "index.php")) {
    \TAS\Core\Web::Redirect($GLOBALS['AppConfig']['AdminURL'] . "/login.php");
} else {
    $GLOBALS['user'] = new User((int) $_SESSION['userid']);
    //$logoURL =  $GLOBALS['AppConfig']['HomeURL'].'/theme/images/logo.jpg';
    $pageParse = array(
        'Content' => '',
        'PageTitle' => 'Admin',
        'MetaExtra' => '',
        'Navigation' => '',
        'Sidebar' => '',
        'Header' => '',
        'Footer' => '',
        'FooterInclusion' => '',
        'MetaDescription'=>'',
        'MetaKeyword'=>'',
        'AdminTop' => (isset($logoURL) && $logoURL != '' ? '<img src="' . $logoURL . '" class="img-responsive logo-size">' : $GLOBALS['AppConfig']['SiteName']),
        //'UserName' => ucwords($GLOBALS['user']->FirstName).' '.ucwords($GLOBALS['user']->LastName),
    );
    $navigationArray = array(
        array(
            'name' => 'Dashboard',
            'icon' => 'home iconsize',
            'link' => $GLOBALS['AppConfig']['AdminURL'] . "/index.php",
            'permission_module' => 'coreadmin',
            'anchor' => 'dashboard',
            'child' => array(
                array(
                    'name' => 'View Dashboard',
                    'icon' => 'tachometer-alt iconsize',
                    'link' => $GLOBALS['AppConfig']['AdminURL'] . "/index.php",
                    'permission_module' => 'coreadmin'
                ),
                array(
                    'name' => 'View Site',
                    'icon' => 'home iconsize',
                    'link' => $GLOBALS['AppConfig']['HomeURL'],
                    'permission_module' => 'coreadmin',
                    'target' => 'blank'
                )
            )
        ),
        array(
            'name' => 'Order',
            'icon' => 'shopping-cart',
            'link' => $GLOBALS['AppConfig']['AdminURL'] . "/order/index.php",
            'permission_module' => 'order',
            'anchor' =>'orders',
            'child' => array(
                array(
                    'name' => 'View Orders',
                    'icon' => 'eye',
                    'link' => $GLOBALS['AppConfig']['AdminURL'] . "/order/index.php",
                    'permission_module' => 'order',
                    
                )
            )
        ), 
        array(
            'name' => 'Ecommerce',
            'icon' => 'shopping-basket iconsize',
            'link' => $GLOBALS['AppConfig']['AdminURL'] . "/brand/index.php",
            'permission_module' => 'ecommerce',
            'anchor' => 'ecommerce',
            'child' => array(
                array(
                    'name' => 'Brand',
                    'icon' => 'cubes iconsize',
                    'link' => $GLOBALS['AppConfig']['AdminURL'] . "/brand/index.php",
                    'permission_module' => 'company',

                    'child' => array(
                        array(
                            'name' => 'View Brands',
                            'icon' => 'eye iconsize',
                            'link' => $GLOBALS['AppConfig']['AdminURL'] . "/brand/index.php",
                            'permission_module' => 'company'
                        ),
                        array(
                            'name' => 'Add Brand',
                            'icon' => 'plus iconsize',
                            'link' => $GLOBALS['AppConfig']['AdminURL'] . "/brand/add.php",
                            'permission_module' => 'company'
                        )
                    )
                ),
                array(
                    'name' => 'Product Category',
                    'icon' => 'sitemap',
                    'link' => $GLOBALS['AppConfig']['AdminURL'] . "/category/index.php",
                    'permission_module' => 'category',
                    'child' => array(
                        array(
                            'name' => 'View Product Category',
                            'icon' => 'eye iconsize',
                            'link' => $GLOBALS['AppConfig']['AdminURL'] . "/category/index.php",
                            'permission_module' => 'category'
                        ),
                        array(
                            'name' => 'Add Product Category',
                            'icon' => 'plus iconsize',
                            'link' => $GLOBALS['AppConfig']['AdminURL'] . "/category/add.php",
                            'permission_module' => 'category'
                        )
                    )
                ),
                array(
                    'name' => 'Product',
                    'icon' => 'eye iconsize',
                    'link' => $GLOBALS['AppConfig']['AdminURL'] . "/product/index.php",
                    'permission_module' => 'product',
                    'child' => array(
                        array(
                            'name' => 'View Products',
                            'icon' => 'eye iconsize',
                            'link' => $GLOBALS['AppConfig']['AdminURL'] . "/product/index.php",
                            'permission_module' => 'product'
                        ),
                        array(
                            'name' => 'Add Product',
                            'icon' => 'plus iconsize',
                            'link' => $GLOBALS['AppConfig']['AdminURL'] . "/product/add.php",
                            'permission_module' => 'product'
                        )
                    )
                ),

                array(
                    'name' => 'Product Variation',
                    'icon' => 'sitemap iconsize',
                    'link' => $GLOBALS['AppConfig']['AdminURL'] . "/variation/index.php",
                    'permission_module' => 'variation',
                    'child' => array(
                        array(
                            'name' => 'View Product Variation',
                            'icon' => 'eye iconsize',
                            'link' => $GLOBALS['AppConfig']['AdminURL'] . "/variation/index.php",
                            'permission_module' => 'variation'
                        ),
                        array(
                            'name' => 'Add Product Variation',
                            'icon' => 'plus iconsize',
                            'link' => $GLOBALS['AppConfig']['AdminURL'] . "/variation/add.php",
                            'permission_module' => 'variation'
                        )
                    )
                ),
                array(
                    'name' => 'Attribute',
                    'icon' => 'eye iconsize',
                    'link' => $GLOBALS['AppConfig']['AdminURL'] . "/attribute/index.php",
                    'permission_module' => 'attribute',
                    'child' => array(
                        array(
                            'name' => 'View Attribute',
                            'icon' => 'eye iconsize',
                            'link' => $GLOBALS['AppConfig']['AdminURL'] . "/attribute/index.php",
                            'permission_module' => 'attribute'
                        ),
                        array(
                            'name' => 'Add Attribute',
                            'icon' => 'plus iconsize',
                            'link' => $GLOBALS['AppConfig']['AdminURL'] . "/attribute/add.php",
                            'permission_module' => 'attribute'
                        )
                    )
                ),
                array(
                    'name' => 'Attribute Options',
                    'icon' => 'eye iconsize',
                    'link' => $GLOBALS['AppConfig']['AdminURL'] . "/attributeoption/index.php",
                    'permission_module' => 'attributeoption',
                    'child' => array(
                        array(
                            'name' => 'View Attribute Options',
                            'icon' => 'eye iconsize',
                            'link' => $GLOBALS['AppConfig']['AdminURL'] . "/attributeoption/index.php",
                            'permission_module' => 'attributeoption'
                        ),
                        array(
                            'name' => 'Add Attribute Options',
                            'icon' => 'plus iconsize',
                            'link' => $GLOBALS['AppConfig']['AdminURL'] . "/attributeoption/add.php",
                            'permission_module' => 'attributeoption'
                        )
                    )
                )
            )
        ),
        array(
            'name' => 'CMS',
            'icon' => 'sticky-note iconsize',
            'link' => $GLOBALS['AppConfig']['AdminURL'] . "/cms/index.php",
            'permission_module' => 'cms',
            'anchor' => 'cms',
            'child' => array(
                array(
                    'name' => 'CMS',
                    'icon' => 'sticky-note iconsize',
                    'link' => $GLOBALS['AppConfig']['AdminURL'] . "/cms/index.php",
                    'permission_module' => 'cms'
                ),
                array(
                    'name' => 'Email CMS',
                    'icon' => 'envelope iconsize',
                    'link' => $GLOBALS['AppConfig']['AdminURL'] . "/cms/email.php",
                    'permission_module' => 'cms'
                ),
                array(
                    'name' => 'Document Manager',
                    'icon' => 'file iconsize',
                    'link' => $GLOBALS['AppConfig']['AdminURL'] . "/docmanager/index.php",
                    'permission_module' => 'docmanager'
                ),
                array(
                    'name' => 'Image Manager',
                    'icon' => 'image iconsize',
                    'link' => $GLOBALS['AppConfig']['AdminURL'] . "/imagemanager/index.php",
                    'permission_module' => 'imagemanager'
                ),
                array(
                    'name' => 'Home Slider',
                    'icon' => 'window-restore iconsize',
                    'link' => $GLOBALS['AppConfig']['AdminURL'] . "/slidemanager/index.php",
                    'permission_module' => 'slidemanager'
                )
            )
        ),

        array(
            'name' => 'Testimonial',
            'icon' => 'quote-left iconsize',
            'link' => $GLOBALS['AppConfig']['AdminURL'] . "/testimonial/index.php",
            'permission_module' => 'testimonial',
            'anchor' => 'testimonial',
            'child' => array(
                array(
                    'name' => 'View Testimonial',
                    'icon' => 'eye iconsize',
                    'link' => $GLOBALS['AppConfig']['AdminURL'] . "/testimonial/index.php",
                    'permission_module' => 'testimonial'
                ),
                array(
                    'name' => 'Add Testimonial',
                    'icon' => 'plus iconsize',
                    'link' => $GLOBALS['AppConfig']['AdminURL'] . "/testimonial/add.php",
                    'permission_module' => 'testimonial'
                )
            )
        ),
        array(
            'name' => 'Customer',
            'icon' => 'user-plus iconsize',
            'link' => $GLOBALS['AppConfig']['AdminURL'] . "/customer/index.php",
            'permission_module' => 'customer',
            'anchor' => 'customers',
            'child' => array(
                array(
                    'name' => 'View Customers',
                    'icon' => 'users iconsize',
                    'link' => $GLOBALS['AppConfig']['AdminURL'] . "/customer/index.php",
                    'permission_module' => 'customer'
                ),
                array(
                    'name' => 'Add Customer',
                    'icon' => 'user-plus iconsize',
                    'link' => $GLOBALS['AppConfig']['AdminURL'] . "/customer/add.php",
                    'permission_module' => 'customer'
                )
            )
        ),
        array(
            'name' => 'User',
            'icon' => 'user iconsize',
            'link' => $GLOBALS['AppConfig']['AdminURL'] . "/user/index.php",
            'permission_module' => 'user',
            'anchor' => 'user',
            'child' => array(
                array(
                    'name' => 'View Users',
                    'icon' => 'users iconsize',
                    'link' => $GLOBALS['AppConfig']['AdminURL'] . "/user/index.php",
                    'permission_module' => 'user'
                ),
                array(
                    'name' => 'Add User',
                    'icon' => 'user-plus iconsize',
                    'link' => $GLOBALS['AppConfig']['AdminURL'] . "/user/add.php",
                    'permission_module' => 'user'
                )
            )
        ),
        array(
            'name' => 'User Role',
            'icon' => 'tasks iconsize',
            'link' => $GLOBALS['AppConfig']['AdminURL'] . "/userrole/index.php",
            'permission_module' => 'userrole',
            'anchor' => 'userrole',
            'child' => array(
                array(
                    'name' => 'View User Role',
                    'icon' => 'eye iconsize',
                    'link' => $GLOBALS['AppConfig']['AdminURL'] . "/userrole/index.php",
                    'permission_module' => 'userrole'
                ),
                array(
                    'name' => 'Add User Role',
                    'icon' => 'plus iconsize',
                    'link' => $GLOBALS['AppConfig']['AdminURL'] . "/userrole/add.php",
                    'permission_module' => 'userrole'
                )
            )
        ),

        array(
            'name' => 'Tools',
            'icon' => 'cog iconsize',
            'link' => $GLOBALS['AppConfig']['AdminURL'] . "/log/index.php",
            'permission_module' => 'log',
            'anchor' => 'tools',
            'child' => array(
                array(
                    'name' => 'Error Log',
                    'icon' => 'exclamation-circle iconsize',
                    'link' => $GLOBALS['AppConfig']['AdminURL'] . "/log/index.php",
                    'permission_module' => 'log'
                ),
                array(
                    'name' => 'Tax',
                    'icon' => 'percent iconsize',
                    'link' => $GLOBALS['AppConfig']['AdminURL'] . "/tools/tax.php",
                    'permission_module' => 'log'
                ),
                array(
                    'name' => 'Shipping Charge',
                    'icon' => 'truck iconsize',
                    'link' => $GLOBALS['AppConfig']['AdminURL'] . "/tools/shipping.php",
                    'permission_module' => 'log'
                )
            )
        )
    );

    $pageParse['Navigation'] .= TemplateHandler::GenerateNavigationMenu($navigationArray);
}
