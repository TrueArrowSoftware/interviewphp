<?php
namespace Framework;
require "./configure.php";
require ("./template.php");
$pageParse['PageTitle'] = 'Search | '.$GLOBALS['AppConfig']['SiteName'];
$messages =array();

/*  BreadCrumb*/
/* $headerimage = $GLOBALS['db']->Fetch($GLOBALS['db']->Execute("Select * from " . $GLOBALS['Tables']['pages'] . " where page= 'Online Shop'"));
 $headerimage = $headerimage['headerimage'];
 $pageParse['PageName'] = 'Login';
 $pageParse['BreadCrumb'] = BreadCrumb('Login', '', $headerimage); */

/********************************************** Start Category HTML Process ***********************************************/

$categoryHTML = Search::CategoryHTML();

/********************************************** End Category HTML Process ***********************************************/

/********************************************** Start Search Array Data Process *****************************************/

$options = array(
    'productname' => (isset($_POST['productname']) ? $_POST['productname']:''),
    'guid' => (isset($_GET['category']) ? $_GET['category']:''),
    'minprice' => '',
    'maxprice' => '',
    'orderby' => ''
);

$searchQuery = Search::GetProductSearchQuery($options);
$productHTML = Search::GetProductSearchHTML($searchQuery);

/************************************************ End Search Array Data Process *****************************************/

/************************************************ Start Search Page HTML Layout *****************************************/

$maxPrice = $GLOBALS['db']->ExecuteScalar('select max(price) from '.$GLOBALS['Tables']['productlist'].' where status="1" and type="mainproduct"');
$pageParse['Content']='
<section class="common-section maxpriceval" data-maxprice="'.($maxPrice!=''?$maxPrice:'0').'" data-searchmax="'.(isset($_POST['newmaxprice']) ?$_POST['newmaxprice']:'100').'">
	<div class="container py-5 single-product-page">
		<div class="row">
			<div class="col-xl-2 col-lg-3 p-3 d-none d-lg-block search-page-options" id="mobile-filters">
			'.($categoryHTML!=''?'
                    <div class="options">
						<h5 class="title">Categories</h5>
						<div class="card card--list card-category card-category-search list-style">
                            <ul>'.$categoryHTML.'</ul>
			            </div>
				   </div>
                ':'').'
				<div class="options">
					<h5 class="title">Price</h5>
					<div id="range-slider">
						<input type="hidden" name="minprice" class="minprice" id="price-min" value="">
						<input type="hidden" name="maxprice" class="maxprice" id="price-max" value="">
                        <input type="hidden" id="categoryval" value="'.(isset($_GET['category']) ? $_GET['category'] : '').'">
					</div>
                    <div class="price-range-setting">
						<div class="row mx-1">
						    <div class="col-sm-12 px-0">
						        <div id="slider-range"></div>
						    </div>
						</div>
						<div class="row slider-labels mx-0">
						    <div class="col-6 px-0 caption"><strong>Min:</strong>'.$GLOBALS['AppConfig']['Currency'].'<span id="slider-range-value1"></span></div>
						    <div class="col-6 px-0 text-right caption"><strong>Max:</strong>'.$GLOBALS['AppConfig']['Currency'].'<span id="slider-range-value2"></span></div>
						</div>
					</div>
				</div>
			 </div>
            
			<div class="col-xl-10 col-lg-9 col-md-12 pl-lg-5 product-section search-page-results">
				<div class="home-product-section">
				    <div class="row search-page-results-box">
						<div class="col-md-12 numbers-sort-sections">
						  <div class="sort-option d-md-flex float-right">
								<div class="d-flex align-items-center justify-content-between w-100">
									<div class="sort-box">
                                        <h4 class="title">Sort By: </h4>
										<select name="sortbyfilter" class="form-control filter-sortby">
											<option value="newest">Newest</option>
											<option value="lowtohigh">Price: Low to High</option>
											<option value="hightolow">Price: High to Low</option>
										</select>
                                    </div>
                                </div>	
							</div>
						</div>
					</div>
				</div>
                <div class="row categories-section categories-section--products-details producthtml">
                '.$productHTML.'
                </div>
			</div>
        </div>
	</div>
</section>';

/************************************************ End Search Page HTML Layout *****************************************/
$pageParse['FooterInclusion'] = '<script type="text/javascript" src="{HomeURL}/theme/scripts/price-slider.js"></script>';
echo \TAS\Core\TemplateHandler::TemplateChooser("single");