<?php

namespace Framework;

class User extends \TAS\Core\Entity
{
    public $UserID;

    public $UserName;

    public $FirstName;

    public $LastName;

    public $Email;

    public $UserRoleID;

    public $Phone;

    public $Status;

    public $AllowLogin;

    public $VerifyEmail;

    public $AddDate;

    public $EditDate;

    public $LastLogin;

    public function __construct($id = 0)
    {
        parent::__construct();
        if (is_numeric($id) && $id > 0) {
            $this->Load($id);
        }
    }

    public function Load($id = 0)
    {
        if (!is_numeric($id) || (int) $id <= 0) {
            if ($this->UserID > 0) {
                $id = $this->UserID;
            } else {
                return false;
            }
        }
        $rs = $GLOBALS['db']->Execute('Select * from '.$GLOBALS['Tables']['user'].' where userid='.(int) $id.' limit 1');

        if (\TAS\Core\DB::Count($rs) > 0) {
            $this->LoadFromRecordSet($rs);
            $this->isLoad = true;
        } else {
            return false;
        }
    }

    public static function GetFields($id = 0)
    {
        $fields = \TAS\Core\Entity::GetFieldsGeneric($GLOBALS['Tables']['user']);

        $user = new User();
        unset($fields['userid']);
        $fields['password']['label'] = 'Password';
        $fields['password']['type'] = 'password';
        $fields['password']['required'] = true;
        $fields['password']['displayorder'] = 5;

        $fields['firstname']['label'] = 'First Name';
        $fields['firstname']['required'] = true;
        $fields['firstname']['displayorder'] = 1;

        $fields['lastname']['label'] = 'Last Name';
        $fields['lastname']['displayorder'] = 2;

        $fields['username']['label'] = 'User Name';
        $fields['username']['css'] = 'form-control unique-username';
        $fields['username']['displayorder'] = 5;
        $fields['username']['required'] = true;

        $fields['email']['label'] = 'Email';
        $fields['email']['type'] = 'email';
        $fields['email']['css'] = 'form-control unique-email';
        $fields['email']['displayorder'] = 6;
        $fields['email']['required'] = true;

        $fields['password']['label'] = 'Password';
        $fields['password']['type'] = 'password';
        $fields['password']['displayorder'] = 7;
        $fields['password']['required'] = true;
        $fields['password']['css'] = 'form-control validatepassword';

        $fields['phone']['label'] = 'Mobile Phone';
        $fields['phone']['type'] = 'phone';
        $fields['phone']['css'] = 'form-control unique-phone';
        $fields['phone']['required'] = true;
        $fields['phone']['displayorder'] = 8;

        $fields['allowlogin']['type'] = 'checkbox';
        $fields['allowlogin']['label'] = 'Allow Login';
        $fields['allowlogin']['displayorder'] = 10;
        $fields['allowlogin']['shortnote'] = '(Uncheck to User to Temporarily Barred)';

        $fields['status']['type'] = 'checkbox';
        $fields['status']['value'] = $user->Status == 1 ? true : false;
        $fields['status']['displayorder'] = 11;

        if ($id > 0) {
            $user = new User($id);
            $fields['email']['additionalattr'] = (($id > 0) ? 'data-rel="'.$user->UserID.'"' : '');
            $fields['username']['additionalattr'] = (($id > 0) ? 'data-rel="'.$user->UserID.'"' : '');
            $fields['phone']['additionalattr'] = (($id > 0) ? 'data-rel="'.$user->UserID.'"' : '');
            $a = $user->ObjectAsArray();
            foreach ($a as $i => $v) {
                if (isset($fields[strtolower($i)])) {
                    $fields[strtolower($i)]['value'] = $v;
                }
            }

            $fields['adddate']['type'] = 'readonly';
            $fields['editdate']['type'] = 'readonly';
            $fields['lastlogin']['type'] = 'readonly';

            $fields['adddate']['label'] = 'Add Date';
            $fields['editdate']['label'] = 'Edit Date';
            $fields['lastlogin']['label'] = 'Last Login';
            $fields['lastlogin']['value'] = $user->LastLogin;
            $fields['lastlogin']['displayorder'] = 13;
            $fields['adddate']['displayorder'] = 14;
            $fields['adddate']['displayorder'] = 15;

            unset($fields['password']);
        } else {
            unset($fields['adddate']);
            unset($fields['editdate']);
            unset($fields['lastlogin']);
        }

        unset($fields['verifyemail']);
        unset($fields['userroleid']);

        return $fields;
    }

