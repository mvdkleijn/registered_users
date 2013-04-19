<?php
/**
 * This file is part of the "Registered Users" plugin for Wolf CMS.
 * Licensed under an MIT style license. For full details see license.txt.
 *
 * @author Martijn van der Kleijn <martijn.niji@gmail.com>
 * @copyright Martijn van der Kleijn, 2009-2011
 * 
 * Original author:
 * 
 * @author Andrew Waters <andrew@band-x.org>
 * @copyright Andrew Waters, 2009
 *
 */
?>

<h1><?php echo __('Registered Users Documentation'); ?></h1>

<h2><?php echo __('Quick start guide'); ?></h2>

<p>To quickly get up and running with the Registered Users plugin, there are two things you will need to do:</p>
<ol>
    <li>Add the pages with the content as listed in "Table 1 - Pages and their content".</li>
    <li>Set your "Login" page's page type to "Login Page".</li>
</ol>
<p>
    Note: when a new user registers through the Registered Users plugin, the new user gets assigned
    a default role. In a standard, unchanged setup of the plugin, a role called "user" will automatically
    be created and used as the default role for newly registered users.
</p>
<p>
    If you do not want to use the default role called "User", then you will need to change this in the plugin's
    settings screen as well as manually create a new role.
</p>

<table>
    <caption>Table 1 - Pages and their content</caption>
    <thead>
        <tr>
            <th><?php echo __('Page'); ?></th>
            <th><?php echo __('Body'); ?></th>
            <th><?php echo __('Notes'); ?></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Login</td>
            <td><code>&lt;?php ru_login_page();?&gt;</code></td>
            <td>Displays login form and link to registration page.</td>
        </tr>
        <tr>
            <td>Registration</td>
            <td><code>&lt;?php ru_register_page();?&gt;</code></td>
            <td>Displays registration form and link to login page.</td>
        </tr>
        <tr>
            <td>Confirmation</td>
            <td><code>&lt;?php ru_confirm_page();?&gt;</code></td>
            <td>Loaded when someone clicks the activation link in their registration email.</td>
        </tr>
        <tr>
            <td>Authorisation Needed</td>
            <td><code>&lt;?php ru_auth_required_page();?&gt;</code></td>
            <td>Loaded when a user tries to access a page they don't have access to.</td>
        </tr>
        <tr>
            <td>Reset Password</td>
            <td><code>&lt;?php ru_reset_page();?&gt;</code></td>
            <td>Emails a "reset password" link to a user.</td>
        </tr>
        <tr>
            <td>Logout</td>
            <td><code>&lt;?php ru_logout();?&gt;</code></td>
            <td>Logs a user out of the site.</td>
        </tr>
    </tbody>
</table>