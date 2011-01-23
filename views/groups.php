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
<h1>User Groups</h1>

<p>Below is a list of groups on the <?php echo kses(Setting::get('admin_title'), array()); ?> website. You may want to add extra groups for Registered Users, Clients, Paying Subscribers etc</p>

<table id="user_types" class="index" cellpadding="0" cellspacing="0" border="0">
	<thead>
		<tr>
			<th class="page"><?php echo __('Group Name'); ?></th>
			<th class="code"><?php echo __('Edit'); ?></th>
		</tr>
	</thead>
	<tbody>
<?php
	global $__CMS_CONN__;
	$permission_settings = "SELECT * FROM ".TABLE_PREFIX."permission";
	$permission_settings = $__CMS_CONN__->prepare($permission_settings);
	$permission_settings->execute();
	while ($settings = $permission_settings->fetchObject()) {
		global $__CMS_CONN__;
		$id = $settings->id;
		$name = $settings->name;
?>

		<tr class="<?php echo odd_even(); ?>">
			<td><img src="<?php echo URL_PUBLIC; ?>wolf/plugins/registered_users/images/user_type_collections.png" align="center" alt="User Group" /> <?php echo '<strong>'.$name.'</strong>'; ?></td>
			<td><?php
		if ($id >= '4'  ) {
			echo '<a href="';
			echo get_url('plugin/registered_users/delete/');
			echo $id;
			echo '" onclick="return confirm(\'Are you sure you want to delete the user group : '.$name.' \');">
			<img src="'.URL_PUBLIC.'admin/images/icon-remove.gif" align="center" alt="Delete User Group" /></a>
			<a href="#" onclick="toggle_popup(\'rename-user-group-'.$id.'\', \'add-user-group\'); return false;">
			<img src="'.URL_PUBLIC.'admin/images/icon-rename.gif" align="center" alt="Rename User Group" /></a>';
			
			// if the user type is default, let's let the client know!
			$defaultusertyperetrieve = "SELECT * FROM ".TABLE_PREFIX."registered_users_settings";
			$defaultusertyperetrieve = $__CMS_CONN__->prepare($defaultusertyperetrieve);
			$defaultusertyperetrieve->execute();
			while ($deeds = $defaultusertyperetrieve->fetchObject()) {
				$defaultusertype = $deeds->default_permissions;
				if ($defaultusertype == $id) {
					echo ' <small><strong>[ Default Group ]</strong></small>';
				}
				else {
					echo ' <small>[ <a href="';
					echo get_url('plugin/registered_users/makedefault/');
					echo $id;
					echo '">make default</a> ]</small>';
				}
			}

			echo '
			<div class="popup" id="rename-user-group-'.$id.'" style="display:none;">
					<form action="';
			echo get_url('plugin/registered_users/rename_user_group/');
			echo '" method="POST" name="rename_user_group">
						<div>
							<input type="hidden" name="id" value="'.$id.'" />
							<input type="text" name="renamed" value="'.$name.'" />
							<input class="button" name="rename_user_type" type="submit" value="Rename User Group">
						</div>
						<p><a class="close-link" href="#" onclick="Element.hide(\'rename-user-group-'.$id.'\'); return false;">Close</a></p>
					</form>
			</div>';
		}
		else {
			// if the user type is default, let's let the client know!
			$defaultusertyperetrieve = "SELECT * FROM ".TABLE_PREFIX."registered_users_settings";
			$defaultusertyperetrieve = $__CMS_CONN__->prepare($defaultusertyperetrieve);
			$defaultusertyperetrieve->execute();
			while ($deeds = $defaultusertyperetrieve->fetchObject()) {
				$defaultusertype = $deeds->default_permissions;
				if ($defaultusertype == $id) {
					echo ' <small><strong>[ default ]</strong></small>';
				}
			}
		}
				?></td>
		</tr>
<?php
}
?>
		<tr>

			<td>
				<img src="<?php echo URL_PUBLIC; ?>wolf/plugins/registered_users/images/blank.png" align="middle" alt="User Group" />
				<a href="#" onclick="toggle_popup('add-user-group', 'add-user-group'); return false;">
				<img src="<?php echo URL_PUBLIC; ?>admin/images/plus.png" align="middle" title="<?php echo __('Add User Group'); ?>" alt="<?php echo __('Add User Group'); ?>" /></a>
			</td>
			<td></td>
		</tr>
	</tbody>
</table>

<div class="popup" id="add-user-group" style="display:none;">
	<form action="<?php echo get_url('plugin/registered_users/add_user_group/'); ?>" method="POST" name="add_user_group">
		<p><small>Group Name</small> <input type="text" name="new_group" value="" /></p>
		<p><small>Make default group for new users?</small> <input type="checkbox" name="default" value="1" /></p>
		<p><input class="button" name="add_user_group" type="submit" value="Add User Group"></p>
		<p><a class="close-link" href="#" onclick="Element.hide('add-user-group'); return false;">Close</a></p>
	</form>
</div>

<p><strong><small>For the integrity of the Core CMS, editing administrators, developers and editors is forbidden.</small></strong></p>