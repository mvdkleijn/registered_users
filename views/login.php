<?php
$snippet = Snippet::findByName('ru-login');

if (false !== $snippet) {
    eval('?' . '>' . $snippet->content_html);
    return true;
} else { ?>

    <p><label for="login-username"><?php echo __('Username'); ?></label> <input id="login-username" type="text" name="login[username]" value="" /></p>
    <p><label for="login-password"><?php echo __('Password'); ?></label> <input id="login-password" type="password" name="login[password]" value="" /></p>
    <input id="login-redirect" type="hidden" name="login[redirect]" value="<?php echo CURRENT_URI; ?>" />
    <p><label class="checkbox" for="login-remember-me"><?php echo __('Stay logged in');?></label> <input id="login-remember-me" type="checkbox" class="checkbox" name="login[remember]" value="checked" /></p>
    <p><label for="submit"> </label><input id="submit_btn" class="btn submit" type="submit" accesskey="s" value="<?php echo __('Log in');?>" /></p>

<?php } ?>