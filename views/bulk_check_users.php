<!DOCTYPE html>
<html lang="en">
<head>
	<title>Globelines Universal Access</title>
	<?php require 'head_includes.php'; ?>
</head>
<body style="padding-right:20px; padding-bottom:20px">
	<div align="left">
		<h3 class="style1">Primary Account: Bulk Check</h3>
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
		<form id="bulkUploadForm" action="<?php echo base_url('subscribers/processBulkCheckSubscribers'); ?>" method="POST" ENCTYPE="multipart/form-data">
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
			if (!is_null($step) && $step == 'show') {
		?>
		<form action="<?php echo base_url('subscribers/processBulkCheckSubscribers'); ?>" method="POST">
			<input type="hidden" name="step" value="download" />
			<input type="hidden" name="set" value="valid" />
			<input type="hidden" name="realm" value="<?php echo $realm; ?>" />
			<input type="hidden" name="path" value="<?php echo $path; ?>" />
			<input type="hidden" name="setdata" value="<?php echo serialize($validRowNumbers); ?>" />
			<?php
			if (count($valid) != 0) {
			?>
			<span class="msg">The following records can be created.</span>
			<input type="submit" class="button2" name="confirm_result" value="extract" />
			<?php
			} else {
			?>
			<span class="msg">There are no valid records that can be created.</span>
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
					<!-- Remove Column 5/17/19 -->
					<!-- <td class="smallFontGRYBG" align="left" nowrap>Customer Type</td> -->
					<td class="smallFontGRYBG" align="left" nowrap>Status</td>
					<td class="smallFontGRYBG" align="left" nowrap>Customer Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>Order Number</td>
					<td class="smallFontGRYBG" align="left" nowrap>Service Number</td>
					<!-- <td class="smallFontGRYBG" align="left" nowrap>Redirected</td> -->
					<!-- <td class="smallFontGRYBG" align="left" nowrap>Service</td> -->
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
					<td class="smallFontWHTBG" nowrap><?php echo $i + 1; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo is_null($row[0]) ? '' : $row[0]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo is_null($row[1]) ? '' : $row[1]; ?></td>
					<!-- <td class="smallFontWHTBG" nowrap>
						<?php/*
						if (isset($row[2]) && !is_null($row[2])) {
							if ($row[2] == 'Residential' || $row[2] == 'R') {
								echo 'R';
							} else if ($row[2] == 'Business' || $row[2] == 'B') {
								echo 'B';
							}
						} else {
							echo '';
						}
						*/?>
					</td> -->
					<td class="smallFontWHTBG" nowrap><?php echo is_null($row[3]) ? '' : $row[3]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo is_null($row[5]) ? '' : $row[5]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo is_null($row[4]) ? '' : $row[4]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo is_null($row[6]) ? '' : $row[6]; ?></td>
					<!-- <td class="smallFontWHTBG" nowrap>
						<?php /*
						if (isset($row[7]) && !is_null($row[7])) {
							if ($row[7] == 'Yes' || $row[7] == 'Y') {
								echo 'Y';
							} else if ($row[7] == 'No' || $row[7] == 'N') {
								echo 'N';
							}
						} else {
							echo '';
						}
						?>
					</td>
					<td class="smallFontWHTBG" nowrap><?php echo is_null($row[8]) ? '' : str_replace('~', '-', $row[8]); */?></td> -->
					<td class="smallFontWHTBG" nowrap>
						<?php
						if ($useIPv6) {
							echo is_null($row[12]) ? '' : $row[12];
						} else {
							echo is_null($row[11]) ? '' : $row[11];
						}
						?>
					</td>
					<td class="smallFontWHTBG" nowrap>
						<?php
						if ($useIPv6) {
							echo is_null($row[13]) ? '' : $row[13];
						} else {
							echo is_null($row[12]) ? '' : $row[12];
						}
						?>
					</td>
					<?php
					if ($useIPv6) {
					?>
					<td class="smallFontWHTBG" nowrap><?php echo is_null($row[9]) ? '' : $row[9]; ?></td>
					<?php
					}
					?>
					<td class="smallFontWHTBG" nowrap>
						<?php
						if ($useIPv6) {
							echo is_null($row[10]) ? '' : $row[10];
						} else {
							echo is_null($row[9]) ? '' : $row[9];
						}
						?>
					</td>
					<td class="smallFontWHTBG" nowrap>
						<?php
						if ($useIPv6) {
							echo is_null($row[11]) ? '' : $row[11];
						} else {
							echo is_null($row[10]) ? '' : $row[10];
						}
						?>
					</td>
					<td class="smallFontWHTBG" nowrap>
						<?php
						if ($useIPv6) {
							echo is_null($row[14]) ? '' : $row[14];
						} else {
							echo is_null($row[13]) ? '' : $row[13];
						}
						?>
					</td>
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
				if (count($invalid) > 0) {
		?>
		<br />
		<form action="<?php echo base_url('subscribers/processBulkCheckSubscribers'); ?>" method="POST">
			<input type="hidden" name="step" value="download" />
			<input type="hidden" name="set" value="invalid" />
			<input type="hidden" name="realm" value="<?php echo $realm; ?>" />
			<input type="hidden" name="path" value="<?php echo $path; ?>" />
			<input type="hidden" name="setdata" value="<?php echo serialize($invalidRowNumbers); ?>" />
			<span class="errorMsg">The following records have missing fields.</span>
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
				for ($i = 0; $i < count($invalid); $i++) {
					$row = $invalid[$i]['rowdata'];
					$err = $invalid[$i]['errors'];
				?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo $i + 1; ?></td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($err['USERNAME']) ? 'style="border:1px solid red;"' : ''; ?>>
						<?php echo is_null($row[0]) ? '' : $row[0]; ?>
					</td>
					<td class="smallFontWHTBG" nowrap><?php echo is_null($row[1]) ? '' : $row[1]; ?></td>
					<!-- <td class="smallFontWHTBG" nowrap <?php //echo isset($err['CUSTOMERTYPE']) ? 'style="border:1px solid red;"' : ''; ?>>
						<?php /*
						if (isset($row[2]) && !is_null($row[2])) {
							if ($row[2] == 'Residential' || $row[2] == 'R') {
								echo 'R';
							} else if ($row[2] == 'Business' || $row[2] == 'B') {
								echo 'B';
							}
						} else {
							echo '';
						}
						*/?>
					</td> -->
					<td class="smallFontWHTBG" nowrap <?php echo isset($err['CUSTOMERSTATUS']) ? 'style="border:1px solid red;"' : ''; ?>><?php echo is_null($row[3]) ? '' : $row[3]; ?></td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($err['RBCUSTOMERNAME']) ? 'style="border:1px solid red;"' : ''; ?>><?php echo is_null($row[5]) ? '' : $row[5]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo is_null($row[4]) ? '' : $row[4]; ?></td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($err['RBSERVICENUMBER']) ? 'style="border:1px solid red;"' : ''; ?>><?php echo is_null($row[6]) ? '' : $row[6]; ?></td>
					<!-- <td class="smallFontWHTBG" nowrap <?php //echo isset($err['RBENABLED']) ? 'style="border:1px solid red;"' : ''; ?>>
						<?php /*
						if (isset($row[7]) && !is_null($row[7])) {
							if ($row[7] == 'Yes' || $row[7] == 'Y') {
								echo 'Y';
							} else if ($row[7] == 'No' || $row[7] == 'N') {
								echo 'N';
							}
						} else {
							echo '';
						}
						?>
					</td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($err['RBACCOUNTPLAN']) ? 'style="border:1px solid red;"' : ''; ?>>
						<?php echo is_null($row[8]) ? '' : str_replace('~', '-', $row[8]); */?>
					</td> -->
					<td class="smallFontWHTBG" nowrap>
						<?php
						if ($useIPv6) {
							echo is_null($row[12]) ? '' : $row[12];
						} else {
							echo is_null($row[11]) ? '' : $row[11];
						}
						?>
					</td>
					<td class="smallFontWHTBG" nowrap>
						<?php
						if ($useIPv6) {
							echo is_null($row[13]) ? '' : $row[13];
						} else {
							echo is_null($row[12]) ? '' : $row[12];
						}
						?>
					</td>
					<?php
					if ($useIPv6) {
					?>
					<td class="smallFontWHTBG" nowrap><?php echo is_null($row[9]) ? '' : $row[9]; ?></td>
					<?php
					}
					?>
					<td class="smallFontWHTBG" nowrap>
						<?php
						if ($useIPv6) {
							echo is_null($row[10]) ? '' : $row[10];
						} else {
							echo is_null($row[9]) ? '' : $row[9];
						}
						?>
					</td>
					<td class="smallFontWHTBG" nowrap>
						<?php
						if ($useIPv6) {
							echo is_null($row[11]) ? '' : $row[11];
						} else {
							echo is_null($row[10]) ? '' : $row[10];
						}
						?>
					</td>
					<td class="smallFontWHTBG" nowrap>
						<?php
						if ($useIPv6) {
							echo is_null($row[14]) ? '' : $row[14];
						} else {
							echo is_null($row[13]) ? '' : $row[13];
						}
						?>
					</td>
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
				if (count($variousErrors) > 0) {
		?>
		<br />
		<form action="<?php echo base_url('subscribers/processBulkCheckSubscribers'); ?>" method="POST">
			<input type="hidden" name="step" value="download" />
			<input type="hidden" name="set" value="various" />
			<input type="hidden" name="realm" value="<?php echo $realm; ?>" />
			<input type="hidden" name="path" value="<?php echo $path; ?>" />
			<input type="hidden" name="setdata" value="<?php echo serialize($variousErrorsRowNumbers); ?>" />
			<span class="errorMsg">The following records have missing/erroneous data.</span>
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
				for ($i = 0; $i < count($variousErrors); $i++) {
					$row = $variousErrors[$i]['rowdata'];
					$err = $variousErrors[$i]['errors'];
				?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo $i + 1; ?></td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($err['USERNAME']) ? 'style="border:1px solid red;"' : ''; ?>>
						<?php echo (is_null($row[0]) ? '' : $row[0]).(isset($err['USERNAME']) ? '&nbsp;<span class="errorMsg">'.$err['USERNAME'].'</span>' : ''); ?>
					</td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($err['PASSWORD']) ? 'style="border:1px solid red;"' : ''; ?>>
						<?php echo (is_null($row[1]) ? '' : $row[1]).(isset($err['PASSWORD']) ? '&nbsp;<span class="errorMsg">'.$err['PASSWORD'].'</span>' : ''); ?>
					</td>
					<!-- <td class="smallFontWHTBG" nowrap <?php //echo isset($err['CUSTOMERTYPE']) ? 'style="border:1px solid red;"' : ''; ?>>
						<?php /*
						if (isset($row[2]) && !is_null($row[2])) {
							if ($row[2] == 'Residential' || $row[2] == 'R') {
								echo 'R';
							} else if ($row[2] == 'Business' || $row[2] == 'B') {
								echo 'B';
							}
						} else {
							echo '';
						}
						echo isset($err['CUSTOMERTYPE']) ? '&nbsp;<span class="errorMsg">'.$err['CUSTOMERTYPE'].'</span>' : '';
						*/?>
					</td> -->
					<td class="smallFontWHTBG" nowrap <?php echo isset($err['CUSTOMERSTATUS']) ? 'style="border:1px solid red;"' : ''; ?>>
						<?php echo (is_null($row[3]) ? '' : $row[3]).(isset($err['CUSTOMERSTATUS']) ? '&nbsp;<span class="errorMsg">'.$err['CUSTOMERSTATUS'].'</span>' : ''); ?>
					</td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($err['RBCUSTOMERNAME']) ? 'style="border:1px solid red;"' : ''; ?>>
						<?php echo (is_null($row[5]) ? '' : $row[5]).(isset($err['RBCUSTOMERNAME']) ? '&nbsp;<span class="errorMsg">'.$err['RBCUSTOMERNAME'].'</span>' : ''); ?>
					</td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($err['RBORDERNUMBER']) ? 'style="border:1px solid red;"' : ''; ?>>
						<?php echo (is_null($row[4]) ? '' : $row[4]).(isset($err['RBORDERNUMBER']) ? '&nbsp;<span class="errorMsg">'.$err['RBORDERNUMBER'].'</span>' : ''); ?>
					</td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($err['RBSERVICENUMBER']) ? 'style="border:1px solid red;"' : ''; ?>>
						<?php echo (is_null($row[6]) ? '' : $row[6]).(isset($err['RBSERVICENUMBER']) ? '&nbsp;<span class="errorMsg">'.$err['RBSERVICENUMBER'].'</span>' : ''); ?>
					</td>
					<!-- <td class="smallFontWHTBG" nowrap <?php //echo isset($err['RBENABLED']) ? 'style="border:1px solid red;"' : ''; ?>>
						<?php /*
						if (isset($row[7]) && !is_null($row[7])) {
							if ($row[7] == 'Yes' || $row[7] == 'Y') {
								echo 'Y';
							} else if ($row[7] == 'No' || $row[7] == 'N') {
								echo 'N';
							}
						} else {
							echo '';
						}
						echo isset($err['RBENABLED']) ? '&nbsp;<span class="errorMsg">'.$err['RBENABLED'].'</span>' : '';
						?>
					</td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($err['RBACCOUNTPLAN']) ? 'style="border:1px solid red;"' : ''; ?>>
						<?php echo (is_null($row[8]) ? '' : str_replace('~', '-', $row[8])).(isset($err['RBACCOUNTPLAN']) ? '&nbsp;<span class="errorMsg">'.$err['RBACCOUNTPLAN'].'</span>' : ''); */?>
					</td> -->
					<td class="smallFontWHTBG" nowrap>
						<?php
						if ($useIPv6) {
							echo is_null($row[12]) ? '' : $row[12];
						} else {
							echo is_null($row[11]) ? '' : $row[11];
						}
						?>
					</td>
					<td class="smallFontWHTBG" nowrap>
						<?php
						if ($useIPv6) {
							echo is_null($row[13]) ? '' : $row[13];
						} else {
							echo is_null($row[12]) ? '' : $row[12];
						}
						?>
					</td>
					<?php
					if ($useIPv6) {
					?>
					<td class="smallFontWHTBG" nowrap <?php echo isset($err['RBADDITIONALSERVICE4']) ? 'style="border:1px solid red;"' : ''; ?>>
						<?php echo (is_null($row[9]) ? '' : $row[9]).(isset($err['RBADDITIONALSERVICE4']) ? '&nbsp;<span class="errorMsg">'.$err['RBADDITIONALSERVICE4'].'</span>' : ''); ?>
					</td>
					<?php
					}
					?>
					<td class="smallFontWHTBG" nowrap <?php echo isset($err['RBIPADDRESS']) ? 'style="border:1px solid red;"' : ''; ?>>
						<?php
						if ($useIPv6) {
							echo (is_null($row[10]) ? '' : $row[10]).(isset($err['RBIPADDRESS']) ? '&nbsp;<span class="errorMsg">'.$err['RBIPADDRESS'].'</span>' : '');
						} else {
							echo (is_null($row[9]) ? '' : $row[9]).(isset($err['RBIPADDRESS']) ? '&nbsp;<span class="errorMsg">'.$err['RBIPADDRESS'].'</span>' : '');
						}
						?>
					</td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($err['RBMULTISTATIC']) ? 'style="border:1px solid red;"' : ''; ?>>
						<?php
						if ($useIPv6) {
							echo (is_null($row[11]) ? '' : $row[11]).(isset($err['RBMULTISTATIC']) ? '&nbsp;<span class="errorMsg">'.$err['RBMULTISTATIC'].'</span>' : '');
						} else {
							echo (is_null($row[10]) ? '' : $row[10]).(isset($err['RBMULTISTATIC']) ? '&nbsp;<span class="errorMsg">'.$err['RBMULTISTATIC'].'</span>' : '');
						}
						?>
					</td>
					<td class="smallFontWHTBG" nowrap>
						<?php
						if ($useIPv6) {
							echo is_null($row[14]) ? '' : $row[14];
						} else {
							echo is_null($row[13]) ? '' : $row[13];
						}
						?>
					</td>
				</tr>
				<?php
				}
				?>
				<tr>
					<td colspan="<?php echo $useIPv6 ? '16' : '15'; ?>"><?php echo (count($variousErrors) == 0 ? 'No' : count($variousErrors)).' Record(s)'; ?></td>
				</tr>
			</tbody>
		</table>
		<br />
		<?php
				}
				if (count($existing) > 0) {
		?>
		<br />
		<form action="<?php echo base_url('subscribers/processBulkCheckSubscribers'); ?>" method="POST">
			<input type="hidden" name="step" value="download" />
			<input type="hidden" name="set" value="existing" />
			<input type="hidden" name="realm" value="<?php echo $realm; ?>" />
			<input type="hidden" name="path" value="<?php echo $path; ?>" />
			<input type="hidden" name="setdata" value="<?php echo serialize($existingRowNumbers); ?>" />
			<span class="errorMsg">The following entries have usernames that already exist in the database.</span>
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
					<?php
					if ($useIPv6) {
					?>
					<td class="smallFontGRYBG" align="left" nowrap>IPv6 Address</td>
					<?php
					}
					?>
					<td class="smallFontGRYBG" align="left" nowrap>IP Address</td>
					<td class="smallFontGRYBG" align="left" nowrap>Network Adress</td>
					<td class="smallFontGRYBG" align="left" nowrap>Addt'l Service 1</td>
					<td class="smallFontGRYBG" align="left" nowrap>Addt'l Service 2</td>
					<td class="smallFontGRYBG" align="left" nowrap>Remarks</td>
				</tr>
				<?php
				for ($i = 0; $i < count($existing); $i++) {
					$row = $existing[$i];
				?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo $i + 1; ?></td>
					<td class="smallFontWHTBG" nowrap style="border:1px solid red;"><?php echo $row['USER_IDENTITY']; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo is_null($row['PASSWORD']) ? '' : $row['PASSWORD']; ?></td>
					<!-- <td class="smallFontWHTBG" nowrap>
						<?php /*
						if (isset($row['CUSTOMERTYPE']) && !is_null($row['CUSTOMERTYPE'])) {
							if ($row['CUSTOMERTYPE'] == 'Residential' || $row['CUSTOMERTYPE'] == 'R') {
								echo 'R';
							} else if ($row['CUSTOMERTYPE'] == 'Business' || $row['CUSTOMERTYPE'] == 'B') {
								echo 'B';
							}
						} else {
							echo '';
						}
						*/?>
					</td> -->
					<td class="smallFontWHTBG" nowrap><?php echo is_null($row['CUSTOMERSTATUS']) ? '' : ($row['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D'); ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo is_null($row['RBCUSTOMERNAME']) ? '' : $row['RBCUSTOMERNAME']; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo is_null($row['RBORDERNUMBER']) ? '' : $row['RBORDERNUMBER']; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo is_null($row['RBSERVICENUMBER']) ? '' : $row['RBSERVICENUMBER']; ?></td>
					<!-- <td class="smallFontWHTBG" nowrap>
						<?php /*
						if (isset($row['RBENABLED']) && !is_null($row['RBENABLED'])) {
							if ($row['RBENABLED'] == 'Yes' || $row['RBENABLED'] == 'Y') {
								echo 'Y';
							} else if ($row['RBENABLED'] == 'No' || $row['RBENABLED'] == 'N') {
								echo 'N';
							}
						} else {
							echo '';
						}
						?>
					</td>
					<td class="smallFontWHTBG" nowrap><?php echo is_null($row['RBACCOUNTPLAN']) ? '' : str_replace('~', '-', $row['RBACCOUNTPLAN']); */?></td> -->
					<?php
					if ($useIPv6) {
					?>
					<td class="smallFontWHTBG" nowrap><?php echo is_null($row['RBADDITIONALSERVICE4']) ? '' : $row['RBADDITIONALSERVICE4']; ?></td>
					<?php
					}
					?>
					<td class="smallFontWHTBG" nowrap><?php echo is_null($row['RBIPADDRESS']) ? '' : $row['RBIPADDRESS']; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo is_null($row['RBMULTISTATIC']) ? '' : $row['RBMULTISTATIC']; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo is_null($row['RBADDITIONALSERVICE1']) ? '' : $row['RBADDITIONALSERVICE1']; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo is_null($row['RBADDITIONALSERVICE2']) ? '' : $row['RBADDITIONALSERVICE2']; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo is_null($row['RBREMARKS']) ? '' : substr($row['RBREMARKS'], 0, 100); ?></td>
				</tr>
				<?php
				}
				?>
				<tr>
					<td colspan="<?php echo $useIPv6 ? '16' : '15'; ?>"><?php echo (count($existing) == 0 ? 'No' : count($existing)).' Record(s)'; ?></td>
				</tr>
			</tbody>
		</table>
		<br />
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
		});
	</script>
</body>
</html>