<?php
namespace Framework;
class Captcha
{
    public static function ValidateCaptcha()
    {
        $sitekey = $GLOBALS['AppConfig']['CaptchaSiteKey'];
        $secretkey = $GLOBALS['AppConfig']['CaptchaSecretKey'];

        if ($GLOBALS['AppConfig']['DeveloperMode'] == true) {
            return true;
        }
        $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
        $recaptcha_secret = $secretkey;
        $recaptcha_response = \TAS\Core\DataFormat::DoSecure($_POST['recaptcha_response']);
        $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
        $recaptcha = json_decode($recaptcha);
        if ($recaptcha->success == 1) {
            return true;
        } else {
            return false;
        }
    }

    public static function GetCaptcha()
    {
        $sitekey = $GLOBALS['AppConfig']['CaptchaSiteKey'];
        $secretkey = $GLOBALS['AppConfig']['CaptchaSecretKey'];

        if ($GLOBALS['AppConfig']['DeveloperMode'] == true) {
            return '';
        } elseif ($GLOBALS['AppConfig']['DeveloperMode'] == true && $GLOBALS['AppConfig']['DebugMode'] == true) {
            return '  <input type="hidden" name="recaptcha_response" id="recaptchaResponse"><script src="https://www.google.com/recaptcha/api.js?render=' . $sitekey . '"></script>
              <script>
                  grecaptcha.ready(function() {
                      grecaptcha.execute("' . $sitekey . '", {action: "login"}).then(function(token) {
                          
                      });
                    });
            </script>';
        } else {
            return '  <input type="hidden" name="recaptcha_response" id="recaptchaResponse"><script src="https://www.google.com/recaptcha/api.js?render=' . $sitekey . '"></script>
              <script>
                  grecaptcha.ready(function() {
                      grecaptcha.execute("' . $sitekey . '", {action: "login"}).then(function(token) {
                         var recaptchaResponse = document.getElementById("recaptchaResponse");
                        recaptchaResponse.value = token;
                      });
                  });
              </script>';
        }
    }
}
