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
 *
 */

/**
 * Contains the following functions for the Front End :
 *
 *	ru_register_page()			Use this on the page you want to have for registrations eg mysite.com/register
 *	ru_login_page()				Use this on the page you want to have for logging in eg mysite.com/login
 *	ru_confirm_page()			This is the page a user clicks through to validate their account
 *	ru_auth_required_page()		Users who are not authorised to view the requested page will be redirected here
 *	ru_reset_page()				Will allow a user to have an email sent to them with a lnk to reset their password
 *	ru_logout()					A page to logout a user and return them to the hompage
 */

/* TODO - cleanup */
class RegisteredUsersController extends PluginController {

    public function __construct() {
        $this->setLayout('backend');
        $this->assignToLayout('sidebar', new View('../../plugins/registered_users/views/sidebar'));
    }

    public function index() {
        $this->display('registered_users/views/index');
    }

    public function documentation() {
        $this->display('registered_users/views/index');
    }

    public function groups() {
        $roles = Role::findAllFrom('Role');
        
        $this->display('registered_users/views/groups', array('roles' => $roles));
    }

    function settings() {
        $roles = Role::findAllFrom('Role');
        
        $this->display('registered_users/views/settings', array('roles' => $roles));
    }

    function notvalidated() {
        $this->display('registered_users/views/notvalidated');
    }

    function statistics() {
        $this->display('registered_users/views/statistics');
    }

