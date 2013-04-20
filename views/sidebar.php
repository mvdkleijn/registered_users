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
<p class="button"><a href="<?php echo get_url('plugin/registered_users/statistics'); ?>"><img src="<?php echo PLUGINS_URI; ?>registered_users/images/statistics.png" align="middle"><?php echo __('Statistics');?></a></p>
<p class="button"><a href="<?php echo get_url('plugin/registered_users/groups'); ?>"><img src="<?php echo PLUGINS_URI; ?>registered_users/images/groups.png" align="middle"><?php echo __('User Roles');?></a></p>
<p class="button"><a href="<?php echo get_url('user'); ?>"><img src="<?php echo PLUGINS_URI; ?>registered_users/images/user.png" align="middle"><?php echo __('Users');?></a></p>
<p class="button"><a href="<?php echo get_url('plugin/registered_users/settings'); ?>"><img src="<?php echo ICONS_URI; ?>settings-32-ns.png" align="middle"><?php echo __('Settings');?></a></p>
<p class="button"><a href="<?php echo get_url('plugin/registered_users/documentation'); ?>"><img src="<?php echo ICONS_URI; ?>documentation-32-ns.png" align="middle"><?php echo __('Documentation');?></a></p>

<div class="box">
    <h3><?php echo __('Registered Users'); ?></h3>
    <p><?php echo __('This plugin allows you to manage user registrations through your Wolf CMS site.'); ?></p>
    <p><?php echo __('It controls the administration of user groups as well as the front end elements of login, logout and registration forms.'); ?></p>
    <p><a href="http://vanderkleijn.net/software/registered-users">http://vanderkleijn.net/software/registered-users</a></p>
</div>