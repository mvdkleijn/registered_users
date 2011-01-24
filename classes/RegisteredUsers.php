<?php

/**
 * This file is part of the "Registered Users" plugin for Wolf CMS.
 * Licensed under an MIT style license. For full details see license.txt.
 *
 * @author Andrew Waters <andrew@band-x.org>
 * @copyright Andrew Waters, 2009
 *
 * @author Martijn van der Kleijn <martijn.niji@gmail.com>
 * @copyright Martijn van der Kleijn, 2009
 *
 */

/**
 * Contains the following functions for the Front End :
 *
 * ru_register_page()			Use this on the page you want to have for registrations eg mysite.com/register
 * ru_login_page()				Use this on the page you want to have for logging in eg mysite.com/login
 * ru_confirm_page()			This is the page a user clicks through to validate their account
 * ru_auth_required_page()		Users who are not authorised to view the requested page will be redirected here
 * ru_reset_page()				Will allow a user to have an email sent to them with a lnk to reset their password
 * ru_logout()					A page to logout a user and return them to the hompage
 */

/* TODO - improve OO, cleanup. */
class RegisteredUsers {

    function __construct() {
        AuthUser::load();
    }

    public function login_page() {

        global $__CMS_CONN__;
        // Only one Row in registration_settings table by default so id='1'...
        // if you need more you can, but you'll probably be writing most of this function again!
        $id = '1';
        $registration_settings = "SELECT * FROM ".TABLE_PREFIX."registered_users_settings WHERE id='$id'";
        foreach ($__CMS_CONN__->query($registration_settings) as $row) {
            $al = $row['allow_login'];
            $lf = $row['login_form'];
            $cl = $row['login_closed_message'];
            $rp = $row['register_page'];
            $ali = $row['already_logged_in'];
            $met = $row['message_error_technical'];
            $message_need_to_register = $row['message_need_to_register'];
        }

        if (AuthUser::isLoggedIn()) {
            echo $ali;
        }
        else {
            // Check the login status
            if ($al == '1') { // Open
                echo '<form action="'.BASE_URL.''.ADMIN_DIR.'/login/login" method="post">';
                echo $lf; // Show Login Form
                echo '</form>
				<p><a href="'.URL_PUBLIC.''.$rp.''.URL_SUFFIX.'">'.$message_need_to_register.'</a></p>';
            }
            elseif ($al == '0') { //Closed
                echo $cl; // Show No Login Allowed Message - useful for maintenance and upgrade time
            }
            else { // You will get this message if the allow_login row in the registration_settings table value does not equal 1 (open) or 0 (closed)
                echo $met;
            }
        }
    }


