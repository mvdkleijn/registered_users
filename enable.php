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
 *	ru_register_page()			Use this on the page you want to have for registrations eg mysite.com/register
 *	ru_login_page()				Use this on the page you want to have for logging in eg mysite.com/login
 *	ru_confirm_page()			This is the page a user clicks through to validate their account
 *	ru_auth_required_page()		Users who are not authorised to view the requested page will be redirected here
 *	ru_reset_page()				Will allow a user to have an email sent to them with a lnk to reset their password
 *	ru_logout()					A page to logout a user and return them to the hompage
 */

global $__CMS_CONN__;

// Check for temporary user table and install if it doesn't exist
$check_registered_users_temp = "SELECT * FROM ".TABLE_PREFIX."registered_users_temp";
$check_registered_users_temp = $__CMS_CONN__->prepare($check_registered_users_temp);
$check_registered_users_temp->execute();
$check_registered_users_temp = $check_registered_users_temp->rowCount();

if ($check_registered_users_temp == 0) {
    $create_temp_users = '
		CREATE TABLE `'.TABLE_PREFIX.'registered_users_temp` (
		`id` int(11) unsigned NOT NULL auto_increment,
		`name` varchar(100) default NULL,
		`email` varchar(255) default NULL,
		`username` varchar(40) NOT NULL,
		`password` varchar(40) default NULL,
		`rand_key` varchar(32) default NULL,
		`reg_date` varchar(40) default NULL,
		PRIMARY KEY  (`id`),
		UNIQUE KEY `username` (`username`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;';
    $stmt = $__CMS_CONN__->prepare($create_temp_users);
    $stmt->execute();
}


// Check for permission_page and install if it doesn't exist

$check_permission_page = "SELECT * FROM ".TABLE_PREFIX."permission_page";
$check_permission_page = $__CMS_CONN__->prepare($check_permission_page);
$check_permission_page->execute();
$check_permission_page = $check_permission_page->rowCount();

if ($check_permission_page == 0) {
    $create_page_permissions = '
		CREATE TABLE `'.TABLE_PREFIX.'permission_page` (
		`page_id` int(25) default NULL,
		`permission_id` int(25) default NULL
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;';
    $stmt = $__CMS_CONN__->prepare($create_page_permissions);
    $stmt->execute();
}


// Check for registration settings and install if it doesn't exist

$check_registration_settings = "SELECT * FROM ".TABLE_PREFIX."registered_users_settings";
$check_registration_settings = $__CMS_CONN__->prepare($check_registration_settings);
$check_registration_settings->execute();
$check_registration_settings = $check_registration_settings->rowCount();

if ($check_registration_settings == 0) {
    $create_settings = 'CREATE TABLE `'.TABLE_PREFIX.'registered_users_settings` (
		`id` int(1) default NULL,
		`allow_registrations` int(1) default NULL,
		`closed_message` varchar(1000) default NULL,
		`login_form` varchar(1000) default NULL,
		`reset_form` varchar(1000) default NULL,
		`registration_form` varchar(5000) default NULL,
		`allow_login` int(1) default NULL,
		`allow_fb_connect` int(1) default NULL,
		`connect_api_key` varchar(32) default NULL,
		`connect_secret_key` varchar(32) default NULL,
		`random_key_length` int(4) default NULL,
		`random_key_type` varchar(20) default NULL,
		`login_closed_message` varchar(1000) default NULL,
		`register_page` varchar(150) default NULL,
		`already_logged_in` varchar(1000) default NULL,
		`default_permissions` int(3) default NULL,
		`welcome_email_pt` varchar(1000) default NULL,
		`register_confirm_msg` varchar(5000) default NULL,
		`welcome_email_pt_foot` varchar(5000) default NULL,
		`confirm_email_subject` varchar(250) default NULL,
		`confirm_email_from` varchar(250) default NULL,
		`confirm_email_reply` varchar(250) default NULL,
		`confirmation_page` varchar(150) default NULL,
		`auth_form` varchar(5000) default NULL,
		`message_empty_name` varchar(1000) default NULL,
		`message_empty_email` varchar(1000) default NULL,
		`message_empty_username` varchar(1000) default NULL,
		`message_empty_password` varchar(1000) default NULL,
		`message_empty_password_confirm` varchar(1000) default NULL,
		`message_notvalid_password` varchar(1000) default NULL,
		`message_notvalid_username` varchar(1000) default NULL,
		`message_notvalid_email` varchar(1000) default NULL,
		`message_error_technical` varchar(1000) default NULL,
		`message_error_already_validated` varchar(1000) default NULL,
		`message_need_to_register` varchar(1000) default NULL,
		`auth_required_page` varchar(100) default NULL,
		`auth_required_page_text` varchar(1000) default NULL,
		`reset_text` varchar(1000) default NULL,
		`reset_no_email` varchar(1000) default NULL,
		`reset_page` varchar(50) default NULL,
		`reset_password_subject` varchar(1000) default NULL,
		`reset_password_from` varchar(1000) default NULL,
		`reset_password_reply` varchar(1000) default NULL,
		`reset_email_body` varchar(1000) default NULL,
		`reset_pass_type` varchar(1000) default NULL,
		`reset_pass_length` varchar(1000) default NULL,
		`reset_email_confirmed` varchar(1000) default NULL,
		`welcome_message` varchar(1000) default NULL

	) ENGINE=MyISAM DEFAULT CHARSET=latin1;';

    $settings_insert = "
	INSERT INTO `".TABLE_PREFIX."registered_users_settings` (
		`id`,
		`allow_registrations`,
		`closed_message`,
		`login_form`,
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
		'<p><label for=\"login-username\">Username</label> <input id=\"login-username\" type=\"text\" name=\"login[username]\" value=\"\" /></p>\r\n<p><label for=\"login-password\">Password</label> <input id=\"login-password\" type=\"password\" name=\"login[password]\" value=\"\" /></p>\r\n<input id=\"login-redirect\" type=\"hidden\" name=\"login[redirect]\" value=\"/\" />\r\n<p><label class=\"checkbox\" for=\"login-remember-me\">Stay logged in </label> <input id=\"login-remember-me\" type=\"checkbox\" class=\"checkbox\" name=\"login[remember]\" value=\"checked\" /></p>\r\n<p><label for=\"submit\">&nbsp;</label><input id=\"submit_btn\" class=\"btn submit\" type=\"submit\" accesskey=\"s\" value=\"Log in\" /></p>',
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
		'4',
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
		'<p>Your Account has been activated!</p><p>You can now login using your username and the password you chose when you registered.</p>' );";


    global $__CMS_CONN__;

    $stmt = $__CMS_CONN__->prepare($create_settings);
    $stmt->execute();
    $stmt = $__CMS_CONN__->prepare($settings_insert);
    $stmt->execute();

}
?>