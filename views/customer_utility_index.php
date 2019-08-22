<!DOCTYPE html>
<html lang="en">
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
	</style>
</head>
<body style="height:100%; background-color:white;">
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
						<td valign="top" height="350" colspan="2">
							<br /><br />
							<form name="loginform" method="POST" action="#">
								<table width="45%" cellspacing="1" cellpadding="0" border="0" bgcolor="#639ace" align="center">
									<tbody>
										<tr>
											<td class="regularB" height="25" align="center">
												<font color="#ffffff">Globelines Subscriber Utility</font>
											</td>
										</tr>
										<tr>
											<td>
												<table width="100%" cellspacing="1" cellpadding="4" border="0" bgcolor="#cee373" align="center">
													<tbody>
														<tr>
														    <td class="errorMsg" align="center" colspan="2">
														        <br></br>
														    </td>
														</tr>
														<tr>
															<td class="regular" width="40%" align="right">Realm:</td>
															<td class="regular"><?php include 'allowed_realms.php'; ?></td>
														</tr>
														<tr>
															<td class="regular" align="right">Username:</td>
															<td class="regular"><input name="username" size="25" maxlength="30" /></input></td>
														</tr>
														<tr>
															<td class="regular" align="right">Password:</td>
															<td><input type="password" name="password" size="25" maxlength="20" /></input></td>
														</tr>
														<tr>
															<td align="center"></td>
															<td>
																<input class="button" type="submit" value="Login" />
																<!--https://dslutility.globelines.com.ph/forgot.php-->
																<!--
																<a class="smallFont" href="">
																	<font color="#990000">Forgot Password?</font>
																</a>
																-->
																<br /><br /><br /><br />
    														</td>
														</tr>
													</tbody>
												</table>
											</td>
										</tr>
										<tr>
											<td><img border="0" usemap="#WorldPass" src="<?php echo base_url('static/img/BBUPlogon.jpeg'); ?>" alt="" /></td>
										</tr>
									</tbody>
								</table>
							</form>
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
    		$('input[name="username"]').focus();
    		$('form[name="loginform"]').submit(function (event) {
    			var username = $('input[name="username"]'),
    				password = $('input[name="password"]'),
    				realm = $('select[name="realm"]').val();
				console.log('username:' + username.val().trim() + ', realm:' + realm + ', password:' + password.val().trim());
    			if (username.val().trim() == '' || password.val().trim() == '') {
					alert('Please fill in the username and password fields.');
					username.focus();
					return false;
    			} else {
    				$.ajax({
						url: '<?php echo base_url("utility/login"); ?>',
						type: 'POST',
						data: {
							'username': username.val().trim(),
							'realm': realm,
							'password': password.val().trim()
						},
						success: function (resp) {
							console.log(resp);
							var response = $.parseJSON(resp);
							if (parseInt(response.status) == 1) {
								window.location = '<?php echo base_url("' + response.redirect + '") ?>';
							} else {
								alert('Invalid login credentials.\n\nPlease try again.');
							}
						},
						error: function (resp) {
							console.log(resp);
							alert('An unexpected error occurred during the request.\n\nPlease try again later.');
						}
					});
    			}
				return false;
    		});
    	});
    </script>
</body>
</html>