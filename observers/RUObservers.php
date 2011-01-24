<?php

	//	Written by Andrew Waters - andrew@band-x.org
    //  Additional code by Martijn van der Kleijn (martijn.niji@gmail.com)
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

function registered_users_page_found($page) {

	// If login is required for the page
	if ($page->getLoginNeeded() == Page::LOGIN_REQUIRED) {

		AuthUser::load();
	
		// Not Logged In
		if ( ! AuthUser::isLoggedIn()) {
	
			global $__CMS_CONN__;
			
			// Get the current page id
			$requested_page_id = $page->id();
			
			// Let's get the page that is set as the login page to prevent any loopbacks
			$getloginpage = 'SELECT * FROM '.TABLE_PREFIX."page WHERE behavior_id='login_page'";
			$getloginpage = $__CMS_CONN__->prepare($getloginpage);
			$getloginpage->execute();
	
			while ($loginpage = $getloginpage->fetchObject()) {
				$loginpage_id = $loginpage->id;
			}
	
			if ($requested_page_id != $loginpage_id) {
				header('Location: '.URL_PUBLIC.'login');
			}
	
		}
	
		// User is logged in
		else {
			// We need to check if the user has permission to access the page
			global $__CMS_CONN__;
			
			// Get requested page id
			$requested_page_id = $page->id();

			// Get permissions that are required for this page
			$permissions_check = "SELECT * FROM ".TABLE_PREFIX."permission_page WHERE page_id='$requested_page_id'";
			$permissions_check = $__CMS_CONN__->prepare($permissions_check);
			$permissions_check->execute();

			$permission_array = array();

			while ($permission = $permissions_check->fetchObject()) {
				$page_permission = $permission->permission_id;
				array_push($permission_array, $page_permission);
			}

			$permissions_count = count($permission_array);

			AuthUser::load();
			$userid = AuthUser::getRecord()->id;

			// Get permissions that this user has
                        /*
			$user_permissions_check = "SELECT * FROM ".TABLE_PREFIX."user_permission WHERE user_id='$userid'";
			$user_permissions_check = $__CMS_CONN__->prepare($user_permissions_check);
			$user_permissions_check->execute();

			$user_permissions_array = array();

			while ($user_permissions = $user_permissions_check->fetchObject()) {
				$user_permission = $user_permissions->permission_id;
				array_push($user_permissions_array, $user_permission);
			}*/
                        $roles = AuthUser::getRecord()->roles();
                        foreach ($roles as $role) {
                            $user_permissions_array[] = $role->id;
                        }
			
			$permission_result = array_intersect($permission_array, $user_permissions_array);
			
			$permission_result_count = count($permission_result);

			if($permission_result_count < 1) {
				// Let's get the authorisation required page
				global $__CMS_CONN__;
				$registration_settings = "SELECT * FROM ".TABLE_PREFIX."registered_users_settings WHERE id='1'";
				foreach ($__CMS_CONN__->query($registration_settings) as $row) {
					$auth_required_page = $row['auth_required_page'];
				}
				header('Location: '.URL_PUBLIC.''.$auth_required_page.'');
			}
		}
	}
}



function registered_users_access_page_checkbox($page) {

	global $__CMS_CONN__;
	$page_id = $page->id;
        $roles = Role::findAllFrom('Role');

	echo '<label for="access">Access:</label> ';

        foreach ($roles as $role) {
		//global $__CMS_CONN__;
		$id = $role->id;
		$name = $role->name;

		echo '<input id="permission_'.$id.'" name="permission_'.$id.'" type="checkbox"';

		$permissions_check = "SELECT * FROM ".TABLE_PREFIX."permission_page WHERE page_id='$page_id'";
		$permissions_check = $__CMS_CONN__->prepare($permissions_check);
		$permissions_check->execute();

		while ($permissions_checked = $permissions_check->fetchObject()) {
			$page_permission = $permissions_checked->permission_id;
			if ($id == $page_permission) {
				echo 'checked';
			} 
		}

		echo ' value="allowed" /> '.$name.' | ';
	}
	echo '<div class="clear"></div>';
}


function registered_users_add_page_permissions($page) {

	global $__CMS_CONN__;
	$page_id = $page->id;

        $roles = Role::findAllFrom('Role');

        foreach ($roles as $role) {
		global $__CMS_CONN__;
		$id = $role->id;

        if (isset($_POST['permission_'.$id.''])) {
            $permission = $_POST['permission_'.$id.''];
    		if ($permission == 'allowed') {
        		$add_page_permission = "INSERT INTO ".TABLE_PREFIX."permission_page VALUES ('".$page_id."','".$id."')";
            	$add_page_permission = $__CMS_CONN__->prepare($add_page_permission);
                $add_page_permission->execute();
            }
		}
	}
}


function registered_users_edit_page_permissions($page) {

	global $__CMS_CONN__;
	$page_id = $page->id;
	
	/*$permissions_list = "SELECT * FROM ".TABLE_PREFIX."permission";
	$permissions_list = $__CMS_CONN__->prepare($permissions_list);
	$permissions_list->execute();*/
        
        $roles = Role::findAllFrom('Role');

	$delete_page_permission = "DELETE FROM ".TABLE_PREFIX."permission_page WHERE page_id = '$page_id'";
	$delete_page_permission = $__CMS_CONN__->prepare($delete_page_permission);
	$delete_page_permission->execute();


	//while ($permission = $permissions_list->fetchObject()) {
        foreach ($roles as $role) {
		$id = $role->id;

		if (isset($_POST['permission_'.$id.''])) {
            $permission = $_POST['permission_'.$id.''];
            if ($permission == 'allowed') {
                $add_page_permission = "INSERT INTO ".TABLE_PREFIX."permission_page VALUES ('".$page_id."','".$id."')";
                $add_page_permission = $__CMS_CONN__->prepare($add_page_permission);
                $add_page_permission->execute();
            }
        }
	}
}


function registered_users_delete_page_permissions($page) {

	// This function cleans up the database if the page is deleted from the site
	global $__CMS_CONN__;
	$page_id = $page->id;
	$delete_page_permission = "DELETE FROM ".TABLE_PREFIX."permission_page WHERE page_id = '$page_id'";
	$delete_page_permission = $__CMS_CONN__->prepare($delete_page_permission);
	$delete_page_permission->execute();
	
}