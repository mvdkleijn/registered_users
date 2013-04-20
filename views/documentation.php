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
?>

<h1><?php echo __('Registered users documentation'); ?></h1>

<h2><?php echo __('Quick start guide'); ?></h2>

<p>To quickly get up and running with the Registered Users plugin, there are two things you will need to do:</p>
<ol style="list-style-position: inline; margin-left: 2.5em;">
    <li>Add the pages with the content as listed in "Table 1 - Pages and their content".</li>
    <li>Set your "Login" page's page type to "Login Page".</li>
</ol>
<div style="border: 1px solid gray; background-color: lightblue; margin: 1em 0; padding: 0.5em;">
    <p>
        When a new user registers through the Registered Users plugin, the new user gets assigned
        a default role. In a standard, unchanged setup of the plugin, a role called "user" will automatically
        be created and used as the default role for newly registered users.
    </p>
    <p>
        If you do not want to use the default role called "User", then you will need to change this in the plugin's
        settings screen as well as manually create a new role.
    </p>
</div>

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
            <td>Register</td>
            <td><code>&lt;?php ru_register_page();?&gt;</code></td>
            <td>Displays registration form and link to login page.</td>
        </tr>
        <tr>
            <td>Register/Confirm</td>
            <td><code>&lt;?php ru_confirm_page();?&gt;</code></td>
            <td>Loaded when someone clicks the activation link in their registration email.</td>
        </tr>
        <tr>
            <td>Authorisation Required</td>
            <td><code>&lt;?php ru_auth_required_page();?&gt;</code></td>
            <td>Loaded when a user tries to access a page they don't have access to.</td>
        </tr>
        <tr>
            <td>Reset</td>
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

<h2><?php echo __('Other things to do'); ?></h2>
<p>
    After running through the quick start guide, you should go to the settings page and check all of the values.
</p>

<h2><?php echo __('Customizing the forms'); ?></h2>
<p>
    A number of forms are used by the Registered Users plugin. Each of these forms have some defaults. If you wish to customize a form,
    you can either change the relevant View or add a Snippet per form.
</p>
<p>Please note that the header (&lt;form ... &gt;) and footer (&lt;/form&gt;) of the form should NOT be included in either of these fields as it is generated dynamically.
    <strong>If you just want to get your site up and running with new registrations, leave the forms as they are by default.</strong> 
</p>
<p>Each form needs its own Snippet and needs a specific name. The names are:</p>
<table>
    <thead>
        <tr>
            <th><?php echo __('Snippet'); ?></th>
            <th><?php echo __('Notes'); ?></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>ru-login</td>
            <td>The login form.</td>
        </tr>
        <tr>
            <td>ru-registration</td>
            <td>The registration form.</td>
        </tr>
        <tr>
            <td>ru-confirmation</td>
            <td>The account confirmation form.</td>
        </tr>
        <tr>
            <td>ru-reset</td>
            <td>The reset form.</td>
        </tr>
    </tbody>
</table>
