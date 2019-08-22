<!DOCTYPE html>
<html lang="en">
<head>
	<title>Globelines Universal Access</title>
	<?php require 'head_includes.php'; ?>
</head>
<body style="padding-right:20px; padding-bottom:20px">
	<div align="left">
		<h3 class="style1">IP Addresses: Bulk Seeding</h3>
	</div>
	<div class="smallFontB" align="right">
		<a href="<?php echo base_url('main/showIpaddressesIndex/1'); ?>">Back to index</a>
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
		<form action="<?php echo base_url('main/bulkIPSeedingProcess'); ?>" method="POST" enctype="multipart/form-data">
			<table>
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
		<form action="" method="POST">
			<input type="hidden" name="path" value="<?php echo $path; ?>" />
			<input type="hidden" name="validRowNumbers" value="<?php echo serialize($vaildRowNumbers); ?>" />
			<input type="hidden" name="invalidRowNumbers" value="<?php echo serialize($invalidRowNumbers); ?>" />
			<input type="hidden" name="step" value="create" />
			The system will attempt update with the following records. Press Ok to start update.
			<input type="submit" class="button2" name="confirm_result" value="OK" />
		</form>
		<table cellspacing="1" cellpadding='3' style="background-color:#cccccc;" border="0" width="100%">
			<tbody>
				<tr>
					<td class="smallFontGRYBG" align="left" nowrap>&nbsp;</td>
					<td class="smallFontGRYBG" align="left" nowrap>IP Address</td>
					<td class="smallFontGRYBG" align="left" nowrap>Location</td>
					<td class="smallFontGRYBG" align="left" nowrap>Description</td>
				</tr>
				<?php
				for ($i = 0; $i < count($valid); $i++) {
					$row = $valid[$i];
				?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[0]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[1]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[2]; ?></td>
				</tr>
				<?php
				}
				?>
				<tr>
					<td colspan="5"><?php echo (count($valid) == 0 ? 'No' : count($valid)).' Record(s)'; ?></td>
				</tr>
			</tbody>
		</table>
		<br />
		<?php
				if (count($invalid)  > 0) {
		?>
		<br /><br /><br />
		<span class="errorMsg">The following records were ignored because they have invalid or missing fields.</span>
		<table cellspacing="1" cellpadding="3" style="background-color:#cccccc;" border="0" width="100%">
			<tbody>
				<tr>
					<td class="smallFontGRYBG" align="left" nowrap>&nbsp;</td>
					<td class="smallFontGRYBG" align="left" nowrap>IP Address</td>
					<td class="smallFontGRYBG" align="left" nowrap>Location</td>
					<td class="smallFontGRYBG" align="left" nowrap>Description</td>
				</tr>
					<?php
					for ($i = 0; $i < count($invalid); $i++) {
						$row = $invalid[$i];
					?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[0]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[1]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[2]; ?></td>
				</tr>
					<?php
					}
					?>
				<tr>
					<td colspan="5"><?php echo (count($invalid) == 0 ? 'No' : count($invalid)).' Record(s)'; ?></td>
				</tr>
			</tbody>
		</table>
		<?php
				}
			} else if (!is_null($step) && $step == 'create') {
		?>
		<span class="msg">The following records were updated.</span>
		<table cellspacing="1" cellpadding="3" style="background-color:#cccccc;" border="0" width="100%">
			<tbody>
				<tr>
					<td class="smallFontGRYBG" align="left" nowrap>&nbsp;</td>
					<td class="smallFontGRYBG" align="left" nowrap>Username</td>
					<td class="smallFontGRYBG" align="left" nowrap>Previous IP Address</td>
					<td class="smallFontGRYBG" align="left" nowrap>New IP Address</td>
				</tr>
				<?php
				for ($i = 0; $i < count($processedRows); $i++) {
					$row = $processedRows[$i];
				?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[4]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[5]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[0]; ?></td>
				</tr>
				<?php
				}
				?>
				<tr>
					<td colspan="4"><?php echo (count($processedRows) == 0 ? 'No' : count($processedRows)).' Record(s)'; ?></td>
				</tr>
			</tbody>
		</table>
		<br />
		<?php
				if (count($notProcessedInvalidIP) != 0) {
		?>
		<br /><br /><br />
		<span class="errorMsg">The following records were not processed because of an invalid IP address.</span>
		<table cellspacing="1" cellpadding="3" style="background-color:#cccccc;" border="0" width="100%">
			<tbody>
				<tr>
					<td class="smallFontGRYBG" align="left" nowrap>&nbsp;</td>
					<td class="smallFontGRYBG" align="left" nowrap>IP Address</td>
					<td class="smallFontGRYBG" align="left" nowrap>Location</td>
					<td class="smallFontGRYBG" align="left" nowrap>Description</td>
				</tr>
					<?php
					for ($i = 0; $i < count($notProcessedInvalidIP); $i++) {
						$row = $notProcessedInvalidIP[$i];
					?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[0]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[1]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[2]; ?></td>
				</tr>
					<?php
					}
					?>
			</tbody>
		</table>
		<br />
		<?php
				}
				if (count($notProcessedIPDNE) != 0) {
		?>
		<br /><br /><br />
		<span class="errorMsg">The following records were not processed because the IP addresses do not exist.</span>
		<table cellspacing="1" cellpadding="3" style="background-color:#cccccc;" border="0" width="100%">
			<tbody>
				<tr>
					<td class="smallFontGRYBG" align="left" nowrap>&nbsp;</td>
					<td class="smallFontGRYBG" align="left" nowrap>IP Address</td>
					<td class="smallFontGRYBG" align="left" nowrap>Location</td>
					<td class="smallFontGRYBG" align="left" nowrap>Description</td>
				</tr>
					<?php
					for ($i = 0; $i < count($notProcessedIPDNE); $i++) {
						$row = $notProcessedIPDNE[$i];
					?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[0]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[1]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[2]; ?></td>
				</tr>
					<?php
					}
					?>
			</tbody>
		</table>
		<br />
		<?php
				}
				if (count($notProcessedIPUsed) != 0) {
		?>
		<br /><br /><br />
		<span class="errorMsg">The following records were not processed because the IP addresses are not available.</span>
		<table cellspacing="1" cellpadding="3" style="background-color:#cccccc;" border="0" width="100%">
			<tbody>
				<tr>
					<td class="smallFontGRYBG" align="left" nowrap>&nbsp;</td>
					<td class="smallFontGRYBG" align="left" nowrap>IP Address</td>
					<td class="smallFontGRYBG" align="left" nowrap>Location</td>
					<td class="smallFontGRYBG" align="left" nowrap>Description</td>
				</tr>
					<?php
					for ($i = 0; $i < count($notProcessedIPUsed); $i++) {
						$row = $notProcessedIPUsed[$i];
					?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[0]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[1]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[2]; ?></td>
				</tr>
					<?php
					}
					?>
			</tbody>
		</table>
		<br />
		<?php
				}
				if (count($notProcessedInvalidLocation) != 0) {
		?>
		<br /><br /><br />
		<span class="errorMsg">The following records were not processed because the locations are invalid.</span>
		<table cellspacing="1" cellpadding="3" style="background-color:#cccccc;" border="0" width="100%">
			<tbody>
				<tr>
					<td class="smallFontGRYBG" align="left" nowrap>&nbsp;</td>
					<td class="smallFontGRYBG" align="left" nowrap>IP Address</td>
					<td class="smallFontGRYBG" align="left" nowrap>Location</td>
					<td class="smallFontGRYBG" align="left" nowrap>Description</td>
				</tr>
					<?php
					for ($i = 0; $i < count($notProcessedInvalidLocation); $i++) {
						$row = $notProcessedInvalidLocation[$i];
					?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[0]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[1]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[2]; ?></td>
				</tr>
					<?php
					}
					?>
			</tbody>
		</table>
		<br />
		<?php
				}
				if (count($notProcessedError) != 0) {
		?>
		<br /><br /><br />
		<span class="errorMsg">The following records encountered an error during update.</span>
		<table cellspacing="1" cellpadding="3" style="background-color:#cccccc;" border="0" width="100%">
			<tbody>
				<tr>
					<td class="smallFontGRYBG" align="left" nowrap>&nbsp;</td>
					<td class="smallFontGRYBG" align="left" nowrap>IP Address</td>
					<td class="smallFontGRYBG" align="left" nowrap>Location</td>
					<td class="smallFontGRYBG" align="left" nowrap>Description</td>
				</tr>
					<?php
					for ($i = 0; $i < count($notProcessedError); $i++) {
						$row = $notProcessedError[$i];
					?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[0]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[1]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[2]; ?></td>
				</tr>
					<?php
					}
					?>
			</tbody>
		</table>
		<?php
				}
			}
		}
		?>
	</div>
</body>
</html>