<?php

/**
 * This file is part of the "Registered Users" plugin for Wolf CMS.
 * Licensed under an MIT style license. For full details see license.txt.
 *
 * @author Martijn van der Kleijn <martijn.niji@gmail.com>
 * @copyright Martijn van der Kleijn, 2009-2013
 * 
 * Original author:
 * 
 * @author Andrew Waters <andrew@band-x.org>
 * @copyright Andrew Waters, 2009
 *
 */

/* Prevent direct access. */
if (!defined('IN_CMS')) {
    exit();
}

// Create default role names "user"
if (!Role::existsIn('Role', 'name="user"')) {
    $role = new Role();
    $role->name = 'user';
    $role->save();
} else {
    $role = Role::findByName('user');
}

// Create database table structures
$PDO = Record::getConnection();
$driver = strtolower($PDO->getAttribute(Record::ATTR_DRIVER_NAME));

// Setup table structure
if ($driver == 'mysql') {
    $PDO->exec("CREATE TABLE IF NOT EXISTS `" . TABLE_PREFIX . "registered_users_temp` (
                    `id` int(11) unsigned NOT NULL auto_increment,
                    `name` varchar(100) default NULL,
                    `email` varchar(255) default NULL,
                    `username` varchar(40) NOT NULL,
                    `password` varchar(40) default NULL,
                    `rand_key` varchar(32) default NULL,
                    `reg_date` varchar(40) default NULL,
                    PRIMARY KEY  (`id`),
                    UNIQUE KEY `username` (`username`),
                    UNIQUE KEY `email` (`email`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
                ");

    $PDO->exec("CREATE TABLE IF NOT EXISTS `" . TABLE_PREFIX . "permission_page` (
                    `page_id` int(25) default NULL,
                    `permission_id` int(25) default NULL
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
                ");

$settings = array(
		'allow_registrations' => '1',
		'closed_message' => '<p>We are not currently open to new registrations.</p>',
		'allow_login' => '1', 
		'allow_fb_connect' => '0',
		'connect_api_key' => '',
		'connect_secret_key' => '',
		'random_key_length' => '8',
		'random_key_type' => 'alnum',
		'login_closed_message' => '<p>Sorry, but login is currently disabled.</p>',
		'register_page' => 'register',
		'already_logged_in' => '<p>You are already logged in to the site.</p>',
		'default_permissions' => $role->id,
		'welcome_email_pt' => 'Thank you for registering with my site\r\n\r\nPlease validate your email address by clicking the link below:',
		'register_confirm_msg' => '<p>Thank you for registering. You have been sent an authorisation code that you must confirm to activate your account.</p>',
		'welcome_email_pt_foot' => 'Thanks\r\n\r\nThe Team',
		'confirm_email_subject' => 'Thanks for registering',
		'confirm_email_from' => 'registrations@mysite.com',
		'confirm_email_reply' => '',
		'confirmation_page' => 'register/confirm',
		'message_empty_name' => '<p>Please tell us your name!</p>', 
		'message_empty_email' => '<p>Please tell us your email!</p>', 
		'message_empty_username' => '<p>Please choose a username!</p>',
		'message_empty_password' => '<p>Please add a password</p>',
		'message_empty_password_confirm' => '<p>Please confirm your password</p>',
		'message_notvalid_password' => '<p>Those passwords don\'t match!</p>',
		'message_notvalid_username' => '<p>Sorry, that username is taken!</p>',
		'message_notvalid_email' => '<p>Sorry, someone at that email address already has an account!</p>',
		'message_error_technical' => '<p>There has been a Technical error</p>',
		'message_error_already_validated' => '<p>You\'ve already validated your account <small>:-)</small></p>',
		'message_need_to_register' => 'Do you need to register?',
		'auth_required_page' => 'authorisation-required',
		'auth_required_page_text' => '<p>You\'ve requested a page which you don\'t have permission to access. Please email the webmaster for help.</p>',
		'reset_text' => '<p>Please enter your email address to have a new password emailed to you</p>',

		'reset_no_email' => '<p>Please enter the email address you used to sign up with.</p>',
		'reset_page' => 'reset',
		'reset_password_subject' => 'Reset password',
		'reset_password_from' => 'reset-password@mysite.com',
		'reset_password_reply' => 'no-reply',
		'reset_email_body' => 'Your new password is: ',
		'reset_pass_type' => 'alpha',
		'reset_pass_length' => '8',
		'reset_email_confirmed' => '<p>A new password has been mailed to you.</p>',
		'welcome_message' => '<p>Your Account has been activated!</p><p>You can now login using your username and the password you chose when you registered.</p>'
);

Plugin::setAllSettings($settings, "registered_users");
