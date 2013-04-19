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

    $PDO->exec("CREATE TABLE IF NOT EXISTS `" . TABLE_PREFIX . "registered_users_settings` (
            		`id` int(1) default NULL,
            		`allow_registrations` int(1) default NULL,
		            `closed_message` TEXT default NULL,
		            `reset_form` TEXT default NULL,
		            `registration_form` TEXT default NULL,
		            `allow_login` int(1) default NULL,
		            `allow_fb_connect` int(1) default NULL,
		            `connect_api_key` varchar(32) default NULL,
		            `connect_secret_key` varchar(32) default NULL,
		            `random_key_length` int(4) default NULL,
		            `random_key_type` varchar(20) default NULL,
		            `login_closed_message` TEXT default NULL,
		            `register_page` varchar(150) default NULL,
		            `already_logged_in` TEXT default NULL,
		            `default_permissions` int(3) default NULL,
		            `welcome_email_pt` TEXT default NULL,
		            `register_confirm_msg` TEXT default NULL,
		            `welcome_email_pt_foot` TEXT default NULL,
		            `confirm_email_subject` varchar(250) default NULL,
		            `confirm_email_from` varchar(250) default NULL,
		            `confirm_email_reply` varchar(250) default NULL,
		            `confirmation_page` varchar(150) default NULL,
		            `auth_form` TEXT default NULL,
		            `message_empty_name` TEXT default NULL,
		            `message_empty_email` TEXT default NULL,
		            `message_empty_username` TEXT default NULL,
		            `message_empty_password` TEXT default NULL,
		            `message_empty_password_confirm` TEXT default NULL,
		            `message_notvalid_password` TEXT default NULL,
		            `message_notvalid_username` TEXT default NULL,
		            `message_notvalid_email` TEXT default NULL,
		            `message_error_technical` TEXT default NULL,
		            `message_error_already_validated` TEXT default NULL,
		            `message_need_to_register` TEXT default NULL,
		            `auth_required_page` varchar(100) default NULL,
		            `auth_required_page_text` TEXT default NULL,
		            `reset_text` TEXT default NULL,
		            `reset_no_email` TEXT default NULL,
		            `reset_page` varchar(50) default NULL,
		            `reset_password_subject` TEXT default NULL,
		            `reset_password_from` TEXT default NULL,
		            `reset_password_reply` TEXT default NULL,
		            `reset_email_body` TEXT default NULL,
		            `reset_pass_type` TEXT default NULL,
		            `reset_pass_length` TEXT default NULL,
		            `reset_email_confirmed` TEXT default NULL,
		            `welcome_message` TEXT default NULL
	            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
                ");
}

