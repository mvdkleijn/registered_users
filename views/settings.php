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
    /*.tab {
        display: inline;
        margin-right: 0.5em;
        border: 1px solid black !important;
    }*/

    .tab {
        display: inline;
        margin-right: 5px;
        border: none !important;
    }

    .tab a:link, .tab a:visited {
        padding: 6px 8px 5px 8px;
        margin: 0;
        color: #000;
        background-color: #e5e5e5; /* #A28C6A; */

        -moz-border-radius: 6px;
        -moz-border-radius-bottomright: 0px;
        -moz-border-radius-bottomleft: 0px;
        -webkit-border-radius: 6px;
        -webkit-border-bottom-right-radius: 0px;
        -webkit-border-bottom-left-radius: 0px;
    }

    .pages { margin: 0em 1em; adding: 1em; border: 1px solid #000; }
    .page { margin: 0; padding: 1em 0em;}

    .tabNavigation { margin: 0.3em 1em; }
    
    .tabNavigation .tab a.here {
        background-color: #76A83A;
        color: #fff;
        font-weight: bold;
        border-bottom: 1px solid #76A83A;
    }

    .tab a:link, .tab a:visited {
        font-size: 90%;
        text-decoration: none !important;
        border-bottom: 1px solid #000;
    }

</style>

<h1><?php echo __('Registered users settings');?></h1>


<script type="text/javascript">
    // <![CDATA[
    
    $(document).ready(function() {
        var tabContainers = $('div.tabs > div.pages > div');

        $('div.tabs ul.tabNavigation a').click(function () {
            $('div.tabs ul.tabNavigation a').removeClass('here').filter(this).addClass('here');
            tabContainers.hide().filter(this.hash).show();
            return false;
        }).filter(':first').click();
    });

    // ]]>
</script>

<?php
    $registration_open = $settings["allow_registrations"];
    $allow_login = $settings["allow_login"];
    $allow_fb_connect = $settings["allow_fb_connect"];
    $connect_api_key = $settings["connect_api_key"];
    $connect_secret_key = $settings["connect_secret_key"];
    $random_key_length = $settings["random_key_length"];
    $random_key_type = $settings["random_key_type"];
    $closed_message = $settings["closed_message"];
    $login_closed_message = $settings["login_closed_message"];
    $login_form = $settings["login_form"];
    $reset_form = $settings["reset_form"];
    $registration_form = $settings["registration_form"];
    $register_page = $settings["register_page"];
    $already_logged_in = $settings["already_logged_in"];
    $welcome_email_pt = $settings["welcome_email_pt"];
    $default_permissions = $settings["default_permissions"];
    $register_confirm_msg = $settings["register_confirm_msg"];
    $welcome_email_pt_foot = $settings["welcome_email_pt_foot"];
    $confirm_email_subject = $settings["confirm_email_subject"];
    $confirm_email_from = $settings["confirm_email_from"];
    $confirm_email_reply = $settings["confirm_email_reply"];
    $confirmation_page = $settings["confirmation_page"];
    $auth_form = $settings["auth_form"];
    $message_error_technical = $settings["message_error_technical"];
    $message_empty_name = $settings["message_empty_name"];
    $message_empty_email = $settings["message_empty_email"];
    $message_empty_username = $settings["message_empty_username"];
    $message_empty_password = $settings["message_empty_password"];
    $message_empty_password_confirm = $settings["message_empty_password_confirm"];
    $message_notvalid_password = $settings["message_notvalid_password"];
    $message_notvalid_username = $settings["message_notvalid_username"];
    $message_notvalid_email = $settings["message_notvalid_email"];
    $message_error_already_validated = $settings["message_error_already_validated"];
    $message_need_to_register = $settings["message_need_to_register"];
    $auth_required_page = $settings["auth_required_page"];
    $auth_required_page_text = $settings["auth_required_page_text"];
    $reset_text = $settings["reset_text"];
    $reset_no_email = $settings["reset_no_email"];
    $reset_page = $settings["reset_page"];
    $reset_password_subject = $settings["reset_password_subject"];
    $reset_password_from = $settings["reset_password_from"];
    $reset_password_reply = $settings["reset_password_reply"];
    $reset_email_body = $settings["reset_email_body"];
    $reset_pass_type = $settings["reset_pass_type"];
    $reset_pass_length = $settings["reset_pass_length"];
    $reset_email_confirmed = $settings["reset_email_confirmed"];
    $welcome_message = $settings["welcome_message"];
?>

<form id="settings" action="<?php echo get_url('plugin/registered_users/edit_settings/'); ?>" method="post" style="margin: 2em 0em;">
    <!--input id="csrf_token" name="csrf_token" type="hidden" value="e1ff5580fae97e143e50d03d7bc84e3a7d77d4f809964bb793745a7f565f5048" /-->

    <div style="">
        <div class="content tabs">

            <ul class="tabNavigation">
                <li class="tab"><a href="#general"><?php echo __('Generic'); ?></a></li>
                <li class="tab"><a href="#regemail"><?php echo __('Registration Email'); ?></a></li>
                <li class="tab"><a href="#pwdemail"><?php echo __('Password Reset Email'); ?></a></li>
                <li class="tab"><a href="#messages"><?php echo __('Messages'); ?></a></li>
            </ul>

            <div class="pages">
                <div id="general" class="page">
                    <table class="fieldset" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td class="label">
                                <label for="allow_registrations">Allow new Registrations</label>
                            </td>
                            <td class="field">
                                <select name="settings[allow_registrations]" id="allow_registrations">
                                    <option value="0" <?php
if ($registration_open == '0') {
    echo 'selected="selected"';
}
?>>No</option>
                                    <option value="1" <?php
                                            if ($registration_open == '1') {
                                                echo 'selected="selected"';
                                            }
?>>Yes</option>
                                </select>
                            </td>
                            <td class="help">
                                <p>Should we let people sign up for new accounts?</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">
                                <label for="allow_login">Allow frontend Login</label>
                            </td>
                            <td class="field">
                                <select name="settings[allow_login]" id="allow_login">
                                    <option value="0" <?php
                                            if ($allow_login == '0') {
                                                echo 'selected="selected"';
                                            }
?>>No</option>
                                    <option value="1" <?php
                                            if ($allow_login == '1') {
                                                echo 'selected="selected"';
                                            }
?>>Yes</option>
                                </select>
                            </td>
                            <td class="help">
                                <p>You may want to disable this during testing / system maintenance</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">
                                <label for="default_permissions">Default User Group</label>
                            </td>
                            <td class="field">
                                <select name="settings[default_permissions]" id="default_permissions">
                                    <?php
                                    foreach ($roles as $role) {
                                        echo '<option value="'.$role->id.'"';
                                        if ($default_permissions == $role->id) {
                                            echo 'selected="selected"';
                                        }
                                        echo '>'.$role->name.'</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                            <td class="help">
                                <p>What permissions new users should have when they first sign up</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">
                                <label>Registration Page</label>
                            </td>
                            <td class="field">
                                <p><small><?php echo URL_PUBLIC ?><input type="text" name="settings[register_page]" value="<?php echo $register_page ?>" /><?php echo URL_SUFFIX ?></small></p>
                            </td>
                            <td class="help">
                                <p>What is the path to your registration page?</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">
                                <label>Confirmation Page</label>
                            </td>
                            <td class="field">
                                <p><small><?php echo URL_PUBLIC ?><input type="text" name="settings[confirmation_page]" value="<?php echo $confirmation_page ?>" /><?php echo URL_SUFFIX ?></small></p>
                            </td>
                            <td class="help">
                                <p>What is the path to the confirmation page new users will see when they validate their email address?</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">
                                <label>Authorisation Required</label>
                            </td>
                            <td class="field">
                                <p><small><?php echo URL_PUBLIC ?><input type="text" name="settings[auth_required_page]" value="<?php echo $auth_required_page ?>" /><?php echo URL_SUFFIX ?></small></p>
                            </td>
                            <td class="help">
                                <p>What is the path to the page users will see if they try access a page they don't have access to?</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">
                                <label>Reset Password Page</label>
                            </td>
                            <td class="field">
                                <p><small><?php echo URL_PUBLIC ?><input type="text" name="settings[reset_page]" value="<?php echo $reset_page ?>" /><?php echo URL_SUFFIX ?></small></p>
                            </td>
                            <td class="help">
                                <p>Which page should we use to reset a users password?</p>
                            </td>
                        </tr>
                    </table>
                </div>
                <div id="regemail" class="page">
                    <p>Each new registrant will be sent an email with a link they need to click on to validate their email address and activate their account. You can customise the email they will be sent below.</p>
                    <table class="fieldset" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td class="label">
                                <label>Email Subject</label>
                            </td>
                            <td class="field">
                                <input type="text" name="settings[confirm_email_subject]" value="<?php echo $confirm_email_subject ?>" />
                            </td>
                            <td class="help">
                                <p>Subject Line in registration email</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">
                                <label>Email From</label>
                            </td>
                            <td class="field">
                                <input type="text" name="settings[confirm_email_from]" value="<?php echo $confirm_email_from ?>" />
                            </td>
                            <td class="help">
                                <p>Where the registration email is addressed from</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">
                                <label>Email Reply-To</label>
                            </td>
                            <td class="field">
                                <input type="text" name="settings[confirm_email_reply]" value="<?php echo $confirm_email_reply ?>" />
                            </td>
                            <td class="help">
                                <p>Left Blank by default</p>
                            </td>
                        </tr>
                    </table>
                    <table class="fieldset" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td class="label">
                                <label for="welcome_email_pt">Header</label>
                            </td>
                            <td class="field">
                                <textarea id="welcome_email_pt" name="settings[welcome_email_pt]"><?php echo $welcome_email_pt ?></textarea>
                            </td>
                            <td class="help">
                                <p>This is a PLAIN TEXT email - do NOT use HTML here</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">
                                <label for="random_key_length">Length of Random key</label>
                            </td>
                            <td class="field">
                                <input type="radio" name="settings[random_key_length]" value="4" <?php
                                    if ($random_key_length == '4') {
                                        echo 'checked';
                                    }
                                    ?>> <small>4</small>
                                <input type="radio" name="settings[random_key_length]" value="8" <?php
                                       if ($random_key_length == '8') {
                                           echo 'checked';
                                       }
                                    ?>> <small>8</small>
                                <input type="radio" name="settings[random_key_length]" value="16" <?php
                                       if ($random_key_length == '16') {
                                           echo 'checked';
                                       }
                                    ?>> <small>16</small>
                                <input type="radio" name="settings[random_key_length]" value="32" <?php
                                       if ($random_key_length == '32') {
                                           echo 'checked';
                                       }
                                    ?>> <small>32</small>
                            </td>
                            <td class="help">
                                <p>How long should the random key that is generated be? Longer is more secure.</p>
                            </td>
                        </tr>

                        <tr>
                            <td class="label">
                                <label for="random_key_type">Random Key Type</label>
                            </td>
                            <td class="field">
                                <select name="settings[random_key_type]" id="random_key_type">
                                    <option value="alnum" <?php
                                       if ($random_key_type == 'alnum') {
                                           echo 'selected="selected"';
                                       }
                                    ?>>Alphanumeric</option>
                                    <option value="alpha" <?php
                                            if ($random_key_type == 'alpha') {
                                                echo 'selected="selected"';
                                            }
                                    ?>>Alpha</option>
                                    <option value="numeric" <?php
                                            if ($random_key_type == 'numeric') {
                                                echo 'selected="selected"';
                                            }
                                    ?>>Numeric</option>
                                    <option value="nozero" <?php
                                            if ($random_key_type == 'nozero') {
                                                echo 'selected="selected"';
                                            }
                                    ?>>Numeric (no zero)</option>
                                </select>
                            </td>
                            <td class="help">
                                <p>What type of random key do you want to generate?</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">
                                <label for="welcome_email_pt_foot">Footer</label>
                            </td>
                            <td class="field">
                                <textarea id="welcome_email_pt_foot" name="settings[welcome_email_pt_foot]"><?php echo $welcome_email_pt_foot ?></textarea>
                            </td>
                            <td class="help">
                                <p>This will be added after the confirmation link has been included in the email. It is considered good practice to explain why someone received this email and also to provide a non web contact method (ideally an address).</p>
                                <p>Again, don't use HTML here as the email is plain text.</p>
                            </td>
                        </tr>
                    </table>
                </div>
                <div id="pwdemail" class="page">
                    <table class="fieldset" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td class="label">
                                <label>Email Subject</label>
                            </td>
                            <td class="field">
                                <input type="text" name="settings[reset_password_subject]" value="<?php echo $reset_password_subject ?>" />
                            </td>
                            <td class="help">
                                <p>Subject line in reset email</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">
                                <label>Email From</label>
                            </td>
                            <td class="field">
                                <input type="text" name="settings[reset_password_from]" value="<?php echo $reset_password_from ?>" />
                            </td>
                            <td class="help">
                                <p>From address in reset email</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">
                                <label>Email Reply</label>
                            </td>
                            <td class="field">
                                <input type="text" name="settings[reset_password_reply]" value="<?php echo $reset_password_reply ?>" />
                            </td>
                            <td class="help">
                                <p>Reply address in reset email</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">
                                <label>Email Body</label>
                            </td>
                            <td class="field">
                                <textarea id="reset_text" name="settings[reset_email_body]" style="height:50px;"><?php echo $reset_email_body ?></textarea>
                            </td>
                            <td class="help">
                                <p>This is the body of the email. Immediately after this body, the new password will be added.</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">
                                <label for="reset_pass_length">Length of Random key</label>
                            </td>
                            <td class="field">
                                <input type="radio" name="settings[reset_pass_length]" value="4" <?php
                                            if ($reset_pass_length == '4') {
                                                echo 'checked';
                                            }
                                    ?>> <small>4</small>
                                <input type="radio" name="settings[reset_pass_length]" value="8" <?php
                                       if ($reset_pass_length == '8') {
                                           echo 'checked';
                                       }
                                    ?>> <small>8</small>
                                <input type="radio" name="settings[reset_pass_length]" value="16" <?php
                                       if ($reset_pass_length == '16') {
                                           echo 'checked';
                                       }
                                    ?>> <small>16</small>
                                <input type="radio" name="settings[reset_pass_length]" value="32" <?php
                                       if ($reset_pass_length == '32') {
                                           echo 'checked';
                                       }
                                    ?>> <small>32</small>
                            </td>
                            <td class="help">
                                <p>How long should the new password that is generated be?</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">
                                <label for="reset_pass_type">Random Password Type</label>
                            </td>
                            <td class="field">
                                <select name="settings[reset_pass_type]" id="reset_pass_type">
                                    <option value="alnum" <?php
                                       if ($reset_pass_type == 'alnum') {
                                           echo 'selected="selected"';
                                       }
                                    ?>>Alphanumeric</option>
                                    <option value="alpha" <?php
                                            if ($reset_pass_type == 'alpha') {
                                                echo 'selected="selected"';
                                            }
                                    ?>>Alpha</option>
                                    <option value="numeric" <?php
                                            if ($reset_pass_type == 'numeric') {
                                                echo 'selected="selected"';
                                            }
                                    ?>>Numeric</option>
                                    <option value="nozero" <?php
                                            if ($reset_pass_type == 'nozero') {
                                                echo 'selected="selected"';
                                            }
                                    ?>>Numeric (no zero)</option>
                                </select>
                            </td>
                            <td class="help">
                                <p>What type of characters do you want to use in the new password?</p>
                            </td>
                        </tr>
                    </table>

                </div>

                <div id="messages" class="page">
                    <p>Below is a list of all front end messages that a user can receive. Edit these messages if you would like to change the wording of the registration process. I would recommended that you use some Javascript for form validation on the Front of the site to make the user experience slicker.</p>
                    <table class="fieldset" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td class="label">
                                <label for="message_error_technical">Technical Error :</label>
                            </td>
                            <td class="field">
                                <textarea id="message_error_technical" name="settings[message_error_technical]" style="height:50px;"><?php echo $message_error_technical ?></textarea>
                            </td>
                            <td class="help">
                                <p>Please use HTML</p>
                            </td>
                        </tr>

                        <tr>
                            <td class="label">
                                <label for="message_empty_name">No name entered :</label>
                            </td>
                            <td class="field">
                                <textarea id="message_empty_name" name="settings[message_empty_name]" style="height:50px;"><?php echo $message_empty_name ?></textarea>
                            </td>
                            <td class="help">
                                <p>Please use HTML</p>
                            </td>
                        </tr>

                        <tr>
                            <td class="label">
                                <label for="message_empty_email">No email entered :</label>
                            </td>
                            <td class="field">
                                <textarea id="message_empty_email" name="settings[message_empty_email]" style="height:50px;"><?php echo $message_empty_email ?></textarea>
                            </td>
                            <td class="help">
                                <p>Please use HTML</p>
                            </td>
                        </tr>

                        <tr>
                            <td class="label">
                                <label for="message_empty_username">No Username entered :</label>
                            </td>
                            <td class="field">
                                <textarea id="message_empty_username" name="settings[message_empty_username]" style="height:50px;"><?php echo $message_empty_username ?></textarea>
                            </td>
                            <td class="help">
                                <p>Please use HTML</p>
                            </td>
                        </tr>


                        <tr>
                            <td class="label">
                                <label for="message_empty_password">No Password entered :</label>
                            </td>
                            <td class="field">
                                <textarea id="message_empty_password" name="settings[message_empty_password]" style="height:50px;"><?php echo $message_empty_password ?></textarea>
                            </td>
                            <td class="help">
                                <p>Please use HTML</p>
                            </td>
                        </tr>

                        <tr>
                            <td class="label">
                                <label for="message_empty_password_confirm">No confirmation Password entered :</label>
                            </td>
                            <td class="field">
                                <textarea id="message_empty_password_confirm" name="settings[message_empty_password_confirm]" style="height:50px;"><?php echo $message_empty_password_confirm ?></textarea>
                            </td>
                            <td class="help">
                                <p>Please use HTML</p>
                            </td>
                        </tr>

                        <tr>
                            <td class="label">
                                <label for="message_notvalid_password">Passwords don't match :</label>
                            </td>
                            <td class="field">
                                <textarea id="message_notvalid_password" name="settings[message_notvalid_password]" style="height:50px;"><?php echo $message_notvalid_password ?></textarea>
                            </td>
                            <td class="help">
                                <p>Please use HTML</p>
                            </td>
                        </tr>

                        <tr>
                            <td class="label">
                                <label for="message_notvalid_username">Invalid Username :</label>
                            </td>
                            <td class="field">
                                <textarea id="message_notvalid_username" name="settings[message_notvalid_username]" style="height:50px;"><?php echo $message_notvalid_username ?></textarea>
                            </td>
                            <td class="help">
                                <p>Please use HTML</p>
                            </td>
                        </tr>

                        <tr>
                            <td class="label">
                                <label for="message_notvalid_email">Invalid Email :</label>
                            </td>
                            <td class="field">
                                <textarea id="message_notvalid_email" name="settings[message_notvalid_email]" style="height:50px;"><?php echo $message_notvalid_email ?></textarea>
                            </td>
                            <td class="help">
                                <p>Please use HTML</p>
                            </td>
                        </tr>

                        <tr>
                            <td class="label">
                                <label for="message_error_already_validated">User has already been validated :</label>
                            </td>
                            <td class="field">
                                <textarea id="message_error_already_validated" name="settings[message_error_already_validated]" style="height:50px;"><?php echo $message_error_already_validated ?></textarea>
                            </td>
                            <td class="help">
                                <p>Please use HTML</p>
                            </td>
                        </tr>

                        <tr>
                            <td class="label">
                                <label for="welcome_message">Welcome Message</label>
                            </td>
                            <td class="field">
                                <textarea id="welcome_message" name="settings[welcome_message]" style="height:50px;"><?php echo $welcome_message ?></textarea>
                            </td>
                            <td class="help">
                                <p>Please use HTML</p>
                            </td>
                        </tr>

                        <tr>
                            <td class="label">
                                <label for="message_need_to_register">Need to Register?</label>
                            </td>
                            <td class="field">
                                <textarea id="message_need_to_register" name="settings[message_need_to_register]" style="height:50px;"><?php echo $message_need_to_register ?></textarea>
                            </td>
                            <td class="help">
                                <p><strong>NO HTML HERE</strong> - this is a link to the registration page from the login form</p>
                            </td>
                        </tr>

                        <tr>
                            <td class="label">
                                <label for="auth_required_page_text">Authorisation Required</label>
                            </td>
                            <td class="field">
                                <textarea id="auth_required_page_text" name="settings[auth_required_page_text]" style="height:50px;"><?php echo $auth_required_page_text ?></textarea>
                            </td>
                            <td class="help">
                                <p>Please use HTML</p>
                                <p>This message will be shown if a user tries to access a page they don't have permission to</p>
                            </td>
                        </tr>

                        <tr>
                            <td class="label">
                                <label for="reset_text">Reset Password</label>
                            </td>
                            <td class="field">
                                <textarea id="reset_text" name="settings[reset_text]" style="height:50px;"><?php echo $reset_text ?></textarea>
                            </td>
                            <td class="help">
                                <p>Please use HTML</p>
                            </td>
                        </tr>

                        <tr>
                            <td class="label">
                                <label>No Email address entered</label>
                            </td>
                            <td class="field">
                                <textarea id="reset_no_email" name="settings[reset_no_email]" style="height:50px;"><?php echo $reset_no_email ?></textarea>
                            </td>
                            <td class="help">
                                <p>Please use HTML</p>
                                <p>Used if someone doesn't add their email address in the reset password page.</p>
                            </td>
                        </tr>

                        <tr>
                            <td class="label">
                                <label>New Password Sent</label>
                            </td>
                            <td class="field">
                                <textarea id="reset_email_confirmed" name="settings[reset_email_confirmed]" style="height:50px;"><?php echo $reset_email_confirmed ?></textarea>
                            </td>
                            <td class="help">
                                <p>Please use HTML</p>
                                <p>Displayed when someone has their password reset</p>
                            </td>
                        </tr>
                    </table>
                    <table class="fieldset" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td class="label">
                                <label for="register_confirm_msg">Registration Confirmation :</label>
                            </td>
                            <td class="field">
                                <textarea id="register_confirm_msg" name="settings[register_confirm_msg]" style="height:50px;"><?php echo $register_confirm_msg ?></textarea>
                            </td>
                            <td class="help">
                                <p>Please use HTML</p><p>This is shown when the registration form is successfully submitted...</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">
                                <label for="closed_message">Registration Closed Message :</label>
                            </td>
                            <td class="field">
                                <textarea id="closed_message" name="settings[closed_message]" style="height:50px;"><?php echo $closed_message ?></textarea>
                            </td>
                            <td class="help">
                                <p>Please use HTML</p><p>This is shown if registration is closed...</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">
                                <label for="login_closed_message">Login Closed Message :</label>
                            </td>
                            <td class="field">
                                <textarea id="login_closed_message" name="settings[login_closed_message]" style="height:50px;"><?php echo $login_closed_message ?></textarea>
                            </td>
                            <td class="help">
                                <p>Please use HTML</p><p>This is shown if login is disabled...</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">
                                <label for="already_logged_in">Already logged in message :</label>
                            </td>
                            <td class="field">
                                <textarea id="already_logged_in" name="settings[already_logged_in]" style="height:50px;"><?php echo $already_logged_in ?></textarea>
                            </td>
                            <td class="help">
                                <p>Please use HTML</p><p>This is shown if the user is already logged in when visiting the login page...</p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="buttons">
        <button id="commit" name="edit_settings" type="submit" accesskey="s"><?php echo __('Save'); ?></button>
        <?php echo __('or'); ?> <a href="<?php echo get_url('plugin/registered_users'); ?>"><?php echo __('Cancel'); ?></a>
    </div>
</form>
