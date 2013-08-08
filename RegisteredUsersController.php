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

/**
 * Contains the following functions for the Front End :
 *
 * 	ru_register_page()		Use this on the page you want to have for registrations eg mysite.com/register
 * 	ru_login_page()			Use this on the page you want to have for logging in eg mysite.com/login
 * 	ru_confirm_page()		This is the page a user clicks through to validate their account
 * 	ru_auth_required_page()		Users who are not authorised to view the requested page will be redirected here
 * 	ru_reset_page()			Will allow a user to have an email sent to them with a lnk to reset their password
 * 	ru_logout()			A page to logout a user and return them to the hompage
 */

/* TODO - cleanup */
class RegisteredUsersController extends PluginController {

    public function __construct() {
        $this->setLayout('backend');
        $this->assignToLayout('sidebar', new View('../../plugins/registered_users/views/sidebar'));
    }

    public function index() {
        $this->documentation();
    }

    public function documentation() {
        $this->display('registered_users/views/documentation');
    }

    function settings() {
        $roles = Role::findAllFrom('Role');

        $this->display('registered_users/views/settings', array(
            'roles' => $roles,
            'settings' => Plugin::getAllSettings('registered_users')));
    }

    public function groups() {
        $roles = Role::findAllFrom('Role');

        $this->display('registered_users/views/groups', array('roles' => $roles));
    }

    function notvalidated() {
        $this->display('registered_users/views/notvalidated');
    }

    function statistics() {
        $this->display('registered_users/views/statistics');
    }

    public function edit_settings() {
        if (isset($_POST['settings'])) {
            if (Plugin::setAllSettings($_POST['settings'], 'registered_users')) {
                Flash::set('success', __('The settings have been saved.'));
            } else {
                Flash::set('error', __('An error occured trying to save the settings.'));
            }
        } else {
            Flash::set('error', __('Could not save settings, no settings found.'));
        }

        redirect(get_url('plugin/registered_users/settings'));
    }

    public function add_user_group() {
        $new_group = trim($_POST['new_group']);
        if (isset($_POST['default']))
            $default = true;
        else
            $default = false;

        if ($new_group == '' || empty($new_group)) {
            Flash::set('error', __('You need to enter a name for your new user group'));
            redirect(get_url('plugin/registered_users/groups'));
        } else {
            $role = new Role();
            $role->name = $new_group;
            $role->save();

            if ($default) {
                Plugin::setSetting("default_permissions", $role->id, "registered_users");
            }

            Flash::set('success', __('The ' . $new_group . ' user group has been added'));
            redirect(get_url('plugin/registered_users/groups'));
        }
    }

    public function add_first_user_group() {
        //global $__CMS_CONN__;
        $PDO = Record::getConnection();
        $new_group_name = $_POST['new_group_name'];
        if ($new_group_name == '' || empty($new_group_name)) {
            Flash::set('error', __('You need to enter a name for your new user group'));
            redirect(get_url('plugin/registered_users/permissions'));
        } else {
            $sql = "INSERT INTO " . TABLE_PREFIX . "permission VALUES ('', :group)";
            $stmt = $PDO->prepare($sql);
            $stmt->execute(array("group" => $new_group_name));
            $sql = "SELECT * FROM " . TABLE_PREFIX . "permission WHERE name=:group";
            $stmt = $PDO->prepare($sql);
            $stmt->execute(array("group" => $new_group_name));
            while ($st = $stmt->fetchObject()) {
                $id = $st->id;
            }
            $this->makedefault($id);
        }
    }

    public function rename_user_group() {
        $name = trim($_POST['renamed']);
        $id = trim($_POST['id']);
        $role = Role::findById($id);
        $role->name = $name;

        if ($role->save()) {
            Flash::set('success', __('' . $name . ' has been updated.'));
            redirect(get_url('plugin/registered_users/groups'));
        } else {
            Flash::set('error', __('Unable to rename group! (' . $name . ')'));
            redirect(get_url('plugin/registered_users/groups'));
        }
    }

    public function makedefault($id) {
        Plugin::setSetting("default_permissions", $id, "registered_users");

        Flash::set('success', __('The default user group has been changed'));
        redirect(get_url('plugin/registered_users/groups'));
    }

    public function delete($id) {
        $role = Role::findById($id);

        if ($role->delete()) {
            Flash::set('success', __('The ' . $name . ' user group has been deleted.'));
            redirect(get_url('plugin/registered_users/groups'));
        } else {
            Flash::set('success', __('Unable to delete the ' . $name . ' user group!'));
            redirect(get_url('plugin/registered_users/groups'));
        }
    }

    function checkfordb() {
        $PDO = Record::getConnection();
        return $PDO->exec("SELECT version FROM " . TABLE_PREFIX . "registered_users_temp") !== false;
    }

}
