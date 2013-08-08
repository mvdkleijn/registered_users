<?php

/**
 * This file is part of the "Registered Users" plugin for Wolf CMS.
 * Licensed under an MIT style license. For full details see license.txt.
 *
 * @author Andrew Waters <andrew@band-x.org>
 * @copyright Andrew Waters, 2009
 *
 */

/*
 * Contains the following functions for the Front End :
 *
 * ru_register_page()			Use this on the page you want to have for registrations eg mysite.com/register
 * ru_login_page()				Use this on the page you want to have for logging in eg mysite.com/login
 * ru_confirm_page()			This is the page a user clicks through to validate their account
 * ru_auth_required_page()		Users who are not authorised to view the requested page will be redirected here
 * ru_reset_page()				Will allow a user to have an email sent to them with a lnk to reset their password
 * ru_logout()					A page to logout a user and return them to the hompage
 */

class RUCommon {

    function __construct() {
        AuthUser::load();
    }

    function registered_users_page_found($page) {

        // function to check access levels here
        header('Location: '.URL_PUBLIC.'login');
    }

    function registered_users_access_page_checkbox($page) {

        $PDO = Record::getConnection();
        $page_id = $page->id;

        $permissions_list = "SELECT * FROM ".TABLE_PREFIX."permission";
        $permissions_list = $PDO->prepare($permissions_list);
        $permissions_list->execute();

        echo '<div style="clear:both;"></div><hr /><h2>Access:</h2>';

        while ($permission = $permissions_list->fetchObject()) {
            $id = $permission->id;
            $name = $permission->name;
            if ($id <= '3'  ) {
            }
            else {
                echo '<input id="permission_'.$name.'" name="permission_'.$name.'" type="checkbox"';

                $permissions_check = "SELECT * FROM ".TABLE_PREFIX."permission_page WHERE page_id='$page_id'";
                $permissions_check = $PDO->prepare($permissions_check);
                $permissions_check->execute();

                while ($permissions_checked = $permissions_check->fetchObject()) {
                    $page_permission = $permissions_checked->permission_id;
                    if ($id == $page_permission) {
                        echo 'checked';
                    }
                }

                echo ' value="allowed"> <label>'.$name.'</label><br />';
            }
        }
        echo '<h3>&nbsp;</h3><hr /><h3>&nbsp;</h3>';
    }

    function registered_users_add_page_permissions($page) {

        $PDO = Record::getConnection();
        $page_id = $page->id;

        $permissions_list = "SELECT * FROM ".TABLE_PREFIX."permission";
        $permissions_list = $PDO->prepare($permissions_list);
        $permissions_list->execute();

        while ($permission = $permissions_list->fetchObject()) {
            $id = $permission->id;
            $name = $permission->name;
            if ($id <= '3'  ) {
            }
            else {
                $permission = $_POST['permission_'.$name.''];
                if ($permission == 'allowed') {
                    $add_page_permission = "INSERT INTO ".TABLE_PREFIX."permission_page VALUES ('".$page_id."','".$id."')";
                    $add_page_permission = $PDO->prepare($add_page_permission);
                    $add_page_permission->execute();
                }
            }
        }
    }

    function registered_users_edit_page_permissions($page) {

        $PDO = Record::getConnection();
        $page_id = $page->id;

        $permissions_list = "SELECT * FROM ".TABLE_PREFIX."permission";
        $permissions_list = $PDO->prepare($permissions_list);
        $permissions_list->execute();

        $delete_page_permission = "DELETE FROM ".TABLE_PREFIX."permission_page WHERE page_id = '$page_id'";
        $delete_page_permission = $PDO->prepare($delete_page_permission);
        $delete_page_permission->execute();

        while ($permission = $permissions_list->fetchObject()) {

            $id = $permission->id;
            $name = $permission->name;

            if ($id <= '3'  ) {
            }
            else {
                $permission = $_POST['permission_'.$name.''];
                if ($permission == 'allowed') {
                    $add_page_permission = "INSERT INTO ".TABLE_PREFIX."permission_page VALUES ('".$page_id."','".$id."')";
                    $add_page_permission = $PDO->prepare($add_page_permission);
                    $add_page_permission->execute();
                }
            }
        }
    }

