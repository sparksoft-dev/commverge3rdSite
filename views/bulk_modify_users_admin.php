<!DOCTYPE html>
<html lang="en">
<head>
	<title>Globelines Universal Access</title>
	<?php require 'head_includes.php'; ?>
</head>
<body style="padding-right:20px; padding-bottom:20px;">
	<div align="left">
		<h3 class="style1">Primary Account: Bulk Modify (Admin)</h3>
	</div>
	<div align="right">
		<a href="<?php echo base_url('subscribers/showUpdateSubscriberFormAdmin'); ?>">Back</a>
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
		<form id="bulkUploadForm" action="<?php echo base_url('subscribers/processBulkUpdateSubscribersAdmin'); ?>" method="POST" ENCTYPE="multipart/form-data">
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
		<form id="bulkConfirmForm" action="<?php echo base_url('subscribers/processBulkUpdateSubscribersAdmin'); ?>" method="POST">
			<input type="hidden" name="realm" value="<?php echo $realm; ?>" />
			<input type="hidden" name="path" value="<?php echo $path; ?>" />
			<input type="hidden" name="validRowNumbers" value="<?php echo serialize($validRowNumbers); ?>" />
			<input type="hidden" name="invalidRowNumbers" value="<?php echo serialize($invalidRowNumbers); ?>" />
			<input type="hidden" name="step" value="update" />
			<?php
			if (count($valid) != 0) {
			?>
			The system will attempt to modify the following records. Press OK to start modification.
			<input type="submit" class="button2" name="confirm_result" value="OK" />
			<?php
			} else {
			?>
			There are no valid records to be modified.
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
					<!-- Remove Attributes 5/20/19 -->
					<!-- <td class="smallFontGRYBG" align="left" nowrap>Customer Type</td> -->
					<td class="smallFontGRYBG" align="left" nowrap>Status</td>
					<td class="smallFontGRYBG" align="left" nowrap>Customer Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>Order Number</td>
					<td class="smallFontGRYBG" align="left" nowrap>Service Number</td>
					<!-- <td class="smallFontGRYBG" align="left" nowrap>Redirected</td>
					<td class="smallFontGRYBG" align="left" nowrap>Service</td> -->
					<!--
					<td class="smallFontGRYBG" align="left" nowrap>Addt'l Service 1</td>
					<td class="smallFontGRYBG" align="left" nowrap>Addt'l Service 2</td>
					-->
					<?php
					if ($useIPv6) {
					?>
					<td class="smallFontGRYBG" align="left" nowrap>IPv6 Address/Cabinet Name</td>
					<?php
					}
					?>
					<td class="smallFontGRYBG" align="left" nowrap>IP Address/Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>Network Address/Cabinet Name</td>
					<!--
					<td class="smallFontGRYBG" align="left" nowrap>Remarks</td>
					-->
				</tr>
				<?php
				for ($i = 0; $i < count($valid); $i++) {
					$row = $valid[$i];
					$mark = $validMarks[$i];
				?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($mark['USERNAME']) ? 'style="border:1px solid green;"' : ''; ?>><?php echo $row[0]; ?></td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($mark['PASSWORD']) ? 'style="border:1px solid green;"' : ''; ?>><?php echo $row[1]; ?></td>
					<!-- <td class="smallFontWHTBG" nowrap <?php //echo isset($mark['CUSTOMERTYPE']) ? 'style="border:1px solid green;"' : ''; ?>><?php //echo $row[2]; ?></td> -->
					<td class="smallFontWHTBG" nowrap <?php echo isset($mark['CUSTOMERSTATUS']) ? 'style="border:1px solid green;"' : ''; ?>><?php echo $row[3]; ?></td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($mark['RBCUSTOMERNAME']) ? 'style="border:1px solid green;"' : ''; ?>><?php echo $row[5]; ?></td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($mark['RBORDERNUMBER']) ? 'style="border:1px solid green;"' : ''; ?>><?php echo $row[4]; ?></td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($mark['RBSERVICENUMBER']) ? 'style="border:1px solid green;"' : ''; ?>><?php echo $row[6]; ?></td>
					<!-- <td class="smallFontWHTBG" nowrap <?php /*echo isset($mark['RBENABLED']) ? 'style="border:1px solid green;"' : ''; ?>><?php echo $row[7]; ?></td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($mark['RBACCOUNTPLAN']) ? 'style="border:1px solid green;"' : ''; ?>>
						<?php echo str_replace('~', '-', $row[8]); */?>
					</td> -->
					<!--
					<td class="smallFontWHTBG" nowrap><?php //echo $row[11]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php //echo $row[12]; ?></td>
					-->
					<?php
					if ($useIPv6) {
					?>
					<td class="smallFontWHTBG" nowrap <?php echo isset($mark['RBADDITIONALSERVICE4']) ? 'style="border:1px solid green;"' : ''; ?>><?php echo $row[9]; ?></td>
					<?php
					}
					?>
					<td class="smallFontWHTBG" nowrap <?php echo isset($mark['RBIPADDRESS']) ? 'style="border:1px solid green;"' : ''; ?>>
						<?php echo $useIPv6 ? $row[10] : $row[9]; ?>
					</td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($mark['RBMULTISTATIC']) ? 'style="border:1px solid green;"' : ''; ?>>
						<?php echo $useIPv6 ? $row[11] : $row[10]; ?>
					</td>
					<!--
					<td class="smallFontWHTBG" nowrap><?php //echo $row[13]; ?></td>
					-->
				</tr>
				<?php
				}
				?>
				<tr>
					<td colspan="<?php echo $useIPv6 ? '13' : '12'; ?>"><?php echo (count($valid) == 0 ? 'No' : count($valid)).' Record(s)'; ?></td>
				</tr>
			</tbody>
		</table>
		<br />
		<?php
				if (count($invalid) != 0) {
		?>
		<br />
		<span class="errorMsg">The following records were ignored because they have invalid or missing fields.</span>
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
					<!--
					<td class="smallFontGRYBG" align="left" nowrap>Addt'l Service 1</td>
					<td class="smallFontGRYBG" align="left" nowrap>Addt'l Service 2</td>
					-->
					<?php
					if ($useIPv6) {
					?>
					<td class="smallFontGRYBG" align="left" nowrap>IPv6 Address/Cabinet Name</td>
					<?php
					}
					?>
					<td class="smallFontGRYBG" align="left" nowrap>IP Address/Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>Network Address/Cabinet Name</td>
					<!--
					<td class="smallFontGRYBG" align="left" nowrap>Remarks</td>
					-->
				</tr>
				<?php
				for ($i = 0; $i < count($invalid); $i++) {
					$row = $invalid[$i];
				?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[0]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[1]; ?></td>
					<!-- <td class="smallFontWHTBG" nowrap><?php //echo $row[2]; ?></td> -->
					<td class="smallFontWHTBG" nowrap><?php echo $row[3]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[5]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[4]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[6]; ?></td>
					<!-- <td class="smallFontWHTBG" nowrap><?php //echo $row[7]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php //echo str_replace('~', '-', $row[8]); ?></td> -->
					<!--
					<td class="smallFontWHTBG" nowrap><?php //echo $row[11]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php //echo $row[12]; ?></td>
					-->
					<?php
					if ($useIPv6) {
					?>
					<td class="smallFontWHTBG" nowrap><?php echo $row[9]; ?></td>
					<?php
					}
					?>
					<td class="smallFontWHTBG" nowrap><?php echo $useIPv6 ? $row[10] : $row[9]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $useIPv6 ? $row[11] : $row[10]; ?></td>
					<!--
					<td class="smallFontWHTBG" nowrap><?php //echo $row[13]; ?></td>
					-->
				</tr>
				<?php
				}
				?>
				<tr>
					<td colspan="<?php echo $useIPv6 ? '13' : '12'; ?>"><?php echo (count($invalid) == 0 ? 'No' : count($invalid)).' Record(s)'; ?></td>
				</tr>
			</tbody>
		</table>
		<br />
		<?php
				}
				if (count($toDelete) != 0) {
		?>
		<br />
		<span class="errorMsg">The following records will be deleted.</span>
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
					<!--
					<td class="smallFontGRYBG" align="left" nowrap>Addt'l Service 1</td>
					<td class="smallFontGRYBG" align="left" nowrap>Addt'l Service 2</td>
					-->
					<?php
					if ($useIPv6) {
					?>
					<td class="smallFontGRYBG" align="left" nowrap>IPv6 Address/Cabinet Name</td>
					<?php
					}
					?>
					<td class="smallFontGRYBG" align="left" nowrap>IP Address/Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>Network Address/Cabinet Name</td>
					<!--
					<td class="smallFontGRYBG" align="left" nowrap>Remarks</td>
					-->
				</tr>
				<?php
				for ($i = 0; $i < count($toDelete); $i++) {
					$row = $toDelete[$i];
				?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[0]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[1]; ?></td>
					<!-- <td class="smallFontWHTBG" nowrap><?php //echo $row[2]; ?></td> -->
					<td class="smallFontWHTBG" nowrap><?php echo $row[3]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[5]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[4]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[6]; ?></td>
					<!-- <td class="smallFontWHTBG" nowrap><?php //echo $row[7]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php //echo str_replace('~', '-', $row[8]); ?></td> -->
					<!--
					<td class="smallFontWHTBG" nowrap><?php //echo $row[11]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php //echo $row[12]; ?></td>
					-->
					<?php
					if ($useIPv6) {
					?>
					<td class="smallFontWHTBG" nowrap><?php echo $row[9]; ?></td>
					<?php
					}
					?>
					<td class="smallFontWHTBG" nowrap><?php echo $useIPv6 ? $row[10] : $row[9]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $useIPv6 ? $row[11] : $row[10]; ?></td>
					<!--
					<td class="smallFontWHTBG" nowrap><?php //echo $row[13]; ?></td>
					-->
				</tr>
				<?php
				}
				?>
				<tr>
					<td colspan="<?php echo $useIPv6 ? '13' : '12'; ?>"><?php echo (count($toDelete) == 0 ? 'No' : count($toDelete)).' Record(s)'; ?></td>
				</tr>
			</tbody>
		</table>
		<?php
				}
			} else if (!is_null($step) && $step == 'update') {
		?>
		<form action="<?php echo base_url('subscribers/processBulkUpdateSubscribersAdmin'); ?>" method="POST">
			<input type="hidden" name="step" value="download" />
			<input type="hidden" name="set" value="updated" />
			<input type="hidden" name="realm" value="<?php echo $realm; ?>" />
			<input type="hidden" name="path" value="<?php echo $path; ?>" />
			<input type="hidden" name="setdata" value="<?php echo serialize($updatedRowNumbers); ?>" />
			<?php
			if (count($updated) != 0) {
			?>
			<span class="msg">The following records were modified.</span>
			<input type="submit" class="button2" name="confirm_result" value="extract" />
			<?php
			} else {
			?>
			<span class="msg">No records were modified.</span>
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
					<!--
					<td class="smallFontGRYBG" align="left" nowrap>Addt'l Service 1</td>
					<td class="smallFontGRYBG" align="left" nowrap>Addt'l Service 2</td>
					-->
					<?php
					if ($useIPv6) {
					?>
					<td class="smallFontGRYBG" align="left" nowrap>IPv6 Address/Cabinet Name</td>
					<?php
					}
					?>
					<td class="smallFontGRYBG" align="left" nowrap>IP Address/Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>Network Address/Cabinet Name</td>
					<!--
					<td class="smallFontGRYBG" align="left" nowrap>Remarks</td>
					-->
				</tr>
				<?php
				for ($i = 0; $i < count($updated); $i++) {
					$row = $updated[$i];
					$mark = $updatedMarks[$i];
				?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap>
						<?php echo $row['USERNAME']; ?>
					</td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($mark['PASSWORD']) ? 'style="border:1px solid green;"' : ''; ?>>
						<?php echo $row['PASSWORD']; ?>
					</td>
					<!-- <td class="smallFontWHTBG" nowrap <?php //echo isset($mark['CUSTOMERTYPE']) ? 'style="border:1px solid green;"' : ''; ?>>
						<?php /*
						if ($row['CUSTOMERTYPE'] == 'Residential' || $row['CUSTOMERTYPE'] == 'R') {
							echo 'R';
						} else if ($row['CUSTOMERTYPE'] == 'Business' || $row['CUSTOMERTYPE'] == 'B') {
							echo 'B';
						}
						*/?>
					</td> -->
					<td class="smallFontWHTBG" nowrap <?php echo isset($mark['CUSTOMERSTATUS']) ? 'style="border:1px solid green;"' : ''; ?>>
						<?php echo is_null($row['CUSTOMERSTATUS']) || $row['CUSTOMERSTATUS'] == '' ? '' : ($row['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D'); ?>
					</td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($mark['RBCUSTOMERNAME']) ? 'style="border:1px solid green;"' : ''; ?>>
						<?php echo $row['RBCUSTOMERNAME']; ?>
					</td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($mark['RBORDERNUMBER']) ? 'style="border:1px solid green;"' : ''; ?>>
						<?php echo $row['RBORDERNUMBER']; ?>
					</td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($mark['RBSERVICENUMBER']) ? 'style="border:1px solid green;"' : ''; ?>>
						<?php echo $row['RBSERVICENUMBER']; ?>
					</td>
					<!-- <td class="smallFontWHTBG" nowrap <?php //echo isset($mark['RBENABLED']) ? 'style="border:1px solid green;"' : ''; ?>>
						<?php /*
						if ($row['RBENABLED'] == 'Yes' || $row['RBENABLED'] == 'Y') {
							echo 'Y';
						} else if ($row['RBENABLED'] == 'No' || $row['RBENABLED'] == 'N') {
							echo 'N';
						}
						?>
					</td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($mark['RBACCOUNTPLAN']) ? 'style="border:1px solid green;"' : ''; ?>>
						<?php echo str_replace('~', '-', $row['RBACCOUNTPLAN']); */?>
					</td> -->
					<!--
					<td class="smallFontWHTBG" nowrap><?php //echo $row[11]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php //echo $row[12]; ?></td>
					-->
					<?php
					if ($useIPv6) {
					?>
					<td class="smallFontWHTBG" nowrap <?php echo isset($mark['RBADDITIONALSERVICE4']) ? 'style="border:1px solid green;"' : ''; ?>>
						<?php echo is_null($row['RBADDITIONALSERVICE4']) ? '' : $row['RBADDITIONALSERVICE4']; ?>
					</td>
					<?php
					}
					?>
					<td class="smallFontWHTBG" nowrap <?php echo isset($mark['RBIPADDRESS']) ? 'style="border:1px solid green;"' : ''; ?>>
						<?php echo is_null($row['RBIPADDRESS']) ? '' : $row['RBIPADDRESS']; ?>
					</td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($mark['RBMULTISTATIC']) ? 'style="border:1px solid green;"' : ''; ?>>
						<?php echo is_null($row['RBMULTISTATIC']) ? '' : $row['RBMULTISTATIC']; ?>
					</td>
					<!--
					<td class="smallFontWHTBG" nowrap><?php //echo $row[13]; ?></td>
					-->
				</tr>
				<?php
				}
				?>
				<tr>
					<td><td colspan="<?php echo $useIPv6 ? '13' : '12'; ?>"><?php echo (count($updated) == 0 ? 'No' : count($updated)).' Record(s)'; ?></td></td>
				</tr>
			</tbody>
		</table>
		<br />
		<?php
				if (count($invalidData) != 0) {
		?>
		<br />
		<form action="<?php echo base_url('subscribers/processBulkUpdateSubscribersAdmin'); ?>" method="POST">
			<input type="hidden" name="step" value="download" />
			<input type="hidden" name="set" value="invalidData" />
			<input type="hidden" name="realm" value="<?php echo $realm; ?>" />
			<input type="hidden" name="path" value="<?php echo $path; ?>" />
			<input type="hidden" name="setdata" value="<?php echo serialize($invalidDataRowNumbers); ?>" />
			<span class="errorMsg">The following records were ignored because they have erroneous data.</span>
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
					<!--
					<td class="smallFontGRYBG" align="left" nowrap>Addt'l Service 1</td>
					<td class="smallFontGRYBG" align="left" nowrap>Addt'l Service 2</td>
					-->
					<?php
					if ($useIPv6) {
					?>
					<td class="smallFontGRYBG" align="left" nowrap>IPv6 Address/Cabinet Name</td>
					<?php
					}
					?>
					<td class="smallFontGRYBG" align="left" nowrap>IP Address/Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>Network Address/Cabinet Name</td>
					<!--
					<td class="smallFontGRYBG" align="left" nowrap>Remarks</td>
					-->
				</tr>
				<?php
				for ($i = 0; $i < count($invalidData); $i++) {
					$row = $invalidData[$i]['rowdata2'];
					$err = $invalidData[$i]['errors'];
				?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($err['USERNAME']) ? 'style="border:1px solid red;"' : ''; ?>>
						<?php echo $row['USERNAME'].(isset($err['USERNAME']) ? '&nbsp;<span class="errorMsg">'.$err['USERNAME'].'</span>' : ''); ?>
					</td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($err['PASSWORD']) ? 'style="border:1px solid red;"' : ''; ?>>
						<?php echo $row['PASSWORD']; ?>
					</td>
					<!-- <td class="smallFontWHTBG" nowrap <?php //echo isset($err['CUSTOMERTYPE']) ? 'style="border:1px solid red;"' : ''; ?>>
						<?php /*
						if ($row['CUSTOMERTYPE'] == 'Residential' || $row['CUSTOMERTYPE'] == 'R') {
							echo 'R';
						} else if ($row['CUSTOMERTYPE'] == 'Business' || $row['CUSTOMERTYPE'] == 'B') {
							echo 'B';
						}
						*/?>
					</td> -->
					<td class="smallFontWHTBG" nowrap <?php echo isset($err['CUSTOMERSTATUS']) ? 'style="border:1px solid red;"' : ''; ?>>
						<?php echo $row['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D'; ?>
					</td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($err['RBCUSTOMERNAME']) ? 'style="border:1px solid red;"' : ''; ?>>
						<?php echo $row['RBCUSTOMERNAME']; ?>
					</td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($err['RBORDERNUMBER']) ? 'style="border:1px solid red;"' : ''; ?>>
						<?php echo $row['RBORDERNUMBER']; ?>
					</td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($err['RBSERVICENUMBER']) ? 'style="border:1px solid red;"' : ''; ?>>
						<?php echo $row['RBSERVICENUMBER'].(isset($err['RBSERVICENUMBER']) ? '&nbsp;<span class="errorMsg">'.$err['RBSERVICENUMBER'].'</span>' : ''); ?>
					</td>
					<!-- <td class="smallFontWHTBG" nowrap <?php //echo isset($err['RBENABLED']) ? 'style="border:1px solid red;"' : ''; ?>>
						<?php /*
						if ($row['RBENABLED'] == 'Yes' || $row['RBENABLED'] == 'Y') {
							echo 'Y';
						} else if ($row['RBENABLED'] == 'No' || $row['RBENABLED'] == 'N') {
							echo 'N';
						}
						?>
					</td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($err['RBACCOUNTPLAN']) ? 'style="border:1px solid red;"' : ''; ?>>
						<?php echo str_replace('~', '-', $row['RBACCOUNTPLAN']).(isset($err['RBACCOUNTPLAN']) ? '&nbsp;<span class="errorMsg">'.$err['RBACCOUNTPLAN'].'</span>' : ''); */?>
					</td> -->
					<!--
					<td class="smallFontWHTBG" nowrap><?php //echo $row[11]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php //echo $row[12]; ?></td>
					-->
					<?php
					if ($useIPv6) {
					?>
					<td class="smallFontWHTBG" nowrap <?php echo isset($err['RBADDITIONALSERVICE4']) ? 'style="border:1px solid red;"' : ''; ?>>
						<?php echo is_null($row['RBADDITIONALSERVICE4']) ? '' : $row['RBADDITIONALSERVICE4']; ?>
					</td>
					<?php
					}
					?>
					<td class="smallFontWHTBG" nowrap <?php echo isset($err['RBIPADDRESS']) ? 'style="border:1px solid red;"' : ''; ?>>
						<?php echo is_null($row['RBIPADDRESS']) ? '' : $row['RBIPADDRESS']; ?>
					</td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($err['RBMULTISTATIC']) ? 'style="border:1px solid red;"' : ''; ?>>
						<?php echo is_null($row['RBMULTISTATIC']) ? '' : $row['RBMULTISTATIC']; ?>
					</td>
					<!--
					<td class="smallFontWHTBG" nowrap><?php //echo $row[13]; ?></td>
					-->
				</tr>
				<?php
				}
				?>
				<tr>
					<td colspan="<?php echo $useIPv6 ? '13' : '12'; ?>"><?php echo (count($invalidData) == 0 ? 'No' : count($invalidData)).' Record(s)'; ?></td>
				</tr>
		</table>
		<br />
		<?php
				}
				if (count($usernameDNE) != 0) {
		?>
		<br />
		<form action="<?php echo base_url('subscribers/processBulkUpdateSubscribersAdmin'); ?>" method="POST">
			<input type="hidden" name="step" value="download" />
			<input type="hidden" name="set" value="usernameDNE" />
			<input type="hidden" name="realm" value="<?php echo $realm; ?>" />
			<input type="hidden" name="path" value="<?php echo $path; ?>" />
			<input type="hidden" name="setdata" value="<?php echo serialize($usernameDNERowNumbers); ?>" />
			<span class="errorMsg">The following records were not modified because the usernames do not exist.</span>
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
					<!--
					<td class="smallFontGRYBG" align="left" nowrap>Addt'l Service 1</td>
					<td class="smallFontGRYBG" align="left" nowrap>Addt'l Service 2</td>
					-->
					<?php
					if ($useIPv6) {
					?>
					<td class="smallFontGRYBG" align="left" nowrap>IPv6 Address/Cabinet Name</td>
					<?php
					}
					?>
					<td class="smallFontGRYBG" align="left" nowrap>IP Address/Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>Network Address/Cabinet Name</td>
					<!--
					<td class="smallFontGRYBG" align="left" nowrap>Remarks</td>
					-->
				</tr>
				<?php
				for ($i = 0; $i < count($usernameDNE); $i++) {
					$row = $usernameDNE[$i];
				?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap style="border:1px solid red;"><?php echo $row[0]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[1]; ?></td>
					<!-- <td class="smallFontWHTBG" nowrap>
						<?php /*
						if ($row[2] == 'Residential' || $row[2] == 'R') {
							echo 'R';
						} else if ($row[2] == 'Business' || $row[2] == 'B') {
							echo 'B';
						}
						*/?>
					</td> -->
					<td class="smallFontWHTBG" nowrap><?php echo $row[3]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[5]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[4]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[6]; ?></td>
					<!-- <td class="smallFontWHTBG" nowrap>
						<?php /*
						if ($row[7] == 'Yes' || $row[7] == 'Y') {
							echo 'Y';
						} else if ($row[7] == 'No' || $row[7] == 'N') {
							echo 'N';
						}
						?>
					</td>
					<td class="smallFontWHTBG" nowrap><?php echo str_replace('~', '-', $row[8]); */?></td> -->
					<!--
					<td class="smallFontWHTBG" nowrap><?php //echo $row[11]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php //echo $row[12]; ?></td>
					-->
					<?php
					if ($useIPv6) {
					?>
					<td class="smallFontWHTBG" nowrap><?php echo $row[9]; ?></td>
					<?php
					}
					?>
					<td class="smallFontWHTBG" nowrap><?php echo $useIPv6 ? $row[10] : $row[9]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $useIPv6 ? $row[11] : $row[10]; ?></td>
					<!--
					<td class="smallFontWHTBG" nowrap><?php //echo $row[13]; ?></td>
					-->
				</tr>
				<?php
				}
				?>
				<tr>
					<td colspan="<?php echo $useIPv6 ? '13' : '12'; ?>"><?php echo (count($usernameDNE) == 0 ? 'No' : count($usernameDNE)).' Record(s)'; ?></td>
				</tr>
			</tbody>
		</table>
		<br />
		<?php
				}
				if (count($ipNetError) != 0) {
		?>
		<br />
		<form action="<?php echo base_url('subscribers/processBulkUpdateSubscribersAdmin'); ?>" method="POST">
			<input type="hidden" name="step" value="download" />
			<input type="hidden" name="set" value="ipNetError" />
			<input type="hidden" name="realm" value="<?php echo $realm; ?>" />
			<input type="hidden" name="path" value="<?php echo $path; ?>" />
			<input type="hidden" name="setdata" value="<?php echo serialize($ipNetErrorRowNumbers); ?>" />
			<span class="errorMsg">The following records were not modified because of invalid ip/net addresses.</span>   
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
					<!--
					<td class="smallFontGRYBG" align="left" nowrap>Addt'l Service 1</td>
					<td class="smallFontGRYBG" align="left" nowrap>Addt'l Service 2</td>
					-->
					<?php
					if ($useIPv6) {
					?>
					<td class="smallFontGRYBG" align="left" nowrap>IPv6 Address/Cabinet Name</td>
					<?php
					}
					?>
					<td class="smallFontGRYBG" align="left" nowrap>IP Address/Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>Network Address/Cabinet Name</td>
					<!--
					<td class="smallFontGRYBG" align="left" nowrap>Remarks</td>
					-->
				</tr>
				<?php
				for ($i = 0; $i < count($ipNetError); $i++) {
					$row = $ipNetError[$i]['rowdata2'];
					$err = $ipNetError[$i]['errors'];
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
					<td class="smallFontWHTBG" nowrap <?php echo isset($err['RBACCOUNTPLAN']) ? 'style="border:1px solid red;"' : ''; ?>>
						<?php echo str_replace('~', '-', $row['RBACCOUNTPLAN']); */?>
					</td> -->
					<!--
					<td class="smallFontWHTBG" nowrap><?php //echo $row[11]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php //echo $row[12]; ?></td>
					-->
					<?php
					if ($useIPv6) {
					?>
					<td class="smallFontWHTBG" nowrap <?php echo isset($err['RBADDITIONALSERVICE4']) ? 'style="border:1px solid red;"' : ''; ?>>
						<?php echo (is_null($row['RBADDITIONALSERVICE4']) ? '' : $row['RBADDITIONALSERVICE4']).(isset($err['RBADDITIONALSERVICE4']) ? '&nbsp;<span class="errorMsg">'.$err['RBADDITIONALSERVICE4'].'</span>' : ''); ?>
					</td>
					<?php
					}
					?>
					<td class="smallFontWHTBG" nowrap <?php echo isset($err['RBIPADDRESS']) ? 'style="border:1px solid red;"' : ''; ?>>
						<?php echo (is_null($row['RBIPADDRESS']) ? '' : $row['RBIPADDRESS']).(isset($err['RBIPADDRESS']) ? '&nbsp;<span class="errorMsg">'.$err['RBIPADDRESS'].'</span>' : ''); ?>
					</td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($err['RBMULTISTATIC']) ? 'style="border:1px solid red;"' : ''; ?>>
						<?php echo (is_null($row['RBMULTISTATIC']) ? '' : $row['RBMULTISTATIC']).(isset($err['RBMULTISTATIC']) ? '&nbsp;<span class="errorMsg">'.$err['RBMULTISTATIC'].'</span>' : ''); ?>
					</td>
					<!--
					<td class="smallFontWHTBG" nowrap><?php //echo $row[13]; ?></td>
					-->
				</tr>
				<?php
				}
				?>
				<tr>
					<td colspan="<?php echo $useIPv6 ? '13' : '12'; ?>"><?php echo (count($ipNetError) == 0 ? 'No' : count($ipNetError)).' Record(s)'; ?></td>
				</tr>
			</tbody>
		</table>
		<br />
		<?php
				}
				if (count($npmError) != 0) {
		?>
		<br />
		<form action="<?php echo base_url('subscribers/processBulkUpdateSubscribersAdmin'); ?>" method="POST">
			<input type="hidden" name="step" value="download" />
			<input type="hidden" name="set" value="npmError" />
			<input type="hidden" name="realm" value="<?php echo $realm; ?>" />
			<input type="hidden" name="path" value="<?php echo $path; ?>" />
			<input type="hidden" name="setdata" value="<?php echo serialize($npmErrorRowNumbers); ?>" />
			<span class="errorMsg">RM: The following records were not modified because of invalid data or connection problems.</span>
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
					<!--
					<td class="smallFontGRYBG" align="left" nowrap>Addt'l Service 1</td>
					<td class="smallFontGRYBG" align="left" nowrap>Addt'l Service 2</td>
					-->
					<?php
					if ($useIPv6) {
					?>
					<td class="smallFontGRYBG" align="left" nowrap>IPv6 Address/Cabinet Name</td>
					<?php
					}
					?>
					<td class="smallFontGRYBG" align="left" nowrap>IP Address/Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>Network Address/Cabinet Name</td>
					<!--
					<td class="smallFontGRYBG" align="left" nowrap>Remarks</td>
					-->
				</tr>
				<?php
				for ($i = 0; $i < count($npmError); $i++) {
					$row = $npmError[$i]['rowdata2'];
					$err = $npmError[$i]['errors'];
				?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1).(isset($err) ? '&nbsp;<span class="errorMsg">'.$err.'</span>' : ''); ?></td>
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
					<!--
					<td class="smallFontWHTBG" nowrap><?php //echo $row[11]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php //echo $row[12]; ?></td>
					-->
					<?php
					if ($useIPv6) {
					?>
					<td class="smallFontWHTBG" nowrap><?php echo is_null($row['RBADDITIONALSERVICE4']) ? '' : $row['RBADDITIONALSERVICE4']; ?></td>
					<?php
					}
					?>
					<td class="smallFontWHTBG" nowrap><?php echo is_null($row['RBIPADDRESS']) ? '' : $row['RBIPADDRESS']; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo is_null($row['RBMULTISTATIC']) ? '' : $row['RBMULTISTATIC']; ?></td>
					<!--
					<td class="smallFontWHTBG" nowrap><?php //echo $row[13]; ?></td>
					-->
				</tr>
				<?php
				}
				?>
				<tr>
					<td colspan="<?php echo $useIPv6 ? '13' : '12'; ?>"><?php echo (count($npmError) == 0 ? 'No' : count($npmError)).' Record(s)'; ?></td>
				</tr>
			</tbody>
		</table>
		<br />
		<?php
				}
				if (count($deleted) != 0) {
		?>
		<br />
		<form action="<?php  ?>" method="POST">
			<input type="hidden" name="step" value="download" />
			<input type="hidden" name="set" value="deleted" />
			<input type="hidden" name="file" value="<?php echo $deletedFile; ?>" />
			<span class="errorMsg">The following records were deleted.</span>
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
					<td class="smallFontGRYBG" align="left" nowrap>IPv6 Address/Cabinet Name</td>
					<?php
					}
					?>
					<td class="smallFontGRYBG" align="left" nowrap>IP Address/Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>Network Address/Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>Remarks</td>
				</tr>
				<?php
				for ($i = 0; $i < count($deleted); $i++) {
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
				?><tr>
					<td colspan="14"><?php echo (count($deleted) == 0 ? 'No' : count($deleted)).' Record(s)'; ?></td>
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
		var proceed = '<?php echo isset($proceed) ? ($proceed ? 1 : 0) : 0; ?>';
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