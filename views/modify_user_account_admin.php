<!DOCTYPE html>
<html lang="en">
<head>
	<title>Globelines Universal Access</title>
	<?php require 'head_includes.php'; ?>
</head>
<body style="padding-right:20px; padding-bottom:20px;">
	<div align="left">
		<h3 class="style1">Modify Account (Admin)</h3>
	</div>
	<div class="smallFontB" align="right">
		<a href="<?php echo base_url('subscribers/processBulkUpdateSubscribersAdmin/upload'); ?>">Bulk Modify (Admin)</a>&nbsp;|&nbsp;
		<a href="<?php echo base_url('subscribers/processBulkUnassignIPv6IPAndNetAddress/upload'); ?>">
			Bulk Unassign<?php echo $useIPv6 ? " IPv6," : ""; ?> IP and Net Address
		</a>
	</div>
	<div class="smallFontB">
		<form name="load" action="<?php echo base_url('subscribers/loadUpdateSubscriberFormAdmin'); ?>" method="POST">
			<table cellspacing="0" cellpadding="5" border="0">
				<tbody>
					<tr colspan="5">
						<td class="regular"><strong>Search:</strong></td>
					</tr>
					<tr>
						<td class="regular">Username:</td>
						<td><input id="start" maxlength="70" name="username" value="<?php echo isset($username) && !is_null($username) ? $username : ''; ?>" autocomplete="off" /><br /></td>
						<td class="regular">Realm:</td>
						<td class="smallFontWHTBG" align="left"><?php include 'allowed_realms.php'; ?></td>
						<td align="middle" colspan="2"><input class="button" type="submit" name="loadbutton" value="load record" /></td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
	<hr />
	<?php
	if ($show) {
	?> 
	<div align="center">
		<table cellspacing="1" cellpadding="2" width="100%" align="center">
			<tr>
				<td>
					<span class="notificationMsg"><?php echo isset($message) && !is_null($message) ? $message : ''; ?></span>
					<span class="errorMsg"><?php echo isset($error) && !is_null($error) ? $error : ''; ?></span>
				</td>
			</tr>
		</table>
	</div>
	<br />
	<?php
		if ($found) {
	?>
	<div class="smallFontB">
		<form name="globeacct" action="<?php echo base_url('subscribers/processUpdateSubscriberAdmin'); ?>" method="POST">
			<table cellspacing="0" cellpadding="5" width="100%" border="0">
				<tbody>
					<tr>
						<td class="regular">Realm:</td>
						<td class="smallFontWHTBG" align="left">
							<?php echo isset($realm) && !is_null($realm) ? $realm : ''; ?>
							<input type="hidden" name="realm" value="<?php echo isset($realm) && !is_null($realm) ? $realm : ''; ?>" />
						</td>
					</tr>
					<tr>
						<td class="regular">Username:</td>
						<td class="smallFontWHTBG" align="left">
							<?php echo isset($username) && !is_null($username) ? $username : ''; ?>
							<input type="hidden" name="username" value="<?php echo isset($username) && !is_null($username) ? $username : ''; ?>" />
						</td>
					</tr>
					<tr>
						<td class="regular">Password:</td>
						<td>
							<input maxlength="20" name="password1" type="password" value="<?php echo isset($subscriber['PASSWORD']) && !is_null($subscriber['PASSWORD']) ? $subscriber['PASSWORD'] : ''; ?>" />&nbsp;
							<font face="verdana" color="#990000" size="1">*required</font>&nbsp;
							<font class="smallFontGRAY">(maximum of 20 characters)</font><br />
						</td>
					</tr>
					<tr>
						<td class="regular">Retype Password:</td>
						<td>
							<input maxlength="20" name="password2" type="password" value="<?php echo isset($subscriber['PASSWORD']) && !is_null($subscriber['PASSWORD']) ? $subscriber['PASSWORD'] : ''; ?>" />&nbsp;
							<font face="verdana" color="#990000" size="1">*required</font><br />
						</td>
					</tr>
					<!-- Remove Attribute 5/17/19 -->
					<!-- <tr>
						<td class="regular">Customer Type:</td>
						<td>
							<select name="acctplan"> 
								<?php /*
								$forCustomerType = 'residential';
								if (isset($subscriber['CUSTOMERTYPE']) && !is_null($subscriber['CUSTOMERTYPE'])) {
									if (strtolower($subscriber['CUSTOMERTYPE']) != 'residential') {
										$forCustomerType = 'business';
									}
								}
								?>
								<option value="Residential" 
									<?php echo $forCustomerType == 'residential' ? 'selected' : ''; ?>>Residential</option>
								<option value="Business" 
									<?php echo $forCustomerType != 'residential' ? 'selected' : ''; */?>>Business</option>
							</select>&nbsp;
							<font face="verdana" color="#990000" size="1">*required</font><br />
						</td>
					</tr> -->
					<tr>
						<td class="regular">Status:</td>
						<td>
							<select name="status">
								<option value="Active" <?php echo isset($subscriber['CUSTOMERSTATUS']) && !is_null($subscriber['CUSTOMERSTATUS']) && $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'selected' : ''; ?>>Active</option>
								<option value="InActive" <?php echo isset($subscriber['CUSTOMERSTATUS']) && !is_null($subscriber['CUSTOMERSTATUS']) && $subscriber['CUSTOMERSTATUS'] == 'InActive' ? 'selected' : ''; ?>>Deactive</option>
								<option value="T" <?php echo isset($subscriber['CUSTOMERSTATUS']) && !is_null($subscriber['CUSTOMERSTATUS']) && $subscriber['CUSTOMERSTATUS'] == 'T' ? 'selected' : ''; ?>>Terminated</option>
							</select>&nbsp;							
							<!-- <font face="verdana" color="#990000" size="1">*required</font><br /> -->
						</td>
					</tr>
					<tr>
						<td class="regular">Customer Name:</td>
						<td>
							<input maxlength="80" size="40" name="custname" value="<?php echo isset($subscriber['RBCUSTOMERNAME']) && !is_null($subscriber['RBCUSTOMERNAME']) ? $subscriber['RBCUSTOMERNAME'] : ''; ?>" />&nbsp;
							<font face="verdana" color="#990000" size="1"></font><br />
						</td>
					</tr>
					<tr>
						<td class="regular">Order Number:</td>
						<td>
							<input maxlength="40" name="ordernum" value="<?php echo isset($subscriber['RBORDERNUMBER']) && !is_null($subscriber['RBORDERNUMBER']) ? $subscriber['RBORDERNUMBER'] : ''; ?>" /><br />
						</td>
					</tr>
					<tr>
						<td class="regular">Service Number:</td>
						<td>
							<input maxlength="40" name="servicenum" value="<?php echo isset($subscriber['RBSERVICENUMBER']) && !is_null($subscriber['RBSERVICENUMBER']) ? $subscriber['RBSERVICENUMBER'] : ''; ?>" />&nbsp;
							<font face="verdana" color="#990000" size="1">*required</font><br />
						</td>
					</tr>
					<!-- <tr>
						<td class="regular">Redirection:</td>
						<td class="regular">
							<?php /*
							$which = '';
							if (isset($subscriber['RBENABLED']) && !is_null($subscriber['RBENABLED'])) {
								$which = $subscriber['RBENABLED'];
							} else {
								$which = 'No'; //default
							}
							?>
							<input type="radio" value="Yes" name="nonedsl" <?php echo $which == 'Yes' ? 'checked' : ''; ?>>Yes
							<input type="radio" value="No" name="nonedsl" <?php echo $which == 'No' ? 'checked' : ''; ?>>No
						</td>
					</tr> 
					<tr>
						<td class="regular">Service Code:</td>
						<td>
							<select name="svccode">
								<?php include 'select_options_allservices.php' */?>
							</select>&nbsp;
							<font face="verdana" color="#990000" size="1">*required</font><br />
						</td>
					</tr> -->
					<tr>
						<td class="regular">IP Address:</td>
						<td>
							<input maxlength="20" name="ipaddress" 
								value="<?php echo isset($subscriber['RBIPADDRESS']) && !is_null($subscriber['RBIPADDRESS']) ? $subscriber['RBIPADDRESS'] : ''; ?>" 
								style="width:200px;" readonly />&nbsp;
							<input class="button" name="ipaddbutton" type="button" value="Assign" />
							<input class="button" name="unassignip" type="button" value="Unassign" />
						</td>
					</tr>
					<tr>
						<td class="regular">Network Address:</td>
						<td>
							<input maxlength="20" name="netaddress" 
								value="<?php echo isset($subscriber['RBMULTISTATIC']) && !is_null($subscriber['RBMULTISTATIC']) ? $subscriber['RBMULTISTATIC'] : ''; ?>" 
								style="width:200px;" readonly />&nbsp;
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
							<input maxlength="60" name="ipv6address"
								value="<?php echo isset($subscriber['RBADDITIONALSERVICE4']) && !is_null($subscriber['RBADDITIONALSERVICE4']) ? $subscriber['RBADDITIONALSERVICE4'] : ''; ?>"
								style="width:200px;" readonly />&nbsp;
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
					<!-- uncomment next block to enable dynamic pool (check also at controllers/subscribers.php) -->
					<!--
					<tr>
						<td class="regular">Dynamic Public IP:</td>
						<td>
							<?php
							/*
							$enableAssignDynamicIPBtn = true;
							$enableUnassignDynamicIPBtn = false;
							$findInCustomerReplyItem = '4874:1=OUTSIDE,0:88=Dynamic-Public_Pool_001';
							if (isset($subscriber['RBIPADDRESS']) && !is_null($subscriber['RBIPADDRESS'])) {
								$enableAssignDynamicIPBtn = false;
							}
							if (isset($subscriber['RBADDITIONALSERVICE3']) && $subscriber['CUSTOMERREPLYITEM'] == $findInCustomerReplyItem) {
								$enableUnassignDynamicIPBtn = true;
							}
							*/
							?>
							<input maxlength="50" name="dynamicip" value="<?php // echo $enableUnassignDynamicIPBtn ? $findInCustomerReplyItem : ''; ?>" readonly />&nbsp;
							<input class="button" name="dynamicipbutton" type="button" value="Assign" <?php // echo $enableAssignDynamicIPBtn ? '' : 'disabled="disabled"'; ?> />
							<input class="button" name="unassigndynamicip" type="button" value="Unassign" <?php // echo $enableUnassignDynamicIPBtn ? '' : 'disabled="disabled"'; ?> />
							<span id="dynamicipwarning" style="color:#990000;"></span>
							<input type="hidden" name="nasIdentifier" value="" />
						</td>
					</tr>
					-->
					<tr>
						<td class="regular">Addtional Service 1:</td>
						<td>
							<select name="svc_add1">
								<option value=""></option>
                                <option value="GQDIAL" <?php echo isset($svc_add1) && $svc_add1 == 'GQDIAL' ? 'selected' : ''; ?>>GQDIAL</option>
                                <option value="GQWIFI" <?php echo isset($svc_add1) && $svc_add1 == 'GQWIFI' ? 'selected' : ''; ?>>GQWIFI</option>
							</select>
						</td>
					</tr>
					<tr>
						<td class="regular">Addtional Service 2:</td>
						<td>
							<select name="svc_add2">
								<option value=""></option>
                                <option value="GQDIAL" <?php echo isset($svc_add2) && $svc_add2 == 'GQDIAL' ? 'selected' : ''; ?>>GQDIAL</option>
                                <option value="GQWIFI" <?php echo isset($svc_add2) && $svc_add2 == 'GQWIFI' ? 'selected' : ''; ?>>GQWIFI</option>
							</select>
						</td>
					</tr>
					<tr>
						<td class="regular" valign="top">Remarks:</td>
						<td class="regular">
							<textarea class="regular" name="remarks" value="" rows="2" wrap="physical" cols="50"><?php echo isset($new_remarks) && !is_null($new_remarks) ? $new_remarks : ''; ?></textarea>
						</td>
					</tr>
					<tr>
						<td class="regular" valign="top">&nbsp;</td>
						<td class="regular">
							<span class="xsmallFont">Account Information History</span>
							<br />
							<input type="hidden" name="current_remarks" value="<?php echo isset($current_remarks) && !is_null($current_remarks) ? $current_remarks : ''; ?>" />
							<div style="width:370px; height:100px; background-color:#ffffff; padding:4px; border:1px solid black; overflow:auto; color:#606060;">
								<span class="xsmallfont" style="font-weight:normal;">
									<input type="hidden" name="informationHistory" value="<?php echo isset($informationHistory) && !is_null($informationHistory) ? $informationHistory : ''; ?>" />
									<?php
									$lines = explode(';', $subscriber['RBREMARKS']);
									for ($i = 0; $i < count($lines); $i++) {
										echo $lines[$i].'<br />';
									}
									?>
								</span>
							</div>
						</td>
					</tr>
					<tr>
						<td class="regular" valign="top">&nbsp;</td>
						<td class="regular">
							<span class="xsmallFont">Account Session History</span>
							<br />
							<input type="hidden" name="sessionHistory" value="<?php echo isset($sessionHistory) && !is_null($sessionHistory) ? $sessionHistory : ''; ?>" />
							<div style="width:370px; height:100px; background-color:#ffffff; padding:4px; border:1px solid black; overflow:auto; color:#606060;">
								<span class="xsmallfont"></span>
							</div>
						</td>
					</tr>
					<tr>
						<td align="middle" colspan="2">
							<input class="button" type="button" name="resetbutton" value="reset field values" />&nbsp;
							<input class="button" type="submit" name="modifybutton" value="modify" />
						</td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
	<?php
		}
	}
	?>
	<script type="text/javascript">
		var radiuspolicy = '',
			proceed = '<?php echo $proceed ? 1 : 0; ?>';
		$(document).ready(function () {
			$('#start').focus();
			$('form[name="load"]').on('submit', function (event) {
				if (parseInt(proceed) == 0) {
					alert('There are connection problems.\n\nPlease reload page to re-check connection.');
					return false;
				}
				var username = $('input[name="username"]'),
					realm = $('select[name="realm"]');
				if (username.val().trim() == '') {
					alert("Please fill in the Username field");
					username.focus();
					return false;
				}
				if (realm.val() == '') {
					alert("Please select a realm");
					realm.focus();
					return false;
				}
				return true;
			});

			<?php
			if ($found) {
			?>
			
			$('select[name="status"]').on('change', function (event) {
                var _this = $(this),
                    dslradios = $('input[name="nonedsl"]');
                if (_this.val() == 'InActive' || _this.val() == 'T') {
                    $(dslradios[0]).prop('disabled', 'disabled');
                    $(dslradios[1]).prop('checked', 'checked');
                } else {
                    //$(dslradios[0]).prop('checked', 'checked');
                    $(dslradios[0]).prop('disabled', '');
                }
            });
			
            $('select[name="svccode"]').one('focus', function (event) {
                radiuspolicy = $(this).val();
            });
            /*
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
			*/
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
				if ($('select[name="status"]').val() == '0') {
					alert("Please pick account status");
					$('select[name="status"]').focus();
					return false;
				}
				// Remove Dependency 6/25/2019
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

				if ($('select[name="status"]').val() == 'T') {
					var proceed2 = confirm('Warning: Subscriber with Terminated Status will be automatically deleted in the database.\n\nAre you sure?');
					if (proceed2) {
						return true;
					} else {
						return false;
					}
				}
			});
			$('input[name="resetbutton"]').on('click', function (event) {
				$('input[name="loadbutton"]').trigger('click');
			});
				<?php
				$enableAssignDynamicIPBtn = false;
				if ($enableAssignDynamicIPBtn) {
				?>
			$('input[name="dynamicipbutton"]').on('click', function (event) {
				var username = $('input[name="username"]'),
					realm = $('input[name="realm"]'),
					dynamicipInput = $('input[name="dynamicip"]'),
					msg = $('#dynamicipwarning'),
					assignBtn = $('input[name="dynamicipbutton"]'),
					unassignBtn = $('input[name="unassigndynamicip"]'),
					assignBtnInitState = assignBtn.prop('disabled'),
					unassignBtnInitState = unassignBtn.prop('disabled'),
					modifyBtn = $('input[name="modifybutton"]'),
					forDynamicIPTextBox = '<?php echo $findInCustomerReplyItem; ?>';
				msg.text('Assigning dynamic public IP');
				var count = 0,
					assignDynamicIPInterval = setInterval(function () {
						if (count == 0) {
							msg.text('Assigning dynamic public IP.');
							count++;
						} else if (count == 1) {
							msg.text('Assigning dynamic public IP..');
							count++;
						} else if (count == 2) {
							msg.text('Assigning dynamic public IP...');
							count++;
						} else if (count == 3) {
							msg.text('Assigning dynamic public IP');
							count = 0;
						}
					}, 500);
				assignBtn.prop('disabled', 'disabled');
				if (!unassignBtnInitState) {
					unassignBtn.prop('disabled', 'disabled');
				}
				modifyBtn.prop('disabled','disabled');
				$.ajax({
					url: '<?php echo base_url("subscribers/ajaxAssignDynamicIP"); ?>',
					type: 'POST',
					data: {
						'username': username.val(),
						'realm': realm.val()
					},
					success: function (resp) {
						clearInterval(assignDynamicIPInterval);
						var response = $.parseJSON(resp);
						if (parseInt(response.session) == 0) {
							msg.text('Account does not have a session. Unable to assign dynamic public IP.');
							assignBtn.prop('disabled', '');
							if (!unassignBtnInitState) {
								unassignBtn.prop('disabled', '');
							}
							modifyBtn.prop('disabled', '');
						} else {
							if (parseInt(response.invalidIP) == 1) {
								msg.text('Invalid session. Unable to assign dynamic public IP.');
								assignBtn.prop('disabled', '');
								if (!unassignBtnInitState) {
									unassignBtn.prop('disabled', '');
								}
								modifyBtn.prop('disabled', '');	
							} else {
								if (parseInt(response.unavailableIP) == 1) {
									msg.text('No dynamic IP available for ' + response.nasIdentifier + '. Unable to assign dynamic public IP.');
									assignBtn.prop('disabled', '');
									if (!unassignBtnInitState) {
										unassignBtn.prop('disabled', '');
									}
									modifyBtn.prop('disabled', '');
								} else {
									msg.text('');
									dynamicipInput.val(forDynamicIPTextBox);
									$('input[name="nasIdentifier"]').val(response.nasIdentifier);
									// assignBtn.prop('disabled', '');
									unassignBtn.prop('disabled', '');
									modifyBtn.prop('disabled', '');
								}
							}
						}
					},
					error: function (resp) {
						clearInterval(assignDynamicIPInterval);
						alert('An error has occurred. Unable to assign dynamic public IP.');
						assignBtn.prop('disabled', '');
						if (!unassignBtnInitState) {
							unassignBtn.prop('disabled', '');
						}
						modifyBtn.prop('disabled', '');
						msg.text('');
					}
				});
			});
				<?php
				}
				?>
			$('input[name="unassigndynamicip"]').on('click', function (event) {
				$('input[name="dynamicip"]').val('');
				$('input[name="dynamicipbutton"]').prop('disabled', '');
				$(this).prop('disabled', 'disabled');
			});
			<?php
			}
			?>
		});

	</script>
</body>
</html>