    /* TODO - make use of wolf plugin settings features? */
    public function edit_settings() {
        global $__CMS_CONN__;
        $allow_registrations = mysql_escape_string($_POST['allow_registrations']);
        $closed_message = mysql_escape_string($_POST['closed_message']);
        $login_form = mysql_escape_string($_POST['login_form']);
        $reset_form = mysql_escape_string($_POST['reset_form']);
        $registration_form = mysql_escape_string($_POST['registration_form']);
        $allow_login = mysql_escape_string($_POST['allow_login']);
        $allow_fb_connect = mysql_escape_string($_POST['allow_fb_connect']);
        $connect_api_key = mysql_escape_string($_POST['connect_api_key']);
        $connect_secret_key = mysql_escape_string($_POST['connect_secret_key']);
        $random_key_length = mysql_escape_string($_POST['random_key_length']);
        $random_key_type = mysql_escape_string($_POST['random_key_type']);
        $login_closed_message = mysql_escape_string($_POST['login_closed_message']);
        $register_page = mysql_escape_string($_POST['register_page']);
        $already_logged_in = mysql_escape_string($_POST['already_logged_in']);
        $default_permissions = mysql_escape_string($_POST['default_permissions']);
        $welcome_email_pt = mysql_escape_string($_POST['welcome_email_pt']);
        $register_confirm_msg = mysql_escape_string($_POST['register_confirm_msg']);
        $welcome_email_pt_foot = mysql_escape_string($_POST['welcome_email_pt_foot']);
        $confirm_email_subject = mysql_escape_string($_POST['confirm_email_subject']);
        $confirm_email_from = mysql_escape_string($_POST['confirm_email_from']);
        $confirm_email_reply = mysql_escape_string($_POST['confirm_email_reply']);
        $confirmation_page = mysql_escape_string($_POST['confirmation_page']);
        $auth_form = mysql_escape_string($_POST['auth_form']);
        $message_empty_name = mysql_escape_string($_POST['message_empty_name']);
        $message_empty_email = mysql_escape_string($_POST['message_empty_email']);
        $message_empty_username = mysql_escape_string($_POST['message_empty_username']);
        $message_empty_password = mysql_escape_string($_POST['message_empty_password']);
        $message_empty_password_confirm = mysql_escape_string($_POST['message_empty_password_confirm']);
        $message_notvalid_password = mysql_escape_string($_POST['message_notvalid_password']);
        $message_notvalid_username = mysql_escape_string($_POST['message_notvalid_username']);
        $message_notvalid_email = mysql_escape_string($_POST['message_notvalid_email']);
        $message_error_technical = mysql_escape_string($_POST['message_error_technical']);
        $message_error_already_validated = mysql_escape_string($_POST['message_error_already_validated']);
        $message_need_to_register = mysql_escape_string($_POST['message_need_to_register']);
        $auth_required_page = mysql_escape_string($_POST['auth_required_page']);
        $auth_required_page_text = mysql_escape_string($_POST['auth_required_page_text']);
        $reset_text = mysql_escape_string($_POST['reset_text']);
        $reset_no_email = mysql_escape_string($_POST['reset_no_email']);
        $reset_pass_length = mysql_escape_string($_POST['reset_pass_length']);
        $reset_pass_type = mysql_escape_string($_POST['reset_pass_type']);
        $reset_email_body = mysql_escape_string($_POST['reset_email_body']);
        $reset_password_reply = mysql_escape_string($_POST['reset_password_reply']);
        $reset_password_from = mysql_escape_string($_POST['reset_password_from']);
        $reset_password_subject = mysql_escape_string($_POST['reset_password_subject']);
        $reset_email_confirmed = mysql_escape_string($_POST['reset_email_confirmed']);
        $welcome_message = mysql_escape_string($_POST['welcome_message']);

        $sql = "UPDATE ".TABLE_PREFIX."registered_users_settings SET
			`allow_registrations`='".$allow_registrations."',
			`closed_message`='".$closed_message."',
			`login_form`='".$login_form."',
			`reset_form`='".$reset_form."',
			`registration_form`='".$registration_form."',
			`allow_login`='".$allow_login."',
			`allow_fb_connect`='".$allow_fb_connect."',
			`connect_api_key`='".$connect_api_key."',
			`connect_secret_key`='".$connect_secret_key."',
			`random_key_length`='".$random_key_length."',
			`random_key_type`='".$random_key_type."',
			`login_closed_message`='".$login_closed_message."',
			`register_page`='".$register_page."',
			`already_logged_in`='".$already_logged_in."',
			`default_permissions`='".$default_permissions."',
			`welcome_email_pt`='".$welcome_email_pt."',
			`register_confirm_msg`='".$register_confirm_msg."',
			`welcome_email_pt_foot`='".$welcome_email_pt_foot."',
			`confirm_email_subject`='".$confirm_email_subject."',
			`confirm_email_from`='".$confirm_email_from."',
			`confirm_email_reply`='".$confirm_email_reply."',
			`confirmation_page`='".$confirmation_page."',
			`auth_form`='".$auth_form."',
			`message_empty_name`='".$message_empty_name."',
			`message_empty_email`='".$message_empty_email."',
			`message_empty_username`='".$message_empty_username."',
			`message_empty_password`='".$message_empty_password."',
			`message_empty_password_confirm`='".$message_empty_password_confirm."',
			`message_notvalid_password`='".$message_notvalid_password."',
			`message_notvalid_username`='".$message_notvalid_username."',
			`message_notvalid_email`='".$message_notvalid_email."',
			`message_error_technical`='".$message_error_technical."',
			`message_error_already_validated`='".$message_error_already_validated."',
			`message_need_to_register`='".$message_need_to_register."',
			`auth_required_page`='".$auth_required_page."',
			`auth_required_page_text`='".$auth_required_page_text."',
			`reset_text`='".$reset_text."',
			`reset_no_email`='".$reset_no_email."',
			`reset_pass_length`='".$reset_pass_length."',
			`reset_pass_type`='".$reset_pass_type."',
			`reset_email_body`='".$reset_email_body."',
			`reset_password_reply`='".$reset_password_reply."',
			`reset_password_from`='".$reset_password_from."',
			`reset_password_subject`='".$reset_password_subject."',
			`reset_email_confirmed`='".$reset_email_confirmed."',
			`welcome_message`='".$welcome_message."'
			WHERE id='1'";
        $stmt = $__CMS_CONN__->prepare($sql);
        $stmt->execute();

        Flash::set('success', __('User Registration Settings have been updated'));
        redirect(get_url('plugin/registered_users/settings'));
    }

