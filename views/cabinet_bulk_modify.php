<!DOCTYPE html>
<html lang="en">
<head>
	<title>Globelines Universal Access</title>
	<?php require 'head_includes.php'; ?>
</head>
<body style="padding-right:20px; padding-bottom:20px">
	<div align="left">
		<h3 class="style1">Cabinets: Bulk Modify</h3>
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
		<form action="<?php echo base_url('main/processBulkModifyCabinets'); ?>" method="POST" enctype="multipart/form-data">
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
				$validCount = count($valid);
				if ($validCount != 0) {
		?>
		<form action="<?php echo base_url('main/processBulkModifyCabinets'); ?>" method="POST">
			<input type="hidden" name="path" value="<?php echo $path; ?>" />
			<input type="hidden" name="validRowNumbers" value="<?php echo serialize($validRowNumbers); ?>" />
			<input type="hidden" name="invalidRowNumbers" value="<?php echo serialize($invalidRowNumbers); ?>" />
			<input type="hidden" name="noChangeRowNumbers" value="<?php echo serialize($noChangeRowNumbers); ?>" />
			<input type="hidden" name="step" value="modify" />
			The system will attempt to modify the following records. Press OK to start modification. 
			<input type="submit" class="button2" name="confirm_result" value="OK" />
		</form>
		<table cellspacing="1" cellpadding='3' style="background-color:#cccccc;" border="0" width="100%">
			<tbody>
				<tr>
					<td class="smallFontGRYBG" align="left" nowrap>&nbsp;</td>
					<td class="smallFontGRYBG" align="left" nowrap>Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>(New) Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>Homing BNG</td>
					<td class="smallFontGRYBG" align="left" nowrap>Data Vlan</td>
				</tr>
					<?php
					for ($i = 0; $i < $validCount; $i++) {
						$row = $valid[$i];
						$mark = $validMarks[$i];
					?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[0]; ?></td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($mark['name']) ? 'style="border:1px solid green;"' : ''; ?>>
						<?php echo $row[1]; ?>
					</td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($mark['homing_bng']) ? 'style="border:1px solid green;"' : ''; ?>>
						<?php echo $row[2]; ?>
					</td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($mark['data_vlan']) ? 'style="border:1px solid green;"' : ''; ?>>
						<?php echo $row[3]; ?>
					</td>
				</tr>
					<?php
					}
					?>
				<tr>
					<td colspan="4"><?php echo $validCount." Record(s)"; ?></td>
				</tr>
			</tbody>
		</table>
		<br />
		<?php
				} else {
		?>
		<span class="msg">There are no valid records to modify.</span>
		<br />
		<?php
				}
				$invalidCount = count($invalid);
				if ($invalidCount != 0) {
		?>
		<br /><br /><br />
		<span class="errorMsg">The following records were ignored because they do not specify which cabinet will be modified.</span>
		<table cellspacing="1" cellpadding='3' style="background-color:#cccccc;" border="0" width="100%">
			<tbody>
				<tr>
					<td class="smallFontGRYBG" align="left" nowrap>&nbsp;</td>
					<td class="smallFontGRYBG" align="left" nowrap>Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>(New) Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>Homing BNG</td>
					<td class="smallFontGRYBG" align="left" nowrap>Data Vlan</td>
				</tr>
				<?php
					for ($i = 0; $i < $invalidCount; $i++) {
						$row = $invalid[$i];
					?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap style="border:1px solid red;"><?php echo $row[0]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[1]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[2]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[3]; ?></td>
				</tr>
					<?php
					}
					?>
				<tr>
					<td colspan="4"><?php echo $invalidCount." Record(s)"; ?></td>
				</tr>
			</tbody>
		</table>
		<br />
		<?php
				}
				$noChangeCount = count($noChange);
				if ($noChangeCount != 0) {
		?>
		<br /><br /><br />
		<span class="errorMsg">The following records were ignored because there are no fields to be modified.</span>
		<table cellspacing="1" cellpadding='3' style="background-color:#cccccc;" border="0" width="100%">
			<tbody>
				<tr>
					<td class="smallFontGRYBG" align="left" nowrap>&nbsp;</td>
					<td class="smallFontGRYBG" align="left" nowrap>Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>(New) Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>Homing BNG</td>
					<td class="smallFontGRYBG" align="left" nowrap>Data Vlan</td>
				</tr>
					<?php
					for ($i = 0; $i < $noChangeCount; $i++) {
						$row = $noChange[$i];
					?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[0]; ?></td>
					<td class="smallFontWHTBG" nowrap style="border:1px solid red;"><?php echo $row[1]; ?></td>
					<td class="smallFontWHTBG" nowrap style="border:1px solid red;"><?php echo $row[2]; ?></td>
					<td class="smallFontWHTBG" nowrap style="border:1px solid red;"><?php echo $row[3]; ?></td>
				</tr>
					<?php
					}
					?>
				<tr>
					<td colspan="4"><?php echo $noChangeCount." Record(s)"; ?></td>
				</tr>
			</tbody>
		</table>
		<br />
		<?php
				}
				$dneCount = count($dne);
				if ($dneCount != 0) {
		?>
		<br /><br /><br />
		<span class="errorMsg">The following records were ignored because their cabinet names do not exist.</span>
		<table cellspacing="1" cellpadding='3' style="background-color:#cccccc;" border="0" width="100%">
			<tbody>
				<tr>
					<td class="smallFontGRYBG" align="left" nowrap>&nbsp;</td>
					<td class="smallFontGRYBG" align="left" nowrap>Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>(New) Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>Homing BNG</td>
					<td class="smallFontGRYBG" align="left" nowrap>Data Vlan</td>
				</tr>
				<?php
				for ($i = 0; $i < $dneCount; $i++) {
					$row = $dne[$i];
				?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap style="border:1px solid red;"><?php echo $row[0]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[1]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[2]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[3]; ?></td>
				</tr>
				<?php
				}
				?>
				<tr>
					<td colspan="4"><?php echo $dneCount." Record(s)"; ?></td>
				</tr>
			</tbody>
		</table>
		<?php
				}
			} else if (!is_null($step) && $step == 'modify') {
		?>
		<span class="msg">The following records were modified.</span>
		<table cellspacing="1" cellpadding='3' style="background-color:#cccccc;" border="0" width="100%">
			<tbody>
				<tr>
					<td class="smallFontGRYBG" align="left" nowrap>&nbsp;</td>
					<td class="smallFontGRYBG" align="left" nowrap>Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>(New) Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>Homing BNG</td>
					<td class="smallFontGRYBG" align="left" nowrap>Data Vlan</td>
				</tr>
					<?php
					$editedCount = count($edited);
					for ($i = 0; $i < $editedCount; $i++) {
						$row = $edited[$i];
						$mark = $markers[$i];
					?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[0]; ?></td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($mark['newName']) ? 'style="border:1px solid green;"' : ''; ?>>
						<?php echo $row[1]; ?>
					</td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($mark['homing_bng']) ? 'style="border:1px solid green;"' : ''; ?>>
						<?php echo $row[2]; ?>
					</td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($mark['data_vlan']) ? 'style="border:1px solid green;"' : ''; ?>>
						<?php echo $row[3]; ?>
					</td>
				</tr>
					<?php
					}
					?>
				<tr>
					<td colspan="5"><?php echo ($editedCount == 0 ? 'No' : $editedCount).' Record(s)'; ?></td>
				</tr>
			</tbody>
		</table>
		<br />
		<?php
				$existingCount = count($existingNewName);
				if ($existingCount != 0) {
		?>
		<br /><br /><br />
		<span class="errorMsg">The following records were not modified because the new cabinet names already exist.</span>
		<table cellspacing="1" cellpadding='3' style="background-color:#cccccc;" border="0" width="100%">
			<tbody>
				<tr>
					<td class="smallFontGRYBG" align="left" nowrap>&nbsp;</td>
					<td class="smallFontGRYBG" align="left" nowrap>Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>(New) Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>Homing BNG</td>
					<td class="smallFontGRYBG" align="left" nowrap>Data Vlan</td>
				</tr>
					<?php
					for ($i = 0; $i < $existingCount; $i++) {
						$row = $existingNewName[$i];
					?>
				<tr>
					<td class="smallFontWHTBG" align="left" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" align="left" nowrap><?php echo $row[0]; ?></td>
					<td class="smallFontWHTBG" align="left" nowrap style="border:1px solid red;"><?php echo $row[1]; ?></td>
					<td class="smallFontWHTBG" align="left" nowrap><?php echo $row[2]; ?></td>
					<td class="smallFontWHTBG" align="left" nowrap><?php echo $row[3]; ?></td>
				</tr>
					<?php
					}
					?>
				<tr>
					<td colspan="5"><?php echo ($existingCount == 0 ? 'No' : $existingCount).' Record(s)'; ?></td>
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
		<span class="errorMsg">The following records were not modified because the new cabinet names have disallowed characters "/".</span>
		<table cellspacing="1" cellpadding='3' style="background-color:#cccccc;" border="0" width="100%">
			<tbody>
				<tr>
					<td class="smallFontGRYBG" align="left" nowrap>&nbsp;</td>
					<td class="smallFontGRYBG" align="left" nowrap>Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>(New) Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>Homing BNG</td>
					<td class="smallFontGRYBG" align="left" nowrap>Data Vlan</td>
				</tr>
					<?php
					for ($i = 0; $i < $disallowedCharacterCount; $i++) {
						$row = $disallowedCharacter[$i];
					?>
				<tr>
					<td class="smallFontWHTBG" align="left" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" align="left" nowrap><?php echo $row[0]; ?></td>
					<td class="smallFontWHTBG" align="left" nowrap style="border:1px solid red;"><?php echo $row[1]; ?></td>
					<td class="smallFontWHTBG" align="left" nowrap><?php echo $row[2]; ?></td>
					<td class="smallFontWHTBG" align="left" nowrap><?php echo $row[3]; ?></td>
				</tr>
					<?php
					}
					?>
				<tr>
					<td colspan="5"><?php echo ($disallowedCharacterCount == 0 ? 'No' : $disallowedCharacterCount).' Record(s)'; ?></td>
				</tr>
		</table>
		<br />
		<?php
				}
				$invalidHomingBngCount = count($invalidHomingBng);
				if ($invalidHomingBngCount != 0) {
		?>
		<br /><br /><br />
		<span class="errorMsg">The following records were not modified because their Homing BNGs are invalid.</span>
		<table cellspacing="1" cellpadding='3' style="background-color:#cccccc;" border="0" width="100%">
			<tbody>
				<tr>
					<td class="smallFontGRYBG" align="left" nowrap>&nbsp;</td>
					<td class="smallFontGRYBG" align="left" nowrap>Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>(New) Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>Homing BNG</td>
					<td class="smallFontGRYBG" align="left" nowrap>Data Vlan</td>
				</tr>
					<?php
					for ($i = 0; $i < $invalidHomingBngCount; $i++) {
						$row = $invalidHomingBng[$i];
					?>
				<tr>
					<td class="smallFontWHTBG" align="left" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" align="left" nowrap><?php echo $row[0]; ?></td>
					<td class="smallFontWHTBG" align="left" nowrap><?php echo $row[1]; ?></td>
					<td class="smallFontWHTBG" align="left" nowrap style="border:1px solid red;"><?php echo $row[2]; ?></td>
					<td class="smallFontWHTBG" align="left" nowrap><?php echo $row[3]; ?></td>
				</tr>
					<?php
					}
					?>
				<tr>
					<td colspan="5"><?php echo ($invalidHomingBngCount == 0 ? 'No' : $invalidHomingBngCount).' Record(s)'; ?></td>
				</tr>
			</tbody>
		</table>
		<br />
		<?php
				}
				$dbErrorCount = count($dbError);
				if ($dbErrorCount != 0) {
		?>
		<br /><br /><br />
		<span class="errorMsg">The following records were not modified because of a database transaction error.</span>
		<table cellspacing="1" cellpadding='3' style="background-color:#cccccc;" border="0" width="100%">
			<tbody>
				<tr>
					<td class="smallFontGRYBG" align="left" nowrap>&nbsp;</td>
					<td class="smallFontGRYBG" align="left" nowrap>Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>(New) Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>Homing BNG</td>
					<td class="smallFontGRYBG" align="left" nowrap>Data Vlan</td>
				</tr>
					<?php
					for ($i = 0; $i < $dbErrorCount; $i++) {
						$row = $dbError[$i];
					?>
				<tr>
					<td class="smallFontWHTBG" align="left" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" align="left" nowrap><?php echo $row[0]; ?></td>
					<td class="smallFontWHTBG" align="left" nowrap><?php echo $row[1]; ?></td>
					<td class="smallFontWHTBG" align="left" nowrap><?php echo $row[2]; ?></td>
					<td class="smallFontWHTBG" align="left" nowrap><?php echo $row[3]; ?></td>
				</tr>
					<?php
					}
					?>
				<tr>
					<td colspan="5"><?php echo $dbErrorCount." Record(s)"; ?></td>
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