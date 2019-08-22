<!DOCTYPE html>
<html lang="en">
<head>
	<title>Globelines Universal Access</title>
	<?php require 'head_includes.php'; ?>
</head>
<body style="padding-right:20px; padding-bottom:20px">
	<div align="left">
		<h3 class="style1">Primary Account: Bulk Load</h3>
	</div>
	<div align="right">
		<a href="<?php echo base_url('subscribers/showCreateSubscriberForm'); ?>">Back</a>
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
		<form id="bulkUploadForm" action="<?php echo base_url('subscribers/processBulkLoadSubscribers'); ?>" method="POST" enctype="multipart/form-data">
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
		<form id="bulkConfirmForm" action="<?php echo base_url('subscribers/processBulkLoadSubscribers'); ?>" method="POST">
			<input type="hidden" name="realm" value="<?php echo $realm; ?>" />
			<input type="hidden" name="path" value="<?php echo $path; ?>" />
			<!-- Edited Spelling for ValidRowNumbers 6/24/2019 -->
			<input type="hidden" name="validRowNumbers" value="<?php echo serialize($validRowNumbers); ?>" />
			<input type="hidden" name="invalidRowNumbers" value="<?php echo serialize($invalidRowNumbers); ?>" />
			<input type="hidden" name="step" value="create" />
			<?php
			if (count($valid) != 0) {
			?>
			The system will attempt to create the following records. Press OK to start creation. 
			<input type="submit" class="button2" name="confirm_result" value="OK" />
			<?php
			} else {
			?>
			There are no valid records to be created.
			<?php
			}
			?>
		</form>
		<table cellspacing="1" cellpadding='3' style="background-color:#cccccc;" border="0" width="100%">
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
					<td class="smallFontGRYBG" align="left" nowrap>IPv6 Address /<br />Cabinet Name</td>
					<?php
					}
					?>
					<td class="smallFontGRYBG" align="left" nowrap>IP Address /<br />Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>Network Address /<br />Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>Remarks</td>
				</tr>
				<?php
				for ($i = 0; $i < count($valid); $i++) {
					$row = $valid[$i];
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
					<td class="smallFontWHTBG" nowrap><?php echo $useIPv6 ? $row[12] : $row[11]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $useIPv6 ? $row[13] : $row[12]; ?></td>
					<?php
					if ($useIPv6) {
					?>
					<td class="smallFontWHTBG" nowrap><?php echo $row[9]; ?></td>
					<?php
					}
					?>
					<td class="smallFontWHTBG" nowrap><?php echo $useIPv6 ? $row[10] : $row[9]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $useIPv6 ? $row[11] : $row[10]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $useIPv6 ? $row[14] : $row[13]; ?></td>
				</tr>
				<?php
				}
				?>
				<tr>
					<td colspan="<?php echo $useIPv6 ? '16' : '15'; ?>"><?php echo (count($valid) == 0 ? 'No' : count($valid)).' Record(s)'; ?></td>
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
					<td class="smallFontGRYBG" align="left" nowrap>Addt'l Service 1</td>
					<td class="smallFontGRYBG" align="left" nowrap>Addt'l Service 2</td>
					<?php
					if ($useIPv6) {
					?>
					<td class="smallFontGRYBG" align="left" nowrap>IPv6 Address /<br />Cabinet Name</td>
					<?php
					}
					?>
					<td class="smallFontGRYBG" align="left" nowrap>IP Address /<br />Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>Network Address /<br />Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>Remarks</td>
				</tr>
				<?php
				for ($i = 0; $i < count($invalid); $i++) {
					$row = $invalid[$i]['rowdata'];
					$err = $invalid[$i]['errors'];
				?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($err['USERNAME']) ? 'style="border:1px solid red;"' : ''; ?>><?php echo $row[0]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[1]; ?></td>
					<!-- <td class="smallFontWHTBG" nowrap><?php //echo $row[2]; ?></td> -->
					<td class="smallFontWHTBG" nowrap <?php echo isset($err['CUSTOMERSTATUS']) ? 'style="border:1px solid red;"' : ''; ?>><?php echo $row[3]; ?></td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($err['RBCUSTOMERNAME']) ? 'style="border:1px solid red;"' : ''; ?>><?php echo $row[5]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[4]; ?></td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($err['RBSERVICENUMBER']) ? 'style="border:1px solid red;"' : ''; ?>><?php echo $row[6]; ?></td>
					<!-- <td class="smallFontWHTBG" nowrap <?php //echo isset($err['RBENABLED']) ? 'style="border:1px solid red;"' : ''; ?>><?php //echo $row[7]; ?></td>
					<td class="smallFontWHTBG" nowrap <?php //echo isset($err['RBACCOUNTPLAN']) ? 'style="border:1px solid red;"' : ''; ?>>
						<?php //echo str_replace('~', '-', $row[8]); ?>
					</td> -->
					<td class="smallFontWHTBG" nowrap><?php echo $useIPv6 ? $row[12] : $row[11]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $useIPv6 ? $row[13] : $row[12]; ?></td>
					<?php
					if ($useIPv6) {
					?>
					<td class="smallFontWHTBG" nowrap><?php echo $row[9]; ?></td>
					<?php
					}
					?>
					<td class="smallFontWHTBG" nowrap><?php echo $useIPv6 ? $row[10] : $row[9]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $useIPv6 ? $row[11] : $row[10]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $useIPv6 ? $row[14] : $row[13]; ?></td>
				</tr>
				<?php
				}
				?>
				<tr>
					<td colspan="<?php echo $useIPv6 ? '16' : '15'; ?>"><?php echo (count($invalid) == 0 ? 'No' : count($invalid)).' Record(s)'; ?></td>
				</tr>
			</tbody>
		</table>
		<br />
		<?php
				}
			} else if (!is_null($step) && $step == 'create') {
		?>
		<form action="<?php echo base_url('subscribers/processBulkLoadSubscribers'); ?>" method="POST">
			<input type="hidden" name="step" value="download" />
			<input type="hidden" name="set" value="created" />
			<input type="hidden" name="realm" value="<?php echo $realm; ?>" />
			<input type="hidden" name="path" value="<?php echo $path; ?>" />
			<input type="hidden" name="setdata" value="<?php echo serialize($createdRowNumbers); ?>" />
			<input type="hidden" name="pw" value="<?php echo $createdPasswords; ?>" />
			<?php
			if (count($created) != 0) {
			?>
			<span class="msg">The following records were created.</span>
			<input type="submit" class="button2" name="confirm_result" value="extract" />
			<?php
			} else {
			?>
			<span class="msg">There were no records created.</span>
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
					<td class="smallFontGRYBG" align="left" nowrap>IPv6 Address /<br />Cabinet Name</td>
					<?php
					}
					?>
					<td class="smallFontGRYBG" align="left" nowrap>IP Address /<br />Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>Network Address /<br />Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>Remarks</td>
				</tr>
				<?php
				for ($i = 0; $i < count($created); $i++) {
					$row = $created[$i];
				?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[0]; ?></td>
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
					<td class="smallFontWHTBG" nowrap><?php echo $useIPv6 ? $row[12] : $row[11]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $useIPv6 ? $row[13] : $row[12]; ?></td>
					<?php
					if ($useIPv6) {
					?>
					<td class="smallFontWHTBG" nowrap><?php echo $row[9]; ?></td>
					<?php
					}
					?>
					<td class="smallFontWHTBG" nowrap><?php echo $useIPv6 ? $row[10] : $row[9]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $useIPv6 ? $row[11] : $row[10]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $useIPv6 ? $row[14] : $row[13]; ?></td>
				</tr>
				<?php
				}
				?>
				<tr>
					<td colspan="<?php echo $useIPv6 ? '16' : '15'; ?>"><?php echo (count($created) == 0 ? 'No' : count($created)).' Record(s)'; ?></td>
				</tr>
			</tbody>
		</table>
		<br/>
		<?php
				if (count($invalidData) != 0) {
		?>
		<br />
		<form action="<?php echo base_url('subscribers/processBulkLoadSubscribers'); ?>" method="POST">
			<input type="hidden" name="step" value="download" />
			<input type="hidden" name="set" value="invalidData" />
			<input type="hidden" name="realm" value="<?php echo $realm; ?>" />
			<input type="hidden" name="path" value="<?php echo $path; ?>" />
			<input type="hidden" name="setdata" value="<?php echo serialize($invalidDataRowNumbers); ?>" />
			<span class="errorMsg">The following records were not created because of invalid and/or incorrect data.</span>
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
					<td class="smallFontGRYBG" align="left" nowrap>IPv6 Address /<br />Cabinet Name</td>
					<?php
					}
					?>
					<td class="smallFontGRYBG" align="left" nowrap>IP Address /<br />Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>Network Address /<br />Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>Remarks</td>
				</tr>
				<?php
				for ($i = 0; $i < count($invalidData); $i++) {
					$row = $invalidData[$i]['rowdata'];
					$err = $invalidData[$i]['errors'];
				?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($err['USERNAME']) ? 'style="border:1px solid red;"' : ''; ?>>
						<?php echo $row[0].(isset($err['USERNAME']) ? '&nbsp;<span class="errorMsg">'.$err['USERNAME'].'</span>' : ''); ?>
					</td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($err['PASSWORD']) ? 'style="border:1px solid red;"' : ''; ?>>
						<?php echo $row[1].(isset($err['PASSWORD']) ? '&nbsp;<span class="errorMsg">'.$err['PASSWORD'].'</span>' : ''); ?>
					</td>
					<!-- <td class="smallFontWHTBG" nowrap <?php /*echo isset($err['CUSTOMERTYPE']) ? 'style="border:1px solid red;"' : ''; ?>>
						<?php
						if ($row[2] == 'Residential' || $row[2] == 'R') {
							echo 'R';
						} else if ($row[2] == 'Business' || $row[2] == 'B') {
							echo 'B';
						}
						echo (isset($err['CUSTOMERTYPE']) ? '&nbsp;<span class="errorMsg">'.$err['CUSTOMERTYPE'].'</span>' : ''); 
						*/?>
					</td> -->
					<td class="smallFontWHTBG" nowrap <?php echo isset($err['CUSTOMERSTATUS']) ? 'style="border:1px solid red;"' : ''; ?>>
						<?php echo $row[3].(isset($err['CUSTOMERSTATUS']) ? '&nbsp;<span class="errorMsg">'.$err['CUSTOMERSTATUS'].'</span>' : ''); ?>
					</td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($err['RBCUSTOMERNAME']) ? 'style="border:1px solid red;"' : ''; ?>>
						<?php echo $row[5].(isset($err['RBCUSTOMERNAME']) ? '&nbsp;<span class="errorMsg">'.$err['RBCUSTOMERNAME'].'</span>' : ''); ?>
					</td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($err['RBORDERNUMBER']) ? 'style="border:1px solid red;"' : ''; ?>>
						<?php echo $row[4].(isset($err['RBORDERNUMBER']) ? '&nbsp;<span class="errorMsg">'.$err['RBORDERNUMBER'].'</span>' : ''); ?>
					</td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($err['RBSERVICENUMBER']) ? 'style="border:1px solid red;"' : ''; ?>>
						<?php echo $row[6].(isset($err['RBSERVICENUMBER']) ? '&nbsp;<span class="errorMsg">'.$err['RBSERVICENUMBER'].'</span>' : ''); ?>
					</td>
					<!-- <td class="smallFontWHTBG" nowrap <?php /* echo isset($err['RBENABLED']) ? 'style="border:1px solid red;"' : ''; ?>>
						<?php
						if ($row[7] == 'Yes' || $row[7] == 'Y') {
							echo 'Y';
						} else if ($row[7] == 'No' || $row[7] == 'N') {
							echo 'N';
						}
						echo (isset($err['RBENABLED']) ? '&nbsp;<span class="errorMsg">'.$err['RBENABLED'].'</span>' : '');
						?>
					</td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($err['RADIUSPOLICY']) ? 'style="border:1px solid red;"' : ''; ?>>
						<?php echo str_replace('~', '-', $row[8]).(isset($err['RADIUSPOLICY']) ? '&nbsp;<span class="errorMsg">'.$err['RADIUSPOLICY'].'</span>' : ''); */?>
					</td> -->
					<td class="smallFontWHTBG" nowrap><?php echo $useIPv6 ? $row[12] : $row[11]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $useIPv6 ? $row[13] : $row[12]; ?></td>
					<?php
					if ($useIPv6) {
					?>
					<td class="smallFontWHTBG" nowrap <?php echo isset($err['RBADDITIONALSERVICE4']) ? 'style="border:1px solid red;"' : ''; ?>>
						<?php echo $row[9].(isset($err['RBADDITIONALSERVICE4']) ? '&nbsp;<span class="errorMsg">'.$err['RBADDITIONALSERVICE4'].'</span>' : ''); ?>
					</td>
					<?php
					}
					?>
					<td class="smallFontWHTBG" nowrap <?php echo isset($err['RBIPADDRESS']) ? 'style="border:1px solid red;"' : ''; ?>>
						<?php echo ($useIPv6 ? $row[10] : $row[9]).(isset($err['RBIPADDRESS']) ? '&nbsp;<span class="errorMsg">'.$err['RBIPADDRESS'].'</span>' : ''); ?>
					</td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($err['RBMULTISTATIC']) ? 'style="border:1px solid red;"' : ''; ?>>
						<?php echo ($useIPv6 ? $row[11] : $row[10]).(isset($err['RBMULTISTATIC']) ? '&nbsp;<span class="errorMsg">'.$err['RBMULTISTATIC'].'</span>' : ''); ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $useIPv6 ? $row[14] : $row[13]; ?></td>
				</tr>
				<?php
				}
				?>
				<tr>
					<td colspan="<?php echo $useIPv6 ? '16' : '15'; ?>"><?php echo (count($invalidData) == 0 ? 'No' : count($invalidData)).' Record(s)'; ?></td>
				</tr>
			</tbody>
		</table>
		<br />
		<?php
				}
				if (count($usernameExists) != 0) {
		?>
		<br />
		<form action="<?php echo base_url('subscribers/processBulkLoadSubscribers'); ?>" method="POST">
			<input type="hidden" name="step" value="download" />
			<input type="hidden" name="set" value="usernameExists" />
			<input type="hidden" name="realm" value="<?php echo $realm; ?>" />
			<input type="hidden" name="path" value="<?php echo $path; ?>" />
			<input type="hidden" name="setdata" value="<?php echo serialize($usernameExistsRowNumbers); ?>" />
			<span class="errorMsg">The following records were not created because their usernames already exist.</span>
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
					<td class="smallFontGRYBG" align="left" nowrap>IPv6 Address /<br />Cabinet Name</td>
					<?php
					}
					?>
					<td class="smallFontGRYBG" align="left" nowrap>IP Address /<br />Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>Network Address /<br />Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>Remarks</td>
				</tr>
				<?php
				for ($i = 0; $i < count($usernameExists); $i++) {
					$row = $usernameExists[$i];
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
					<td class="smallFontWHTBG" nowrap><?php echo $useIPv6 ? $row[12] : $row[11]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $useIPv6 ? $row[13] : $row[12]; ?></td>
					<?php
					if ($useIPv6) {
					?>
					<td class="smallFontWHTBG" nowrap><?php echo $row[9]; ?></td>
					<?php
					}
					?>
					<td class="smallFontWHTBG" nowrap><?php echo $useIPv6 ? $row[10] : $row[9]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $useIPv6 ? $row[11] : $row[10]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $useIPv6 ? $row[14] : $row[13]; ?></td>
				</tr>
				<?php
				}
				?>
				<tr>
					<td colspan="<?php echo $useIPv6 ? '16' : '15'; ?>"><?php echo (count($usernameExists) == 0 ? 'No' : count($usernameExists)).' Record(s)'; ?></td>
				</tr>
			</tbody>
		</table>
		<br />
		<?php
				}
				if (count($ipNetError) != 0) {
		?>
		<br />
		<form action="<?php echo base_url('subscribers/processBulkLoadSubscribers'); ?>" method="POST">
			<input type="hidden" name="step" value="download" />
			<input type="hidden" name="set" value="ipNetError" />
			<input type="hidden" name="realm" value="<?php echo $realm; ?>" />
			<input type="hidden" name="path" value="<?php echo $path; ?>" />
			<input type="hidden" name="setdata" value="<?php echo serialize($ipNetErrorRowNumbers); ?>" />
			<span class="errorMsg">The following records were not created because either the Static or Multi-Static IP is invalid or already used.</span>
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
					<td class="smallFontGRYBG" align="left" nowrap>IPv6 Address /<br />Cabinet Name</td>
					<?php
					}
					?>					
					<td class="smallFontGRYBG" align="left" nowrap>IP Address /<br />Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>Network Address /<br />Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>Remarks</td>
				</tr>
				<?php
				for ($i = 0; $i < count($ipNetError); $i++) {
					$row = $ipNetError[$i]['rowdata'];
					$err = $ipNetError[$i]['errors'];
				?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[0]; ?></td>
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
					<td class="smallFontWHTBG" nowrap><?php echo $useIPv6 ? $row[12] : $row[11]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $useIPv6 ? $row[13] : $row[12]; ?></td>
					<?php
					if ($useIPv6) {
					?>
					<td class="smallFontWHTBG" nowrap <?php echo isset($err['RBADDITIONALSERVICE4']) ? 'style="border:1px solid red;"' : ''; ?>>
						<?php echo $row[9].(isset($err['RBADDITIONALSERVICE4']) ? '&nbsp;<span class="errorMsg">'.$err['RBADDITIONALSERVICE4'].'</span>' : ''); ?>
					</td>
					<?php
					}
					?>
					<td class="smallFontWHTBG" nowrap <?php echo isset($err['RBIPADDRESS']) ? 'style="border:1px solid red;"' : ''; ?>>
						<?php echo ($useIPv6 ? $row[10] : $row[9]).(isset($err['RBIPADDRESS']) ? '&nbsp;<span class="errorMsg">'.$err['RBIPADDRESS'].'</span>' : ''); ?>
					</td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($err['RBMULTISTATIC']) ? 'style="border:1px solid red;"' : ''; ?>>
						<?php echo ($useIPv6 ? $row[11] : $row[10]).(isset($err['RBMULTISTATIC']) ? '&nbsp;<span class="errorMsg">'.$err['RBMULTISTATIC'].'</span>' : ''); ?>
					</td>
					<td class="smallFontWHTBG" nowrap><?php echo $useIPv6 ? $row[14] : $row[13]; ?></td>
				</tr>
				<?php
				}
				?>
				<tr>
					<td colspan="<?php echo $useIPv6 ? '16' : '15'; ?>"><?php echo (count($ipNetError) == 0 ? 'No' : count($ipNetError)).' Record(s)'; ?></td>
				</tr>
			</tbody>
		</table>
		<br />
		<?php
				}
				if (count($npmError) != 0) {
		?>
		<br />
		<form action="<?php echo base_url('subscribers/processBulkLoadSubscribers'); ?>" method="POST">
			<input type="hidden" name="step" value="download" />
			<input type="hidden" name="set" value="npmError" />
			<input type="hidden" name="realm" value="<?php echo $realm; ?>" />
			<input type="hidden" name="path" value="<?php echo $path; ?>" />
			<input type="hidden" name="setdata" value="<?php echo serialize($npmErrorRowNumbers); ?>" />
			<span class="errorMsg">RM: The following records were not created because of invalid data or connection problems.</span>
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
					<td class="smallFontGRYBG" align="left" nowrap>IPv6 Address /<br />Cabinet Name</td>
					<?php
					}
					?>
					<td class="smallFontGRYBG" align="left" nowrap>IP Address /<br />Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>Network Address /<br />Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>Remarks</td>
				</tr>
				<?php
				for ($i = 0; $i < count($npmError); $i++) {
					$row = $npmError[$i]['rowdata'];
					$err = $npmError[$i]['errors'];
				?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1).(isset($err) ? '&nbsp;<span class="errorMsg">'.$err.'</span>' : ''); ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[0]; ?></td>
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
					<td class="smallFontWHTBG" nowrap><?php echo $useIPv6 ? $row[12] : $row[11]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $useIPv6 ? $row[13] : $row[12]; ?></td>
					<?php
					if ($useIPv6) {
					?>
					<td class="smallFontWHTBG" nowrap><?php echo $row[9]; ?></td>
					<?php
					}
					?>
					<td class="smallFontWHTBG" nowrap><?php echo $useIPv6 ? $row[10] : $row[9]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $useIPv6 ? $row[11] : $row[10]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $useIPv6 ? $row[14] : $row[13]; ?></td>
				</tr>
				<?php
				}
				?>
				<tr>
					<td colspan="<?php echo $useIPv6 ? '16' : '15'; ?>"><?php echo (count($npmError) == 0 ? 'No' : count($npmError)).' Record(s)'; ?></td>
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
		var proceed = '<?php echo (isset($proceed) && $proceed) ? 1 : 0; ?>';
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