<!DOCTYPE html>
<html lang="en">
<head>
	<title>Globelines Universal Access</title>
	<?php require 'head_includes.php'; ?>
</head>
<body style="padding-right:20px; padding-bottom:20px;">
	<div align="left">
		<h3 class="style1">Display Quota</h3>
	</div>
	<div align="center">
		<span class="notificationMsg"><?php echo isset($message) && !is_null($message) ? $message : ''; ?></span>
	</div>
	<div align="center">
		<form name="frmMain" action="<?php echo base_url('main/displayQuotaDo'); ?>" method="POST">
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
		if (isset($found)) {
			if($found === false) {
		?>
		<!--NOT FOUND-->
		<table cellspacing="1" cellpadding="2" width="100%" align="center">
			<tr>
				<td>
					<span class="notificationMsg"><?php echo isset($message) && !is_null($message) ? $message : ''; ?></span>
					<span class="errorMsg"><?php echo isset($error) && !is_null($error) ? $error : ''; ?></span>
				</td>
			</tr>
		</table>
		<?php
			} else {
		?>
		<!--FOUND-->
		<!--USERNAME-->
		<table align="center" border="0" cellpadding="4" cellspacing="0" width="100%">
			<tbody>
				<tr>
					<td class="regular" align="center">
						<strong><span style="text-decoration:underline;"><?php echo $user.'@'.$realm; ?></span></strong> User Account
					</td>
				</tr>
			</tbody>
		</table>
		<!--USAGE-->
		<!-- Remove Table 5/21/19 -->
		<!-- <?php /*
				if (isset($usage) && !is_null($usage['responseCode']) && intval($usage['responseCode']) == 200) {
		?>

		<?php
			$revPlan = (isset($usage['PLAN']) ? str_replace('~', '-', $usage['PLAN']) : '');
		?>

		<table style="background-color:#cccccc;" border="0" cellpadding="3" cellspacing="1" width="50%">
			<tr>
				<td colspan="2" class="smallFontGRYBG"><strong>USAGE INFORMATION</strong></td>
			</tr>
			<?php if(!in_array($revPlan,$excludedplans)): ?>
				<tr>
					<td class="myFontWHT">Usage Limit</td>
					<td class="myFontWHT">
						<?php
						if (isset($usage['VOLUMEQUOTA'])) {
							if (is_null($usage['VOLUMEQUOTA']) || $usage['VOLUMEQUOTA'] == '') {
								$usage['VOLUMEQUOTA'] = 0;
							}
							$quota = floatval($usage['VOLUMEQUOTA']);
							$fquota = number_format($quota, 0, '.', ',');
							$qparts = explode(',', $fquota);
							$qstr = '';
							if (count($qparts) >= 4) { //GB
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
				<td class="myFontWHT">Usage (%)</td>
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
				<td class="myFontWHT" style="<?php echo ($bgcolor == '') ? '' : 'background-color:'.$bgcolor.'; color:'.$textcolor.';'; ?>">
					<?php
					echo isset($usage) ? (isset($ustr) ? $ustr : '') : '';
					?>
				</td>
			</tr>
			<tr>
				<td class="myFontWHT">Status</td>
				<td class="myFontWHT" style="<?php echo ($bgcolor == '') ? '' : 'background-color:'.$bgcolor.'; color:'.$textcolor.';'; ?>">
					<?php
					echo isset($usage) ? $status : '';
					?>
				</td>
			</tr>
			<?php
			if ((isset($usage['vodQuota']) && !is_null($usage['vodQuota'])) && (isset($usage['vodUsage']) && !is_null($usage['vodUsage']))) {
			?>
			<tr>
				<td class="myFontWHT">VOD Expiry</td>
				<td class="myFontWHT"><?php echo $usage['vodExpiry']; ?></td>
			</tr>

			<?php if(!in_array($revPlan,$excludedplans)): ?>
				<tr>
					<td class="myFontWHT">VOD Limit</td>
					<td class="myFontWHT">
						<?php
						if (isset($usage['vodQuota']) && !is_null($usage['vodQuota'])) {
							$vquota = floatval($usage['vodQuota']);
							$vfquota = number_format($vquota, 0, '.', ',');
							$vqparts = explode(',', $vfquota);
							$vqstr = '';
							if (count($vqparts) >= 4) { //GB
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
				<td class="myFontWHT">VOD Usage (%)</td>
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
					if (count($vuparts) >= 4) {
						$crop = substr($vfused, 0, strrpos($vfused, ','));
						$gb = $vused / (1024 * 1024 * 1024);
						$gb = round($gb, 2);
						$vustr = $crop.' KB ('.$gb.' GB) ';
						if(!in_array($revPlan,$excludedplans))
							$vustr.= $vpct.'%';
					} else if (count($vuparts) == 3) {
						$crop = substr($vfused, 0, strrpos($vfused, ','));
						$mb = $vused / (1024 * 1024);
						$mb = round($mb, 2);
						$vustr = $crop.' KB ('.$mb.' MB) ';
						if(!in_array($revPlan,$excludedplans))
							$vustr.= $vpct.'%';
					} else if (count($vuparts) == 2) {
						$kb = $vused / 1024;
						$kb = round($kb, 2);
						$vustr = $vfused.' B ('.$kb.' KB) ';
						if(!in_array($revPlan,$excludedplans))
							$vustr.= $vpct.'%';
					} else if (count($vuparts) == 1) {
						$vustr = $vfused.' B';
						if(!in_array($revPlan,$excludedplans))
							$vustr.= ', '.$vpct.'%';
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
				<td class="myFontWHT" style="<?php echo ($bgcolor == '') ? '' : 'background-color:'.$bgcolor.'; color:'.$textcolor.';'; ?>">
					<?php
					echo (isset($usage['vodUsage']) && !is_null($usage['vodUsage'])) ? $vustr : '';
					?>
				</td>
			</tr>
			<tr>
				<td class="myFontWHT">Status</td>
				<td class="myFontWHT" style="<?php echo ($bgcolor == '') ? '' : 'background-color:'.$bgcolor.'; color:'.$textcolor.';'; ?>">
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
		*/?> -->
		<!--SUBSCRIBER INFO-->
		<table border="0" cellpadding="5" cellspacing="0" width="100%">
			<tbody>
				<tr>
					<td valign="top" width="50%">
						<table style="background-color:#cccccc;" border="0" cellpadding="3" cellspacing="1" width="100%">
							<tbody>
								<tr>
									<td colspan="2" class="smallFontGRYBG"><strong>USER INFORMATION</strong></td>
								</tr>
								<tr>
									<td class="myFontWHT" width="30%">Customer Type:</td>
									<td class="myFontWHT" width="70%">
										<!-- <strong>
											<?php //echo !is_null($subscriber['CUSTOMERTYPE']) ? $subscriber['CUSTOMERTYPE'] : ''; ?>
										</strong> -->
									</td>
								</tr>
								<tr>
									<td class="myFontWHT" width="30%">Realm:</td>
									<td class="myFontWHT" width="70%"><?php echo !is_null($subscriber['RBREALM']) ? $subscriber['RBREALM'] : ''; ?></td>
								</tr>
								<tr>
									<td class="myFontWHT">Username:</td>
									<td class="myFontWHT"><?php echo !is_null($subscriber['USERNAME']) ? $subscriber['USERNAME'] : ''; ?></td>
								</tr>
								<!--
								<tr>
									<td class="myFontWHT">Customer Type:</td>
									<td class="myFontWHT">${accountplan}</td>
								</tr>
								-->
								<tr>
									<td class="myFontWHT">Status:</td>
									<td class="myFontWHT"><?php echo !is_null($subscriber['CUSTOMERSTATUS']) ? ($subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D') : ''; ?></td>
								</tr>
								<tr>
									<td class="myFontWHT">Active Services:</td>
									<td class="myFontWHT">
										<?php /*
										echo !is_null($subscriber['RBACCOUNTPLAN']) ? str_replace('~', '-', $subscriber['RBACCOUNTPLAN']) : '';
										echo !is_null($subscriber['RBADDITIONALSERVICE1']) ? ', '.$subscriber['RBADDITIONALSERVICE1'] : '';
										echo !is_null($subscriber['RBADDITIONALSERVICE2']) ? ', '.$subscriber['RBADDITIONALSERVICE2'] : '';
										*/?>
									</td>
								</tr>
								<tr>
									<td class="myFontWHT">Inactive Services:</td>
									<td class="myFontWHT"></td>
								</tr>
								<tr>
									<td class="myFontWHT">Order Number:</td>
									<td class="myFontWHT"><?php echo !is_null($subscriber['RBORDERNUMBER']) ? $subscriber['RBORDERNUMBER'] : ''; ?></td>
								</tr>
								<tr>
									<td class="myFontWHT">Service Number:</td>
									<td class="myFontWHT"><?php echo !is_null($subscriber['RBSERVICENUMBER']) ? $subscriber['RBSERVICENUMBER'] : ''; ?></td>
								</tr>
								<tr>
									<td class="myFontWHT">Customer Name:</td>
									<td class="myFontWHT"><?php echo !is_null($subscriber['RBCUSTOMERNAME']) ? $subscriber['RBCUSTOMERNAME'] : ''; ?></td>
								</tr>
								<tr>
									<td class="myFontWHT" nowrap="nowrap">Install Address:</td>
									<td class="myFontWHT"></td>
								</tr>
								<tr>
									<td class="myFontWHT">Service DSLAM:</td>
									<td class="myFontWHT"></td>
								</tr>
								<tr>
									<td class="myFontWHT">Service PVC:</td>
									<td class="myFontWHT"></td>
								</tr>
								<tr>
									<td class="myFontWHT">Unlimited Access:</td>
									<td class="myFontWHT"><?php echo !is_null($subscriber['RBUNLIMITEDACCESS']) ? $subscriber['RBUNLIMITEDACCESS'] : ''; ?></td>
								</tr>
								<tr>
									<td class="myFontWHT">Email Address:</td>
									<td class="myFontWHT"></td>
								</tr>
								<tr>
									<td class="myFontWHT">Mother's Maiden:</td>
									<td class="myFontWHT"></td>
								</tr>
								<tr>
									<td class="myFontWHT">Mother's Birthdate:</td>
									<td class="myFontWHT"></td>
								</tr>
								<tr>
									<td class="myFontWHT">Default Reply:</td>
									<td class="myFontWHT"><?php echo !is_null($subscriber['CUSTOMERREPLYITEM']) ? $subscriber['CUSTOMERREPLYITEM'] : ''; ?></td>
								</tr>
								<tr>
									<td class="myFontWHT">Hint Question:</td>
									<td class="myFontWHT"></td>
								</tr>
								<tr>
									<td class="myFontWHT">Hint Answer:</td>
									<td class="myFontWHT"></td>
								</tr>
							</tbody>
						</table>
						<br />
						<table style="background-color:#cccccc;" border="0" cellpadding="3" cellspacing="1" width="100%">
							<tbody>
								<tr>
									<td colspan="2" class="smallFontGRYBG"><strong>ACCOUNT INFORMATION</strong></td>
								</tr>
								<tr>
									<td class="myFontWHT">Time Slot:</td>
									<td class="myFontWHT"><?php echo !is_null($subscriber['RBTIMESLOT']) ? $subscriber['RBTIMESLOT'] : ''; ?></td>
								</tr>
								<tr>
									<td class="myFontWHT" nowrap="nowrap" width="30%">Number of Session:</td>
									<td class="myFontWHT" width="70%"><?php echo !is_null($subscriber['RBNUMBEROFSESSION']) ? $subscriber['RBNUMBEROFSESSION'] : ''; ?></td>
								</tr>
								<tr>
									<td class="myFontWHT">Service SSG:</td>
									<td class="myFontWHT"></td>
								</tr>
								<tr>
									<td class="myFontWHT">Caller ID:</td>
									<td class="myFontWHT"></td>
								</tr>
								<tr>
									<td class="myFontWHT">Trusted Caller ID:</td>
									<td class="myFontWHT"></td>
								</tr>
								<tr>
									<td class="myFontWHT">Email Addresses:</td>
									<td class="myFontWHT"></td>
								</tr>
							</tbody>
						</table>
					</td>
					<td valign="top" width="50%">
						<table style="background-color:#cccccc;" border="0" cellpadding="3" cellspacing="1" width="100%">
							<tbody>
								<tr>
									<td colspan="2" class="smallFontGRYBG"><strong>OTHER INFORMATION</strong></td>
								</tr>
								<tr>
									<td class="myFontWHT" width="40%">Created By:</td>
									<td class="myFontWHT" width="70%"><?php echo !is_null($subscriber['RBCREATEDBY']) ? $subscriber['RBCREATEDBY'] : ''; ?></td>
								</tr>
								<tr>
									<td class="myFontWHT">Date Created:</td>
									<td class="myFontWHT">
										<?php
										if (!is_null($subscriber['CREATEDATE'])) {
											$parts = explode(' ', $subscriber['CREATEDATE']);
											$parts[1] = str_replace('.', ':', $parts[1]);
											echo $parts[0].' '.substr($parts[1], 0, strrpos($parts[1], ':')).' '.$parts[2];
										} else {
											echo '';
										}
										?>
									</td>
								</tr>
								<tr>
									<td class="myFontWHT" nowrap="nowrap">Previous Status:</td>
									<td class="myFontWHT"><?php echo !is_null($subscriber['RBCHANGESTATUSFROM']) ? ($subscriber['RBCHANGESTATUSFROM'] == 'Active' ? 'A' : 'D') : ''; ?></td>
								</tr>
								<tr>
									<td class="myFontWHT">Date Status Changed:</td>
									<td class="myFontWHT">
										<?php
										if (!is_null($subscriber['RBCHANGESTATUSDATE'])) {
											$parts = explode(' ', $subscriber['RBCHANGESTATUSDATE']);
											$parts[1] = str_replace('.', ':', $parts[1]);
											echo $parts[0].' '.substr($parts[1], 0, strrpos($parts[1], ':')).' '.$parts[2];
										} else {
											echo '';
										}
										?>
									</td>
								</tr>
								<tr>
									<td class="myFontWHT">Status Changed By:</td>
									<td class="myFontWHT"><?php echo !is_null($subscriber['RBCHANGESTATUSBY']) ? $subscriber['RBCHANGESTATUSBY'] : ''; ?></td>
								</tr>
							</tbody>
						</table>
						<br />
						<table style="background-color:#cccccc;" border="0" cellpadding="3" cellspacing="1" width="100%">
							<tbody>
								<tr>
									<td colspan="3" class="smallFontGRYBG"><strong>USAGE ALERT</strong></td>
								</tr>
								<tr>
									<td class="myFontWHT">&nbsp;</td>
									<td class="myFontWHT" align="center"><strong>Enabled</strong></td>
									<td class="myFontWHT" align="center"><strong>Time Alert (Hrs)</strong></td>
								</tr>
								<tr>
									<td class="myFontWHT">Daily</td>
									<td class="myFontWHT" align="center"></td>
									<td class="myFontWHT" align="center"></td>
								</tr>
								<tr>
									<td class="myFontWHT">Weekly</td>
									<td class="myFontWHT" align="center"></td>
									<td class="myFontWHT" align="center"></td>
								</tr>
								<tr>
									<td class="myFontWHT">Monthly</td>
									<td class="myFontWHT" align="center"></td>
									<td class="myFontWHT" align="center"></td>
								</tr>
							</tbody>
						</table>
						<br />
						<table style="background-color:#cccccc;" border="0" cellpadding="3" cellspacing="1" width="100%">
							<tbody>
								<tr>
									<td colspan="3" class="smallFontGRYBG"><strong>HISTORY</strong></td>
								</tr>
								<tr>
									<td class="myFontWHT" width="30%">Account Information History:</td>
									<td class="myFontWHT" colspan="2">
										<div style="height:50px; background-color:#ffffff; padding: 4px; overflow:auto;">
											<span class="xsmallfont">
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
									<td class="myFontWHT">Account Session History:</td>
									<td class="myFontWHT" colspan="2">
										<!--<%= Util.ObjectToString(request.getAttribute("sessionhistory")).replace(";", "<br>")%>-->
										<div style="height:50px; background-color:#ffffff; padding: 4px; overflow:auto;">
											<span class="xsmallfont"><!--<%= Util.ObjectToString(request.getAttribute("sessionhistory")).replace(";", "<br>")%>--></span>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
						<br /><br />
					</td>
				</tr>
				<tr>
					<td colspan="2" align="center"><br /></td>
				</tr>
			</tbody>
		</table>
		<?php
			}
		}
		?>
		<br />
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
