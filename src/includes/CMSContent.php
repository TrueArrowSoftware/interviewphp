<?php

namespace Framework;

class CMSContent extends \TAS\Core\Entity
{
    /**
     * Function to display contact form and process it.
     */
    public static function ContactAfterContent($page)
    {
        $messages = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (Captcha::validatecaptcha()) {
                $d = [];
                $d['name'] = \TAS\Core\DataFormat::DoSecure($_POST['name']);
                $d['email'] = \TAS\Core\DataFormat::DoSecure($_POST['email']);
                $d['subject'] = \TAS\Core\DataFormat::DoSecure($_POST['subject']);
                $d['phone'] = \TAS\Core\DataFormat::DoSecure($_POST['phone']);
                $d['comment'] = nl2br(str_replace('\r', '<br>', \TAS\Core\DataFormat::DoSecure($_POST['message'])));
                if (\TAS\Core\DataValidate::ValidateEmail($_POST['email']) && \TAS\Core\Utility::DoEmail(1, $d, ADMIN_EMAIL, $GLOBALS['AppConfig']['SenderEmail'])) {
                    \TAS\Core\Utility::DoEmail(1, $d, $_POST['email'], ADMIN_EMAIL);
                    $messages[] = [
                        'message' => _('We have got your contact request. Our team will get back to you shortly!'),
                        'level' => 1,
                    ];
                } else {
                    $messages[] = [
                        'message' => _('Due to techincal failure your message is not processed. Please contact us by other mean.'),
                        'level' => 10,
                    ];
                }
            } else {
                $messages[] = [
                    'message' => _('Human Verification Failed'),
                    'level' => 10,
                ];
            }
        }

        $content = $GLOBALS['db']->ExecuteScalar('Select content  from '.$GLOBALS['Tables']['pages']." where slug= 'contactus' limit 1");
        $HTML = '

<!-- Inner page content start -->
<section class="container py-5 inner-page-content-area contact-page">
  <div class="row">
    <div class="col-lg-5 col-md-12">
        <div class="text">
            <h2 class="title text-uppercase mb-4">Contact</h2>
           '.stripslashes($content).'
        </div>
    </div>
    <div class="col-lg-6 offset-lg-1 col-md-12 mt-lg-0 mt-4">
        <div> '.\TAS\Core\UI::UIMessageDisplay($messages).'</div>
        <div class="contact-form">
            <form action="" method="post" class="validate">
                <div class="row">
                    <div class="form-group col-sm-6">
                        <input type="text" class="form-control rounded-0" name="name" placeholder="Name*" required>
                    </div>
                    <div class="form-group col-sm-6">
                        <input type="text" class="form-control rounded-0" name="email" placeholder="Email*" required>
                    </div>
                    <div class="form-group col-sm-12">
                        <input type="number" class="form-control rounded-0" name="phone" placeholder="Phone*" required>
                    </div>
                    <div class="form-group col-sm-12">
                        <input type="text" class="form-control rounded-0" name="subject" placeholder="Subject*" required>
                    </div>
                    <div class="form-group col-sm-12">
                        <textarea cols="30" rows="10" name="message" class="form-control rounded-0" placeholder="Write what do you want*" required></textarea>
                    </div>
                    <div class="captcha">'.Captcha::GetCaptcha().'</div>
                    <div class="col-sm-6 book-button text-center">
                        <button class="btn btn-primary text-uppercase rounded-0 bold d-block py-2" type="submit">Send</button> 
                    </div>
                </div>
            </form>
        </div>
    </div>
  </div>
</section>
<hr>
<div class="container-fluid">
  <!--div class="row">
    <iframe src="" width="100%" height="470" allowfullscreen="">
    </iframe>
  </div-->
</div>';

        return $HTML;
    }
}
