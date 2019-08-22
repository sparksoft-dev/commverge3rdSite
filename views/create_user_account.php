<!DOCTYPE html>
<html lang="en">
<head>
	<title>Globelines Universal Access</title>
	<?php require 'head_includes.php'; ?>
</head>
<body style="padding-right:20px; padding-bottom:20px;">
	<div align="left">
		<h3 class="style1">Create Primary Account</h3>
	</div>
	<div class="smallFontB" align="right">
		<a href="<?php echo base_url('subscribers/processBulkLoadSubscribers/upload'); ?>">Bulk Load</a>&nbsp;|&nbsp;
		<a href="<?php echo base_url('subscribers/processBulkCheckSubscribers/upload'); ?>">Bulk Check</a>
	</div>
	<div align="center">
		<table cellspacing="1" cellpadding="2" width="100%" align="center">
			<tr>
				<td>
					<span class="notificationMsg"><?php echo isset($message) ? $message : ''; ?></span>
					<span class="errorMsg"><?php echo isset($error) ? $error : ''; ?></span>
				</td>
			</tr>
		</table>
	</div>
	<br />
	<div class="smallFontB">
		<form name="globeacct" action="<?php echo base_url('subscribers/processCreateSubscriber'); ?>" method="post">
			<table cellspacing="0" cellpadding="5" width="100%" border="0">
				<tbody>
					<tr>
						<td class="regular">Realm:</td>
						<td class="smallFontWHTBG" align="left"><?php include 'allowed_realms.php'; ?></td>
					</tr>
					<tr>
						<td class="regular">Username:</td>
						<td>
							<input id="start" maxlength="70" name="username" value="<?php echo isset($username) ? $username : ''; ?>" autocomplete="off" />&nbsp;
							<font face="verdana" color="#990000" size="1">*required</font>&nbsp;
							<font class="smallFontGRAY">(maximum of 70 characters)</font><br />
						</td>
					</tr>
					<tr>
						<td class="regular">Password:</td>
						<td>
							<input type="password" maxlength="20" name="password1" value="<?php echo isset($password) ? $password : ''; ?>" />&nbsp;
							<font face="verdana" color="#990000" size="1">*required</font>&nbsp;
							<font class="smallFontGRAY">(maximum of 20 characters)</font><br />
						</td>
					</tr>
					<tr>
						<td class="regular">Retype Password:</td>
						<td>
							<input type="password" maxlength="20" name="password2" value="<?php echo isset($password) ? $password : ''; ?>" />&nbsp;
							<font face="verdana" color="#990000" size="1">*required</font><br />
						</td>
					</tr>
					<!-- Remove Attributes 5/21/19 -->
					<!-- <tr>
						<td class="regular">Customer Type:</td>
						<td>
							<select name="acctplan">
								<option value="Residential" <?php //echo isset($acctplan) ? ($acctplan == 'Residential' ? 'selected' : '') : 'selected'; ?>>Residential</option>
								<option value="Business" <?php //echo isset($acctplan) && $acctplan == 'Business' ? 'selected' : ''; ?>>Business</option>
							</select>&nbsp;
							<font face="verdana" color="#990000" size="1">*required</font><br />
						</td>
					</tr> -->
					<tr>
						<td class="regular">Status:</td>
						<td>
							<select name="status">
								<!-- Only Active status will be displayed 6/28/19 -->
								<!-- <option value="InActive" <?php //echo isset($status) ? ($status == 'InActive' ? 'selected' : '') : ''; ?>>Deactive</option> -->
								<option value="Active" <?php echo isset($status) ? ($status == 'Active' ? 'selected' : '') : 'selected'; ?>>Active</option>
							</select>&nbsp;
							<!-- Removed as required field 6/28/19 -->
							<!-- <font face="verdana" color="#990000" size="1">*required</font><br /> -->
						</td>
					</tr>
					<tr>
						<td class="regular">Customer Name:</td>
						<td>
							<input maxlength="80" size="40" name="custname" value="<?php echo isset($custname) ? $custname : ''; ?>" />&nbsp;
							<!-- Removed as required field 6/24/2019-->
							<font face="verdana" color="#990000" size="1"></font><br />
						</td>
					</tr>
					<tr>
						<td class="regular">Order Number:</td>
						<td><input maxlength="40" name="ordernum" value="<?php echo isset($ordernum) ? $ordernum : ''; ?>" /><br /></td>
					</tr>
					<tr>
						<td class="regular">Service Number:</td>
						<td>
							<input maxlength="40" name="servicenum" value="<?php echo isset($servicenum) ? $servicenum : ''; ?>" />&nbsp;
							<font face="verdana" color="#990000" size="1">*required</font><br />
						</td>
					</tr>
					<!-- <tr>
						<td class="regular">Redirection:</td>
						<td class="regular">
							<input type="radio" value="Yes" name="nonedsl" <?php //echo isset($nonedsl) ? ($nonedsl == 'Y' ? 'checked' : '') : ''; ?> />Yes
							<input type="radio" value="No" name="nonedsl" <?php //echo isset($nonedsl) ? ($nonedsl == 'Y' ? '' : 'checked') : 'checked' ?> />No
						</td>
					</tr>
					<tr>
						<td class="regular">Service Code:</td>
						<td>
							<select name="svccode">
								<?php //include 'select_options_allservices.php'; ?>
							</select>&nbsp;
							<font face="verdana" color="#990000" size="1">*required</font><br />
						</td>
					</tr> -->
					<tr>
						<td class="regular">IP Address:</td>
						<td>
							<input maxlength="20" value="<?php echo isset($ipaddress) ? $ipaddress : ''; ?>" name="ipaddress" style="width:200px;" readonly />&nbsp;
							<input class="button" name="ipaddbutton" type="button" value="Assign" />
							<input class="button" name="unassignip" type="button" value="Unassign" />
						</td>
					</tr>
					<tr>
						<td class="regular">Network Address:</td>
						<td>
							<input maxlength="20" value="<?php echo isset($netaddress) ? $netaddress : ''; ?>" name="netaddress" style="width:200px;" readonly />&nbsp;
							<input class="button" name="netaddbutton" type="button" value="Assign" />
							<input class="button" name="unassignnet" type="button" value="Unassign" />
							<span id="netwarning" style="color:#990000;"></span>
						</td>
					</tr>
					<?php
					if ($useIPv6) {
					?>
					<tr>
						<td class="regular">IPv6 Address:</td>
						<td>
							<input maxlength="50" value="<?php echo isset($ipv6address) ? $ipv6address : ''; ?>" name="ipv6address" style="width:200px;" readonly />&nbsp;
							<input class="button" name="ipv6addbutton" type="button" value="Assign" />
							<input class="button" name="unassignipv6" type="button" value="Unassign" />
						</td>
					</tr>
					<?php
					} else {
					?>
					<input type="hidden" name="ipv6address" value="" />
					<?php
					}
					?>
					<tr>
						<td class="regular">Addtional Service 1:</td>
						<td>
							<select name="svc_add1" disabled>
								<option value=""></option>
								<option value="GQDIAL" selected>GQDIAL</option>
								<!--
								<option value="GQDIAL" <?php //echo isset($svc_add1) && $svc_add1 == 'GQDIAL' ? 'selected' : ''; ?>>GQDIAL</option>
								<option value="GQWIFI" <?php //echo isset($svc_add1) && $svc_add1 == 'GQWIFI' ? 'selected' : ''; ?>>GQWIFI</option>
								-->
							</select>
							<input type="hidden" name="svc_add1" value="GQDIAL" />
						</td>
					</tr>
					<tr>
						<td class="regular">Addtional Service 2:</td>
						<td>
							<select name="svc_add2" disabled>
								<option value=""></option>
                                <option value="GQDIAL" selected>GQWIFI</option>
								<!--
								<option value="GQDIAL" <?php //echo isset($svc_add2) && $svc_add2 == 'GQDIAL' ? 'selected' : ''; ?>>GQDIAL</option>
								<option value="GQWIFI" <?php //echo isset($svc_add2) && $svc_add2 == 'GQWIFI' ? 'selected' : ''; ?>>GQWIFI</option>
								-->
							</select>
							<input type="hidden" name="svc_add2" value="GQWIFI" />
						</td>
					</tr>
					<tr>
						<td class="regular" valign="top">Remarks:</td>
						<td class="regular">
							<textarea class="regular" name="remarks" rows="5" wrap="physical" cols="50"><?php echo isset($remarks) ? $remarks : ''; ?></textarea>
						</td>
					</tr>
					<tr>
						<td align="middle" colspan="2"><input class="button" type="submit" value="Create" /></td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
	<script type="text/javascript">
		var radiuspolicy = '',
			proceed = '<?php echo $proceed ? 1 : 0; ?>';
		$(document).ready(function () {
			$('#start').focus();
			/*
			$('select[name="status"]').on('change', function (event) {
				var _this = $(this),
					dslradios = $('input[name="nonedsl"]');
				if (_this.val() == 'InActive') {
					$(dslradios[0]).prop('disabled', 'disabled');
					$(dslradios[1]).prop('checked', 'checked');
				} else {
					//$(dslradios[0]).prop('checked', 'checked');
					$(dslradios[0]).prop('disabled', '');
				}
			});
			*/
			$('select[name="svccode"]').one('focus', function (event) {
				radiuspolicy = $(this).val();
			});
			$('select[name="svccode"]').on('change', function (event) {
				if (radiuspolicy != '') {
					//var changed = confirm('Changing the Service Code will Unassign IP and Network addresses.\n\nContinue?');
					var changed = true;
					if (changed) {
						$('input[name="unassignip"], input[name="unassignnet"]').trigger('click');
					} else {
						$(this).val(radiuspolicy);
					}
				}
				$(this).blur();
				$(this).one('focus', function (event) {
					radiuspolicy = $(this).val();
				});
			});
			$('input[name="ipv6addbutton"]').on('click', function (event) {
				var username = $('input[name="username"]').val().trim(),
					realm = $('select[name="realm"]').val(),
					service = $('select[name="svccode"]').val(),
					location = '-',
					cabinet = '-',
					href = '<?php echo base_url("main/showAssignIPv6Form"); ?>',
					windowSpecs = 'height=300, width=550, resizable=no, scrollbars=no, status=no, toolbar=no, titlebar=no';
				username = username == '' ? '-' : username;
				realm = realm == '' ? '-' : realm;
				href = href + '/' + username + '/' + realm + '/' + location + '/' + cabinet + '/-/-/-';
				window.open(href, 'assignIPv6Address', windowSpecs);
				return true;
			});
			$('input[name="unassignipv6"]').on('click', function (event) {
				$('input[name="ipv6address"]').val('');
			});
			$('input[name="ipaddbutton"]').on('click', function (event) {
				var username = $('input[name="username"]').val().trim(),
					realm = $('select[name="realm"]').val(),
					// Remove SVC dependency 7/1/19
					// service = $('select[name="svccode"]').val(),
					gpon = 'N',
					location = '-',
					cabinet = '-',
					href = '<?php echo base_url("main/showAssignIPForm"); ?>',
					windowSpecs = 'height=300, width=550, resizable=no, scrollbars=no, status=no, toolbar=no, titlebar=no';
				// if (service.toLowerCase().indexOf('gpon') != -1) {
				// 	gpon = 'Y';
				// }
				username = username == '' ? '-' : username;
				realm = realm == '' ? '-' : realm;
				href = href + '/' + username + '/' + realm + '/' + location + '/' + gpon + '/' + cabinet + '/-/-';
				window.open(href, 'assignIPAddress', windowSpecs);
				return true;
			});
			$('input[name="unassignip"]').on('click', function (event) {
				$('input[name="ipaddress"]').val('');
				$('input[name="netaddress"]').val('');
			}); 
			$('input[name="netaddbutton"]').on('click', function (event) {
				if ($('input[name="ipaddress"]').val().trim() == '') {
					$('#netwarning').text('Please select an IP Address first');
				} else {
					$('#netwarning').text('');
					var username = $('input[name="username"]').val().trim(),
						realm = $('select[name="realm"]').val(),
						location = '-',
						cabinet = '-',
						ipAddress = '-',
						subnet = '-',
						findCabinet = '-',
						pickSubnet = '-',
						href = '<?php echo $useSeparateSubnetForNetAddress ? base_url("main/showAssignNetForm") : base_url("main/showAssignNetFormOld"); ?>',
						windowSpecs = 'height=300, width=550, resizable=no, scrollbars=no, status=no, toolbar=no, titlebar=no';
					username = username == '' ? '-' : username;
					realm = realm == '' ? '-' : realm;
					href = href + '/' + username + '/' + realm + '/' + location + '/' + cabinet + '/' + ipAddress + '/' + subnet + '/' + findCabinet + '/' + pickSubnet;
					window.open(href, 'assignNetAddress', windowSpecs);
				}
			});
			$('input[name="unassignnet"]').on('click', function (event) {
				$('input[name="netaddress"]').val('');
			});
			$('form[name="globeacct"]').on('submit', function (event) {
				if (parseInt(proceed) == 0) {
					alert('There are connection problems.\n\nPlease reload page to re-check connection.');
					return false;
				}
				if ($('input[name="username"]').val().trim() == '') {
					alert("Please fill in the username field");
					$('input[name="username"]').focus();
					return false;
				}
				if ($('input[name="password1"]').val().trim() == '') {
					alert("Please fill in the password field");
					$('input[name="password1"]').focus();
					return false;
				}
				if ($('input[name="password1"]').val().trim() != $('input[name="password2"]').val().trim()) {
					alert("Please fill in your password correctly");
					$('input[name="password2"]').focus();
					return false;
				}
				if ($('select[name="acctplan"]').val() == '0') {
					alert("Please pick account plan");
					$('select[name="acctplan"]').focus();
					return false;
				}
				// Removed required dependency 6/28/19
				// if ($('select[name="status"]').val() == '0') {
				// 	alert("Please pick account status");
				// 	$('select[name="status"]').focus();
				// 	return false;
				// }
				// if ($('input[name="custname"]').val().trim() == '') {
				// 	alert("Please fill in the customer name field");
				// 	$('input[name="custname"]').focus();
				// 	return false;
				// }
				if ($('input[name="servicenum"]').val().trim() == '') {
					alert("Please fill in the service number field");
					$('input[name="servicenum"]').focus();
					return false;
				}
				var dslradios = $('input[name="nonedsl"]');
				if ($(dslradios[0]).prop('checked')) {
					if ($('select[name="svccode"]').val() == '') {
						alert("Please fill in the service code field");
						$('select[name="svccode"]').focus();
						return false;
					}
				}
				if ($('input[name="ipaddress"]').val().trim() == '' && $('input[name="netaddress"]').val().trim() != '') {
					alert("You cannot have an account that has a net address but no IP address.");
					$('input[name="ipaddress"]').focus();
					return false;
				}
				return true;
			});
		});
	</script>
</body>
</html>