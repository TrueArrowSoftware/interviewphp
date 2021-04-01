<?php
$pageParse = array (
    'Content' => '',
    'PageTitle' => $GLOBALS ['AppConfig'] ['LegalName'],
    'MetaDescription'=>'',
    'MetaKeyword'=>'',
    'Header' => '',
    'AdditionalHeader' => '',
    'Footer' => 'Copyright &copy; ' . date ( "Y" ) . ' ' . $GLOBALS ['AppConfig'] ['LegalName'] . '. All rights reserved. ',
    'Navigation' => '',
    'FooterNavigation' => '',
    'Sidebar' => '',
    'FooterInclusion' => '',
    'MetaExtra' => '',
    'HeaderImage'=>'',
    'BreadCrumb'=>'',
    'BreadCrumbTitle'=>'',
    'BreadCrumbSubTitle'=>'',
    'CopyRight' => '&copy; ' . date('Y') . ' TAS',
);

/* Site Top Bar Navigation */
$pageParse['Navigation'] .='<header class="header-section" id="top">
	<div class="container-fluid main-header">
		<div class="container">
			<nav class="row navbar-expand-lg p-0 justify-content-between align-items-center">
				<div class="col-lg-2 col-sm-5 logo mr-2 mr-sm-0">
					<a class="navbar-brand py-0 m-0" href="{HomeURL}">
					  <!--img src="assets/images/logo-white.png" class="img-fluid"-->
                       Site Logo 
					</a>
				</div>
			  	<button class="navbar-toggler ml-auto mobilemenu-setting" type="button" data-toggle="collapse" data-target="#mobilemenu" aria-controls="mobilemenu" aria-expanded="false" aria-label="Toggle navigation">
				    <div class="menu-icon">
				      <span class="icon-bar"></span>
				      <span class="icon-bar"></span>
				      <span class="icon-bar"></span>
				    </div>
			  	</button>

			  	<div class="col-lg-10 col-md-12 mx-auto search-menu-section">
			  		<div class="search-menu-inner d-flex justify-content-between">
				  		<form action="{HomeURL}/productsearch.php'.(isset($_GET['category']) ? '?category='.$_GET['category']:'').'" method="POST" class="form-inline search-bar">
							<input class="form-control productname" type="text" name="productname" placeholder="Search products & brands" value="'.(isset($_POST['productname']) ? $_POST['productname']:'').'">
							<button class="btn" type="submit"><i class="fas fa-search mr-2"></i><span>Search</span></button>
						</form>
					    <ul class="account-menu d-flex align-items-center">
                            <li class="nav-item"><a class="nav-link" href="{HomeURL}/productsearch.php">Shop</a></li>
                            <li class="nav-item position-relative"><a class="nav-link" href="{HomeURL}/cart.php"><span class="cartspan">Cart</span><i class="fas fa-shopping-cart ml-3"></i></a><span class="countermsg" id="countcart">0</span></li>
					        '.(isset($_SESSION['userid']) && $_SESSION['userid'] > 0 ? '
                            <li class="nav-item"><a class="nav-link" href="{HomeURL}/logout.php">Logout</a></li>
                            ':'
                            <li class="nav-item"><a class="nav-link" href="{HomeURL}/login.php">Sign In</a></li>
                            ').'
						</ul>
			  		</div>
				</div>
			</nav>
		</div>
	</div>
</header>';

$pageParse['FooterNavigation'].='<footer class="container-fluid pt-7">
	<div class="container">
		<div class="col-xl-11 col-md-12 mx-auto">
			<div class="row mb-5">
				<div class="col-lg-4 col-sm-6 mb-5 mb-lg-0">
					<h4 class="heading">Categories</h4>
					<ul class="list-style">
						<li><a href="#">Women</a></li>
						<li><a href="#">Men</a></li>
						<li><a href="#">Kids</a></li>
						<li><a href="#">Home</a></li>
						<li><a href="#">Handbags</a></li>
					</ul>
				</div>
				<div class="col-lg-4 col-sm-6">
					<h4 class="heading">Company</h4>
					<ul class="list-style">
						<li><a href="#">About</a></li>
						<li><a href="#">Blog</a></li>
						<li><a href="#">FAQs</a></li>
						<li><a href="#">Press</a></li>
						<li><a href="#">Accessibility</a></li>
					</ul>
				</div>
				<div class="col-lg-4 col-sm-6">
					<div class="social-section">
						<h4 class="heading">Connect with us</h4>
						<ul class="list-style mb-4 social-icons">
							<li class="d-inline-block"><a href="#"><i class="fab fa-facebook"></i></a></li>
							<li class="d-inline-block"><a href="#"><i class="fab fa-twitter"></i></a></li>
							<li class="d-inline-block"><a href="#"><i class="fab fa-pinterest-p"></i></a></li>
							<li class="d-inline-block"><a href="#"><i class="fab fa-instagram"></i></a></li>
						</ul>
					</div>
					<div class="social-section">
						<h4 class="heading">Shop In</h4>
						<select class="form-control">
							<option value="india">India</option>
							<option value="malta">Malta</option>
							<option value="America">America</option>
							<option value="south-africa">South Africa</option>
						</select>
					</div>
				</div>
			</div>
			<div class="row">
				<ul class="col-md-12 copyright text-center list-style">
					<li>'.$pageParse['CopyRight'].'</li>
					<li><a href="#">Privacy</a></li>
					<li><a href="#">Terms</a></li>
					<li><a href="#">Copyright Policy</a></li>
					<li><a href="{HomeURL}/contactus">Contact</a></li>
                    '.(isset($_SESSION['userid']) ? '
                    <li><a href="{HomeURL}/profile">Dashboard</a></li>
                    ':'').'
				</ul>
			</div>
		</div>
	</div>
</footer>

<div class="go-top" id="gototop"><a href="#top"><i class="fas fa-long-arrow-alt-up"></i></a></div>';

function UserLogin()
{
    if(isset($_SESSION['userid']))
    {
        \TAS\Core\Web::Redirect( $GLOBALS['AppConfig']['HomeURL']);
    }
}

function SideBarHeader()
{
    if (!isset($_SESSION['userid'])) {
        \TAS\Core\Web::Redirect($GLOBALS['AppConfig']['HomeURL']);
    }
    
    $sidebar = '<ul id="sidebar" class="card card--dashboard p-3">
                    <li><a href="{HomeURL}/profile/index.php">Profile</a></li>
                    <li><a href="{HomeURL}/profile/changepassword.php">Change Password</a></li>                
                    <li><a href="{HomeURL}/orders/index.php">Orders</a></li>
                </ul>';
    return $sidebar;
}

$messages = array ();