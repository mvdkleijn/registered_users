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
<style>
    .odd { background-color: #efefef; }
    td { padding: 0.5em; }
</style>
<h1><?php echo __('User Roles'); ?></h1>

<p>
    Below is a list of groups on the <?php echo Setting::get('admin_title'); ?>
    website. You may want to add extra groups for Registered Users, Clients,
    Paying Subscribers etc.
</p>

<table style="width: 100%; margin: 1em 2em 1em 0em;">
    <thead>
        <tr>
            <th class="page"><?php echo __('Group Name'); ?></th>
            <th class="code"><?php echo __('Edit'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($roles as $role) {
            $id = $role->id;
            $name = $role->name;
        ?>

            <tr class="<?php echo odd_even(); ?>">
                <td><img src="<?php echo PLUGINS_URI; ?>registered_users/images/user_type_collections.png" align="top" alt="User Group" /> <?php echo '<strong>'.$name.'</strong>'; ?></td>
                <td style="text-align: left; width: 15em;"><?php
        if ($id >= '4') {
            echo '<a href="';
            echo get_url('plugin/registered_users/delete/');
            echo $id;
            echo '" onclick="return confirm(\'Are you sure you want to delete the user group : '.$name.' \');">
			<img src="'.URL_PUBLIC.'wolf/admin/images/icon-remove.gif" lign="top" alt="Delete User Group" /></a>
			<a href="#" onclick="toggle_rename_popup(\''.$id.'\', \''.$name.'\'); return false;">
			<img src="'.URL_PUBLIC.'wolf/admin/images/icon-rename.gif" lign="" alt="Rename User Group" /></a>';

            // if the user type is default, let's let the client know!
            $PDO = Record::getConnection();
            $defaultusertyperetrieve = "SELECT * FROM ".TABLE_PREFIX."registered_users_settings";
            $defaultusertyperetrieve = $PDO->prepare($defaultusertyperetrieve);
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
        }
        else {
            // if the user type is default, let's let the client know!
            $conn = Record::getConnection();
            $defaultusertyperetrieve = "SELECT * FROM ".TABLE_PREFIX."registered_users_settings";
            $defaultusertyperetrieve = $conn->prepare($defaultusertyperetrieve);
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
                <a href="#add-user-group" class="popupLink">
                    <img src="<?php echo ICONS_URI; ?>action-add-16.png" align="middle" title="<?php echo __('Add User Group'); ?>" alt="<?php echo __('Add User Group'); ?>" /></a>
            </td>
        </tr>
    </tbody>
</table>

<p><strong><small>For the integrity of the core CMS, editing administrators, developers and editors is forbidden.</small></strong></p>

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

    <div id="rename_user_group" class="window">
        <div class="titlebar">
            <?php echo __('Rename group'); ?>
            <a href="#" class="close">[x]</a>
        </div>
        <div class="content">
            <form action="<?php echo get_url('plugin/registered_users/rename_user_group/'); ?>" method="post" name="rename_user_group">
                <input type="hidden" id="rename_user_group_id" name="id" value="'.$id.'" />
                <p>Group Name <input id="rename_user_group_new_name" maxlength="255" name="renamed" type="text" value="" /></p>
                <input id="add_user_group_button" name="commit" type="submit" value="<?php echo __('Rename group'); ?>" />
            </form>
        </div>
    </div>
</div>

<div id="popups">
    <div class="popup" id="rename_user_group" style="display:none;">
        <h3><?php echo __('Rename group'); ?></h3>
        <form action="<?php echo get_url('plugin/registered_users/rename_user_group/'); ?>" method="post"> 
            <div>
                <input type="hidden" id="rename_user_group_id" name="id" value="'.$id.'" />
                <p>Group Name <input id="rename_user_group_new_name" maxlength="255" name="renamed" type="text" value="" /></p>
                <input id="add_user_group_button" name="commit" type="submit" value="<?php echo __('Rename group'); ?>" />
            </div>
            <p><a class="close-link" href="#" onclick="toggle_rename_popup(); return false;"><?php echo __('Close'); ?></a></p>
        </form>
    </div>
</div>