    function registered_users_delete_page_permissions($page) {

        $PDO = Record::getConnection();
        $page_id = $page->id;
        $delete_page_permission = "DELETE FROM ".TABLE_PREFIX."permission_page WHERE page_id = '$page_id'";
        $delete_page_permission = $PDO->prepare($delete_page_permission);
        $delete_page_permission->execute();
    }

    public function resetpassword($email) {
        $settings = Plugin::getAllSettings("registered_users");

        $reset_pass_type = $settings['reset_pass_type'];
        $reset_pass_length = $settings['reset_pass_length'];
        $reset_password_subject = $settings['reset_password_subject'];
        $reset_password_from = $settings['reset_password_from'];
        $reset_email_body = $settings['reset_email_body'];
        $reset_email_confirmed = $settings['reset_email_confirmed'];

        $common = new RUCommon();
        $newpassword = $common->random_string($reset_pass_type, $reset_pass_length);
        $newpasswordencrypted = sha1($newpassword);

        $PDO = Record::getConnection();

        $updatepassword = "UPDATE ".TABLE_PREFIX."user SET password='".$newpasswordencrypted."' WHERE email='$email'";
        $updatepassword = $PDO->prepare($updatepassword);
        $updatepassword->execute();

        $subject = "$reset_password_subject";
        $headers = "From: $reset_password_from\r\nReply-To: no-reply";
        $message = ''.$reset_email_body.''.$newpassword.'' ;

        mail($email, $subject, $message, $headers);

        echo $reset_email_confirmed;
    }

    public function confirmation_email($email,$name) {
        $settings = Plugin::getAllSettings("registered_users");
        $PDO = Record::getConnection();

        $welcome_email_pt_head = $settings['welcome_email_pt'];
        $welcome_email_pt_foot = $settings['welcome_email_pt_foot'];
        $confirm_email_from = $settings['confirm_email_from'];
        $confirm_email_reply = $settings['confirm_email_reply'];
        $confirm_email_subject = $settings['confirm_email_subject'];
        $confirmation_page = $settings['confirmation_page'];
            
        $registration_settings = "SELECT * FROM ".TABLE_PREFIX."registered_users_temp WHERE email=:email";
        $stmt = $PDO->prepare($registration_settings);
        $stmt->execute(array("email" => $email));
        while ($row = $stmt->fetchObject()) {
            $rand_key = $row->rand_key; // Let's generate a Random Key that can be used to identify someone -> validate them
        }
        $subject = "$confirm_email_subject";
        $headers = "From: $confirm_email_from\r\nReply-To: $confirm_email_reply";
        $message = 'Hi '.$name.',
	
	'.$welcome_email_pt_head .'
	
	'.URL_PUBLIC . $confirmation_page. URL_SUFFIX.'?id='.$rand_key.'&email='.$email.'
	
	'. $welcome_email_pt_foot.'' ;

        mail($email, $subject, $message, $headers);
    }

    public function random_string($type, $len) {
        switch($type) {
            case 'alnum'	:
            case 'numeric'	:
            case 'nozero'	:
            case 'alpha'	:
                switch ($type) {
                    case 'alnum'	:	$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        break;
                    case 'alpha'	:	$pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        break;
                    case 'numeric'	:	$pool = '0123456789';
                        break;
                    case 'nozero'	:	$pool = '123456789';
                        break;
                }

                $str = '';
                for ($i=0; $i < $len; $i++) {
                    $str .= substr($pool, mt_rand(0, strlen($pool) -1), 1);
                }
                return $str;
                break;
            case 'unique' : return md5(uniqid(mt_rand()));
                break;
        }
    }

    function numeric($str) {
        return ( ! ereg("^[0-9\.]+$", $str)) ? FALSE : TRUE;
    }