    public function registration_page() {

        global $__CMS_CONN__;
        // Only one Row in registration_settings table by default so id='1'...
        // if you need more you can, but you'll probably be writing most of this function again!
        $id = '1';
        $registration_settings = "SELECT * FROM ".TABLE_PREFIX."registered_users_settings WHERE id='$id'";
        foreach ($__CMS_CONN__->query($registration_settings) as $row) {
            $al = $row['allow_login'];
            $lf = $row['login_form'];
            $cl = $row['login_closed_message'];
            $rp = $row['register_page'];
            $ali = $row['already_logged_in'];
            $len = $row['random_key_length'];
            $type = $row['random_key_type'];
        }

        if (AuthUser::isLoggedIn()) {
            echo $ali;
        }
        else {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                // This is a quick bit of PHP form validation - I'd recommend some nice Javascript validation in addition if you can be bothered :)
                // Double the importance if you're capturing extra fields on signup...
                global $__CMS_CONN__;

                $name = mysql_escape_string($_POST['name']);
                $email = mysql_escape_string($_POST['email']);
                $username = mysql_escape_string($_POST['username']);
                $password = mysql_escape_string($_POST['password']);
                $confirm_pass = mysql_escape_string($_POST['confirm_pass']);

                $registration_settings = "SELECT * FROM ".TABLE_PREFIX."registered_users_settings WHERE id='1'";
                $registration_settings = $__CMS_CONN__->prepare($registration_settings);
                $registration_settings->execute();

                while ($settings = $registration_settings->fetchObject()) {

                    $met = $settings->message_error_technical;
                    $message_empty_name = $settings->message_empty_name;
                    $message_empty_email = $settings->message_empty_email;
                    $message_empty_username = $settings->message_empty_username;
                    $message_empty_password = $settings->message_empty_password;
                    $message_empty_password_confirm = $settings->message_empty_password_confirm;
                    $message_notvalid_password = $settings->message_notvalid_password;
                    $message_notvalid_username = $settings->message_notvalid_username;
                    $message_notvalid_email = $settings->message_notvalid_email;
                    $type = $settings->random_key_type;
                    $len = $settings->random_key_length;

                }

                if(empty($_POST['name'])) {
                    echo $message_empty_name;
                }

                elseif(empty($_POST['email'])) {
                    echo $message_empty_email;
                }

                elseif(empty($_POST['username'])) {
                    echo $message_empty_username;
                }

                elseif(empty($_POST['password'])) {
                    echo $message_empty_password;
                }

                elseif(empty($_POST['confirm_pass'])) {
                    echo $message_empty_password_confirm;
                }

                elseif(($_POST['password']) != ($_POST['confirm_pass']) ) {
                    echo $message_notvalid_password;
                }

                else {
                    // Check for unique username
                    global $__CMS_CONN__;

                    // Check User Table
                    $check_unique_username = "SELECT * FROM ".TABLE_PREFIX."user WHERE username='$username'";
                    $result = $__CMS_CONN__->prepare($check_unique_username);
                    $result->execute();
                    $count = $result->rowCount();

                    // Check Temp User Table
                    $check_unique_username_temp = "SELECT * FROM ".TABLE_PREFIX."registered_users_temp WHERE username='$username'";
                    $check_unique_username_temp = $__CMS_CONN__->prepare($check_unique_username_temp);
                    $check_unique_username_temp->execute();
                    $check_unique_username_temp = $check_unique_username_temp->rowCount();

                    if ($count == '1' || $check_unique_username_temp == '1') {
                        echo $message_notvalid_username;
                    }
                    else {
                        // We want to make sure that email isn't already registered
                        global $__CMS_CONN__;

                        // Check in Main User Table
                        $check_unique_email = "SELECT * FROM ".TABLE_PREFIX."user WHERE email='$email'";
                        $result = $__CMS_CONN__->prepare($check_unique_email);
                        $result->execute();
                        $count = $result->rowCount();

                        // Check Temp User Table
                        $check_unique_email_temp = "SELECT * FROM ".TABLE_PREFIX."registered_users_temp WHERE email='$email'";
                        $check_unique_email_temp = $__CMS_CONN__->prepare($check_unique_email_temp);
                        $check_unique_email_temp->execute();
                        $check_unique_email_temp = $check_unique_email_temp->rowCount();

                        if ($count == 1 || $check_unique_email_temp == 1) {
                            echo $message_notvalid_email;
                        }
                        else {
                            $common = new RUCommon();
                            $random_key = $common->random_string($type, $len);
                            //$password = sha1($password);
                            $today = date('Y-m-d G:i:s');
                            global $__CMS_CONN__;
                            $sql = "INSERT INTO ".TABLE_PREFIX."registered_users_temp VALUES ('','".$name."','".$email."','".$username."','".$password."','".$random_key."','".$today."')";
                            $stmt = $__CMS_CONN__->prepare($sql);
                            $stmt->execute();
                            $common->confirmation_email($email,$name);
                            $registration_settings = "SELECT * FROM ".TABLE_PREFIX."registered_users_settings WHERE id='1'";
                            foreach ($__CMS_CONN__->query($registration_settings) as $row) {
                                $register_confirm_msg = $row['register_confirm_msg'];
                            }
                            echo $register_confirm_msg;
                        }
                    }
                }
            }
            else {
                global $__CMS_CONN__;
                $registration_settings = "SELECT * FROM ".TABLE_PREFIX."registered_users_settings WHERE id='1'";
                foreach ($__CMS_CONN__->query($registration_settings) as $row) {
                    $register_page = $row['register_page'];
                }
                echo '<form id="registration" class="registration" action="'.URL_PUBLIC.''.$register_page.''.URL_SUFFIX.'" method="post">';

                global $__CMS_CONN__;
                // Only one Row in registration_settings table by default so id='1'...
                // if you need more you can, but you'll probably be writing most of this function again!
                $id = '1';
                $registration_settings = "SELECT * FROM ".TABLE_PREFIX."registered_users_settings WHERE id='$id'";
                foreach ($__CMS_CONN__->query($registration_settings) as $row) {
                    $ar = $row['allow_registrations'];
                    $met = $row['message_error_technical'];
                    $cm = $row['closed_message'];
                    $rf = $row['registration_form'];
                }
                // Check the registration status
                if ($ar == '1') { // if registration is Open
                    echo $rf; // Show Registration Form
                }
                elseif ($ar == '0') { // if registration is Closed
                    echo $cm; // Show Closed Shop Message - useful for testing with closed set of users
                }
                else { // You will get this message if the allow_registration row in the registration_settings table value does not equal 1 (open) or 0 (closed)
                    echo $met;
                }


                echo '</form>';
            }
        }
    }

    function confirm() {

        $common = new RUCommon();

        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            // Get the confirmation ID and email address
            $rand_key_confirm = $_GET['id'];
            $email = $_GET['email'];

            if(empty($_GET['id']) || empty($_GET['email'])) {
                echo '<p>Please enter your details manually, we are experiencing a technical problem</p>';
                global $__CMS_CONN__;
                $registration_settings = "SELECT * FROM ".TABLE_PREFIX."registered_users_settings WHERE id='1'";
                foreach ($__CMS_CONN__->query($registration_settings) as $row) {
                    $confirmation_page = $row['confirmation_page'];
                    $auth_form = $row['auth_form'];
                }
                echo '<form action="'.URL_PUBLIC.''.$confirmation_page.''.URL_SUFFIX.'" method="post">';
                echo $auth_form;
                echo '</form>';
            }
            else {
                $common->validateaccount($email, $rand_key_confirm);
            }
        }
        elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $email = mysql_escape_string($_POST['email']);
            $rand_key_confirm = mysql_escape_string($_POST['rand_key']);

            if(empty($_POST['email'])) {
                echo '<p>Please enter your email</p>';
            }

            if(empty($_POST['rand_key'])) {
                echo '<p>Please enter your Authorisation Code</p>';
            }
            else {
                $common->validateaccount($email, $rand_key_confirm);
            }
        }
        else {

            global $__CMS_CONN__;

            $registration_settings = "SELECT * FROM ".TABLE_PREFIX."registered_users_settings WHERE id='1'";
            foreach ($__CMS_CONN__->query($registration_settings) as $row) {
                $confirmation_page = $row['confirmation_page'];
                $auth_form = $row['auth_form'];
            }
            echo '<form action="'.URL_PUBLIC.''.$confirmation_page.''.URL_SUFFIX.'" method="post">';
            echo $auth_form;
            echo '</form>';
        }
    }

    function auth_required_page() {

        global $__CMS_CONN__;

        $registration_settings = "SELECT * FROM ".TABLE_PREFIX."registered_users_settings WHERE id='1'";
        foreach ($__CMS_CONN__->query($registration_settings) as $row) {
            $auth_required_page_text = $row['auth_required_page_text'];
        }

        echo $auth_required_page_text;
    }

    function password_reset() {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            global $__CMS_CONN__;

            $email = mysql_escape_string($_POST['email']);

            $registration_settings = "SELECT * FROM ".TABLE_PREFIX."registered_users_settings WHERE id='1'";
            foreach ($__CMS_CONN__->query($registration_settings) as $row) {
                $reset_no_email = $row['reset_no_email'];
            }

            if(empty($_POST['email'])) {
                echo $reset_no_email;
            }
            else {
                $common = new RUCommon();
                $common->resetpassword($email);
            }
        }
        else {
            global $__CMS_CONN__;
            $registration_settings = "SELECT * FROM ".TABLE_PREFIX."registered_users_settings WHERE id='1'";
            foreach ($__CMS_CONN__->query($registration_settings) as $row) {
                $reset_form = $row['reset_form'];
                $reset_page = $row['reset_page'];
                $reset_text = $row['reset_text'];
            }
            echo $reset_text;
            echo '<form action="'.URL_PUBLIC.''.$reset_page.''.URL_SUFFIX.'" method="post">';
            echo $reset_form;
            echo '</form>';
        }
    }

}