    public function add_user_group() {
        global $__CMS_CONN__;
        $new_group = trim(mysql_escape_string($_POST['new_group']));
        if (isset($_POST['default']))
            $default = true;
        else
            $default = false;

        if ($new_group == '' || empty($new_group)) {
            Flash::set('error', __('You need to enter a name for your new user group'));
            redirect(get_url('plugin/registered_users/groups'));
        }
        else {
            $role = new Role();
            $role->name = $new_group;
            $role->save();

            if ($default) {
                $sql = "UPDATE ".TABLE_PREFIX."registered_users_settings SET default_permissions='".$role->id."'";
                $stmt = $__CMS_CONN__->prepare($sql);
                $stmt->execute();
            }
            
            Flash::set('success', __('The '.$new_group.' user group has been added'));
            redirect(get_url('plugin/registered_users/groups'));
        }
    }

    public function add_first_user_group() {
        global $__CMS_CONN__;
        $new_group_name = mysql_escape_string($_POST['new_group_name']);
        if ($new_group_name == '' || empty($new_group_name)) {
            Flash::set('error', __('You need to enter a name for your new user group'));
            redirect(get_url('plugin/registered_users/permissions'));
        }
        else {
            $sql = "INSERT INTO ".TABLE_PREFIX."permission VALUES ('','".$new_group_name."')";
            $stmt = $__CMS_CONN__->prepare($sql);
            $stmt->execute();
            $sql = "SELECT * FROM ".TABLE_PREFIX."permission WHERE name='".$new_group_name."'";
            $stmt = $__CMS_CONN__->prepare($sql);
            $stmt->execute();
            while ($st = $stmt->fetchObject()) {
                $id = $st->id;
            }
            $sql = "UPDATE ".TABLE_PREFIX."registered_users_settings SET `default_permissions`='".$id."'";
            $stmt = $__CMS_CONN__->prepare($sql);
            $stmt->execute();

            Flash::set('success', __('The '.$new_group_name.' user group has been added'));
            redirect(get_url('plugin/registered_users'));
        }
    }

    public function rename_user_group() {
        $name = trim(mysql_escape_string($_POST['renamed']));
        $id = trim(mysql_escape_string($_POST['id']));
        $role = Role::findById($id);
        $role->name = $name;
        
        if ($role->save()) {
            Flash::set('success', __(''.$name.' has been updated.'));
            redirect(get_url('plugin/registered_users/groups'));
        }
        else {
            Flash::set('error', __('Unable to rename group! ('.$name.')'));
            redirect(get_url('plugin/registered_users/groups'));            
        }
    }

    public function makedefault($id) {
        global $__CMS_CONN__;

        $sql = "UPDATE ".TABLE_PREFIX."registered_users_settings SET `default_permissions`='".$id."'";
        $stmt = $__CMS_CONN__->prepare($sql);
        $stmt->execute();

        Flash::set('success', __('The default user group has been changed'));
        redirect(get_url('plugin/registered_users/groups'));
    }

    public function delete($id) {
        $role = Role::findById($id);

        if ($role->delete()) {
            Flash::set('success', __('The '.$name.' user group has been deleted.'));
            redirect(get_url('plugin/registered_users/groups'));
        }
        else {
            Flash::set('success', __('Unable to delete the '.$name.' user group!'));
            redirect(get_url('plugin/registered_users/groups'));
        }
    }

    function checkfordb() {
        global $__CMS_CONN__;
        $PDO = Record::getConnection();
        return $PDO->exec("SELECT version FROM ".TABLE_PREFIX."registered_users_temp") !== false;
    }

}