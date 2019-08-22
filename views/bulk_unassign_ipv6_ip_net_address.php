<!DOCTYPE html>
<html lang="en">
<head>
	<title>Globelines Universal Access</title>
	<?php require 'head_includes.php'; ?>
</head>
<body style="padding-right:20px; padding-bottom:20px;">
	<div align="left">
		<h3 class="style1">Primary Account: Bulk Unassign Net Address</h3>
	</div>
	<div align="right">
		<a href="<?php echo base_url('subscribers/showUpdateSubscriberForm'); ?>">Back</a>
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
		<form id="bulkUploadForm" action="<?php echo base_url('subscribers/processBulkUnassignIPv6IPAndNetAddress'); ?>" method="POST" ENCTYPE="multipart/form-data">
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
		<form id="bulkConfirmForm" action="<?php echo base_url('subscribers/processBulkUnassignIPv6IPAndNetAddress'); ?>" method="POST">
			<input type="hidden" name="realm" value="<?php echo $realm; ?>" />
			<input type="hidden" name="path" value="<?php echo $path; ?>" />
			<input type="hidden" name="validRowNumbers" value="<?php echo serialize($validRowNumbers); ?>" />
			<input type="hidden" name="invalidRowNumbers" value="<?php echo serialize($invalidRowNumbers); ?>" />
			<input type="hidden" name="noChangeRowNumbers" value="<?php echo serialize($noChangeRowNumbers); ?>" />
			<input type="hidden" name="step" value="unassign" />
			<?php
			$validCount = count($valid);
			if ($validCount != 0) {
			?>
			The system will attempt to unassign the following marked addresses. Press OK to start modification.
			 <input type="submit" class="button2" name="confirm_result" value="OK" />
			<?php
			} else {
			?>
			There are no valid addresses to unassign.
			<?php
			}
			?>
		</form>
		<table cellspacing="1" cellpadding="3" style="background-color:#cccccc;" border="0" width="100%">
			<tbody>
				<tr>
					<td class="smallFontGRYBG" align="left" nowrap>&nbsp;</td>
					<td class="smallFontGRYBG" align="left" nowrap>Username</td>
					<?php
					if ($useIPv6) {
					?>
					<td class="smallFontGRYBG" align="left" nowrap>IPv6 Address</td>
					<?php
					}
					?>
					<td class="smallFontGRYBG" align="left" nowrap>IP Address</td>
					<td class="smallFontGRYBG" align="left" nowrap>Network Address</td>
				</tr>
				<?php
				for ($i = 0; $i < $validCount; $i++) {
					$row = $valid[$i];
					$mark = $validMarks[$i];
				?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap <?php echo isset($mark['USERNAME']) ? 'style="border:1px solid green;"' : ''; ?>><?php echo $row[0]; ?></td>
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
				</tr>
				<?php
				}
				?>
				<tr>
					<td colspan="<?php echo $useIPv6 ? '5' : '4'; ?>"><?php echo ($validCount == 0 ? 'No' : $validCount).' Record(s)'; ?></td>
				</tr>
			</tbody>
		</table>
		<br />
		<?php
				$invalidCount = count($invalid);
				if ($invalidCount != 0) {
		?>
		<br />
		<span class="errorMsg">The following records were ignored because they have invalid or missing fields.</span>
		<table cellspacing="1" cellpadding="3" style="background-color:#cccccc;" border="0" width="100%">
			<tbody>
				<tr>
					<td class="smallFontGRYBG" align="left" nowrap>&nbsp;</td>
					<td class="smallFontGRYBG" align="left" nowrap>Username</td>
					<?php
					if ($useIPv6) {
					?>
					<td class="smallFontGRYBG" align="left" nowrap>IPv6 Address</td>
					<?php
					}
					?>
					<td class="smallFontGRYBG" align="left" nowrap>IP Address</td>
					<td class="smallFontGRYBG" align="left" nowrap>Network Address</td>
				</tr>
				<?php
				for ($i = 0; $i < $invalidCount; $i++) {
					$row = $invalid[$i];
				?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[0]; ?></td>
					<?php
					if ($useIPv6) {
					?>
					<td class="smallFontWHTBG" nowrap><?php echo $row[9]; ?></td>
					<?php
					}
					?>
					<td class="smallFontWHTBG" nowrap><?php echo $useIPv6 ? $row[10] : $row[9]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $useIPv6 ? $row[11] : $row[10]; ?></td>
				</tr>
				<?php
				}
				?>
				<tr>
					<td colspan="<?php echo $useIPv6 ? '5' : '4'; ?>"><?php echo ($invalidCount == 0 ? 'No' : $invalidCount).' Record(s)'; ?></td>
				</tr>
			</tbody>
		</table>
		<br />
		<?php
				}
				$noChangeCount = count($noChange);
				if ($noChangeCount != 0) {
		?>
		<br />
		<span class="errorMsg">No changes will be made to the following records.</span>
		<table cellspacing="1" cellpadding="3" style="background-color:#cccccc;" border="0" width="100%">
			<tbody>
				<tr>
					<td class="smallFontGRYBG" align="left" nowrap>&nbsp;</td>
					<td class="smallFontGRYBG" align="left" nowrap>Username</td>
					<?php
					if ($useIPv6) {
					?>
					<td class="smallFontGRYBG" align="left" nowrap>IPv6 Address</td>
					<?php
					}
					?>
					<td class="smallFontGRYBG" align="left" nowrap>IP Address</td>
					<td class="smallFontGRYBG" align="left" nowrap>Network Address</td>
				</tr>
				<?php
				for ($i = 0; $i < $noChangeCount; $i++) {
					$row = $noChange[$i];
				?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[0]; ?></td>
					<?php
					if ($useIPv6) {
					?>
					<td class="smallFontWHTBG" nowrap><?php echo $row[9]; ?></td>
					<?php
					}
					?>
					<td class="smallFontWHTBG" nowrap><?php echo $useIPv6 ? $row[10] : $row[9]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $useIPv6 ? $row[11] : $row[10]; ?></td>
				</tr>
				<?php
				}
				?>
				<tr>
					<td colspan="<?php echo $useIPv6 ? '5' : '4'; ?>"><?php echo ($noChangeCount == 0 ? 'No' : $noChangeCount).' Record(s)'; ?></td>
				</tr>
			</tbody>
		</table>
		<?php
				}
				$dneCount = count($dne);
				if ($dneCount != 0) {
		?>
		<br />
		<span class="errorMsg">The following have no records on the database.</span>
		<table cellspacing="1" cellpadding="3" style="background-color:#cccccc;" border="0" width="100%">
			<tbody>
				<tr>
					<td class="smallFontGRYBG" align="left" nowrap>&nbsp;</td>
					<td class="smallFontGRYBG" align="left" nowrap>Username</td>
					<?php
					if ($useIPv6) {
					?>
					<td class="smallFontGRYBG" align="left" nowrap>IPv6 Address</td>
					<?php
					}
					?>
					<td class="smallFontGRYBG" align="left" nowrap>IP Address</td>
					<td class="smallFontGRYBG" align="left" nowrap>Network Address</td>
				</tr>
				<?php
				for ($i = 0; $i < $dneCount; $i++) {
					$row = $dne[$i];
				?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row[0]; ?></td>
					<?php
					if ($useIPv6) {
					?>
					<td class="smallFontWHTBG" nowrap><?php echo $row[9]; ?></td>
					<?php
					}
					?>
					<td class="smallFontWHTBG" nowrap><?php echo $useIPv6 ? $row[10] : $row[9]; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $useIPv6 ? $row[11] : $row[10]; ?></td>
				</tr>
				<?php
				}
				?>
				<tr>
					<td colspan="<?php echo $useIPv6 ? '5' : '4'; ?>"><?php echo ($dneCount == 0 ? 'No' : $dneCount).' Record(s)'; ?></td>
				</tr>
			</tbody>
		</table>
		<?php
				}
			} else if (!is_null($step) && $step == 'unassign') {
				$unassignedCount = count($unassigned);
		?>
		<form action="<?php echo base_url('subscribers/processBulkUnassignIPv6IPAndNetAddress'); ?>" method="POST">
			<input type="hidden" name="step" value="download" />
			<input type="hidden" name="set" value="unassigned" />
			<input type="hidden" name="realm" value="<?php echo $realm; ?>" />
			<input type="hidden" name="path" value="<?php echo $path; ?>" />
			<input type="hidden" name="setdata" value="<?php echo serialize($unassignedRowNumbers); ?>" />
			<?php
			if ($unassignedCount != 0) {
			?>
			<span class="msg">The following records were modified.</span>
			<!-- <input type="submit" class="button2" name="confirm_result" value="extract" /> -->
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
					<?php
					if ($useIPv6) {
					?>
					<td class="smallFontGRYBG" align="left" nowrap>IPv6 Address</td>
					<?php
					}
					?>
					<td class="smallFontGRYBG" align="left" nowrap>IP Address</td>
					<td class="smallFontGRYBG" align="left" nowrap>Network Address</td>
				</tr>
				<?php
				for ($i = 0; $i < $unassignedCount; $i++) {
					$row = $unassigned[$i];
					$mark = $unassignedMarks[$i];
				?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row['USER_IDENTITY']; ?></td>
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
				</tr>
				<?php
				}
				?>
				<tr>
					<td colspan="<?php echo $useIPv6 ? '5' : '4'; ?>"><?php echo (count($unassigned) == 0 ? 'No' : count($unassigned)).' Record(s)'; ?></td>
				</tr>
			</tbody>
		</table>
		<br />
		<?php
				$notUnassignedCount = count($notUnassigned);
				if ($notUnassignedCount != 0) {
		?>
		<br />
		<form action="<?php echo base_url('subscribers/processBulkUnassignIPv6IPAndNetAddress'); ?>" method="POST">
			<input type="hidden" name="step" value="download" />
			<input type="hidden" name="set" value="notunassigned" />
			<input type="hidden" name="realm" value="<?php echo $realm; ?>" />
			<input type="hidden" name="path" value="<?php echo $path; ?>" />
			<input type="hidden" name="setdata" value="<?php echo serialize($notUnassignedRowNumbers); ?>" />
			<span class="errorMsg">The following records encountered an error on update.</span>
			<!-- <input type="submit" class="button2" name="confirm_result" value="extract" /> -->
		</form>
		<table cellspacing="1" cellpadding="3" style="background-color:#cccccc;" border="0" width="100%">
			<tbody>
				<tr>
					<td class="smallFontGRYBG" align="left" nowrap>&nbsp;</td>
					<td class="smallFontGRYBG" align="left" nowrap>Username</td>
					<?php
					if ($useIPv6) {
					?>
					<td class="smallFontGRYBG" align="left" nowrap>IPv6 Address/Cabinet Name</td>
					<?php
					}
					?>
					<td class="smallFontGRYBG" align="left" nowrap>IP Address/Cabinet Name</td>
					<td class="smallFontGRYBG" align="left" nowrap>Network Address/Cabinet Name</td>
				</tr>
				<?php
				for ($i = 0; $i < $notUnassignedCount; $i++) {
					$row = $notUnassigned[$i]['subsdata'];
				?>
				<tr>
					<td class="smallFontWHTBG" nowrap><?php echo ($i + 1); ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row['USER_IDENTITY']; ?></td>
					<?php
					if ($useIPv6) {
					?>
					<td class="smallFontWHTBG" nowrap><?php echo $row['RBADDITIONALSERVICE4']; ?></td>
					<?php
					}
					?>
					<td class="smallFontWHTBG" nowrap><?php echo $row['RBIPADDRESS']; ?></td>
					<td class="smallFontWHTBG" nowrap><?php echo $row['RBMULTISTATIC']; ?></td>
				</tr>
				<?php
				}
				?>
				<tr>
					<td colspan="<?php echo $useIPv6 ? '5' : '4'; ?>"><?php echo (count($notUnassignedCount) == 0 ? 'No' : count($notUnassignedCount)).' Record(s)'; ?></td>
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