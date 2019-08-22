<!DOCTYPE html>
<html lang="en">
<head>
	<title>Globelines Universal Access</title>
	<?php require 'head_includes.php'; ?>
</head>
<body style="padding-right:20px; padding-bottom:20px;">
	<div align="left">
		<h3 class="style1">Change My Password</h3>
	</div>
	<div align="center">
		<form name="changepassword" method="post" action="<?php echo base_url('main/processMyPasswordChange'); ?>">
			<table cellspacing="1" cellpadding="3" style="background-color:#ffffff;" border="0">
				<tbody>
					<tr>
						<td class="xsmallFontWHTBG">Username</td>
						<td class="xsmallFontWHTBG"><?php echo isset($username) && !is_null($username) ? $username : ''; ?></td>
					</tr>
					<tr>
						<td class="xsmallFontWHTBG">Current Password</td>
						<td class="xsmallFontWHTBG"><input class="textstyle" type="password" name="currentpassword" value="" /></td>
					</tr>
					<tr>
						<td class="xsmallFontWHTBG">New Password</td>
						<td class="xsmallFontWHTBG"><input class="textstyle" type="password" name="newpassword1" value="" /></td>
					</tr>
					<tr>
						<td class="xsmallFontWHTBG">Retype New Password</td>
						<td class="xsmallFontWHTBG"><input class="textstyle" type="password" name="newpassword2" value="" /></td>
					</tr>
					<tr>
						<td align="middle" colspan="2" class="xsmallFontWHTBG"><input class="button2" type="submit" value="change password" /></td>
					</tr>
				</tbody>
			</table>
		</form>
		<table cellspacing="1" cellpadding="2" width="100%" align="center">
			<tr>
				<td align="center">
					<span class="notificationMsg" id="notificationMsg"><?php echo isset($message) && !is_null($message) ? $message : ''; ?></span>
					<span class="errorMsg" id="errorMsg"><?php echo isset($error) && !is_null($error) ? $error : ''; ?></span>
				</td>
			</tr>
		</table>
	</div>
	<script type="text/javascript">
		$(document).ready(function () {
			$('form[name="changepassword"]').on('submit', function (event) {
				var current = $('input[name="currentpassword"]'),
					password1 = $('input[name="newpassword1"]'),
					password2 = $('input[name="newpassword2"]'); 
				if (current.val().trim() == '') {
					alert('Please fill in current password field');	
					current.focus();
					return false;
				}
				if (password1.val().trim() == '') {
					alert('Please fill in new password field');
					password1.focus();
					return false;
				}
				if (password2.val().trim() == '') {
					alert('Please retype your new password');
					password2.focus();
					return false;
				}
				if (password1.val().trim() != password2.val().trim() || password1.val().length != password2.val().length) {
					alert('Please retype your password. Password did not match.');
					password1.focus();
					return false;
				}
				return true;
			});  
		});
	</script>
</body>
</html>