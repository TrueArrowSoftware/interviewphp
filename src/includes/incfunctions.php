<?php
use PHPMailer\PHPMailer\PHPMailer;
function BreadCrumb($title,$subtitle, $backgroundimage)
{
    
    if ($backgroundimage != '') {
        $backgroundimage = $GLOBALS['AppConfig']['UserFileURL'] . '/image/headerimage/' . $backgroundimage;
    }
    else
    {
        $backgroundimage = $GLOBALS['AppConfig']['HomeURL'] . '/theme/images/banner.jpg';
    }
    
    $section='<section class="container-fluid py-5 inner-banner-section" style="background: url('.$backgroundimage.'),#ccc;background-size: cover;background-repeat: no-repeat;background-position: center;height: 400px;">
	<div class="container inner-banner">
		<div class="d-md-flex align-items-center justify-content-between">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="{HomeURL}">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page">'.$title.'</li>
				</ol>
			</nav>
		</div>
	</div>
</section>';
    return $section;
}

function PasswordValidation($password)
{
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number    = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('@[^\w]@', $password);
    
    if (strlen($password) < 8) {
        return  "Password should be at least 8 characters in length.";
    }elseif(!$uppercase){
        return  "Password must include at least upper case letter.";
    }elseif(!$lowercase){
        return  "Password must include at least lower case letter.";
    }elseif(!$number){
        return  "Password must include at least one number.";
    }elseif(!$specialChars){
        return  "Password must include at least one special character.";
    }else{
        return true;
    }
}
