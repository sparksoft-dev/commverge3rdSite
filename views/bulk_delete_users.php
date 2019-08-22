<!DOCTYPE html>
<html lang="en">
<head>
	<title>Globelines Universal Access</title>
	<?php require 'head_includes.php'; ?>
</head>
<body style="padding-right:20px; padding-bottom:20px;">
	<div align="left">
		<h3 class="style1">Primary Account: Bulk Delete</h3>
	</div>
	<div align="right">
		<a href="<?php echo base_url('subscribers/showDeleteSubscriberForm'); ?>">Back</a>
	</div>
	<div align="center">
		<span class="notificationMsg"><?php echo isset($message) && !is_null($message) ? $message : ''; ?></span>
		<div align="left">
			<span class="errorMsg" align="left"><?php echo isset($error) ? $error : ''; ?></span>
		</div>
		<br />
	</div>
	<div>
		<?php
		if (!isset($step) || (isset($step) && $step == 'upload')) {
		?>
		<form id="bulkUploadForm" action="<?php echo base_url('subscribers/processBulkDeleteSubscribers'); ?>" method="POST" ENCTYPE="multipart/form-data">
			<table>
				<tr>
					<td class="regular" width="25%">Realm</td>
					<td class="smallFontWHTBG" align="left"><?php include 'allowed_realms.php'; ?></td>
				</tr>

				<tr>
					<td class="regular" width="25%">File: </td>
					<td class="smallFontWHTBG" align="left"><input type="file" name="file" /></td>
				</tr>
				<tr>
					<td class="regular" width="25%" colspan="2">
						<input type="hidden" name="step" value="upload" />
						<input type="submit" value="upload" class="button2" />
					</td>
				</tr>
			</table>
		</form>
		<?php
		} else {
			if (!is_null($step) && $step == 'confirm') {
		?>
		<form id="bulkConfirmForm" action="<?php echo base_url('subscribers/processBulkDeleteSubscribers'); ?>" method="POST">
			<input type="hidden" name="realm" value="<?php echo $realm; ?>" />
			<input type="hidden" name="path" value="<?php echo $path; ?>" />
			<input type="hidden" name="validCN" value='<?php echo serialize($validCN); ?>' />
			<input type="hidden" name="invalidCN" value='<?php echo serialize($invalidCN); ?>' />
			<input type="hidden" name="step" value="delete" />
			<?php
			if (count($validCN) != 0) {
			?>
			The system will attempt to delete the following records. Press OK to start deletion.
			<input type="submit" class="button2" name="confirm_result" value="OK" />
			<?php
			} else {
			?>
			There are no valid records to be deleted.
			<?php   
			}
			?>
		</form>
		<table cellspacing="1" cellpadding="3" style="background-color:#cccccc;" border="0" width="100%">
			<tbody>
				<tr>
					<td class="smallFontGRYBG" align="left" nowrap>&nbsp;</td>
					<td class="smallFontGRYBG" align="left" nowrap>Username</td>
					<td class="smallFontGRYBG" align="left" nowrap>Password</td>
					<!-- Remove Columns 6/24/2019 -->
					<!-- <td class="smallFontGRYBG" align="left" nowrap>Customer Type</td> -->
					<td class="smallFontGRYBG" align="left" nowrap>Status</td>
					<td class="smallFontGRYBG" align="left" nowrap>Customer Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>Order Number</td>
					<td class="smallFontGRYBG" align="left" nowrap>Service Number</td>
					<!-- <td class="smallFontGRYBG" align="left" nowrap>Redirected</td>
					<td class="smallFontGRYBG" align="left" nowrap>Service</td> -->
					<td class="smallFontGRYBG" align="left" nowrap>Addt'l Service 1</td>
					<td class="smallFontGRYBG" align="left" nowrap>Addt'l Service 2</td>
					<?php
					if ($useIPv6) {
					?>
					<td class="smallFontGRYBG" align="left" nowrap>IPv6 Address</td>
					<?php
					}
					?>
					<td class="smallFontGRYBG" align="left" nowrap>IP Address</td>
					<td class="smallFontGRYBG" align="left" nowrap>Network Address</td>
					<td class="smallFontGRYBG" align="left" nowrap>Remarks</td>
				</tr>
				<?php
				for ($i = 0; $i < count($valid); $i++) {
					$row = $valid[$i];
				?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row['USERNAME']; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row['PASSWORD']; ?></td>
					<!-- <td class="smallFontWHTBG" nowrap>
						<?php /*
						if ($row['CUSTOMERTYPE'] == 'Residential' || $row['CUSTOMERTYPE'] == 'R') {
							echo 'R';
						} else if ($row['CUSTOMERTYPE'] == 'Business' || $row['CUSTOMERTYPE'] == 'B') {
							echo 'B';
						}
						*/?>
					</td> -->
					<td class="smallFontWHTBG" nowrap><?php echo $row['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D'; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row['RBCUSTOMERNAME']; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row['RBORDERNUMBER']; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row['RBSERVICENUMBER']; ?></td>
					<!-- <td class="smallFontWHTBG" nowrap>
						<?php /*
						if ($row['RBENABLED'] == 'Yes' || $row['RBENABLED'] == 'Y') {
							echo 'Y';
						} else if ($row['RBENABLED'] == 'No' || $row['RBENABLED'] == 'N') {
							echo 'N';
						}
						?>
					</td>
					<td class="smallFontWHTBG" nowrap><?php echo str_replace('~', '-', $row['RBACCOUNTPLAN']); */?></td> -->
					<td class="smallFontWHTBG" nowrap><?php echo $row['RBADDITIONALSERVICE1']; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row['RBADDITIONALSERVICE2']; ?></td>
					<?php
					if ($useIPv6) {
					?>
					<td class="smallFontWHTBG" nowrap><?php echo $row['RBADDITIONALSERVICE4'] ?></td>
					<?php
					}
					?>
					<td class="smallFontWHTBG" nowrap><?php echo $row['RBIPADDRESS']; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row['RBMULTISTATIC']; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row['RBREMARKS']; ?></td>
				</tr>
				<?php
				}
				?>
				<tr>
					<td colspan="<?php echo $useIPv6 ? '16' : '15'; ?>"><?php echo (count($valid) == 0 ? 'No' : count($valid)).' Record(s)'; ?></td>
				</tr>
			</tbody>
		</table>
		<?php
				if (count($invalidCN) != 0) {
		?>
		<br />
		<span class="errorMsg">The following cannot be deleted because they are not in the system.</span>
		<table cellspacing="1" cellpadding="3" style="background-color:#cccccc;" border="0">
			<tbody>
				<tr>
					<td class="smallFontGRYBG" align="left" nowrap>&nbsp;</td>
					<td class="smallFontGRYBG" align="left" nowrap>Username</td>
					<!--
					<td class="smallFontGRYBG" align="left" nowrap>Password</td>
					<td class="smallFontGRYBG" align="left" nowrap>Account Plan</td>
					<td class="smallFontGRYBG" align="left" nowrap>Status</td>
					<td class="smallFontGRYBG" align="left" nowrap>Customer Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>Order Number</td>
					<td class="smallFontGRYBG" align="left" nowrap>Service Number</td>
					<td class="smallFontGRYBG" align="left" nowrap>Enabled</td>
					<td class="smallFontGRYBG" align="left" nowrap>Service</td>
					-->
					<!--
					<td class="smallFontGRYBG" align="left" nowrap>Addt'l Service 1</td>
					<td class="smallFontGRYBG" align="left" nowrap>Addt'l Service 2</td>
					-->
					<!--
					<td class="smallFontGRYBG" align="left" nowrap>IP Address</td>
					<td class="smallFontGRYBG" align="left" nowrap>Network Address</td>
					-->
					<!--
					<td class="smallFontGRYBG" align="left" nowrap>Remarks</td>
					-->
				</tr>
				<?php
				for ($i = 0; $i < count($invalidCN); $i++) {
					$row = $invalidCN[$i];
				?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row; ?></td>
					<!--
					<td class="smallFontWHTBG" nowrap><?php echo $row[1]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[2]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[3]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[4]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[5]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[6]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[7]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[8]; ?></td>
					-->
					<!--
					<td class="smallFontWHTBG" nowrap><?php echo $row[11]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[12]; ?></td>
					-->
					<!--
					<td class="smallFontWHTBG" nowrap><?php echo $row[9]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[10]; ?></td>
					-->
					<!--
					<td class="smallFontWHTBG" nowrap><?php echo $row[13]; ?></td>
					-->
				</tr>
				<?php
				}
				?>
				<tr>
					<td colspan="2"><?php echo (count($invalidCN) == 0 ? 'No' : count($invalidCN)).' Record(s)'; ?></td>
				</tr>
			</tbody>
		</table>
		<br />
		<?php
				}
			} else if (!is_null($step) && $step == 'delete') {
		?>
		<form action="<?php echo base_url('subscribers/processBulkDeleteSubscribers'); ?>" method="POST">
			<input type="hidden" name="step" value="download" />
			<input type="hidden" name="set" value="deleted" />
			<input type="hidden" name="file" value="<?php echo $file; ?>" />
			<?php
			if (count($deleted) != 0) {
			?>
			<span class="msg">The following records were deleted.</span>
			<input type="submit" class="button2" name="confirm_result" value="extract" />
			<?php
			} else {
			?>
			<span class="msg">There were no records deleted.</span>
			<?php
			}
			?>
		</form>
		<table cellspacing="1" cellpadding="3" style="background-color:#cccccc;" border="0" width="100%">
			<tbody>
				<tr>
					<td class="smallFontGRYBG" align="left" nowrap>&nbsp;</td>
					<td class="smallFontGRYBG" align="left" nowrap>Username</td>
					<td class="smallFontGRYBG" align="left" nowrap>Password</td>
					<!-- <td class="smallFontGRYBG" align="left" nowrap>Customer Type</td> -->
					<td class="smallFontGRYBG" align="left" nowrap>Status</td>
					<td class="smallFontGRYBG" align="left" nowrap>Customer Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>Order Number</td>
					<td class="smallFontGRYBG" align="left" nowrap>Service Number</td>
					<!-- <td class="smallFontGRYBG" align="left" nowrap>Redirected</td>
					<td class="smallFontGRYBG" align="left" nowrap>Service</td> -->
					<td class="smallFontGRYBG" align="left" nowrap>Addt'l Service 1</td>
					<td class="smallFontGRYBG" align="left" nowrap>Addt'l Service 2</td>
					<?php
					if ($useIPv6) {
					?>
					<td class="smallFontGRYBG" align="left" nowrap>IPv6 Address</td>
					<?php
					}
					?>
					<td class="smallFontGRYBG" align="left" nowrap>IP Address</td>
					<td class="smallFontGRYBG" align="left" nowrap>Network Address</td>
					<td class="smallFontGRYBG" align="left" nowrap>Remarks</td>
				</tr>
				<?php
				for ($i = 0; $i < count($deleted); $i++) {
					if (!isset($deleted[$i])) {
						continue;
					}
					$row = $deleted[$i];
				?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row['USERNAME']; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row['PASSWORD']; ?></td>
					<!-- <td class="smallFontWHTBG" nowrap>
						<?php /*
						if ($row['CUSTOMERTYPE'] == 'Residential' || $row['CUSTOMERTYPE'] == 'R') {
							echo 'R';
						} else if ($row['CUSTOMERTYPE'] == 'Business' || $row['CUSTOMERTYPE'] == 'B') {
							echo 'B';
						}
						*/?>
					</td> -->
					<td class="smallFontWHTBG" nowrap><?php echo $row['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D'; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row['RBCUSTOMERNAME']; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row['RBORDERNUMBER']; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row['RBSERVICENUMBER']; ?></td>
					<!-- <td class="smallFontWHTBG" nowrap>
						<?php /*
						if ($row['RBENABLED'] == 'Yes' || $row['RBENABLED'] == 'Y') {
							echo 'Y';
						} else if ($row['RBENABLED'] == 'No' || $row['RBENABLED'] == 'N') {
							echo 'N';
						}
						?>
					</td>
					<td class="smallFontWHTBG" nowrap><?php echo str_replace('~', '-', $row['RBACCOUNTPLAN']); */?></td> -->
					<td class="smallFontWHTBG" nowrap><?php echo $row['RBADDITIONALSERVICE1']; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row['RBADDITIONALSERVICE2']; ?></td>
					<?php
					if ($useIPv6) {
					?>
					<td class="smallFontWHTBG" nowrap><?php echo $row['RBADDITIONALSERVICE4']; ?></td>
					<?php
					}
					?>
					<td class="smallFontWHTBG" nowrap><?php echo $row['RBIPADDRESS']; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row['RBMULTISTATIC']; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row['RBREMARKS']; ?></td>
				</tr>
				<?php
				}
				?>
				<tr>
					<td><td colspan="16"><?php echo (count($deleted) == 0 ? 'No' : count($deleted)).' Record(s)'; ?></td></td>
				</tr>
			</tbody>
		</table>
		<br />
		<?php
				if (count($notDeleted) != 0) { // count($notDeleted) will always be zero (see reason below)
		?>
		<br />
		<!--
		this part will never show up because accounts will always be deleted even if they have ip and/or net address(es) attached
		-->
		<form action="<?php echo base_url('subscribers/processBulkDeleteSubscribers'); ?>" method="POST">
			<input type="hidden" name="step" value="download" />
			<input type="hidden" name="set" value="dne" />
			<input type="hidden" name="file" value="<?php echo $dne; ?>" />
			<span class="errorMsg">The following records were not deleted because an IP and/or Network address was assigned to them.</span>
			<input type="submit" class="button2" name="confirm_result" value="extract" />
		</form>
		<table cellspacing="1" cellpadding="3" style="background-color:#cccccc;" border="0" width="100%">
			<tbody>
				<tr>
					<td class="smallFontGRYBG" align="left" nowrap>&nbsp;</td>
					<td class="smallFontGRYBG" align="left" nowrap>Username</td>
					<td class="smallFontGRYBG" align="left" nowrap>Password</td>
					<!-- <td class="smallFontGRYBG" align="left" nowrap>Customer Type</td> -->
					<td class="smallFontGRYBG" align="left" nowrap>Status</td>
					<td class="smallFontGRYBG" align="left" nowrap>Customer Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>Order Number</td>
					<td class="smallFontGRYBG" align="left" nowrap>Service Number</td>
					<!-- <td class="smallFontGRYBG" align="left" nowrap>Redirected</td>
					<td class="smallFontGRYBG" align="left" nowrap>Service</td> -->
					<td class="smallFontGRYBG" align="left" nowrap>Addt'l Service 1</td>
					<td class="smallFontGRYBG" align="left" nowrap>Addt'l Service 2</td>
					<?php
					if ($useIPv6) {
					?>
					<td class="smallFontGRYBG" align="left" nowrap>IPv6 Address</td>
					<?php
					}
					?>
					<td class="smallFontGRYBG" align="left" nowrap>IP Address</td>
					<td class="smallFontGRYBG" align="left" nowrap>Network Address</td>
					<td class="smallFontGRYBG" align="left" nowrap>Remarks</td>
				</tr>
				<?php
				for ($i = 0; $i < count($notDeleted); $i++) {
					$row = $notDeleted[$i];
				?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row['USERNAME']; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row['PASSWORD']; ?></td>
					<!-- <td class="smallFontWHTBG" nowrap>
						<?php /*
						if ($row['CUSTOMERTYPE'] == 'Residential' || $row['CUSTOMERTYPE'] == 'R') {
							echo 'R';
						} else if ($row['CUSTOMERTYPE'] == 'Business' || $row['CUSTOMERTYPE'] == 'B') {
							echo 'B';
						}
						*/?>
					</td> -->
					<td class="smallFontWHTBG" nowrap><?php echo $row['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D'; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row['RBCUSTOMERNAME']; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row['RBORDERNUMBER']; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row['RBSERVICENUMBER']; ?></td>
					<!-- <td class="smallFontWHTBG" nowrap>
						<?php /*
						if ($row['RBENABLED'] == 'Yes' || $row['RBENABLED'] == 'Y') {
							echo 'Y';
						} else if ($row['RBENABLED'] == 'No' || $row['RBENABLED'] == 'N') {
							echo 'N';
						}
						?>
					</td>
					<td class="smallFontWHTBG" nowrap><?php echo str_replace('~', '-', $row['RBACCOUNTPLAN']); */?></td> -->
					<td class="smallFontWHTBG" nowrap><?php echo $row['RBADDITIONALSERVICE1']; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row['RBADDITIONALSERVICE2']; ?></td>
					<?php
					if ($useIPv6) {
					?>
					<td class="smallFontWHTBG" nowrap><?php echo $row['RBADDITIONALSERVICE4']; ?></td>
					<?php
					}
					?>
					<td class="smallFontWHTBG" nowrap><?php echo $row['RBIPADDRESS']; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row['RBMULTISTATIC']; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row['RBREMARKS']; ?></td>
				</tr>
				<?php
				}
				?>
				<tr>
					<td colspan="<?php echo $useIPv6 ? '16' : '15'; ?>"><?php echo (count($notDeleted) == 0 ? 'No' : count($notDeleted)).' Record(s)'; ?></td>
				</tr>
		</table>
		<!--
		this part will never show up because accounts will always be deleted even if they have ip and/or net address(es) attached
		-->
		<?php
				}
				if (count($invalidCN) != 0) {
		?>
		<br />
		<form action="<?php echo base_url('subscribers/processBulkDeleteSubscribers') ?>" method="POST">
			<input type="hidden" name="step" value="download" />
			<input type="hidden" name="set" value="dne" />
			<input type="hidden" name="file" value="<?php echo $dne; ?>" />
			<span class="errorMsg">Usernames not found in database.</span>
			<input type="submit" class="button2" name="confirm_result" value="extract" />
		</form>
		<table cellspacing="1" cellpadding="3" style="background-color:#cccccc;" border="0">
			<tbody>
				<tr>
					<td class="smallFontGRYBG" align="left" nowrap>&nbsp;</td>
					<td class="smallFontGRYBG" align="left" nowrap>Username</td>
				</tr>
				<?php
				for ($i = 0; $i < count($invalidCN); $i++) {
					$row = $invalidCN[$i];
				?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row; ?></td>
				</tr>
				<?php
				}
				?>
				<tr>
					<td colspan="2"><?php echo (count($invalidCN) == 0 ? 'No' : count($invalidCN)).' Record(s)'; ?></td>
				</tr>
			</tbody>	
		</table>
		<?php
				}
			}
		}
		?>
	</div>
	<script type="text/javascript">
		var proceed = '<?php echo $proceed ? 1 : 0; ?>';
		$(document).ready(function () {
			$('#bulkUploadForm').on('submit', function (event) {
				if (parseInt(proceed) == 0) {
					alert('There are connection problems.\n\nPlease reload page to re-check connection.');
					return false;
				}
			});
			$('#bulkConfirmForm').on('submit', function (event) {
				if (parseInt(proceed) == 0) {
					alert('There are connection problems.\n\nPlease reload page to re-check connection.');
					return false;
				}
			});
		});
	</script>
</body>
</html>	