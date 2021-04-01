<?php
namespace TAS;
$IsIndex = true;
require "./configure.php";
require ("./template.php");

/* Homepage Slider */
$sliderimage = new \TAS\Core\ImageFile();
$sliderimage->LinkerType = 'homeslider';
$sliderimage = $sliderimage->GetImageLinkerType();
$slider = array();
$countImage=0;
if(!empty($sliderimage))
{
    foreach ($sliderimage as $slide) {
        $tag = json_decode($slide['tag'], true);
        $slider[] = '<div class="carousel-item '.($countImage=='0'?'active':'').'">
            <div class="home-banner-img text-center mb-4" style="background: url(' . $slide['url'] . ') no-repeat;height: 330px;background-position: center;background-size: cover;">
              <div class="banner-content">
                <div class="heading-section mb-4 text-center">
                  <h2 class="banner-title bold text-white text-capitalize">'.$tag['desc'].'</h2>
                  <p class="text-white">'.$tag['title'].'</p>
                </div>
              </div>
            </div>
          </div>';
        $countImage++;
    }
}

if(!empty($slider))
{
    $pageParse['Content'].='<section class="container px-0">
	   <div class="home-slider-section">
			<div id="home-banner" class="carousel slide" data-ride="carousel" data-interval="3000">
				<div class="carousel-inner">
				    ' . implode('', $slider) . '
				</div>
				        
				<a class="carousel-control-prev icon-nav" href="#home-banner" data-slide="prev">
					<i class="fas fa-angle-left"></i>
				</a>
				<a class="carousel-control-next icon-nav" href="#home-banner" data-slide="next">
					<i class="fas fa-angle-right"></i>
				</a>
			</div>
		</div>
    </section>';
}


/* Other Contents */
$pageParse['Content'] .='<hr><h2 class="text-center p-5">Comming Soon</h2><hr>';


echo \TAS\Core\TemplateHandler::TemplateChooser("home");