    function alpha_numeric($str) {
        return ( ! preg_match("/^([-a-z0-9])+$/i", $str)) ? FALSE : TRUE;
    }

    function validateaccount($email,$rand_key_confirm) {

        $rand_key = $rand_key_confirm;
        $PDO = Record::getConnection();

        $check_validated = "SELECT * FROM ".TABLE_PREFIX."user WHERE email='$email'";
        $result = $PDO->prepare($check_validated);
        $result->execute();
        $count = $result->rowCount();

        if ($count > 0) {
            $settings = Plugin::getAllSettings("registered_users");
            $met = $settings["message_error_technical"];
            $message_empty_name = $settings["message_empty_name"];
            $message_empty_email = $settings["message_empty_email"];
            $message_empty_username = $settings["message_empty_username"];
            $message_empty_password = $settings["message_empty_password"];
            $message_empty_password_confirm = $settings["message_empty_password_confirm"];
            $message_notvalid_password = $settings["message_notvalid_password"];
            $message_notvalid_username = $settings["message_notvalid_username"];
            $message_notvalid_email = $settings["message_notvalid_email"];
            $message_error_already_validated = $settings["message_error_already_validated"];
            echo $message_error_already_validated;
        }
        else {
            $today = date('Y-m-d G:i:s');
            $registration_temp = "SELECT * FROM ".TABLE_PREFIX."registered_users_temp WHERE email='$email'";
            foreach ($PDO->query($registration_temp) as $row) {
                $name = $row['name'];
                $email = $row['email'];
                $username = $row['username'];
                $password = $row['password'];
                $rand_key = $row['rand_key'];
                $reg_date = $row['reg_date'];
                $welcome_message = $row['welcome_message'];
                $message_notvalid_password = $row['message_notvalid_password'];
            }

            if ($rand_key_confirm == $rand_key) {
                // Let's transfer the user from the temp table to the user table
                //$update_user_table = "INSERT INTO ".TABLE_PREFIX."user (`id`,`name`,`email`,`username`,`password`,`created_on`,`updated_on`,`created_by_id`,`updated_by_id`) VALUES	('','$name','$email','$username','$password','$reg_date','$today','','');";
                //$stmt = $__CMS_CONN__->prepare($update_user_table);
                //$stmt->execute();
                $user = new User();
                $user->name = $name;
                $user->email = $email;
                $user->username = $username;
                $user->salt = AuthUser::generateSalt();
                $user->password = AuthUser::generateHashedPassword($password, $user->salt);
                $user->created_on = $reg_date;
                $user->updated_on = $today;
                $user->save();
                // We don't need them in the temp table anymore
                $delete_temp_user ="DELETE FROM ".TABLE_PREFIX."registered_users_temp WHERE email='$email'";
                $stmt = $PDO->prepare($delete_temp_user);
                $stmt->execute();
                // And let's make sure we have some permissions set so that user can then do something!
                // First we need the default permssion ID
                $def_permission = Plugin::getSetting("default_permissions", "registered_users");
                // Then we need the correct user ID
                /*$user = "SELECT * FROM ".TABLE_PREFIX."user WHERE email='$email'";
                foreach ($__CMS_CONN__->query($user) as $row) {
                    $id = $row['id'];
                }*/
                $id = $user->id;
                $set_permissions ="INSERT INTO ".TABLE_PREFIX."user_role (`user_id`,`role_id`) VALUES ('$id','$permission_id');";
                $stmt = $PDO->prepare($set_permissions);
                $stmt->execute();
                // We also need to add the profile settings into DB
                $addprofile ="INSERT INTO ".TABLE_PREFIX."user_profile (`id`,`firstlogin`,`subscribe`,`sysnotifications`,`haspic`,`profile_blurb`) VALUES ($id,'1','1','1','0','your public profile...');";
                $addprofile = $PDO->prepare($addprofile);
                $addprofile->execute();
                echo $welcome_message;
                $loadloginclass = new RegisteredUser();
                $loadloginclass->login_page();
            }
            else {
                echo $message_notvalid_password;
            }
        }
    }
}
