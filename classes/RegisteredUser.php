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
 * The RegisteredUser class contains the features needed to register a new user.
 * 
 * @todo Look to see if "frontend" functions can be removed
 */
class RegisteredUser {

    function __construct() {
        AuthUser::load();
    }

    public function login_page() {
        $settings = Plugin::getAllSettings("registered_users");
        $al = $settings['allow_login'];
        //$lf = $row['login_form'];
        $lf = new View('../../plugins/registered_users/views/login');
        $cl = $settings['login_closed_message'];
        $rp = $settings['register_page'];
        $ali = $settings['already_logged_in'];
        $met = $settings['message_error_technical'];
        $message_need_to_register = $settings['message_need_to_register'];

        if (AuthUser::isLoggedIn()) {
            echo $ali;
        } else {
            // Check the login status
            if ($al == '1') { // Open
                echo '<form action="' . BASE_URL . '' . ADMIN_DIR . '/login/login" method="post">';
                echo $lf; // Show Login Form
                echo '</form>
				<p><a href="' . URL_PUBLIC . '' . $rp . '' . URL_SUFFIX . '">' . $message_need_to_register . '</a></p>';
            } elseif ($al == '0') { //Closed
                echo $cl; // Show No Login Allowed Message - useful for maintenance and upgrade time
            } else { // You will get this message if the allow_login row in the registration_settings table value does not equal 1 (open) or 0 (closed)
                echo $met;
            }
        }
    }


