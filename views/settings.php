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
<style>
label {
	vertical-align: top;
	float: left;
	width:150px;
	text-align: right;
	font-weight: bold;
	padding-right: 10px;
}
.field, .help {
	vertical-align: top;
}
.spacer {
	padding-bottom: 30px;
}
</style>

<h1>Registered Users Settings</h1>

<div class="form-area">

<div id="tab-control-registered-users" class="tab_control">
	<div id="tabs-admin" class="tabs">
		<div id="tab-admin-toolbar" class="tab_toolbar">&nbsp;</div>
	</div>



	<div id="admin-pages" class="pages">

		<div id="setup-page" class="page">

<?php
global $__CMS_CONN__;

$registration_settings = "SELECT * FROM ".TABLE_PREFIX."registered_users_settings WHERE id='1'";
$registration_settings = $__CMS_CONN__->prepare($registration_settings);
$registration_settings->execute();

while ($settings = $registration_settings->fetchObject()) {
	global $__CMS_CONN__;
	$id = $settings->id;
	$registration_open = $settings->allow_registrations;
	$allow_login = $settings->allow_login;
	$allow_fb_connect = $settings->allow_fb_connect;
	$connect_api_key = $settings->connect_api_key;
	$connect_secret_key = $settings->connect_secret_key;
	$random_key_length = $settings->random_key_length;
	$random_key_type = $settings->random_key_type;
	$closed_message = $settings->closed_message;
	$login_closed_message = $settings->login_closed_message;
	$login_form = $settings->login_form;
	$reset_form = $settings->reset_form;
	$registration_form = $settings->registration_form;
	$register_page = $settings->register_page;
	$already_logged_in = $settings->already_logged_in;
	$welcome_email_pt = $settings->welcome_email_pt;
	$default_permissions = $settings->default_permissions;
	$register_confirm_msg = $settings->register_confirm_msg;
	$welcome_email_pt_foot = $settings->welcome_email_pt_foot;
	$confirm_email_subject = $settings->confirm_email_subject;
	$confirm_email_from = $settings->confirm_email_from;
	$confirm_email_reply = $settings->confirm_email_reply;
	$confirmation_page = $settings->confirmation_page;
	$auth_form = $settings->auth_form;
	$message_error_technical = $settings->message_error_technical;
	$message_empty_name = $settings->message_empty_name;
	$message_empty_email = $settings->message_empty_email;
	$message_empty_username = $settings->message_empty_username;
	$message_empty_password = $settings->message_empty_password;
	$message_empty_password_confirm = $settings->message_empty_password_confirm;
	$message_notvalid_password = $settings->message_notvalid_password;
	$message_notvalid_username = $settings->message_notvalid_username;
	$message_notvalid_email = $settings->message_notvalid_email;
	$message_error_already_validated = $settings->message_error_already_validated;
	$message_need_to_register = $settings->message_need_to_register;
	$auth_required_page = $settings->auth_required_page;
	$auth_required_page_text = $settings->auth_required_page_text;
	$reset_text = $settings->reset_text;
	$reset_no_email = $settings->reset_no_email;
	$reset_page = $settings->reset_page;
	$reset_password_subject = $settings->reset_password_subject;
	$reset_password_from = $settings->reset_password_from;
	$reset_password_reply = $settings->reset_password_reply;
	$reset_email_body = $settings->reset_email_body;
	$reset_pass_type = $settings->reset_pass_type;
	$reset_pass_length = $settings->reset_pass_length;
	$reset_email_confirmed = $settings->reset_email_confirmed;
	$welcome_message = $settings->welcome_message;
}
?>
			<form action="<?php echo get_url('plugin/registered_users/edit_settings/'); ?>" method="POST" name="edit_settings">

		        <table class="fieldset" cellpadding="0" cellspacing="0" border="0">
		
					<tr>
						<td class="label">
							<label for="allow_registrations">Allow new Registrations</label>
						</td>
						<td class="field">
							<select name="allow_registrations" id="allow_registrations">
								<option value="0" <?php if($registration_open == '0') { echo 'selected="selected"'; } ?>>No</option>
								<option value="1" <?php if($registration_open == '1') { echo 'selected="selected"'; } ?>>Yes</option>
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
							<select name="allow_login" id="allow_login">
								<option value="0" <?php if($allow_login == '0') { echo 'selected="selected"'; } ?>>No</option>
								<option value="1" <?php if($allow_login == '1') { echo 'selected="selected"'; } ?>>Yes</option>
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
                                                    <select name="default_permissions" id="default_permissions">
