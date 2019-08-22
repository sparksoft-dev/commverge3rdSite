<!DOCTYPE html>
<html lang="en">
<head>
	<title>Globelines Universal Access</title>
	<?php require 'head_includes.php'; ?>
</head>
<body style="padding-right:20px; padding-bottom:20px">
	<div align="left">
		<h3 class="style1">Net Addresses: Bulk Create</h3>
	</div>
	<div class="smallFontB" align="right">
		<a href="<?php echo base_url('main/showNetaddressesIndex/1'); ?>">Back to index</a>
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
		<form action="<?php echo base_url('main/processBulkNetaddressCreation'); ?>" method="POST" enctype="multipart/form-data">
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
		<form action="<?php echo base_url('main/processBulkNetaddressCreation'); ?>" method="POST">
			<input type="hidden" name="path" value="<?php echo $path; ?>" />
			<input type="hidden" name="validRowNumbers" value="<?php echo serialize($vaildRowNumbers); ?>" />
			<input type="hidden" name="invalidRowNumbers" value="<?php echo serialize($invalidRowNumbers); ?>" />
			<input type="hidden" name="step" value="create" />
			The system will attempt to create the following records. Press OK to start creation. 
			<input type="submit" class="button2" name="confirm_result" value="OK" />
		</form>
		<table cellspacing="1" cellpadding='3' style="background-color:#cccccc;" border="0" width="100%">
			<tbody>
				<tr>
					<td class="smallFontGRYBG" align="left" nowrap>&nbsp;</td>
					<td class="smallFontGRYBG" align="left" nowrap>Network Address</td>
					<td class="smallFontGRYBG" align="left" nowrap>Location</td>
					<td class="smallFontGRYBG" align="left" nowrap>Description</td>
				</tr>
				<?php
				for ($i = 0; $i < count($valid); $i++) {
					$row = $valid[$i];
				?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[0].$row[1]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[2]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[3]; ?></td>
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
				if (count($invalid) > 0) {
		?>
		<br /><br /><br />
		<span class="errorMsg">The following records were ignored because they have invalid or missing fields.</span>
		<table cellspacing="1" cellpadding="3" style="background-color:#cccccc;" border="0" width="100%">
			<tbody>
				<tr>
					<td class="smallFontGRYBG" align="left" nowrap>&nbsp;</td>
					<td class="smallFontGRYBG" align="left" nowrap>Network Address</td>
					<td class="smallFontGRYBG" align="left" nowrap>Location</td>
					<td class="smallFontGRYBG" align="left" nowrap>Description (50 Characters Maximum)</td>
				</tr>
				<?php
				for ($i = 0; $i < count($invalid); $i++) {
					$row = $invalid[$i];
				?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[0].$row[1]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[2]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[3]; ?></td>
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
		?>
		<?php
			} else if (!is_null($step) && $step == 'create') {
		?>
		<span class="msg">The following records were created.</span>
		<table cellspacing="1" cellpadding="3" style="background-color:#cccccc;" border="0" width="100%">
			<tbody>
				<tr>
					<td class="smallFontGRYBG" align="left" nowrap>&nbsp;</td>
					<td class="smallFontGRYBG" align="left" nowrap>Network Address</td>
					<td class="smallFontGRYBG" align="left" nowrap>Location</td>
					<td class="smallFontGRYBG" align="left" nowrap>Description</td>
				</tr>
				<?php
				for ($i = 0; $i < count($created); $i++) {
					$row = $created[$i];
				?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[0].$row[1]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[2]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[3]; ?></td>
				</tr>
				<?php
				}
				?>
				<tr>
					<td colspan="5"><?php echo (count($created) == 0 ? 'No' : count($created)).' Record(s)'; ?></td>
				</tr>
			</tbody>
		</table>
		<br />
		<?php
				if (count($invalidNet) != 0) {
		?>
		<br /><br /><br />
		<span class="errorMsg">The following records were not created because of an invalid Network address.</span>
		<table cellspacing="1" cellpadding="3" style="background-color:#cccccc;" border="0" width="100%">
			<tbody>
				<tr>
					<td class="smallFontGRYBG" align="left" nowrap>&nbsp;</td>
					<td class="smallFontGRYBG" align="left" nowrap>Network Address</td>
					<td class="smallFontGRYBG" align="left" nowrap>Location</td>
					<td class="smallFontGRYBG" align="left" nowrap>Description</td>
				</tr>
				<?php
				for ($i = 0; $i < count($invalidNet); $i++) {
					$row = $invalidNet[$i];
				?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[0].$row[1]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[2]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[3]; ?></td>
				</tr>
				<?php
				}
				?>
			</tbody>
		</table>
		<br />
		<?php
				}
				if (count($existingNet) != 0) {
		?>
		<br /><br /><br />
		<span class="errorMsg">The following records were not created because the Network addresses already exist.</span>
		<table cellspacing="1" cellpadding="3" style="background-color:#cccccc;" border="0" width="100%">
			<tbody>
				<tr>
					<td class="smallFontGRYBG" align="left" nowrap>&nbsp;</td>
					<td class="smallFontGRYBG" align="left" nowrap>Network Address</td>
					<td class="smallFontGRYBG" align="left" nowrap>Location</td>
					<td class="smallFontGRYBG" align="left" nowrap>Description</td>
				</tr>
				<?php
				for ($i = 0; $i < count($existingNet); $i++) {
					$row = $existingNet[$i];
				?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[0].$row[1]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[2]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[3]; ?></td>
				</tr>
				<?php
				}
				?>
			</tbody>
		</table>
		<br />
		<?php
				}
				if (count($invalidLocation) != 0) {
		?>
		<br /><br /><br />
		<span class="errorMsg">The following records were not created because the Location is invalid.</span>
		<table cellspacing="1" cellpadding="3" style="background-color:#cccccc;" border="0" width="100%">
			<tbody>
				<tr>
					<td class="smallFontGRYBG" align="left" nowrap>&nbsp;</td>
					<td class="smallFontGRYBG" align="left" nowrap>Network Address</td>
					<td class="smallFontGRYBG" align="left" nowrap>Location</td>
					<td class="smallFontGRYBG" align="left" nowrap>Description</td>
				</tr>
				<?php
				for ($i = 0; $i < count($invalidLocation); $i++) {
					$row = $invalidLocation[$i];
				?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[0].$row[1]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[2]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[3]; ?></td>
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
<body>
</html>