    public function registration_page() {

        $settings = Plugin::getAllSettings("registered_users");
        $al = $settings['allow_login'];
        //$lf = $settings['login_form'];
        $cl = $settings['login_closed_message'];
        //$rp = $row['register_page'];
        $rp = new View('../../plugins/registered_users/views/registration');
        $ali = $settings['already_logged_in'];
        $len = $settings['random_key_length'];
        $type = $settings['random_key_type'];

        if (AuthUser::isLoggedIn()) {
            echo $ali;
        } else {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                // This is a quick bit of PHP form validation - I'd recommend some nice Javascript validation in addition if you can be bothered :)
                // Double the importance if you're capturing extra fields on signup...
                global $__CMS_CONN__;

                $PDO = Record::getConnection();
                $name = $_POST['name'];
                $email = $_POST['email'];
                $username = $_POST['username'];
                $password = $_POST['password'];
                $confirm_pass = $_POST['confirm_pass'];

                $met = $settings["message_error_technical"];
                $message_empty_name = $settings["message_empty_name"];
                $message_empty_email = $settings["message_empty_email"];
                $message_empty_username = $settings["message_empty_username"];
                $message_empty_password = $settings["message_empty_password"];
                $message_empty_password_confirm = $settings["message_empty_password_confirm"];
                $message_notvalid_password = $settings["message_notvalid_password"];
                $message_notvalid_username = $settings["message_notvalid_username"];
                $message_notvalid_email = $settings["message_notvalid_email"];
                $type = $settings["random_key_type"];
                $len = $settings["random_key_length"];

                if (empty($_POST['name'])) {
                    echo $message_empty_name;
                } elseif (empty($_POST['email'])) {
                    echo $message_empty_email;
                } elseif (empty($_POST['username'])) {
                    echo $message_empty_username;
                } elseif (empty($_POST['password'])) {
                    echo $message_empty_password;
                } elseif (empty($_POST['confirm_pass'])) {
                    echo $message_empty_password_confirm;
                } elseif (($_POST['password']) != ($_POST['confirm_pass'])) {
                    echo $message_notvalid_password;
                } else {
                    // Check for unique username
                    $PDO = Record::getConnection();

                    // Check User Table
                    $check_unique_username = "SELECT * FROM " . TABLE_PREFIX . "user WHERE username=:username";
                    $result = $PDO->prepare($check_unique_username);
                    $result->execute(array("username" => $username));
                    $count = $result->rowCount();

                    // Check Temp User Table
                    $check_unique_username_temp = "SELECT * FROM " . TABLE_PREFIX . "registered_users_temp WHERE username=:username";
                    $check_unique_username_temp = $PDO->prepare($check_unique_username_temp);
                    $check_unique_username_temp->execute(array("username" => $username));
                    $check_unique_username_temp = $check_unique_username_temp->rowCount();

                    if ($count == '1' || $check_unique_username_temp == '1') {
                        echo $message_notvalid_username;
                    } else {
                        // We want to make sure that email isn't already registered
                        global $__CMS_CONN__;

                        // Check in Main User Table
                        $check_unique_email = "SELECT * FROM " . TABLE_PREFIX . "user WHERE email=:email";
                        $result = $PDO->prepare($check_unique_email);
                        $result->execute(array("email" => $email));
                        $count = $result->rowCount();

                        // Check Temp User Table
                        $check_unique_email_temp = "SELECT * FROM " . TABLE_PREFIX . "registered_users_temp WHERE email=:email";
                        $check_unique_email_temp = $PDO->prepare($check_unique_email_temp);
                        $check_unique_email_temp->execute(array("email" => $email));
                        $check_unique_email_temp = $check_unique_email_temp->rowCount();

                        if ($count == 1 || $check_unique_email_temp == 1) {
                            echo $message_notvalid_email;
                        } else {
                            $common = new RUCommon();
                            $random_key = $common->random_string($type, $len);
                            //$password = sha1($password);
                            $today = date('Y-m-d G:i:s');
                            $sql = "INSERT INTO " . TABLE_PREFIX . "registered_users_temp (name, email, username, password, rand_key, reg_date) VALUES (:name, :email, :username , :password, :random_key, :today)";
                            $PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            $stmt = $PDO->prepare($sql);
                            $stmt->execute(array(
                                "name" => $name,
                                "email" => $email,
                                "username" => $username,
                                "password" => $password,
                                "random_key" => $random_key,
                                "today" => $today
                            ));
                            $common->confirmation_email($email, $name);
                                $register_confirm_msg = $settings['register_confirm_msg'];
                            echo $register_confirm_msg;
                        }
                    }
                }
            } else {
                $register_page = $settings['register_page'];
                echo '<form id="registration" class="registration" action="' . URL_PUBLIC . '' . $register_page . '' . URL_SUFFIX . '" method="post">';

                $ar = $settings['allow_registrations'];
                $met = $settings['message_error_technical'];
                $cm = $settings['closed_message'];
                //$rf = $row['registration_form'];
                $rf = new View('../../plugins/registered_users/views/registration');
                // Check the registration status
                if ($ar == '1') { // if registration is Open
                    echo $rf; // Show Registration Form
                } elseif ($ar == '0') { // if registration is Closed
                    echo $cm; // Show Closed Shop Message - useful for testing with closed set of users
                } else { // You will get this message if the allow_registration row in the registration_settings table value does not equal 1 (open) or 0 (closed)
                    echo $met;
                }


                echo '</form>';
            }
        }
    }

    function confirm() {

        $common = new RUCommon();

        $confirmation_page = Plugin::getSetting('confirmation_page', "registered_users");

        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            if (!isset($_GET['id']) || !isset($_GET['email']) || empty($_GET['id']) || empty($_GET['email'])) {
                echo '<p>Please enter your details manually, we are experiencing a technical problem</p>';
                //$confirmation_page = new View('../../plugins/registered_users/views/confirm');
                //$auth_form = $row['auth_form'];
                $auth_form = new View('../../plugins/registered_users/views/confirm');
                echo '<form action="' . URL_PUBLIC . '' . $confirmation_page . '' . URL_SUFFIX . '" method="post">';
                echo $auth_form;
                echo '</form>';
            } else {
                // Get the confirmation ID and email address
                $rand_key_confirm = $_GET['id'];
                $email = $_GET['email'];

                $common->validateaccount($email, $rand_key_confirm);
            }
        } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $email = trim($_POST['email']);
            $rand_key_confirm = trim($_POST['rand_key']);

            if (empty($_POST['email'])) {
                echo '<p>Please enter your email</p>';
            }

            if (empty($_POST['rand_key'])) {
                echo '<p>Please enter your Authorisation Code</p>';
            } else {
                $common->validateaccount($email, $rand_key_confirm);
            }
        } else {
            $auth_form = new View('../../plugins/registered_users/views/confirm');
            echo '<form action="' . URL_PUBLIC . '' . $confirmation_page . '' . URL_SUFFIX . '" method="post">';
            echo $auth_form;
            echo '</form>';
        }
    }

    function auth_required_page() {
        $auth_required_page_text = Plugin::getSetting('auth_required_page_text', "registered_users");
        echo $auth_required_page_text;
    }

    function password_reset() {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($_POST['email']);
            $reset_no_email = Plugin::getSetting('reset_no_email', "registered_users");

            if (empty($_POST['email'])) {
                echo $reset_no_email;
            } else {
                $common = new RUCommon();
                $common->resetpassword($email);
            }
        } else {
            $reset_form = new View('../../plugins/registered_users/views/reset');
            $reset_page = Plugin::getSetting('reset_page', "registered_users");
            $reset_text = Plugin::getSetting('reset_text', "registered_users");
            echo $reset_text;
            echo '<form action="'.URL_PUBLIC.''.$reset_page.''.URL_SUFFIX.'" method="post">';
            echo $reset_form;
            echo '</form>';
        }
    }

}