<?php
foreach ($roles as $role) {
	echo '<option value="'.$role->id.'"';
	if($default_permissions == $role->id) { echo 'selected="selected"'; }
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
							<p><small><?php echo URL_PUBLIC ?><input type="text" name="register_page" value="<?php echo $register_page ?>" /><?php echo URL_SUFFIX ?></small></p>
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
							<p><small><?php echo URL_PUBLIC ?><input type="text" name="confirmation_page" value="<?php echo $confirmation_page ?>" /><?php echo URL_SUFFIX ?></small></p>
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
							<p><small><?php echo URL_PUBLIC ?><input type="text" name="auth_required_page" value="<?php echo $auth_required_page ?>" /><?php echo URL_SUFFIX ?></small></p>
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
							<p><small><?php echo URL_PUBLIC ?><input type="text" name="reset_page" value="<?php echo $reset_page ?>" /><?php echo URL_SUFFIX ?></small></p>
						</td>
						<td class="help">
							<p>Which page should we use to reset a users password?</p>
						</td>
					</tr>

        </table>
        
        
		</div>

		<div id="reset-page" class="page">

			<table class="fieldset" cellpadding="0" cellspacing="0" border="0">

				<legend style="font-weight: bold;"><?php echo __('Password Reset Options'); ?></legend>

				<tr>
					<td class="label">
						<label>Email Subject</label>
					</td>
					<td class="field">
						<input type="text" name="reset_password_subject" value="<?php echo $reset_password_subject ?>" />
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
						<input type="text" name="reset_password_from" value="<?php echo $reset_password_from ?>" />
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
						<input type="text" name="reset_password_reply" value="<?php echo $reset_password_reply ?>" />
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
						<textarea id="reset_text" name="reset_email_body" style="height:50px;"><?php echo $reset_email_body ?></textarea>
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
						<input type="radio" name="reset_pass_length" value="4" <?php if($reset_pass_length == '4') { echo 'checked'; } ?>> <small>4</small>
						<input type="radio" name="reset_pass_length" value="8" <?php if($reset_pass_length == '8') { echo 'checked'; } ?>> <small>8</small>
						<input type="radio" name="reset_pass_length" value="16" <?php if($reset_pass_length == '16') { echo 'checked'; } ?>> <small>16</small>
						<input type="radio" name="reset_pass_length" value="32" <?php if($reset_pass_length == '32') { echo 'checked'; } ?>> <small>32</small>
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
						<select name="reset_pass_type" id="reset_pass_type">
							<option value="alnum" <?php if($reset_pass_type == 'alnum') { echo 'selected="selected"'; } ?>>Alphanumeric</option>
							<option value="alpha" <?php if($reset_pass_type == 'alpha') { echo 'selected="selected"'; } ?>>Alpha</option>
							<option value="numeric" <?php if($reset_pass_type == 'numeric') { echo 'selected="selected"'; } ?>>Numeric</option>
							<option value="nozero" <?php if($reset_pass_type == 'nozero') { echo 'selected="selected"'; } ?>>Numeric (no zero)</option>
						</select>
					</td>
					<td class="help">
						<p>What type of characters do you want to use in the new password?</p>
					</td>
				</tr>






			</table>






		</div>


		<div id="new-registration-page" class="page">


			<p>Each new registrant will be sent an email with a link they need to click on to validate their email address and activate their account. You can customise the email they will be sent below.</p>

			<p class="spacer">&nbsp;</p>

			<table class="fieldset" cellpadding="0" cellspacing="0" border="0">

				<legend style="font-weight: bold;"><?php echo __('Email Header'); ?></legend>

				<tr>
					<td class="label">
						<label>Email Subject</label>
					</td>
					<td class="field">
						<input type="text" name="confirm_email_subject" value="<?php echo $confirm_email_subject ?>" />
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
						<input type="text" name="confirm_email_from" value="<?php echo $confirm_email_from ?>" />
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
						<input type="text" name="confirm_email_reply" value="<?php echo $confirm_email_reply ?>" />
					</td>
					<td class="help">
						<p>Left Blank by default</p>
					</td>
				</tr>
			
			</table>

			<p class="spacer">&nbsp;</p>

			<table class="fieldset" cellpadding="0" cellspacing="0" border="0">

				<legend style="font-weight: bold;"><?php echo __('Email Body'); ?></legend>

				<tr>
					<td class="label">
						<label for="welcome_email_pt">Header</label>
					</td>
					<td class="field">
						<textarea id="welcome_email_pt" name="welcome_email_pt"><?php echo $welcome_email_pt ?></textarea>
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
						<input type="radio" name="random_key_length" value="4" <?php if($random_key_length == '4') { echo 'checked'; } ?>> <small>4</small>
						<input type="radio" name="random_key_length" value="8" <?php if($random_key_length == '8') { echo 'checked'; } ?>> <small>8</small>
						<input type="radio" name="random_key_length" value="16" <?php if($random_key_length == '16') { echo 'checked'; } ?>> <small>16</small>
						<input type="radio" name="random_key_length" value="32" <?php if($random_key_length == '32') { echo 'checked'; } ?>> <small>32</small>
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
						<select name="random_key_type" id="random_key_type">
							<option value="alnum" <?php if($random_key_type == 'alnum') { echo 'selected="selected"'; } ?>>Alphanumeric</option>
							<option value="alpha" <?php if($random_key_type == 'alpha') { echo 'selected="selected"'; } ?>>Alpha</option>
							<option value="numeric" <?php if($random_key_type == 'numeric') { echo 'selected="selected"'; } ?>>Numeric</option>
							<option value="nozero" <?php if($random_key_type == 'nozero') { echo 'selected="selected"'; } ?>>Numeric (no zero)</option>
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
						<textarea id="welcome_email_pt_foot" name="welcome_email_pt_foot"><?php echo $welcome_email_pt_foot ?></textarea>
					</td>
					<td class="help">
						<p>This will be added after the confirmation link has been included in the email. It is considered good practice to explain why someone received this email and also to provide a non web contact method (ideally an address).</p>
						<p>Again, don't use HTML here as the email is plain text.</p>
					</td>
				</tr>
			
			</table>

		</div>


		<div id="forms-page" class="page">

			<p>If you'd like to build some custom forms and adapt this plugin, this is where you would do it.</p>
			<p>Please note that the header (&lt;form ... &gt;) and footer (&lt;/form&gt;) of the form should NOT be included in either of these fields as it is generated dynamically.</p>
			
			<p><strong>If you just want to get your site up and running with new registrations, leave these forms as they are now.</strong></p>
			
			<p class="spacer">&nbsp;</p>
			
			<p><label for="registration_form">Registration Form :</label>
			<textarea id="registration_form" name="registration_form" style="width:70%"><?php echo $registration_form ?></textarea></p>
			
			<p><label for="login_form">Login Form :</label>
			<textarea id="login_form" name="login_form" style="width:70%"><?php echo $login_form ?></textarea>
			
			<p><label for="auth_form">Authorisation Form :</label>
			<textarea id="auth_form" name="auth_form" style="width:70%"><?php echo $auth_form ?></textarea>

			<p><label for="reset_form">Reset Password Form :</label>
			<textarea id="reset_form" name="reset_form" style="width:70%"><?php echo $reset_form ?></textarea>


		</div>


		<div id="messages-page" class="page">

<h2>Messages</h2>

<p>Below is a list of all front end messages that a user can receive. Edit these messages if you would like to change the wording of the registration process. I would recommended that you use some Javascript for form validation on the Front of the site to make the user experience slicker.</p>

<p class="spacer">&nbsp;</p>


			<table class="fieldset" cellpadding="0" cellspacing="0" border="0">

				<legend style="font-weight: bold;"><?php echo __('Error Messages'); ?></legend>

				<tr>
					<td class="label">
						<label for="message_error_technical">Technical Error :</label>
					</td>
					<td class="field">
						<textarea id="message_error_technical" name="message_error_technical" style="height:50px;"><?php echo $message_error_technical ?></textarea>
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
						<textarea id="message_empty_name" name="message_empty_name" style="height:50px;"><?php echo $message_empty_name ?></textarea>
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
						<textarea id="message_empty_email" name="message_empty_email" style="height:50px;"><?php echo $message_empty_email ?></textarea>
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
						<textarea id="message_empty_username" name="message_empty_username" style="height:50px;"><?php echo $message_empty_username ?></textarea>
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
						<textarea id="message_empty_password" name="message_empty_password" style="height:50px;"><?php echo $message_empty_password ?></textarea>
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
						<textarea id="message_empty_password_confirm" name="message_empty_password_confirm" style="height:50px;"><?php echo $message_empty_password_confirm ?></textarea>
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
						<textarea id="message_notvalid_password" name="message_notvalid_password" style="height:50px;"><?php echo $message_notvalid_password ?></textarea>
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
						<textarea id="message_notvalid_username" name="message_notvalid_username" style="height:50px;"><?php echo $message_notvalid_username ?></textarea>
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
						<textarea id="message_notvalid_email" name="message_notvalid_email" style="height:50px;"><?php echo $message_notvalid_email ?></textarea>
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
						<textarea id="message_error_already_validated" name="message_error_already_validated" style="height:50px;"><?php echo $message_error_already_validated ?></textarea>
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
						<textarea id="welcome_message" name="welcome_message" style="height:50px;"><?php echo $welcome_message ?></textarea>
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
						<textarea id="message_need_to_register" name="message_need_to_register" style="height:50px;"><?php echo $message_need_to_register ?></textarea>
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
						<textarea id="auth_required_page_text" name="auth_required_page_text" style="height:50px;"><?php echo $auth_required_page_text ?></textarea>
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
						<textarea id="reset_text" name="reset_text" style="height:50px;"><?php echo $reset_text ?></textarea>
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
						<textarea id="reset_no_email" name="reset_no_email" style="height:50px;"><?php echo $reset_no_email ?></textarea>
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
						<textarea id="reset_email_confirmed" name="reset_email_confirmed" style="height:50px;"><?php echo $reset_email_confirmed ?></textarea>
					</td>
					<td class="help">
						<p>Please use HTML</p>
						<p>Displayed when someone has their password reset</p>
					</td>
				</tr>



			</table>

			<p class="spacer">&nbsp;</p>

			<table class="fieldset" cellpadding="0" cellspacing="0" border="0">

				<legend style="font-weight: bold;"><?php echo __('Registration Confirmation'); ?></legend>

				<tr>
					<td class="label">
						<label for="register_confirm_msg">Registration Confirmation :</label>
					</td>
					<td class="field">
						<textarea id="register_confirm_msg" name="register_confirm_msg" style="height:50px;"><?php echo $register_confirm_msg ?></textarea>
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
						<textarea id="closed_message" name="closed_message" style="height:50px;"><?php echo $closed_message ?></textarea>
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
						<textarea id="login_closed_message" name="login_closed_message" style="height:50px;"><?php echo $login_closed_message ?></textarea>
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
						<textarea id="already_logged_in" name="already_logged_in" style="height:50px;"><?php echo $already_logged_in ?></textarea>
					</td>
					<td class="help">
						<p>Please use HTML</p><p>This is shown if the user is already logged in when visiting the login page...</p>
					</td>
				</tr>

			</table>



	</div>
	<div id="facebook-page" class="page" style="display:none;">

		<table class="fieldset" cellpadding="0" cellspacing="0" border="0">

			<tr>
				<td class="label">
					<label for="allow_fb_connect">Enable FB Connect</label>
				</td>
				<td class="field">
					<select name="allow_fb_connect" id="allow_fb_connect">
						<option value="0" <?php if($allow_fb_connect == '0') { echo 'selected="selected"'; } ?>>No</option>
						<option value="1" <?php if($allow_fb_connect == '1') { echo 'selected="selected"'; } ?>>Yes</option>
					</select>
				</td>
				<td class="help">
					<p><a href="http://developers.facebook.com/connect.php" target="_blank">What is Facebook Connect?</a></p>
				</td>
			</tr>

			<tr>
				<td class="label">
					<label>Connect API key</label>
				</td>
				<td class="field">
					<input type="text" name="connect_api_key" value="<?php echo $connect_api_key ?>" size="32" />
				</td>
				<td class="help">
					<p><a href="http://www.facebook.com/developers/createapp.php" target="_blank">Create a Facebook application</a></p>
				</td>
			</tr>

			<tr>
				<td class="label">
					<label>Connect Secret key</label>
				</td>
				<td class="field">
					<input type="text" name="connect_secret_key" value="<?php echo $connect_secret_key ?>" size="32" />
				</td>
				<td class="help">
					<p>Given to you by Facebook</p>
				</td>
			</tr>

		</table>

	</div>

	<p><label>&nbsp;</label><input class="button" name="edit_settings" type="submit" value="Edit User Registration Settings"></p>
	<p>&nbsp;</p>
    </div>
    </form>

</div>

</div>

<script type="text/javascript">
	var tabControlMeta = new TabControl('tab-control-registered-users');
	tabControlMeta.addTab('tab-setup', 'Setup', 'setup-page');
	tabControlMeta.addTab('tab-new-registration', 'Registration Email', 'new-registration-page');
	tabControlMeta.addTab('tab-reset', 'Password Reset Email', 'reset-page');
	tabControlMeta.addTab('tab-forms', 'Forms', 'forms-page');
	tabControlMeta.addTab('tab-messages', 'System Messages', 'messages-page');
	tabControlMeta.addTab('tab-facebook', 'Facebook', 'facebook-page');
	tabControlMeta.select(tabControlMeta.firstTab());
</script>
