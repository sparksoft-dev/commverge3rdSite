<!DOCTYPE html>
<html lang="en">
<head>
	<title>Globelines Universal Access</title>
	<?php require 'head_includes.php'; ?>
</head>
<body style="padding-right:20px; padding-bottom:20px">
	<div align="left">
		<h3 class="style1">Cabinets: Bulk Delete</h3>
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
		<form action="<?php echo base_url('main/processBulkDeleteCabinets'); ?>" method="POST" enctype="multipart/form-data">
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
		<form action="<?php echo base_url('main/processBulkDeleteCabinets'); ?>" method="POST">
			<input type="hidden" name="path" value="<?php echo $path; ?>" />
			<input type="hidden" name="idsToDelete" value="<?php echo serialize($idsToDelete); ?>">
			<input type="hidden" name="step" value="delete" />
			The system will attempt to delete the following records. Press OK to start deletion. 
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
				for ($i = 0; $i < $validCount; $i++) {
					$row = $valid[$i];
				?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row['name']; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row['homing_bng']; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row['data_vlan']; ?></td>
				</tr>
				<?php
				}
				?>
				<tr>
					<td colspan="4"><?php echo $validCount.' Record(s)'; ?></td>
				</tr>
			</tbody>
		</table>
		<br />
		<?php
				} else {
		?>
		<span class="msg">There are no valid records to delete.</span>
		<br />
		<?php
				}
				$dneCount = count($dne);
				if ($dneCount > 0) {
		?>
		<br /><br /><br />
		<span class="errorMsg">The following records were ignored because their cabinet names are non-existent.</span>
		<table cellspacing="1" cellpadding="3" style="background-color:#cccccc;" border="0" width="100%">
			<tbody>
				<tr>
					<td class="smallFontGRYBG" align="left" nowrap>&nbsp;</td>
					<td class="smallFontGRYBG" align="left" nowrap>Cabinet Name</td>
					<!-- <td class="smallFontGRYBG" align="left" nowrap>Homing BNG</td>
					<td class="smallFontGRYBG" align="left" nowrap>Data Vlan</td> -->
				</tr>
				<?php
				for ($i = 0; $i < $dneCount; $i++) {
					$row = $dne[$i];
				?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row; ?></td>
					<!-- <td class="smallFontWHTBG" nowrap><?php //echo $row['homing_bng']; ?></td>
					<td class="smallFontWHTBG" nowrap><?php //echo $row['data_vlan']; ?></td> -->
				</tr>
				<?php
				}
				?>
				<tr>
					<td colspan="4"><?php echo $dneCount.' Record(s)'; ?></td>
				</tr>
			</tbody>
		</table>
		<br />
		<?php
				}
				$invalidCount = count($invalid);
				if ($invalidCount > 0) {
		?>
		<br /><br /><br />
		<span class="errorMsg">The following records were ignored because they have not specified a cabinet name.</span>
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
					<td colspan="4"><?php echo $invalidCount.' Record(s)'; ?></td>
				</tr>
			</tbody>
		</table>
		<?php
				}
			} else if (!is_null($step) && $step == 'delete') {
				$deletedCount = count($deleted);
				if ($deletedCount != 0) {
		?>
		<span class="msg">The following records were deleted.</span>
		<table cellspacing="1" cellpadding="3" style="background-color:#cccccc;" border="0" width="100%">
			<tbody>
				<tr>
					<td class="smallFontGRYBG" align="left" nowrap>&nbsp;</td>
					<td class="smallFontGRYBG" align="left" nowrap>Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>Homing BNG</td>
					<td class="smallFontGRYBG" align="left" nowrap>Data Vlan</td>
				</tr>
				<?php
				for ($i = 0; $i < $deletedCount; $i++) {
					$row = $deleted[$i];
				?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row['name']; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row['homing_bng']; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row['data_vlan']; ?></td>
				</tr>
				<?php
				}
				?>
				<tr>
					<td colspan="4"><?php echo $deletedCount.' Record(s)'; ?></td>
				</tr>
			</tbody>
		</table>
		<?php
				} else {
		?>
		<span class="errorMsg">There were no records deleted.</span>
		<?php
				}
				$notDeletedCount = count($notDeleted);
				if ($notDeletedCount != 0) {
		?>
		<br /><br /><br />
		<span class="errorMsg">The following records were not deleted.</span>
		<table cellspacing="1" cellpadding="3" style="background-color:#cccccc;" border="0" width="100%">
			<tbody>
				<tr>
					<td class="smallFontGRYBG" align="left" nowrap>&nbsp;</td>
					<td class="smallFontGRYBG" align="left" nowrap>Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>Homing BNG</td>
					<td class="smallFontGRYBG" align="left" nowrap>Data Vlan</td>
				</tr>
				<?php
				for ($i = 0; $i < $notDeletedCount; $i++) {
					$row = $notDeleted[$i];
				?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row['name']; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row['homing_bng']; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row['data_vlan']; ?></td>
				</tr>
				<?php
				}
				?>
				<tr>
					<td colspan="4"><?php echo $notDeletedCount.' Record(s)'; ?></td>
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