$settings_insert = "
	INSERT INTO `" . TABLE_PREFIX . "registered_users_settings` (
		`id`,
		`allow_registrations`,
		`closed_message`,
		`reset_form`,
		`registration_form`,
		`allow_login`,
		`allow_fb_connect`,
		`connect_api_key`,
		`connect_secret_key`,
		`random_key_length`,
		`random_key_type`,
		`login_closed_message`,
		`register_page`,
		`already_logged_in`,
		`default_permissions`,
		`welcome_email_pt`,
		`register_confirm_msg`,
		`welcome_email_pt_foot`,
		`confirm_email_subject`,
		`confirm_email_from`,
		`confirm_email_reply`,
		`confirmation_page`,
		`auth_form`,
		`message_empty_name`,
		`message_empty_email`,
		`message_empty_username`,
		`message_empty_password`,
		`message_empty_password_confirm`,
		`message_notvalid_password`,
		`message_notvalid_username`,
		`message_notvalid_email`,
		`message_error_technical`,
		`message_error_already_validated`,
		`message_need_to_register`,
		`auth_required_page`,
		`auth_required_page_text`,
		`reset_text`,
		`reset_no_email`,
		`reset_page`,
		`reset_password_subject`,
		`reset_password_from`,
		`reset_password_reply`,
		`reset_email_body`,
		`reset_pass_type`,
		`reset_pass_length`,
		`reset_email_confirmed`,
		`welcome_message` )
	VALUES (
		'1',
		'1',
		'<p>We are not currently open to new registrations.</p>',
		'<p><label for=\"email\">Email :</label> <input id=\"email\" type=\"text\" name=\"email\" value=\"\" /></p>
<p><label for=\"submit\"></label> <input id=\"submit_btn\" class=\"btn submit\" type=\"submit\" accesskey=\"s\" value=\"Reset your password\" /></p>',
		'<p><label for=\"name\">Name</label>\n<input class=\"text-input validate[\'required\']\" id=\"name\" maxlength=\"100\" name=\"name\" size=\"20\" type=\"text\" value=\"\" /></p>\n<p><label class=\"optional\" for=\"email\">E-mail</label>\n<input class=\"text-input validate[\'required\',\'email\']\" id=\"email\" maxlength=\"40\" name=\"email\" size=\"20\" type=\"text\" value=\"\" /></p>\n<p><label for=\"username\">Username</label>\n<input class=\"text-input validate[\'required\']\" id=\"username\" maxlength=\"40\" name=\"username\" size=\"20\" type=\"text\" value=\"\" /></p>\n<p><label for=\"password\">Password</label>\n<input class=\"text-input validate[\'required\']\" id=\"password\" maxlength=\"40\" name=\"password\" size=\"20\" type=\"password\" value=\"\" /></p>\n<p><label for=\"confirm_pass\">Confirm Password</label>\n<input class=\"text-input validate[\'required\',\'confirm[password]\']\" id=\"confirm_pass\" maxlength=\"40\" name=\"confirm_pass\" size=\"20\" type=\"password\" value=\"\" /></p>\n<p><label for=\"signup\">&nbsp;</label><input id=\"submit_btn\" class=\"btn submit\" type=\"submit\" accesskey=\"s\" value=\"Register\" /></p>',
		'1',
		'0',
		'',
		'',
		'8',
		'alnum',
		'<p>Sorry, but login is currently disabled.</p>',
		'register',
		'<p>You are already logged in to the site.</p>',
		'" . $role->id . "',
		'Thank you for registering with my site\r\n\r\nPlease validate your email address by clicking the link below:',
		'<p>Thank you for registering. You have been sent an authorisation code that you must confirm to activate your account.</p>',
		'Thanks\r\n\r\nThe Team',
		'Thanks for registering',
		'registrations@mysite.com',
		'',
		'register/confirm',
		'<p><label for=\"email\">Email :</label> <input id=\"email\" type=\"text\" name=\"email\" value=\"\" /></p>\r\n<p><label for=\"rand_key\">Authorisation Code :</label> <input id=\"rand_key\" type=\"text\" name=\"rand_key\" value=\"\" /></p>\r\n<p><label for=\"submit\"></label><input id=\"submit_btn\" class=\"btn submit\" type=\"submit\" accesskey=\"s\" value=\"Activate your account\" /></p>',
		'<p>Please tell us your name!</p>',
		'<p>Please tell us your email!</p>',
		'<p>Please choose a username!</p>',
		'<p>Please add a password</p>',
		'<p>Please confirm your password</p>',
		'<p>Those passwords don\'t match!</p>',
		'<p>Sorry, that username is taken!</p>',
		'<p>Sorry, someone at that email address already has an account!</p>',
		'<p>There has been a Technical error</p>',
		'<p>You\'ve already validated your account <small>:-)</small></p>',
		'Do you need to register?',
		'authorisation-required',
		'<p>You\'ve requested a page which you don\'t have permission to access. Please email the webmaster for help.</p>',
		'<p>Please enter your email address to have a new password emailed to you</p>',
		'<p>Please enter the email address you used to sign up with.</p>',
		'reset',
		'Reset password',
		'reset-password@mysite.com',
		'no-reply',
		'Your new password is: ',
		'alpha',
		'8',
		'<p>A new password has been mailed to you.</p>',
		'<p>Your Account has been activated!</p><p>You can now login using your username and the password you chose when you registered.</p>' );
        ";

$stmt = $PDO->prepare($settings_insert);
$stmt->execute();