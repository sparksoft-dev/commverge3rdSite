<!DOCTYPE html>
<html lang="en">
<head>
	<title>Globelines Universal Access</title>
	<?php require 'head_includes.php'; ?>
</head>
<body style="padding-right:20px; padding-bottom:20px">
	<div align="left">
		<h3 class="style1">Cabinets: Bulk Create</h3>
	</div>
	<div class="smallFontB" align="right">
		<a href="<?php echo base_url('main/showCabinetsIndex/1'); ?>">Back to index</a>
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
		<form action="<?php echo base_url('main/processBulkLoadCabinets'); ?>" method="POST" enctype="multipart/form-data">
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
		<form action="<?php echo base_url('main/processBulkLoadCabinets'); ?>" method="POST">
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
					<td class="smallFontGRYBG" align="left" nowrap>Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>Homing BNG</td>
					<td class="smallFontGRYBG" align="left" nowrap>Data Vlan</td>
				</tr>
				<?php
				$validCount = count($valid);
				for ($i = 0; $i < $validCount; $i++) {
					$row = $valid[$i];
				?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[0]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[2]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[3]; ?></td>
				</tr>
				<?php
				}
				?>
				<tr>
					<td colspan="4"><?php echo ($validCount == 0 ? 'No' : $validCount).' Record(s)'; ?></td>
				</tr>
			</tbody>
		</table>
		<br />
		<?php
				$invalidCount = count($invalid);
				if ($invalidCount > 0) {
		?>
		<br /><br /><br />
		<span class="errorMsg">The following records were ignored because they have invalid or missing fields.</span>
		<table cellspacing="1" cellpadding="3" style="background-color:#cccccc;" border="0" width="100%">
			<tbody>
				<tr>
					<td class="smallFontGRYBG" align="left" nowrap>&nbsp;</td>
					<td class="smallFontGRYBG" align="left" nowrap>Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>Homing BNG</td>
					<td class="smallFontGRYBG" align="left" nowrap>Data Vlan</td>
				</tr>
				<?php
				for ($i = 0; $i < $invalidCount; $i++) {
					$row = $invalid[$i];
				?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[0]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[2]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[3]; ?></td>
				</tr>
				<?php
				}
				?>
				<tr>
					<td colspan="4"><?php echo ($invalidCount == 0 ? 'No' : $invalidCount).' Record(s)'; ?></td>
				</tr>
			</tbody>
		</table>
		<?php
				}
			} else if (!is_null($step) && $step == 'create') {
		?>
		<span class="msg">The following records were created.</span>
		<table cellspacing="1" cellpadding="3" style="background-color:#cccccc;" border="0" width="100%">
			<tbody>
				<tr>
					<td class="smallFontGRYBG" align="left" nowrap>&nbsp;</td>
					<td class="smallFontGRYBG" align="left" nowrap>Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>Homing BNG</td>
					<td class="smallFontGRYBG" align="left" nowrap>Data Vlan</td>
				</tr>
				<?php
				$createdCount = count($created);
				for ($i = 0; $i < $createdCount; $i++) {
					$row = $created[$i];
				?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[0]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[2]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[3]; ?></td>
				</tr>
				<?php
				}
				?>
				<tr>
					<td colspan="4"><?php echo ($createdCount == 0 ? 'No' : $createdCount).' Record(s)'; ?></td>
				</tr>
			</tbody>
		</table>
		<br />
		<?php
				$invalidHomingBngCount = count($invalidHomingBng);
				if ($invalidHomingBngCount != 0) {
		?>
		<br /><br /><br />
		<span class="errorMsg">The following records were not created because of an invalid Homing BNG.</span>
		<table cellspacing="1" cellpadding="3" style="background-color:#cccccc;" border="0" width="100%">
			<tbody>
				<tr>
					<td class="smallFontGRYBG" align="left" nowrap>&nbsp;</td>
					<td class="smallFontGRYBG" align="left" nowrap>Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>Homing BNG</td>
					<td class="smallFontGRYBG" align="left" nowrap>Data Vlan</td>
				</tr>
				<?php
				for ($i = 0; $i < $invalidHomingBngCount; $i++) {
					$row = $invalidHomingBng[$i];
				?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[0]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[2]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[3]; ?></td>
				</tr>
				<?php
				}
				?>
				<tr>
					<td colspan="4"><?php echo ($invalidHomingBngCount == 0 ? 'No' : $invalidHomingBngCount).' Record(s)'; ?></td>
				</tr>
			</tbody>
		</table>
		<br />
		<?php
				}
				$existingCabinetNameCount = count($existingCabinetName);
				if ($existingCabinetNameCount != 0) {
		?>
		<br /><br /><br />
		<span class="errorMsg">The following records were not created because the Cabinet names already exist.</span>
		<table cellspacing="1" cellpadding="3" style="background-color:#cccccc;" border="0" width="100%">
			<tbody>
				<tr>
					<td class="smallFontGRYBG" align="left" nowrap>&nbsp;</td>
					<td class="smallFontGRYBG" align="left" nowrap>Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>Homing BNG</td>
					<td class="smallFontGRYBG" align="left" nowrap>Data Vlan</td>
				</tr>
				<?php
				for ($i = 0; $i < $existingCabinetNameCount; $i++) {
					$row = $existingCabinetName[$i];
				?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[0]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[2]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[3]; ?></td>
				</tr>
				<?php
				}
				?>
				<tr>
					<td colspan="4"><?php echo ($existingCabinetNameCount == 0 ? 'No' : $existingCabinetNameCount).' Record(s)'; ?></td>
				</tr>
			</tbody>
		</table>
		<br />
		<?php
				}
				$disallowedCharacterCount = count($disallowedCharacter);
				if ($disallowedCharacterCount != 0) {
		?>
		<br /><br /><br />
		<span class="errorMsg">The following records were not created because the Cabinet names had a disallowed character "/".</span>
		<table cellspacing="1" cellpadding="3" style="background-color:#cccccc;" border="0" width="100%">
			<tbody>
				<tr>
					<td class="smallFontGRYBG" align="left" nowrap>&nbsp;</td>
					<td class="smallFontGRYBG" align="left" nowrap>Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>Homing BNG</td>
					<td class="smallFontGRYBG" align="left" nowrap>Data Vlan</td>
				</tr>
				<?php
				for ($i = 0; $i < $disallowedCharacterCount; $i++) {
					$row = $disallowedCharacter[$i];
				?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[0]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[2]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[3]; ?></td>
				</tr>
				<?php
				}
				?>
				<tr>
					<td colspan="4"><?php echo ($disallowedCharacterCount == 0 ? 'No' : $disallowedCharacterCount).' Record(s)'; ?></td>
				</tr>
		</table>
		<br />
		<?php
				}
				$dbErrorCount = count($dbError);
				if ($dbErrorCount != 0) {
		?>
		<br /><br /><br />
		<span class="errorMsg">The following records were not created because of an error in the database transaction.</span>
		<table cellspacing="1" cellpadding="3" style="background-color:#cccccc;" border="0" width="100%">
			<tbody>
				<tr>
					<td class="smallFontGRYBG" align="left" nowrap>&nbsp;</td>
					<td class="smallFontGRYBG" align="left" nowrap>Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>Homing BNG</td>
					<td class="smallFontGRYBG" align="left" nowrap>Data Vlan</td>
				</tr>
				<?php
				for ($i = 0; $i < $dbErrorCount; $i++) {
					$row = $dbError[$i];
				?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[0]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[2]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[3]; ?></td>
				</tr>
				<?php
				}
				?>
				<tr>
					<td colspan="4"><?php echo ($dbErrorCount == 0 ? 'No' : $dbErrorCount).' Record(s)'; ?></td>
				</tr>
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