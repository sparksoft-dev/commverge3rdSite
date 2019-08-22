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
	<div align="right">
		<a href="<?php echo base_url('subscribers/showUpdateSubscriberFormAdmin'); ?>">Back to modify page</a>
	</div>
	<div align="center">
		<span class="notificationMsg"><?php echo isset($message) && !is_null($message) ? $message : ''; ?></span>
	</div>
	<br />
	<div align="center">
		<table cellspacing="1" cellpadding="3" style="background-color:#cccccc;" border="0">
			<tbody>
				<tr>
					<td class="xsmallFontWHTBG" width="25%"><strong>Realm:</strong></td>
					<td class="xsmallFontWHTBG" align="left"><?php echo isset($subscriber['RBREALM']) && !is_null($subscriber['RBREALM']) ? $subscriber['RBREALM'] : ''; ?></td>
				</tr>
				<tr>
					<td class="xsmallFontWHTBG"><strong>Username:</strong></td>
					<td class="xsmallFontWHTBG"><?php echo isset($subscriber['USERNAME']) && !is_null($subscriber['USERNAME']) ? $subscriber['USERNAME'] : ''; ?></td>
				</tr>
				<tr>
					<td class="xsmallFontWHTBG"><strong>Password:</strong></td>
					<td class="xsmallFontWHTBG">
						<?php 
						if (isset($subscriber['PASSWORD']) && !is_null($subscriber['PASSWORD'])) {
							//echo $subscriber['PASSWORD'];
							for ($i = 0; $i < strlen($subscriber['PASSWORD']); $i++) {
								echo '*';
							}
						} else {
							echo '';
						}
						?>
					</td>
				</tr>
				<tr>
					<td class="xsmallFontWHTBG"><strong>Customer Type:</strong></td>
					<td class="xsmallFontWHTBG">
						<?php echo isset($subscriber['CUSTOMERTYPE']) && !is_null($subscriber['CUSTOMERTYPE']) ? $subscriber['CUSTOMERTYPE'] : ''; ?>
					</td>
				</tr>
				<tr>
					<td class="xsmallFontWHTBG"><strong>Status:</strong></td>
					<td class="xsmallFontWHTBG">
						<?php echo isset($subscriber['CUSTOMERSTATUS']) && !is_null($subscriber['CUSTOMERSTATUS']) ? ($subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D') : ''; ?>
					</td>
				</tr>
				<tr>
					<td class="xsmallFontWHTBG"><strong>Customer Name:</strong></td>
					<td class="xsmallFontWHTBG">
						<?php echo isset($subscriber['RBCUSTOMERNAME']) && !is_null($subscriber['RBCUSTOMERNAME']) ? $subscriber['RBCUSTOMERNAME'] : ''; ?>
					</td>
				</tr>
				<tr>
					<td class="xsmallFontWHTBG"><strong>Order Number:</strong></td>
					<td class="xsmallFontWHTBG">
						<?php echo isset($subscriber['RBORDERNUMBER']) && !is_null($subscriber['RBORDERNUMBER']) ? $subscriber['RBORDERNUMBER'] : ''; ?>
					</td>
				</tr>
				<tr>
					<td class="xsmallFontWHTBG"><strong>Service Number:</strong></td>
					<td class="xsmallFontWHTBG">
						<?php echo isset($subscriber['RBSERVICENUMBER']) && !is_null($subscriber['RBSERVICENUMBER']) ? $subscriber['RBSERVICENUMBER'] : ''; ?>
					</td>
				</tr>
				<tr>
					<td class="xsmallFontWHTBG"><strong>Redirection:</strong></td>
					<td class="xsmallFontWHTBG">
						<?php echo isset($subscriber['RBENABLED']) && !is_null($subscriber['RBENABLED']) ? $subscriber['RBENABLED'] : ''; ?>
					</td>
				</tr>
				<tr>
					<td class="xsmallFontWHTBG"><strong>Radius Policy:</strong></td>
					<td class="xsmallFontWHTBG">
						<?php echo isset($subscriber['RBACCOUNTPLAN']) && !is_null($subscriber['RBACCOUNTPLAN']) ? str_replace('~', '-', $subscriber['RBACCOUNTPLAN']) : ''; ?>
					</td>
				</tr>
				<?php
				if ($useIPv6) {
				?>
				<tr>
					<td class="xsmallFontWHTBG"><strong>IPv6 Address:</strong></td>
					<td class="xsmallFontWHTBG">
						<?php echo isset($subscriber['RBADDITIONALSERVICE4']) && !is_null($subscriber['RBADDITIONALSERVICE4']) ? str_replace('~', '-', $subscriber['RBADDITIONALSERVICE4']) : ''; ?>
					</td>
				</tr>
				<?php
				}
				?>
				<tr>
					<td class="xsmallFontWHTBG"><strong>IP Address:</strong></td>
					<td class="xsmallFontWHTBG">
						<?php echo isset($subscriber['RBIPADDRESS']) &&  !is_null($subscriber['RBIPADDRESS']) ? $subscriber['RBIPADDRESS'] : ''; ?>
					</td>
				</tr>
				<tr>
					<td class="xsmallFontWHTBG"><strong>Network Address:</strong></td>
					<td class="xsmallFontWHTBG">
						<?php echo isset($subscriber['RBMULTISTATIC']) && !is_null($subscriber['RBMULTISTATIC']) ? $subscriber['RBMULTISTATIC'] : ''; ?>
					</td>
				</tr>
				<tr>
					<td class="xsmallFontWHTBG"><strong>Addtional Service 1:</strong></td>
					<td class="xsmallFontWHTBG">
						<?php echo isset($subscriber['RBADDITIONALSERVICE1']) && !is_null($subscriber['RBADDITIONALSERVICE1']) ? $subscriber['RBADDITIONALSERVICE1'] : ''; ?>
					</td>
				</tr>
				<tr>
					<td class="xsmallFontWHTBG"><strong>Addtional Service 2:</strong></td>
					<td class="xsmallFontWHTBG">
						<?php echo isset($subscriber['RBADDITIONALSERVICE2']) && !is_null($subscriber['RBADDITIONALSERVICE2']) ? $subscriber['RBADDITIONALSERVICE2'] : ''; ?>
					</td>
				</tr>
				<tr>
					<td class="xsmallFontWHTBG" valign="top"><strong>Account Information History:</strong></td>
					<td class="xsmallFontWHTBG">
						<div style="height:150px; background-color:#ffffff; padding:4px; overflow:auto;">
							<span class="xsmallfont" style="font-weight:normal;">
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
					<td class="xsmallFontWHTBG" valign="top"><strong>Account Session History:</strong></td>
					<td class="xsmallFontWHTBG">
						<div style="height:150px; background-color:#ffffff; padding:4px; overflow:auto;">
							<span class="xsmallfont"></span>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</body>
</html>