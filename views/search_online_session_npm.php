<!DOCTYPE html>
<html lang="en">
<head>
	<title>Globelines Universal Access</title>
	<?php require "head_includes.php"; ?>
</head>
<body style="padding-right:20px; padding-bottom:20px;">
	<div align="left">
		<h3 class="style1">Search Online Session in NPM</h3>
	</div>
	<div align="center">
		<span class="notificationMsg"><?php echo isset($message) && !is_null($message) ? $message : ''; ?></span>
	</div>
	<div align="center">
		<form name="frmMain" action="<?php echo base_url('main/showOnlineSessionDoNPM'); ?>" method="POST">
			<table cellspacing="1" cellpadding="3" border="0" class="white-bg">
				<tr>
					<td class="xsmallFontWHTBG" align="center">
						User ID:<br /><input class="textstyle" size="30" type="text" name="user" value="<?php echo isset($user) && !is_null($user) ? $user : ''; ?>" />
					</td>
					<td class="xsmallFontWHTBG" align="center">
						Realm:<br /><?php include "allowed_realms.php"; ?>
					</td>
					<td class="xsmallFontWHTBG" align="center">
						<br /><input name="submit" type="submit" value="search" class="button2" />
					</td>
				</tr>
			</table>
		</form>
		<?php
		if (isset($sessions) && $sessions['status']) {
			if (isset($sessions) && $sessions['data'] !== false) {
				$session = $sessions['data'][0]; //show only first session if more than 1
		?>
		<table cellspacing="1" cellpadding="3" border="0" style="background-color:#cccccc;">
			<tr>
				<td class="xsmallFontWHTBG" colspan="2" width="35%" nowrap><strong>Session Id</strong></td>
				<td class="xsmallFontWHTBG" width="65%" nowrap><?php echo !is_null($session['sessionId']) ? $session['sessionId'] : ''; ?></td>
			</tr>
			<tr>
				<td class="xsmallFontWHTBG" colspan="2" width="35%" nowrap><strong>Account Session Id</strong></td>
				<td class="xsmallFontWHTBG" width="65%" nowrap><?php echo !is_null($session['acctSessionId']) ? $session['acctSessionId'] : ''; ?></td>
			</tr>
			<tr>
				<td class="xsmallFontWHTBG" colspan="2" width="35%" nowrap><strong>Calling Station Id</strong></td>
				<td class="xsmallFontWHTBG" width="65%" nowrap><?php echo !is_null($session['callingStationId']) ? $session['callingStationId'] : ''; ?></td>
			</tr>
			<tr>
				<td class="xsmallFontWHTBG" colspan="2" width="35%" nowrap><strong>Circuit Type</strong></td>
				<td class="xsmallFontWHTBG" width="65%" nowrap><?php echo !is_null($session['circuitType']) ? $session['circuitType'] : ''; ?></td>
			</tr>
			<tr>
				<td class="xsmallFontWHTBG" colspan="2" width="35%" nowrap><strong>Context</strong></td>
				<td class="xsmallFontWHTBG" width="65%" nowrap><?php echo !is_null($session['context']) ? $session['context'] : ''; ?></td>
			</tr>
			<tr>
				<td class="xsmallFontWHTBG" colspan="2" width="35%" nowrap><strong>Session IP</strong></td>
				<td class="xsmallFontWHTBG" width="65%" nowrap><?php echo !is_null($session['sessionIp']) ? $session['sessionIp'] : ''; ?></td>
			</tr>
			<tr>
				<td class="xsmallFontWHTBG" colspan="2" width="35%" nowrap><strong>MAC Address</strong></td>
				<td class="xsmallFontWHTBG" width="65%" nowrap><?php echo !is_null($session['macAddress']) ? $session['macAddress'] : ''; ?></td>
			</tr>
			<tr>
				<td class="xsmallFontWHTBG" colspan="2" width="35%" nowrap><strong>Medium</strong></td>
				<td class="xsmallFontWHTBG" width="65%" nowrap><?php echo !is_null($session['medium']) ? $session['medium'] : ''; ?></td>
			</tr>
			<tr>
				<td class="xsmallFontWHTBG" colspan="2" width="35%" nowrap><strong>NAS Identifier</strong></td>
				<td class="xsmallFontWHTBG" width="65%" nowrap><?php echo !is_null($session['nasId']) ? $session['nasId'] : ''; ?></td>
			</tr>
			<tr>
				<td class="xsmallFontWHTBG" colspan="2" width="35%" nowrap><strong>NAS Port Id</strong></td>
				<td class="xsmallFontWHTBG" width="65%" nowrap><?php echo !is_null($session['NASPortId']) ? $session['NASPortId'] : ''; ?></td>
			</tr>
			<tr>
				<td class="xsmallFontWHTBG" colspan="2" width="35%" nowrap><strong>NAS Port Type</strong></td>
				<td class="xsmallFontWHTBG" width="65%" nowrap><?php echo !is_null($session['NASPortType']) ? $session['NASPortType'] : ''; ?></td>
			</tr>
			<tr>
				<td class="xsmallFontWHTBG" colspan="2" width="35%" nowrap><strong>NAS Type</strong></td>
				<td class="xsmallFontWHTBG" width="65%" nowrap><?php echo !is_null($session['nasType']) ? $session['nasType'] : ''; ?></td>
			</tr>
			<tr>
				<td class="xsmallFontWHTBG" colspan="2" width="35%" nowrap><strong>Start Time</strong></td>
				<td class="xsmallFontWHTBG" width="65%" nowrap><?php echo !is_null($session['startTime']) ? $session['startTime'] : ''; ?></td>
			</tr>
			<tr>
				<td class="xsmallFontWHTBG" colspan="2" width="35%" nowrap><strong>Subscriber Account</strong></td>
				<td class="xsmallFontWHTBG" width="65%" nowrap><?php echo !is_null($session['subscriberAccount']) ? $session['subscriberAccount'] : ''; ?></td>
			</tr>
		</table>
		<table border="0">
			<tr>
				<td align="right">
					<form action="<?php echo base_url('main/deleteOnlineSessionNPMProcess'); ?>" method="POST">
						<input type="hidden" name="sessionid" value="<?php echo !is_null($session['sessionId']) ? $session['sessionId'] : ''; ?>" />
						<input type="hidden" name="nasid" value="<?php echo !is_null($session['nasId']) ? $session['nasId'] : ''; ?>" />
						<input type="hidden" name="acctsessionid" value="<?php echo !is_null($session['acctSessionId']) ? $session['acctSessionId'] : ''; ?>" />
						<input type="hidden" name="subscriberaccount" value="<?php echo !is_null($session['subscriberAccount']) ? $session['subscriberAccount'] : ''; ?>" />
						<input type="hidden" name="sessionip" value="<?php echo !is_null($session['sessionIp']) ? $session['sessionIp'] : ''; ?>" />
						<input type="submit" name="submit" value="Delete Session" class="button" />
					</form>
				</td>
			</tr>
		</table>
		<?php
			} else {

			}
		?>
		<br />
		<table border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="30%" colspan="2" class="xsmallFontWHTBG" class="regularB">Record(s) Found:</td>
				<td width="65%">&nbsp;&nbsp;<?php echo $sessions['data'] == false ? '0' : count($sessions['data']); ?></td>
			</tr>
		</table>
		<?php
		}
		?>
	</div>
	<script type="text/javascript">
		$(document).ready(function () {
			$('input[name="user"]').focus();
            $('form[name="frmMain"]').on('submit', function (event) {
                var _this = $(this),
                    user = _this.find('input[name="user"]'),
                    realm = _this.find('select[name="realm"]');
                if (user.val().trim() == '') {
                    alert('Please fill in the User ID field');
                    user.focus();
                    return false;
                } else if (user.val().trim().indexOf(' ') != -1) {
                    alert('User ID must not contain spaces');
                    user.focus();
                    return false;
                }
                if (realm.val() == '') {
                    alert('Please select a realm');
                    realm.focus();
                    return false;
                }
                return true;
            });
        });
	</script>
</body>
</html>