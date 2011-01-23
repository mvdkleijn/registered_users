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
<p class="button"><a href="<?php echo get_url('plugin/registered_users/settings'); ?>"><img src="<?php echo URI_PUBLIC; ?>wolf/plugins/registered_users/images/settings.png" align="middle">Settings</a></p>
<p class="button"><a href="<?php echo get_url('plugin/registered_users/groups'); ?>"><img src="<?php echo URI_PUBLIC; ?>wolf/plugins/registered_users/images/groups.png" align="middle">User Groups</a></p>
<p class="button"><a href="<?php echo get_url('plugin/registered_users/statistics'); ?>"><img src="<?php echo URI_PUBLIC; ?>wolf/plugins/registered_users/images/statistics.png" align="middle">Statistics</a></p>
<p class="button"><a href="<?php echo get_url('user'); ?>"><img src="<?php echo URI_PUBLIC; ?>wolf/plugins/registered_users/images/user.png" align="middle">Users</a></p>
<div class="box">
<h3>Registered Users</h3>
<p>This plugin allows you to manage user registrations through your Wolf CMS site.</p>
<p>It controls the administration of user groups as well as the front end bits (forms, registrations etc)</p>
</div>