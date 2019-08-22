<!DOCTYPE html>
<html lang="en">
<head>
	<title>Globelines Universal Access</title>
	<?php require 'head_includes.php'; ?>
</head>
<body>
	<table cellspacing="1" cellpadding="0" width="100%" align="center" style="background-color:#639ACE;" border="0">
		<tbody>
			<tr>
				<td class="regularB" align="middle" height="25"><font color="#ffffff">Assign IPv6 Address</font></td>
			</tr>
			<tr>
				<form name="assignip">
					<table cellspacing="1" cellpadding="4" width="100%" align="center" style="background-color:#ffffff;" border="0">
						<tbody>
							<tr>
								<td height="25" colspan="4"></td>
							</tr>
							<tr>
								<td class="regular" align="right">Username:</td>
								<td class="regular" colspan="3">
									<?php
									if ((isset($username) && !is_null($username) && $username != '') && (isset($realm) && !is_null($realm))){
										echo $username.'@'.$realm;
									} else {
										echo '';
									}
									?>
									<span id="username" style="display:none;"><?php echo isset($username) && !is_null($username) ? $username : ''; ?></span>
									<span id="realm" style="display:none;"><?php echo isset($realm) && !is_null($realm) ? $realm : ''; ?></span>
								</td>
							</tr>
							<tr>
								<td class="regular" align="right">Search IPv6:</td>
								<td class="regular" colspan="3">
									<?php
									$ipValue = (isset($ipv6) && !is_null($ipv6) && $ipv6 != '-') ? $ipv6.'/'.$subnet : '';
									?>
									<input type="text" name="findip" id="findip" value="<?php echo $ipValue; ?>" style="margin-right:10px; width:150px;" />
									<input type="button" class="button" value="Search" id="findipbtn" />
									<span id="findipmsg" style="margin-left:10px;"></span>
								</td>
							</tr>
							<tr>
								<td class="regular" align="right">Search Cabinet:</td>
								<td class="regular" colspan="3">
									<?php
									$cabinetName = (isset($cabinet) && !is_null($cabinet) && $cabinet != '-') ? $cabinet : '';
									?>
									<input type="text" name="findcabinet" id="findcabinet" value="<?php echo $cabinetName; ?>" style="margin-right:10px; width:150px;" />
									<input type="button" class="button" value="Search" id="findcabinetbtn" />
									<span id="findcabinetmsg" style="margin-left:10px;"></span>
								</td>
							</tr>
							<tr>
								<td height="20" colspan="4"></td>
							</tr>
							<tr>
								<td class="regular" align="right" style="width:24%;">Location:</td>
								<td style="width:26%;">
									<select name="iplocation">
										<option value=""></option>
										<?php
										if (isset($locations) && !is_null($locations)) {
											$locationCount = count($locations);
											for ($i = 0; $i < $locationCount; $i++) {
												echo '<option value="'.$locations[$i].'" '.($locations[$i] == $location ? 'selected' : '').'>'.
													$locations[$i].
												'</option>';
											}
										}
										?>
									</select>
								</td>
								<td class="regular" align="right" style="width:24%;">Cabinet Name:</td>
								<td style="width:26%;">
									<select name="cabinets">
										<option value="" location=""></option>
										<?php
										if (isset($cabinets) && !is_null($cabinets)) {
											$cabinetCount = count($cabinets);
											for ($i = 0; $i < $cabinetCount; $i++) {
												$selected = false;
												if (isset($cabinetForDropdown) && !is_null($cabinetForDropdown) && $cabinetForDropdown != '-') {
													// $selected = intval($cabinets[$i]['id']) == intval($cabinetId);
													$selected = strtolower($cabinets[$i]['name']) == strtolower($cabinetForDropdown);
												}
												echo '<option value="'.$cabinets[$i]['id'].'" location="'.$cabinets[$i]['homing_bng'].'"'.($selected ? ' selected' : '').'>'.
													$cabinets[$i]['name'].
												'</option>';
											}
										}
										?>
									</select>
								</td>
							</tr>
							<tr>
								<td class="regular" align="right">IPv6 Address:</td>
								<td>
									<select id="ipid" name="popipaddress">
										<?php
										if (isset($ipv6addresses) && !is_null($ipv6addresses)) {
											if ($location != '') {
												$ipCount = count($ipv6addresses);
												for ($i = 0; $i < $ipCount; $i++) {
													$selected = false;
													if (isset($ip) && !is_null($ip) && $ip != '-') {
														$selected = $ipv6addresses[$i]['IPV6ADDR'] == $ip;
													}
													echo '<option value="'.$ipv6addresses[$i]['IPV6ADDR'].'"'.($selected ? ' selected' : '').'>'.
														$ipv6addresses[$i]['IPV6ADDR'].
													'</option>';
												} 
											}
										}                                    
										?>
									</select>
								</td>
								<td colspan="2"></td>
							</tr>
							<tr>
								<td align="middle" colspan="2"><br />
									<input class="button" name="selectip" type="button" value="Select" />&nbsp;
									<input class="button" name="closewindow" type="button" value="Cancel" />
								</td>
								<td colspan="2"></td>
							</tr>
						</tbody>
					</table>
				</form>
			</tr>
		</tbody>
	</table>
	<script type="text/javascript">
		var ipLocationDropdown = $('select[name="iplocation"]'),
			ipAddressDropdown = $('select[name="popipaddress"]'),
			usernameContainer = $('#username'),
			realmContainer = $('#realm'),
			selectIpButton = $('input[name="selectip"]'),
			closeWindowButton = $('input[name="closewindow"]'),
			cabinetDropdown = $('select[name="cabinets"]'),
			findIpField = $('#findip'),
			findIpButton = $('#findipbtn'),
			findIpMsg = $('#findipmsg'),
			findCabinetField = $('#findcabinet'),
			findCabinetButton = $('#findcabinetbtn'),
			findCabinetMsg = $('#findcabinetmsg');
		$(document).ready(function () {
			ipLocationDropdown.on('change', function (event) {
				var _this = $(this),
					username = usernameContainer.text().trim(),
					realm = realmContainer.text().trim(),
					location = _this.val(),
					cabinet = '',
					url = '<?php echo base_url("main/showAssignIPv6Form"); ?>';
				username = username == '' ? '-' : username;
				realm = realm == '' ? '-' : realm;
				location = location == '' ? '-' : location;
				cabinet = location == '-' ? '-' : cabinetDropdown.find('option[location="' + location + '"]').first().text();
				url = url + '/' + username + '/' + realm + '/' + location.replace(/ /g, '~') + '/' + cabinet.replace(/ /g, '~') + '/-/-/-';
				top.location.href = url;
			});
			selectIpButton.on('click', function (event) {
				var ip = ipAddressDropdown.val();
				if (ip.trim() != '') {
					window.opener.document.globeacct.ipv6address.value = ip;
					window.opener.document.getElementById('netwarning').innerHTML = '';
				}
				window.close();
			});
			closeWindowButton.on('click', function (event) {
				window.close();
			});
			cabinetDropdown.on('change', function (event) {
				var _this = $(this),
					location = _this.find('option:selected').attr('location');
				ipLocationDropdown.val(location).trigger('change');
			});
			findIpField.on('keypress', function (event) {
				if (event.which == 13) {
					findIpButton.trigger('click');
				}
			});
			findIpButton.on('click', function (event) {
				var _this = $(this),
					ipToFind = findIpField.val().trim(),
					parts = null,
					ipParts = null,
					theIp = '',
					username = usernameContainer.text().trim(),
					realm = realmContainer.text().trim(),
					url = '<?php echo base_url("main/showAssignIPv6Form"); ?>';
				username = username == '' ? '-' : username;
				realm = realm == '' ? '-' : realm;
				if (ipToFind == '') {
					findIpMsg.empty().append('Enter an ipv6 address to find.');
					findIpField.focus();
					return false;
				}
				if (ipToFind.indexOf('/') == -1) {
					findIpMsg.empty().append('Incorrect ipv6 format.');
					findIpField.focus();
					return false;
				} else {
					parts = ipToFind.split('/');
					if (parts.length != 2) {
						findIpMsg.empty().append('Incorrect ipv6 format (no subnet).');
						findIpField.focus();
						return false;
					} else {
						ipParts = parts[0].split(':');
						if (ipParts.length != 8) {
							findIpMsg.empty().append('Incorrect ipv6 format.');
							findIpField.focus();
							return false;
						}
						theIp = parts[0].replace(/:/g, '~') + '/' + parts[1];
					}
				}
				_this.prop('disabled', 'disabled');
				findIpField.prop('disabled', 'disabled');
				findCabinetButton.prop('disabled', 'disabled');
				findCabinetField.prop('disabled', 'disabled');
				selectIpButton.prop('disabled', 'disabled');
				findIpMsg.empty().append('Finding ip address...');
				$.ajax({
					url: '<?php echo base_url("main/findIpv6AddressToAssign"); ?>',
					type: 'post', 
					data: {
						'ipv6': theIp
					},
					success: function (resp) {
						var response = JSON.parse(resp);
						if (parseInt(response.found) == 0) {
							_this.prop('disabled', '');
							findIpField.prop('disabled', '');
							findCabinetButton.prop('disabled', '');
							findCabinetField.prop('disabled', '');
							selectIpButton.prop('disabled', '');
							findIpMsg.empty().append(ipToFind + ' not found.');
							findCabinetField.val('');
							findCabinetMsg.empty();
							ipLocationDropdown.val('');
							cabinetDropdown.val('');
							ipAddressDropdown.empty();
						} else {
							if (response.used == 'Y') {
								_this.prop('disabled', '');
								findIpField.prop('disabled', '');
								findCabinetButton.prop('disabled', '');
								findCabinetField.prop('disabled', '');
								selectIpButton.prop('disabled', '');
								findIpMsg.empty().append(ipToFind + ' is already used.');
								findCabinetField.val('');
								findCabinetMsg.empty();
								ipLocationDropdown.val('');
								cabinetDropdown.val('');
								ipAddressDropdown.empty();
							} else {
								url = url + '/' + username + '/' + realm + '/' + 
									response.location.replace(/ /g, '~') + '/' + response.cabinetForDropdown + '/' + ipToFind + '/-/-';
								top.location.href = url;
							}
						}
					},
					error: function (resp) {
						_this.prop('disabled', '');
						findIpField.prop('disabled', '');
						findCabinetButton.prop('disabled', '');
						findCabinetField.prop('disabled', '');
						selectIpButton.prop('disabled', '');
						findIpMsg.empty().append('An error occurred. Please try again.');
					}
				});
			});
			findCabinetField.on('keypress', function (event) {
				if (event.which == 13) {
					findCabinetButton.trigger('click');
				}
			});
			findCabinetButton.on('click', function (event) {
				var _this = $(this),
					cabinetToFind = findCabinetField.val().trim(),
					username = usernameContainer.text().trim(),
					realm = realmContainer.text().trim(),
					url = '<?php echo base_url("main/showAssignIPv6Form"); ?>';
				username = username == '' ? '-' : username;
				realm = realm == '' ? '-' : realm;
				if (cabinetToFind == '') {
					findCabinetMsg.empty().append('Enter a cabinet to find.');
					findCabinetField.focus();
					return false;
				}
				_this.prop('disabled', 'disabled');
				findCabinetField.prop('disabled', 'disabled');
				findIpButton.prop('disabled', 'disabled');
				findIpField.prop('disabled', 'disabled');
				selectIpButton.prop('disabled', 'disabled');
				findCabinetMsg.empty().append('Finding cabinet...');
				$.ajax({
					url: '<?php echo base_url("main/findCabinetToAssign"); ?>',
					type: 'post',
					data: {
						'cabinet': cabinetToFind.replace(/ /g, '~')
					},
					success: function (resp) {
						var response = JSON.parse(resp);
						if (parseInt(response.found) == 0) {
							_this.prop('disabled', '');
							findCabinetField.prop('disabled', '');
							findIpButton.prop('disabled', '');
							findIpField.prop('disabled', '');
							selectIpButton.prop('disabled', '');
							findCabinetMsg.empty().append(cabinetToFind + ' not found.');
							findIpField.val('');
							findIpMsg.empty();
							ipLocationDropdown.val('');
							cabinetDropdown.val('');
							ipAddressDropdown.empty();
						} else {
							url = url + '/' + username + '/' + realm + '/' + response.location.replace(/ /g, '~') + '/' + 
								response.cabinetForDropdown.replace(/ /g, '~') + '/-/-/' + cabinetToFind.replace(/ /g, '~');
							top.location.href = url;
						}
					},
					error: function (resp) {
						_this.prop('disabled', '');
						findCabinetField.prop('disabled', '');
						findIpButton.prop('disabled', '');
						findIpField.prop('disabled', '');
						selectIpButton.prop('disabled', '');
						findCabinetMsg.empty().append('An error occurred. Please try again.');
					}
				});
			});
		});
	</script>
</body>
</html>