    /**
     * Test is given username and email is unique in system.
     *
     * @param array $d
     *                 An Array with key as username and email.
     */
    public static function UniqueEmail($d, $userid = 0)
    {
        if ($userid == 0) {
            $count = $GLOBALS['db']->ExecuteScalar('select count(*) from '.$GLOBALS['Tables']['user']." where email='".$d['email']."'");
        } else {
            $count = $GLOBALS['db']->ExecuteScalar('select count(*) from '.$GLOBALS['Tables']['user']." where email='".$d['email']."'
				and userid != '".(int) $userid."' ");
        }

        if ($count > 0) {
            return false;
        } else {
            return true;
        }
    }

    public static function UniqueUserName($d, $userid = 0)
    {
        if ($userid == 0) {
            $count = $GLOBALS['db']->ExecuteScalar('select count(*) from '.$GLOBALS['Tables']['user']." where username='".$d['username']."'");
        } else {
            $count = $GLOBALS['db']->ExecuteScalar('select count(*) from '.$GLOBALS['Tables']['user']." where username='".$d['username']."'
				and userid != '".(int) $userid."' ");
        }

        if ($count > 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Check if user is already present, if yes return UserID, else return bool false.
     *
     * @param array $values
     *                      User ID to check if he is referred to us
     *
     * @return number|bool false is not, else numeric userid of who refer them.
     */
    public static function Add($values = [])
    {
        if (!self::Validate($values, $GLOBALS['Tables']['user'])) {
            self::SetError('Please use unique email', '10');

            return false;
        } elseif (!User::UniqueEmail($values)) {
            self::SetError('Please use unique email', '10');

            return false;
        } elseif (!User::UniqueUserName($values)) {
            self::SetError('Please use unique username', '10');

            return false;
        } else {
            if (isset($values['phone'])) {
                $values['phone'] = preg_replace('/[^0-9]/', '', $values['phone']);
            }

            if ($GLOBALS['db']->Insert($GLOBALS['Tables']['user'], $values)) {
                $id = $GLOBALS['db']->GeneratedID();

                return $id;
            } else {
                return false;
            }
        }
    }

    public function Update($values = [])
    {
        if (is_null($values) || count($values) == 0) {
            $tv = json_decode($this->ToJson(), true);
            foreach ($tv as $k => $v) {
                $values[strtolower($k)] = $v;
            }

            $values['editdate'] = date('Y-m-d H:i:s');
        }

        if (isset($values['phone'])) {
            $values['phone'] = preg_replace('/[^0-9]/', '', $values['phone']);
        }

        if (!self::Validate($values, 'user') || $this->UserID == 0) {
            return false;
        } elseif (!User::UniqueEmail($values, $this->UserID)) {
            self::SetError('Please use unique email', '10');

            return false;
        } elseif (!User::UniqueUserName($values, $this->UserID)) {
            self::SetError('Please use unique username', '10');

            return false;
        } else {
            if ($GLOBALS['db']->Update($GLOBALS['Tables']['user'], $values, $this->UserID, 'userid')) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * Delete the User.
     *
     * @param int $id
     *
     * @return boolean
     */
    public static function Delete($id)
    {
        if (!is_numeric($id) || (int) $id <= 0) {
            return false;
        }
        $id = floor((int) $id);

        $delete = $GLOBALS['db']->Execute('Delete from '.$GLOBALS['Tables']['user'].' where userid='.(int) $id.' limit 1');

        return true;
    }

    /**
     * returns true is user is logged in.
     *
     * @return boolean
     */
    public static function IsLoggedIn()
    {
        return (isset($_SESSION['userid']) && (int) $_SESSION['userid'] > 0) ? true : false;
    }

    public static function CheckUserType()
    {
        if (isset($_SESSION['userid'])) {
            $userDetails = new User($_SESSION['userid']);

            return $userDetails->UserType;
        }
    }

    /**
     * Return UserId if user found with login, else return boolean false.
     *
     * @param string $username
     * @param string $password
     */
    public static function AuthenticateUser($username, $password)
    {
        if (trim($username) == '' || trim($password) == '') {
            return false;
        } else {
            $user = $GLOBALS['db']->ExecuteScalarRow('Select userid, password from '.$GLOBALS['Tables']['user']." where username='".$username."'
				or email='".$username."' and status=1 and allowlogin=1 limit 1");

            if ($user) {
                $userid = $user['userid'];
                $password = password_verify($password, $user['password']);
                if (is_numeric($userid) && $userid > 0 && is_bool($password) == true && $password === true) {
                    $GLOBALS['db']->Execute('UPDATE '.$GLOBALS['Tables']['user'].' set lastlogin=now() where userid='.$userid);

                    return $userid;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    /**
     * Send Verification Email for User.
     */
    public static function SendVerificationEmail($userid)
    {
        if (is_numeric($userid) && (int) $userid > 0) {
            $user = new User($userid);
            if ($user->IsLoaded()) {
                $userArray = $user->EmailKeywords();
                $userArray['hash'] = md5(strtolower($user->UserName).strtolower($user->AddDate));
                $userArray['login'] = $GLOBALS['AppConfig']['HomeURL'].'/verifyaccount.php?hash='.$userArray['hash'].'&email='.$user->Email;

                return \TAS\Core\Utility::DoEmail(4, $userArray, $user->Email, $GLOBALS['AppConfig']['SenderEmail']);
            }
        }

        return false;
    }

    /**
     * Send welcome email Email for User.
     */
    public static function SendWelcomeEmail($userid)
    {
        if (is_numeric($userid) && (int) $userid > 0) {
            $user = new User($userid);
            if ($user->IsLoaded()) {
                $userArray = $user->EmailKeywords();
                $userArray['login'] = $GLOBALS['AppConfig']['HomeURL'].'/login.php';
                $sender = $GLOBALS['AppConfig']['SenderEmail'];
                $attachment = $GLOBALS['AppConfig']['PhysicalPath'].DIRECTORY_SEPARATOR.'theme'.DIRECTORY_SEPARATOR.'sbtmanual.pdf';

                return \TAS\Core\Utility::DoEmail(4, $userArray, $user->Email, $sender, $attachment);
            }
        }

        return false;
    }

    /**
     * Verify an account for email.
     *
     * @param unknown $email
     * @param unknown $hash
     *
     * @return boolean
     */
    public static function VerifyAccount($email, $hash)
    {
        $rs = $GLOBALS['db']->Execute('Select * from '.$GLOBALS['Tables']['user']." where email='".$email."' and status=1");
        if (\TAS\Core\DB::Count($rs) > 0) {
            while ($row = $GLOBALS['db']->Fetch($rs)) {
                $newhash = md5(strtolower($row['username']).strtolower($row['adddate']));
                if ($newhash == $hash) {
                    $GLOBALS['db']->Execute('update '.$GLOBALS['Tables']['user'].' set verifyemail=1 where userid='.(int) $row['userid']);

                    return true;
                }
            }

            return false;
        } else {
            return false;
        }
    }

    /*
     * Password reset within one day
     * after 1 day link is expired
     *  */
    public static function ResetPassword($code, $password)
    {
        $userid = $GLOBALS['db']->ExecuteScalar('Select customerid FROM '.$GLOBALS['Tables']['passwordverification']." where code = '".\TAS\Core\DataFormat::DoSecure($code)."' and adddate > '".date('Y-m-d h:i:s', strtotime('-1 day'))."'");
        if (is_numeric($userid)) {
            $password = password_hash($password, PASSWORD_DEFAULT);
            $a = $GLOBALS['db']->Execute('Update '.$GLOBALS['Tables']['user']." set password = '".$password."' where userid = '".$userid."'");
            if ($a) {
                $GLOBALS['db']->Execute('Delete FROM '.$GLOBALS['Tables']['passwordverification']." where code = '".\TAS\Core\DataFormat::DoSecure($code)."'");

                return true;
            } else {
                return 'Unable to reset password at this moment. Please try again later.';
            }
        } else {
            return 'Your reset password link has been expired.';
        }
    }

    /*
     * Forget password send reset password link
     *  */

    public static function ResetPasswordMail($email)
    {
        $rs = $GLOBALS['db']->Execute('Select * from '.$GLOBALS['Tables']['user']." where username='".$email."' limit 1");
        if (\TAS\core\DB::Count($rs) > 0) {
            while ($row = $GLOBALS['db']->Fetch($rs)) {
                $d = [];
                $d['code'] = uniqid();
                $d['customerid'] = $row['userid'];
                $d['adddate'] = date('Y-m-d h:i:s');
                $GLOBALS['db']->Insert($GLOBALS['Tables']['passwordverification'], $d);
                $row['name'] = ucfirst($row['firstname']).' '.ucfirst($row['lastname']);
                $row['link'] = $GLOBALS['AppConfig']['HomeURL'].'/resetpassword.php?code='.$d['code'];
                \TAS\Core\Utility::DoEmail(5, $row, $email, $GLOBALS['AppConfig']['SenderEmail']);

                return $d['code'];
            }

            return false;
        } else {
            return false;
        }
    }
}
