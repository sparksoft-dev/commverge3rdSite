<!DOCTYPE html>
<html lang="en" style="height: 100%;">
<head>
	<title>Globelines Subscriber Utility</title>
	<?php require 'head_includes.php'; ?>
	<style type="text/css">
		.button {
			font-weight: bold;
			font-size: 12px;
			color: #666;
			border-style: outset;
			font-family: verdana;
			background-color: #CEE373;
		}
		.regularColor {
			font-size: 10pt;
			color: #10188C;
			font-family: Verdana,Helvetica;
		}
		.smallFontREDB {
			font-weight: bold;
			font-size: 8pt;
			color: #C00;
			font-family: Verdana,Helvetica;
		}
	</style>
</head>
<body statictyle="height:100%; background-color:white;">
	<div id="wrap">
		<div id="main">
			<table cellspacing="0" cellpadding="0" width="99%" align="center" border="0">
				<tbody>
					<tr>
						<td>
							<a href="http://site.globe.com.ph/web/broadband"><img alt="" src="<?php echo base_url('static/img/globe-logo-util.jpg'); ?>" border="0" /></a>
						</td>
						<td align="right">
							<a href="http://site.globe.com.ph/web/broadband"><img alt="" src="<?php echo base_url('static/img/glbb.gif'); ?>" border="0" /></a>
						</td>
					</tr>
				</tbody>
			</table>
			<table cellspacing="0" cellpadding="0" width="99%" align="center" border="0">
				<tbody>
					<tr>
						<td style="background-color:#66cc33; font-size:0px;" colspan="2"><img height="2" alt="" src="<?php echo base_url('static/img/blank.gif'); ?>" width="1" border="0" /></td>
					</tr>
					<tr>
						<td align="right">[<a class="smallFontREDB" href="<?php echo base_url('utility/logout'); ?>">Sign Out</a>]</td>
					</tr>
					<tr>
						<td valign="top" height="350" colspan="2">
							<?php include 'customer_utility_menu.php'; ?>
							<table width="80%" cellspacing="0" cellpadding="4" border="0" align="center">
								<tbody>
									<tr>
										<td></td>
									</tr>
									<tr>
										<td align="center"><img border="0" src="<?php echo base_url('static/img/chgpwd.gif'); ?>" /></img></td>
									</tr>
									<tr>
										<td class="regular">
											<strong>INSTRUCTIONS</strong>: Your password is case sensitive. It must be from six to eight (6-8) characters long. 
											It may contain numbers (0-9), special symbols (e.g., !0) and upper and lowercase letters (A-Z, a-z), but no spaces. Your 
											password must be difficult to guess. Our system will not acknowledge weak passwords. 
										</td>
									</tr>
									<tr>
										<td class="regular">
											<font color="#990000">
												NOTE: For security reason, please note that this will not change your e-mail 
												login password. To change your e-mail password, login to 
												<a target="_blank" href="http://webmail.glinesnx.com.ph/">Globelines Webmail</a>
												and go to the "Option" tab.
											</font>
											<br /></br />
										</td>
									</tr>
									<tr>
										<td>
											<br /><br />
											<form name="changepassword" method="POST" action="<?php echo base_url('utility/changePassword'); ?>">
												<input type="hidden" name="action" value="save" />
    											<input type="hidden" value="" name="username" />
    											<table cellspacing="0" cellpadding="3" border="0" align="center">
													<tbody>
														<tr>
															<td class="errorMsg" align="center" colspan="2"></td>
														</tr>
														<tr>
															<td class="regular" valign="center">Current Password</td>
															<td><input type="password" name="current" size="25" maxlength="20" /></td>
														</tr>
														<tr>
															<td class="regular" valign="center">New Password</td>
															<td><input type="password" name="new_pwd" size="25" maxlength="20"></input></td>
														</tr>
														<tr>
															<td class="regular" valign="center">Retype New Password</td>
															<td><input type="password" name="new_pwd2" size="25" maxlength="20" /></td>
														</tr>
														<tr>
															<td colspan="2" style="text-align:center;">
																<span class="smallFontREDB" id="error"><?php echo isset($error) && !is_null($error) ? $error : ''; ?></span>
																<span id="message"><strong><?php echo isset($message) && !is_null($message) ? $message : ''; ?></strong></span>
																<br />
															</td>
														</tr>
														<tr>
															<td align="center" colspan="2">
																<br /></br />
																<input class="button" type="submit" value="Save" />&nbsp;&nbsp;
																<input id="clear" class="button" type="button" value="Clear" />
															</td>
														</tr>														
													</tbody>
												</table>
    										</form>
										</td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div id="footer" style="margin-top:-20px;">
		<div style="padding-right:5px;" vertical-align="center">
			<img alt="" src="<?php echo base_url('static/img/globe-logo-util.jpg'); ?>" border="0" width="90px" />
			<span style="position:relative; top:-15px;">&copy; 2014 Globelines Broadband - Globe Telecom, Inc. All Rights Reserved</span>
		</div>
	</div>
	<script type="text/javascript">
	$(document).ready(function () {
		$('input[name="current"]').focus();
		$('form[name="changepassword"]').submit(function (event) {
			var current = $('input[name="current"]'),
				newPwd = $('input[name="new_pwd"]'),
				newPwd2 = $('input[name="new_pwd2"]');
			if (current.val().trim() == '' || newPwd.val().trim() == '' || newPwd2.val().trim() == '') {
				alert('Please fill in all input fields.');
				current.focus();
				return false;
			}
			if (newPwd.val().trim() != newPwd2.val().trim()) {
				alert('New password does not match.');
				newPwd.focus();
				return false;
			}
			if (newPwd.val().length < 6) {
				alert('New password is too short.');
				newPwd.focus();
				return false;	
			}
			var number = /\d/;
			if (number.test(newPwd.val().trim()) === false) {
				alert('New password must have at least one number.');
				newPwd.focus();
				return false;
			}
			var symbol = /\W/;
			if (symbol.test(newPwd.val().trim()) === false) {
				alert('New password must have at least symbol.');
				newPwd.focus();
				return false;	
			}
			return true;
		});
		$('#clear').on('click', function () {
			$('input[name="current"]').val('');
			$('input[name="new_pwd"]').val('');
			$('input[name="new_pwd2"]').val('');
			$('#error').empty();
			$('#message').empty();
		});
	});
	</script>
</body>
</html>