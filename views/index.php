<?php

	//	Written by Andrew Waters - andrew@band-x.org
	//	Please leave credit

   /*
	*	Contains the following functions for the Front End :
	*	
	*	ru_register_page()			Use this on the page you want to have for registrations eg mysite.com/register
	*	ru_login_page()				Use this on the page you want to have for logging in eg mysite.com/login
	*	ru_confirm_page()			This is the page a user clicks through to validate their account
	*	ru_auth_required_page()		Users who are not authorised to view the requested page will be redirected here
	*	ru_reset_page()				Will allow a user to have an email sent to them with a lnk to reset their password
	*	ru_logout()					A page to logout a user and return them to the hompage
	*/

?>
<h1>Registered Users</h1>

<?php

/* TODO - Fix... Bad karma to put stuff like this in view. */
try {
    $__CMS_CONN__ = new PDO(DB_DSN, DB_USER, DB_PASS);
} 
catch (PDOException $error) {
    die('Warning - you need the PDO MySQL driver to enable this plugin...');
}
    
if ($__CMS_CONN__->getAttribute(PDO::ATTR_DRIVER_NAME) == 'mysql')
    $__CMS_CONN__->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);

?>


<!--div class="popup" id="add-user-group" style="display:none;">
	<form action="<?php echo get_url('plugin/registered_users/add_first_user_group/'); ?>" method="POST" name="add_user_group">
		<div>
		<input type="text" name="new_group_name" value="" /> <input class="button" name="add_user_type" type="submit" value="Add User Group">
		</div>
		<p><a class="close-link" href="#" onclick="Element.hide('add-user-group'); return false;">Close</a></p>
	</form>
</div-->

<p>This plugin allows you to add login and registration features to Wolf CMS 0.5.5+</p>
<p>You need to go through the following process to enable the all the features this plugin provides:</p>
<p><strong>1. Add the following pages and the respective code below.</strong></p>

<table id="registered_users" class="index" cellpadding="0" cellspacing="0" border="0">
	<thead>
		<tr>
			<th class="page"><?php echo __('Page'); ?></th>
			<th class="code"><?php echo __('Code'); ?></th>
			<th class="notes"><?php echo __('Notes'); ?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><small><strong>Login</strong></small></td>
			<td><small><strong><font color="red">&lt;?php</font> ru_login_page(); <font color="red">?&gt;</font></strong></small></td>
			<td><small>Displays login form and link to registration page.</small></td>
		</tr>
		<tr>
			<td><small><strong>Registration</strong></small></td>
			<td><small><strong><font color="red">&lt;?php</font> ru_register_page(); <font color="red">?&gt;</font></strong></small></td>
			<td><small>Displays registration form and link to login page.</small></td>
		</tr>
		<tr>
			<td><small><strong>Confirmation</strong></small></td>
			<td><small><strong><font color="red">&lt;?php</font> ru_confirm_page(); <font color="red">?&gt;</font></strong></small></td>
			<td><small>This page is loaded when someone clicks the activation link in their registration email.</small></td>
		</tr>
		<tr>
			<td><small><strong>Authorisation Needed</strong></small></td>
			<td><small><strong><font color="red">&lt;?php</font> ru_auth_required_page(); <font color="red">?&gt;</font></strong></small></td>
			<td><small>This page is loaded when a user tries to access a page they don't have access to.</small></td>
		</tr>
		<tr>
			<td><small><strong>Reset Password</strong></small></td>
			<td><small><strong><font color="red">&lt;?php</font> ru_reset_page(); <font color="red">?&gt;</font></strong></small></td>
			<td><small>This page emails a link to a user for them to reset their password.</small></td>
		</tr>
		<tr>
			<td><small><strong>Logout</strong></small></td>
			<td><small><strong>
				<font color="red">&lt;?php</font> ru_logout(); <font color="red">?&gt;</font></strong></small></td>
			<td><small>This page logs a user out of the site.</small></td>
		</tr>
	</tbody>
</table>
<p><small><strong>Please make sure none of these pages require login as it important for everyone to be able to access them.</strong></small></p>

<p><strong>2. Go to your login page and give it the "Login Page" type.</strong></p>

<p><strong>3. <a href="#add-user-group" class="popupLink" nclick="toggle_popup('add-user-group', 'add-user-group'); return false;">
				<img src="<?php echo URL_PUBLIC; ?>wolf/admin/images/plus.png" align="bottom" title="<?php echo __('Add User Group'); ?>" alt="<?php echo __('Add User Group'); ?>" /></a> Add a default user group for new registrations.</strong></p>
<p><strong>4. Set up the <a href="<?php echo get_url('plugin/registered_users/settings'); ?>">finer details.</a></strong></p>

<div id="boxes">
    <div id="add-user-group" class="window">
	<div class="titlebar">
            <?php echo __('Add user group'); ?>
            <a href="#" class="close">[x]</a>
        </div>
        <div class="content">
            <form action="<?php echo get_url('plugin/registered_users/add_user_group/'); ?>" method="post" name="add_user_group">
                <p>Group Name <input id="new_group" maxlength="255" name="new_group" type="text" value="" /></p>
		<p>Make default group for new users? <input type="checkbox" name="default" value="1" /></p>
                <input id="add_user_group_button" name="commit" type="submit" value="<?php echo __('Add user group'); ?>" />
            </form>
        </div>
    </div>
</div>