<!DOCTYPE html>
<html lang="en">
<head>
	<title>Globelines Universal Access</title>
	<?php require 'head_includes.php'; ?>
</head>
<body style="padding-right:20px; padding-bottom:20px;">
	<div align="left">
		<h3 class="style1">Delete Account</h3>
	</div>
	<div class="smallFontB" align="right">
		<a href="<?php echo base_url('subscribers/processBulkDeleteSubscribers/upload'); ?>">Bulk Delete</a>&nbsp;
		<!-- HideLinks 5/16/19 -->
		<!-- <a href="<?php //echo base_url('subscribers/showUnsubscribeVodForm'); ?>">Unsubscribe VOD</a> -->
	</div>

	</div>
	<div class="smallFontB">
		<form name="load" action="<?php echo base_url('subscribers/loadDeleteSubscriberForm'); ?>" method="POST">
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
					<?php
					/*
					if (isset($subscriber) && !(is_null($subscriber['RBIPADDRESS']) && is_null($subscriber['RBMULTISTATIC']))) {
						$message = 'This subscriber cannot be deleted. An IP and/or Network Address is assigned to it.';
					}
					*/
					?>
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
		<form name="globeacct" action="<?php echo base_url('subscribers/processDeleteSubscriber'); ?>" method="POST">
			<table cellspacing="0" cellpadding="5" width="100%" border="0">
				<tbody>
					<tr>
						<td class="regular" width="20%">Realm:</td>
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
						<td class="smallFontWHTBG">
							<?php
							if (isset($subscriber['PASSWORD']) && !is_null($subscriber['PASSWORD'])) {
								$len = strlen($subscriber['PASSWORD']);
								for ($i = 0; $i < $len; $i++) {
									echo '*';
								}
							} else {
								echo '';
							}
							?>
						</td>
					</tr>
					<!-- REMOVE ATTRIBUTE 5/17/19 -->
					<!-- <tr>
						<td class="regular">Customer Type:</td>
						<td class="smallFontWHTBG">
							<?php //echo isset($subscriber['CUSTOMERTYPE']) && !is_null($subscriber['CUSTOMERTYPE']) ? $subscriber['CUSTOMERTYPE'] : ''; ?>
						</td>
					</tr> -->
					<tr>
						<td class="regular">Status:</td>
						<td class="smallFontWHTBG">
							<?php echo $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D'; ?>
						</td>
					</tr>
					<tr>
						<td class="regular">Customer Name:</td>
						<td class="smallFontWHTBG">
							<?php echo $subscriber['RBCUSTOMERNAME']; ?>
						</td>
					</tr>
					<tr>
						<td class="regular">Order Number:</td>
						<td class="smallFontWHTBG">
							<?php echo isset($subscriber['RBORDERNUMBER']) && !is_null($subscriber['RBORDERNUMBER']) ? $subscriber['RBORDERNUMBER'] : ''; ?>
						</td>
					</tr>
					<tr>
						<td class="regular">Service Number:</td>
						<td class="smallFontWHTBG">
							<?php echo isset($subscriber['RBSERVICENUMBER']) && !is_null($subscriber['RBSERVICENUMBER']) ? $subscriber['RBSERVICENUMBER'] : ''; ?>
						</td>
					</tr>
					<!-- <tr>
						<td class="regular">Redirection:</td>
						<td class="smallFontWHTBG">
							<?php //echo isset($subscriber['RBENABLED']) && !is_null($subscriber['RBENABLED']) ? $subscriber['RBENABLED'] : ''; ?>
						</td>
					</tr> 
					<tr>
						<td class="regular">Service Code:</td>
						<td class="smallFontWHTBG">
							<?php //echo str_replace('~', '-', $subscriber['RBACCOUNTPLAN']); ?>
						</td>
					</tr> -->
					<?php
					if ($useIPv6) {
					?>
					<tr>
						<td class="regular">IPv6 Address:</td>
						<td class="smallFontWHTBG">
							<?php echo isset($subscriber['RBADDITIONALSERVICE4']) && !is_null($subscriber['RBADDITIONALSERVICE4']) ? $subscriber['RBADDITIONALSERVICE4'] : ''; ?>
						</td>
					</tr>
					<?php
					}
					?>
					<tr>
						<td class="regular">IP Address:</td>
						<td class="smallFontWHTBG">
							<?php echo isset($subscriber['RBIPADDRESS']) && !is_null($subscriber['RBIPADDRESS']) ? $subscriber['RBIPADDRESS'] : ''; ?>
						</td>
					</tr>
					<tr>
						<td class="regular">Network Address:</td>
						<td class="smallFontWHTBG">
							<?php echo isset($subscriber['RBMULTISTATIC']) && !is_null($subscriber['RBMULTISTATIC']) ? $subscriber['RBMULTISTATIC'] : ''; ?>
						</td>
					</tr>
					<tr>
						<td class="regular">Addtional Service 1:</td>
						<td class="smallFontWHTBG">
							<?php echo isset($subscriber['RBADDITIONALSERVICE1']) && !is_null($subscriber['RBADDITIONALSERVICE1']) ? $subscriber['RBADDITIONALSERVICE1'] : ''; ?>
						</td>
					</tr>
					<tr>
						<td class="regular">Addtional Service 2:</td>
						<td class="smallFontWHTBG">
							<?php echo isset($subscriber['RBADDITIONALSERVICE2']) && !is_null($subscriber['RBADDITIONALSERVICE2']) ? $subscriber['RBADDITIONALSERVICE2'] : ''; ?>
						</td>
					</tr>
					<!--
					<tr>
						<td class="regular" valign="top">Remarks:</td>
						<td class="smallFontWHTBG">
							<textarea class="regular" name="remarks" value="" rows="2" wrap="physical" style="resize:none;" readonly cols="50"><?php // echo isset($subscriber['RBREMARKS']) && !is_null($subscriber['RBREMARKS']) ? $subscriber['RBREMARKS'] : ''; ?></textarea>
						</td>
					</tr>
					<tr>
						<td class="regular" valign="top">&nbsp;</td>
						<td class="regular">
							<span class="xsmallFont">Account Information History</span>
							<br />
							<input type="hidden" name="informationHistory" value="<?php // echo $informationHistory; ?>" />
							<div style="width:370px; height:100px; background-color:#ffffff; padding:4px; border:1px solid black; overflow:auto; color:#606060;">
								<span class="xsmallfont">
									
								</span>
							</div>
						</td>
					</tr>
					<tr>
						<td class="regular" valign="top">&nbsp;</td>
						<td class="regular">
							<span class="xsmallFont">Account Session History</span>
							<br />
							<input type="hidden" name="sessionHistory" value="<?php // echo $sessionHistory; ?>" />
							<div style="width:370px; height:100px; background-color:#ffffff; padding:4px; border:1px solid black; overflow:auto; color:#606060;">
								<span class="xsmallfont"></span>
							</div>
						</td>
					</tr>
					-->
					<tr>
						<td></td>
						<td align="left">
							<?php
							//if (is_null($subscriber['RBIPADDRESS']) && is_null($subscriber['RBMULTISTATIC']))  {
							?>
							<input class="button" type="submit" name="modifybutton" value="delete" />
							<?php
							//}
							?>
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
		var proceed = '<?php echo $proceed ? 1 : 0; ?>';
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
			$('form[name="globeacct"]').on('submit', function (event) {
				if (parseInt(proceed) == 0) {
					alert('There are connection problems.\n\nPlease reload page to re-check connection.');
					return false;
				}
				var proceed2 = confirm('Do you want to delete this account?');
				if (proceed2) {
					return true;
				} else {
					return false;
				}
			});
			<?php
			}
			?>
		});
	</script>
</body>
</html>
