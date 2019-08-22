<!DOCTYPE html>
<html lang="en">
<head>
	<title>Globelines Universal Access</title>
	<?php require 'head_includes.php'; ?>
</head>
<body style="padding-right:20px; padding-bottom:20px;">
	<div>
		<h3 class="style1">Display Account</h3>
	</div>
	<div class="smallFontB">
		<form name="load" action="<?php echo base_url('subscribers/showSubscriberInfo'); ?>" method="post">
			<table cellspacing="0" cellpadding="5" border="0">
				<tbody>
					<tr colspan="5">
						<td class="regular"><strong>Search:</strong></td>
					</tr>
					<tr>
						<td class="regular">Username:</td>
						<td>
							<input id="start" maxlength="30" name="username" value="<?php echo isset($username) && !is_null($username) ? $username : ''; ?>" autocomplete="off" /><br />
						</td>
						<td class="regular">Realm</td>
						<td class="smallFontWHTBG" align="left"><?php include 'allowed_realms.php'; ?></td>
						<td align="middle" colspan="2"><input class="button" type="submit" value="load record" /></td>
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
	<div>
		<table align="center" border="0" cellpadding="4" cellspacing="0" width="100%">
			<tbody>
				<tr>
					<td class="regular" align="center">
						<strong><span style="text-decoration:underline;"><?php echo $username.'@'.$realm; ?></span></strong> User Account
					</td>
				</tr>
			</tbody>
		</table>
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
										<strong>
											<?php echo !is_null($subscriber['CUSTOMERTYPE']) ? $subscriber['CUSTOMERTYPE'] : ''; ?>
										</strong>
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
										<?php
										echo !is_null($subscriber['RBACCOUNTPLAN']) ? str_replace('~', '-', $subscriber['RBACCOUNTPLAN']) : '';
										echo !is_null($subscriber['RBADDITIONALSERVICE1']) ? ', '.$subscriber['RBADDITIONALSERVICE1'] : '';
										echo !is_null($subscriber['RBADDITIONALSERVICE2']) ? ', '.$subscriber['RBADDITIONALSERVICE2'] : '';
										?>
									</td>
								</tr>
								<tr>
									<td class="myFontWHT">Redirected:</td>
									<td class="myFontWHT"><?php echo !is_null($subscriber['RBENABLED']) ? $subscriber['RBENABLED'] : ''; ?></td>
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
												<!--
												<%
													int passLen = 0;
													String passHide = "";
													for (String remark : Util.ObjectToString(request.getAttribute("remarks")).split(";")) {
														if (remark.toLowerCase().contains("reset password") || remark.toLowerCase().contains("change password")) {
															passLen = remark.substring(remark.indexOf("word:") + 6, remark.length()).length();
															passHide = "";
															for (int i = 0; i < passLen; i++) {
																passHide = passHide.concat("*");
															}
															out.print(remark.substring(0, remark.length() - passLen) + passHide + "<br />");
														} else if (remark.toLowerCase().contains("changed password to")) {
															passLen = remark.substring(remark.indexOf("password to") + 12, remark.indexOf(" on ")).length();
															passHide = "";
															for (int i = 0; i < passLen; i++) {
																passHide = passHide.concat("*");
															}
															out.print(remark.substring(0, remark.indexOf("password to") + 12) + passHide + remark.substring(remark.indexOf(" on "), remark.length()) + "<br />");
														} else {
															out.print(remark + "<br />");
														}
													}
												%>
												-->
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
	</div>
	<?php
		}
	}
	?>
	<script type="text/javascript">
		$(document).ready(function () {
			$('#start').focus();
			$('form[name="load"]').on('submit', function (event) {
				var username = $('input[name="username"]');
				if (username.val().trim() == '') {
					alert("Please fill in the Username field");
					username.focus();
					return false;
				}
				return true;
			});
		});
	</script>
</body>
</html>