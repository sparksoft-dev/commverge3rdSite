<!DOCTYPE html>
<html lang="en">
<head>
	<title>Globelines Universal Access</title>
	<?php require 'head_includes.php'; ?>
</head>
<body style="padding-right:20px; padding-bottom:20px;">
	<div align="left">
		<h3 class="style1">Search Online Session</h3>
	</div>
	<div align="center">
		<span class="notificationMsg"><?php echo isset($message) && !is_null($message) ? $message : ''; ?></span>
	</div>
	<div align="center">
		<form name="frmMain" action="<?php echo base_url('main/showOnlineSessionDo2'); ?>" method="POST">
			<table cellspacing="1" cellpadding="3" border="0" class="white-bg">
				<tr>
					<td class="xsmallFontWHTBG" align="center">
						User ID:<br /><input class="textstyle" size="30" type="text" name="user" value="<?php echo isset($user) && !is_null($user) ? $user : ''; ?>" />
					</td>
					<td class="xsmallFontWHTBG" align="center">
						Realm:<br /><?php include 'allowed_realms.php'; ?>
					</td>
					<td class="xsmallFontWHTBG" align="center">
						<br /><input name="submit" type="submit" value="search" class="button2" />
					</td>
				</tr>
			</table>
		</form>
		<?php
		if (isset($sessions) && $sessions['status'] !== false) {
			if (count($sessions['data']) >= 1) {
				for ($k = 0; $k < count($sessions['data']); $k++) {
					$session = $sessions['data'][$k];
		?>
		<!-- session data table -->
		<table cellspacing="1" cellpadding="3" border="0" style="background-color:#cccccc;">
			<tr>
				<td class="xsmallFontWHTBG" colspan="2" width="35%" nowrap><strong>Username</strong></td>
				<td class="xsmallFontWHTBG" width="65%" nowrap><?php echo isset($sessions) && $sessions['status'] !== false ? $session['USER_NAME'] : ''; ?></td>
			</tr>
			<tr>
				<td class="xsmallFontWHTBG" colspan="2" width="35%" nowrap><strong>Unique Session ID</strong></td>
				<td class="xsmallFontWHTBG" width="65%" nowrap><?php echo isset($sessions) && $sessions['status'] !== false ? $session['ACCT_SESSION_ID'] : ''; ?></td>
			</tr>
			<tr>
				<td class="xsmallFontWHTBG" colspan="2" width="35%" nowrap><strong>Subscriber IPV4</strong></td>
				<td class="xsmallFontWHTBG" width="65%" nowrap><?php echo isset($sessions) && $sessions['status'] !== false ? $session['FRAMED_IP_ADDRESS'] : ''; ?></td>
			</tr>

			<tr>
				<td class="xsmallFontWHTBG" colspan="2" width="35%" nowrap><strong>NAS Name</strong></td>
				<td class="xsmallFontWHTBG" width="65%" nowrap><?php echo isset($sessions) && $sessions['status'] !== false ? $session['NAS_IDENTIFIER'] : ''; ?></td>
			</tr>

			<tr>
				<td class="xsmallFontWHTBG" colspan="2" width="35%" nowrap><strong>NAS IP</strong></td>
				<td class="xsmallFontWHTBG" width="65%" nowrap><?php echo isset($sessions) && $sessions['status'] !== false ? $session['NAS_IP_ADDRESS'] : ''; ?></td>
			</tr>
			<tr>
				<td class="xsmallFontWHTBG" colspan="2" width="35%" nowrap><strong>NAS Port ID</strong></td>
				<td class="xsmallFontWHTBG" width="65%" nowrap><?php echo isset($sessions) && $sessions['status'] !== false ? $session['NAS_PORT_ID'] : ''; ?></td>
			</tr>
			<tr>
				<td class="xsmallFontWHTBG" colspan="2" width="35%" nowrap><strong>Session State</strong></td>
				<td class="xsmallFontWHTBG" width="65%" nowrap><?php echo isset($sessions) && $sessions['status'] !== false ? $session['SESSION_STATUS'] : ''; ?></td>
			</tr>
			<tr>
				<td class="xsmallFontWHTBG" colspan="2" width="35%" nowrap><strong>User Concurrency ID</strong></td>
				<td class="xsmallFontWHTBG" width="65%" nowrap><?php echo isset($sessions) && $sessions['status'] !== false ? $session['CONCUSERID'] : ''; ?></td>
			</tr>
			<tr>
				<td class="xsmallFontWHTBG" colspan="2" width="35%" nowrap><strong>Calling Station ID</strong></td>
				<td class="xsmallFontWHTBG" width="65%" nowrap><?php echo isset($sessions) && $sessions['status'] !== false ? $session['CALLING_STATION_ID'] : ''; ?></td>
			</tr>
			<tr>
				<td class="xsmallFontWHTBG" colspan="2" width="35%" nowrap><strong>Start Time</strong></td>
				<td class="xsmallFontWHTBG" width="65%" nowrap>
					<?php
					if (isset($sessions) && $sessions['status'] !== false) {
						$startTime = $session['START_TIME'];
						echo $startTime;
						// $parts = explode(' ', $startTime);
						// $dateParts = explode('-', $parts[0]);
						// $timeParts = explode('.', $parts[1]);
						// echo $dateParts[0].' '.$dateParts[1].' '.'20'.$dateParts[2].' '.$timeParts[0].':'.$timeParts[1].':'.$timeParts[2].' '.$parts[2];
					} else {
						echo '';
					}
					?>
				</td>
			</tr>
		</table>
		<br />
		<?php
				}
		?>
		<!-- usage & vod data table -->
		<?php
				if (isset($usage) && !is_null($usage['responseCode']) && intval($usage['responseCode']) == 200) {
		?>
		<?php
			$revPlan = (isset($usage['PLAN']) ? str_replace('~', '-', $usage['PLAN']) : '');
		?>

		<table cellspacing="1" cellpadding="3" border="0" style="background-color:#ffffff;">
			<tr>
				<td style="width:100px;">Service Plan</td>
				<td style="border:1px solid black; width:225px;"><?php echo isset($usage['PLAN']) ? str_replace('~', '-', $usage['PLAN']) : ''; ?></td>
			</tr>

			<?php if(!in_array($revPlan,$excludedplans)): ?>
			<tr>
				<td>Usage Limit</td>
				<td style="border:1px solid black;">
					<?php
					if (isset($usage['VOLUMEQUOTA'])) {
						if (is_null($usage['VOLUMEQUOTA']) || $usage['VOLUMEQUOTA'] == '') {
							$usage['VOLUMEQUOTA'] = 0;
						}
						$quota = floatval($usage['VOLUMEQUOTA']);
						$fquota = number_format($quota, 0, '.', ',');
						$qparts = explode(',', $fquota);
						$qstr = '';
						if (count($qparts) >= 4) { //GB, TB
							$crop = substr($fquota, 0, strrpos($fquota, ','));
							$gb = $quota / (1024 * 1024 * 1024);
							$gb = round($gb, 2);
							$qstr = $crop.' KB ('.$gb.' GB)';
						} else if (count($qparts) == 3) { //MB
							$crop = substr($fquota, 0, strrpos($fquota, ','));
							$mb = $quota / (1024 * 1024);
							$mb = round($mb, 2);
							$qstr = $crop.' KB ('.$mb.' MB)';
						} else if (count($qparts) == 2) { //KB
							$kb = $quota / 1024;
							$kb = round($kb, 2);
							$qstr = $fquota.' B ('.$kb.' KB)';
						} else if (count($qparts) == 1) { //B
							$qstr = $fquota.' B';
						}
					} else {
						$quota = 0;
						$qstr = '0 B';
					}
					echo $qstr;
					?>
				</td>
			</tr>
			<?php
				else:
					$quota = 0;
				endif;
			?>
			<tr>
				<td>Usage (%)</td>
				<?php
				$bgcolor = '';
				$textcolor = 'black';
				$status = 'Normal';
				$pct = 0;
				if (isset($usage['VOLUMEUSAGE'])) {
					$used = floatval($usage['VOLUMEUSAGE']);
					$fused = number_format($used, 0, '.', ',');
					$uparts = explode(',', $fused);
					$ustr = '';
					$pct = floatval($quota) == 0 ? 0 : ($used / $quota) * 100;
					$pct = round($pct, 2);
					if (count($uparts) >= 4) {
						$crop = substr($fused, 0, strrpos($fused, ','));
						$gb = $used / (1024 * 1024 * 1024);
						$gb = round($gb, 2);
						$ustr = $crop.' KB ('.$gb.' GB) ';
						if(!in_array($revPlan,$excludedplans))
							$ustr.=$pct.'%';
					} else if (count($uparts) == 3) {
						$crop = substr($fused, 0, strrpos($fused, ','));
						$mb = $used / (1024 * 1024);
						$mb = round($mb, 2);
						$ustr = $crop.' KB ('.$mb.' MB) ';
						if(!in_array($revPlan,$excludedplans))
							$ustr.=$pct.'%';
					} else if (count($uparts) == 2) {
						$kb = $used / 1024;
						$kb = round($kb, 2);
						$ustr = $fused.' B ('.$kb.' KB) ';
						if(!in_array($revPlan,$excludedplans))
							$ustr.=$pct.'%';
					} else if (count($uparts) == 1) {
						$ustr = $fused.' B';
						if(!in_array($revPlan,$excludedplans))
							$ustr.=', '.$pct.'%';
					}
					if ($pct >= 90 && $pct < 100) {
						$bgcolor = 'yellow';
						$textcolor = 'black';
						$status = 'Critical';
					} else if ($pct >= 100) {
						$bgcolor = 'red';
						$textcolor = 'white';
						$status = 'Exceeded';
					}
				}
				?>
				<td style="border:1px solid black; <?php echo ($bgcolor == '') ? '' : 'background-color:'.$bgcolor.'; color:'.$textcolor.';'; ?>">
					<?php
						echo isset($usage) ? (isset($ustr) ? $ustr : '') : '';
					?>
				</td>
			</tr>
			<tr>
				<td>Status</td>
				<td style="border:1px solid black; <?php echo ($bgcolor == '') ? '' : 'background-color:'.$bgcolor.'; color:'.$textcolor.';'; ?>">
					<?php echo isset($usage) ? $status : ''; ?>
				</td>
			</tr>
			<tr style="height:15px;">
				<td></td>
				<td>
					<?php
					/*
					if ($eventDate === false) {
						echo '';
					} else {
						$theDate = $eventDate['eventDate'];
						$parts = explode(' ', $theDate);
						$timeParts = explode('.', $parts[1]);
						if ($eventDate['event'] == 'up') {
							echo 'Boosted on '.$parts[0].' '.$timeParts[0].':'.$timeParts[1].':'.$timeParts[2].' '.$parts[2].' ';
						} else if ($eventDate['event'] == 'down') {
							echo 'Throttled Down on '.$parts[0].' '.$timeParts[0].':'.$timeParts[1].':'.$timeParts[2].' '.$parts[2].' ';
						}
					}
					*/
					?>
				</td>
			</tr>
			<?php
			if ((isset($usage['vodQuota']) && !is_null($usage['vodQuota'])) && (isset($usage['vodUsage']) && !is_null($usage['vodUsage']))) {
			?>
			<tr>
				<td>VOD Expiry</td>
				<td style="border:1px solid black;"><?php echo $usage['vodExpiry']; ?></td>
			</tr>
			<?php if(!in_array($revPlan,$excludedplans)): ?>
				<tr>
					<td>VOD Limit</td>
					<td style="border:1px solid black;">
						<?php
						if (isset($usage['vodQuota']) && !is_null($usage['vodQuota'])) {
							$vquota = floatval($usage['vodQuota']);
							$vfquota = number_format($vquota, 0, '.', ',');
							$vqparts = explode(',', $vfquota);
							$vqstr = '';
							if (count($vqparts) == 4) { //GB
								$crop = substr($vfquota, 0, strrpos($vfquota, ','));
								$gb = $vquota / (1024 * 1024 * 1024);
								$gb = round($gb, 2);
								$vqstr = $crop.' KB ('.$gb.' GB)';
							} else if (count($vqparts) == 3) { //MB
								$crop = substr($vfquota, 0, strrpos($vfquota, ','));
								$mb = $vquota / (1024 * 1024);
								$mb = round($mb, 2);
								$vqstr = $crop.' KB ('.$mb.' MB)';
							} else if (count($vqparts) == 2) { //KB
								$kb = $vquota / 1024;
								$kb = round($kb, 2);
								$vqstr = $vfquota.' B ('.$kb.' KB)';
							} else if (count($vqparts) == 1) { //B
								$vqstr = $vfquota.' B';
							}
							echo $vqstr;
						}
						?>
					</td>
				</tr>
			<?php
				else:
					$vquota = 0;
				endif;
			?>
			<tr>
				<td>VOD Usage (%)</td>
				<?php
				$bgcolor = '';
				$textcolor = 'black';
				$status = 'Normal';
				$pct = 0;
				if (isset($usage['vodUsage']) && !is_null($usage['vodUsage'])) {
					$vused = floatval($usage['vodUsage']);
					$vfused = number_format($vused, 0, '.', ',');
					$vuparts = explode(',', $vfused);
					$vustr = '';
					$vpct = floatval($vquota) == 0 ? 0 : ($vused / $vquota) * 100;
					$vpct = round($vpct, 2);
					if (count($vuparts) == 4) {
						$crop = substr($vfused, 0, strrpos($vfused, ','));
						$gb = $vused / (1024 * 1024 * 1024);
						$gb = round($gb, 2);
						$vustr = $crop.' KB ('.$gb.' GB) ';
						if(!in_array($revPlan,$excludedplans))
							$vustr.=$vpct.'%';
					} else if (count($vuparts) == 3) {
						$crop = substr($vfused, 0, strrpos($vfused, ','));
						$mb = $vused / (1024 * 1024);
						$mb = round($mb, 2);
						$vustr = $crop.' KB ('.$mb.' MB) ';
						if(!in_array($revPlan,$excludedplans))
							$vustr.=$vpct.'%';
					} else if (count($vuparts) == 2) {
						$kb = $vused / 1024;
						$kb = round($kb, 2);
						$vustr = $vfused.' B ('.$kb.' KB) ';
						if(!in_array($revPlan,$excludedplans))
							$vustr.=$vpct.'%';
					} else if (count($vuparts) == 1) {
						$vustr = $vfused.' B';
						if(!in_array($revPlan,$excludedplans))
							$vustr.=', '.$vpct.'%';
					}
					if ($vpct >= 90 && $vpct < 100) {
						$bgcolor = 'yellow';
						$textcolor = 'black';
						$status = 'Critical';
					} else if ($vpct >= 100) {
						$bgcolor = 'red';
						$textcolor = 'white';
						$status = 'Exceeded';
					}
				}
				?>
				<td style="border:1px solid black; <?php echo ($bgcolor == '') ? '' : 'background-color:'.$bgcolor.'; color:'.$textcolor.';'; ?>">
					<?php
					echo (isset($usage['vodUsage']) && !is_null($usage['vodUsage'])) ? $vustr : '';
					?>
				</td>
			</tr>
			<tr>
				<td>Status</td>
				<td style="border:1px solid black; <?php echo ($bgcolor == '') ? '' : 'background-color:'.$bgcolor.'; color:'.$textcolor.';'; ?>">
					<?php
					echo (isset($usage['vodUsage']) && !is_null($usage['vodUsage'])) ? $status : '';
					?>
				</td>
			</tr>
			<?php
			}
			?>
		</table>
		<?php
				}
		?>
		<!-- nas location data table -->
		<table cellspacing="1" cellpadding="3" border="0" style="background-color:#fff;">
			<tr>
				<td style="width:125px;">BNG Area Connected</td>
				<td style="border:1px solid black; width:325px;">
					<?php echo (isset($bngInfo['correct_bng']) && array_key_exists('nas_description', $bngInfo['correct_bng'] ) ) ? $bngInfo['correct_bng']['nas_description'] : ''; ?>
				</td>
			</tr>
			<tr>
				<td>BNG Area Registered</td>
				<td style="border:1px solid black;">
					<?php echo isset($bngInfo['registered_bng']['nas_description']) ? $bngInfo['registered_bng']['nas_description'] : ''; ?>
				</td>
			</tr>
		<?php
				if (!isset($bngInfo['registered_bng']) || (isset($bngInfo['registered_bng']) && empty($bngInfo['registered_bng']))) {
		?>
			<tr>
				<td>Status</td>
				<td style="border:1px solid black; color:white; background-color:red;" align="middle">
					Warning: This account is in default location <br />and needs to be migrated.
				</td>
			</tr>
			<tr>
				<td></td>
				<td style="border:1px solid black;" align="middle">
					<form id="changeBngAreaRegForm" action="<?php echo base_url('main/changeBngAreaRegistration'); ?>" method="POST">
						<input type="hidden" name="user" value="<?php echo isset($sessions) && $sessions['status'] !== false ? $session['CONCUSERID'] : ''; ?>" />
						<input type="hidden" name="nasname" value="<?php echo isset($sessions) && $sessions['status'] !== false ? $session['NAS_IDENTIFIER'] : ''; ?>" />
						<input type="hidden" name="sessionid" value="<?php echo isset($sessions) && $sessions['status'] !== false ? $session['ACCT_SESSION_ID'] : ''; ?>" />
						<input type="hidden" name="username" value="<?php echo isset($sessions) && $sessions['status'] !== false ? $session['USER_NAME'] : ''; ?>" />
						<input type="hidden" name="ipv4address" value="<?php echo isset($sessions) && $sessions['status'] !== false ? $session['FRAMED_IP_ADDRESS'] : ''; ?>" />
						<input type="hidden" name="nasipaddress" value="<?php echo isset($sessions) && $sessions['status'] !== false ? $session['NAS_IP_ADDRESS'] : ''; ?>" />
						<input type="hidden" name="correctrmlocation" value="<?php echo (isset($bngInfo) && array_key_exists('rm_location',$bngInfo['correct_bng'] ) ) ? $bngInfo['correct_bng']['rm_location'] : ''; ?>" />
						<input type="hidden" name="oldsubsidentity" value="<?php echo isset($oldSubsIdentity) ? $oldSubsIdentity : ''; ?>" />
						<input type="submit" name="submit" value="Click Here to Change BNG Area Registration" class="button" style="width:315px;" />
					</form>
				</td>
			</tr>
		<?php
				} else {
					if ($bngInfo['correct_bng']['rm_location'] != $bngInfo['registered_bng']['rm_location']) {
		?>
			<tr>
				<td>Status</td>
				<td style="border:1px solid black; color:white; background-color:red;" align="middle">
					Warning: This account is connected in a BNG area<br />outside its registered BNG area.
				</td>
			</tr>
			<tr>
				<td></td>
				<td style="border:1px solid black;" align="middle">
					<form id="changeBngAreaRegForm" action="<?php echo base_url('main/changeBngAreaRegistration'); ?>" method="POST">
						<input type="hidden" name="user" value="<?php echo isset($sessions) && $sessions['status'] !== false ? $session['CONCUSERID'] : ''; ?>" />
						<input type="hidden" name="nasname" value="<?php echo isset($sessions) && $sessions['status'] !== false ? $session['NAS_IDENTIFIER'] : ''; ?>" />
						<input type="hidden" name="sessionid" value="<?php echo isset($sessions) && $sessions['status'] !== false ? $session['ACCT_SESSION_ID'] : ''; ?>" />
						<input type="hidden" name="username" value="<?php echo isset($sessions) && $sessions['status'] !== false ? $session['USER_NAME'] : ''; ?>" />
						<input type="hidden" name="ipv4address" value="<?php echo isset($sessions) && $sessions['status'] !== false ? $session['FRAMED_IP_ADDRESS'] : ''; ?>" />
						<input type="hidden" name="nasipaddress" value="<?php echo isset($sessions) && $sessions['status'] !== false ? $session['NAS_IP_ADDRESS'] : ''; ?>" />
						<input type="hidden" name="correctrmlocation" value="<?php echo isset($bngInfo) ? $bngInfo['correct_bng']['rm_location'] : ''; ?>" />
						<input type="hidden" name="oldsubsidentity" value="<?php echo isset($oldSubsIdentity) ? $oldSubsIdentity : ''; ?>" />
						<input type="submit" name="submit" value="Click Here to Change BNG Area Registration" class="button" style="width:315px;" />
					</form>
				</td>
			</tr>
		<?php
					}
				}
		?>
		</table>
		<br /><br />
		<table border="0">
			<tr>
				<td align="right">
					<form action="<?php echo base_url('main/deleteOnlineSessionProcess'); ?>" method="POST">
						<input type="hidden" name="user" value="<?php echo isset($sessions) && $sessions['status'] !== false ? $session['CONCUSERID'] : ''; ?>" />
						<input type="hidden" name="nasname" value="<?php echo isset($sessions) && $sessions['status'] !== false ? $session['NAS_IDENTIFIER'] : ''; ?>" />
						<input type="hidden" name="sessionid" value="<?php echo isset($sessions) && $sessions['status'] !== false ? $session['ACCT_SESSION_ID'] : ''; ?>" />
						<input type="hidden" name="username" value="<?php echo isset($sessions) && $sessions['status'] !== false ? $session['USER_NAME'] : ''; ?>" />
						<input type="hidden" name="ipv4address" value="<?php echo isset($sessions) && $sessions['status'] !== false ? $session['FRAMED_IP_ADDRESS'] : ''; ?>" />
						<input type="hidden" name="nasipaddress" value="<?php echo isset($sessions) && $sessions['status'] !== false ? $session['NAS_IP_ADDRESS'] : ''; ?>" />
						<input type="submit" name="submit" value="Delete Session" class="button" />
					</form>
				</td>
			</tr>
		</table>
		<?php
			} else {

			}
		}
		?>
		<?php
		if (isset($sessions)) {
		?>
		<br />
		<table border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="30%" colspan="2" class="xsmallFontWHTBG" class="regularB">Record(s) Found:</td>
				<td width="65%">&nbsp;&nbsp;<?php echo $sessions['status'] === false ? 0 : count($sessions['data']); ?></td>
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
			// nas location check
			$('#changeBngAreaRegForm').on('submit', function (event) {
				var proceed = confirm('Please confirm if you want to change BNG Area Registration to\nthe BNG Area of the connected modem.');
				return proceed;
			});
		});
	</script>
</body>
</html>
