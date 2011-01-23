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
<h1>Statistics</h1>


<table width="200" border="0" id="data" style="display:none;">
	<tr>
		<th>Day</th>
		<th>New Subscribers</th>
	</tr>

<?php

global $__CMS_CONN__;

$users = "SELECT * FROM ".TABLE_PREFIX."user";
$users = $__CMS_CONN__->prepare($users);
$users->execute();
$users_count = $users->rowCount();


// month on month

$months = array(
	'Jan'	=>	'01',
	'Feb'	=>	'02',
	'Mar'	=>	'03',
	'Apr'	=>	'04',
	'May'	=>	'05',
	'June'	=>	'06',
	'July'	=>	'07',
	'Aug'	=>	'08',
	'Sep'	=>	'09',
	'Oct'	=>	'10',
	'Nov'	=>	'11',
	'Dec'	=>	'12'
);

while (list($monthname, $month) = each ($months)) {
	$users = "SELECT * FROM ".TABLE_PREFIX."user WHERE created_on LIKE '%-$month-%'";
	$users = $__CMS_CONN__->prepare($users);
	$users->execute();
	$users_count = $users->rowCount(); ?>
		<tr>
			<td><?php echo $monthname; ?></td>
			<td><?php echo $users_count; ?></td>
		</tr><?php
} ?>

</table>

<h2>New Subscribers, by month</h2>
<canvas id="graph" width="550" height="220"></canvas>

<script type="text/javascript" src="<?php echo URL_PUBLIC ?>wolf/plugins/registered_users/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo URL_PUBLIC ?>wolf/plugins/registered_users/js/mocha.js"></script>