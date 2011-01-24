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

Plugin::setInfos(array(
    'id'          => 'registered_users',
    'title'       => 'Registered Users',
    'description' => 'Allows you to manage new user registrations on your site.',
    'version'     => '0.9.9',
    'author'      => 'Andrew Waters',
    'website'     => 'http://www.band-x.org/',
    'update_url'  => 'http://www.band-x.org/update.xml',
    'require_wolf_version' => '0.7.3'
));

Plugin::addController('registered_users', 'Registered Users', 'administrator');

Observer::observe('view_page_edit_plugins',	'registered_users_access_page_checkbox');
Observer::observe('page_add_after_save',	'registered_users_add_page_permissions');
Observer::observe('page_edit_after_save',	'registered_users_edit_page_permissions');
Observer::observe('page_delete',			'registered_users_delete_page_permissions');
Observer::observe('page_found',				'registered_users_page_found');

Behavior::add('login_page', '');

include('classes/RegisteredUsers.php');
include('classes/RUCommon.php');
include('observers/RUObservers.php');

function ru_login_page() {
    $registered_users_class = new RegisteredUsers();
    $loginpage = $registered_users_class->login_page();
    echo $loginpage;
}

function ru_register_page() {
    $registered_users_class = new RegisteredUsers();
    $registerpage = $registered_users_class->registration_page();
    echo $registerpage;
}

function ru_confirm_page() {
    $registered_users_class = new RegisteredUsers();
    $confirmation_page = $registered_users_class->confirm();
    echo $confirmation_page;
}

function ru_auth_required_page() {
    $registered_users_class = new RegisteredUsers();
    $auth_required_page = $registered_users_class->auth_required_page();
    echo $auth_required_page;
}

function ru_reset_page() {
    $registered_users_class = new RegisteredUsers();
    $reset_page = $registered_users_class->password_reset();
    echo $reset_page;
}

function ru_logout() {
    $controller = new LoginController();
    $logout = $controller->logout();
    $logout;
}