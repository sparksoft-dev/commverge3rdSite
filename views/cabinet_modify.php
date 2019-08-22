<!DOCTYPE html>
<html lang="en">
<head>
	<title>Globelines Universal Access</title>
	<?php require 'head_includes.php'; ?>
</head>
<body style="padding-right:20px; padding-bottom:20px;">
	<div>
		<h3 class="style1">Cabinets: Modify</h3>
	</div>
	<div align="right" class="smallFontB">
		<a href="<?php echo base_url('main/showCabinetsIndex/1'); ?>">Back to index</a>
	</div>
	<div align="center">
		<table cellspacing="1" cellpadding="2" width="100%" align="center">
			<tr>
				<td>
					<span class="notificationMsg"><?php echo isset($message) && !is_null($message) ? $message : ''; ?></span>
					<span class="errorMsg"><?php echo isset($error) && !is_null($error) ? $error : ''; ?></span>
				</td>
			</tr>
		</table>
	</div>
	<div>
		<form name="editForm" action="<?php echo base_url('main/processCabinetModification'); ?>" method="post">
			<table cellpadding="2">
				<tr>
					<td class="smallFontWHTBG" align="left"><strong>Cabinet Name</strong></td>
					<td class="smallFontWHTBG" align="left">
						<input type="text" name="cabinetName" id="cabinetName" value="<?php echo isset($cabinetName) && !is_null($cabinetName) ? $cabinetName : ''; ?>" />
						<font face="verdana" color="#990000" size="1">*required</font>
					</td>
				</tr>
				<tr>
					<td class="smallFontWHTBG" align="left"><strong>Homing BNG</strong></td>
					<td class="smallFontWHTBG" align="left">
						<select name="cabinetBng" id="cabinetBng">
							<?php
							$selected = isset($cabinetBng) && !is_null($cabinetBng) ? $cabinetBng : '';
							$locationCount = count($locations);
							for ($i = 0; $i < $locationCount; $i++) {
								$location = $locations[$i];
								$show = false;
								if (intval($location['id']) == intval($selected) && $selected != '') {
									$show = true;
								}
								echo '<option value="'.$location['id'].'"'.($show ? ' selected' : '').'>'.
									$location['location'].
								'</option>';
							}
							?>
						</select>
						<font face="verdana" color="#990000" size="1">*required</font>
					</td>
				</tr>
				<tr>
					<td class="smallFontWHTBG" align="left"><strong>Data VLAN</strong></td>
					<td class="smallFontWHTBG" align="left">
						<input type="text" name="cabinetVlan" id="cabinetVlan" value="<?php echo isset($cabinetVlan) && !is_null($cabinetVlan) ? $cabinetVlan : ''; ?>">
						<!--<font face="verdana" color="#990000" size="1">*required</font>-->
					</td>
				</tr>
				<tr>
					<td align="right" colspan="2">
						<input type="hidden" name="cabinetId" id="cabinetId" value="<?php echo isset($cabinetId) && !is_null($cabinetId) ? $cabinetId : ''; ?>" />
						<input class="button" type="submit" value="save" />
					</td>
				</tr>
			</table>
		</form>
	</div>
	<script type="text/javascript">
		$(document).ready(function () {
			$('form[name="editForm"]').on('submit', function (event) {
				if ($('#cabinetName').val().trim() == '') {
					$('span.errorMsg').empty().append('Please enter cabinet name.');
					$('#cabinetName').focus();
					return false;
				}
				// if ($('#cabinetVlan').val().trim() == '') {
				//	$('span.errorMsg').empty().append('Please enter cabinet vlan.');
				//	$('#cabinetVlan').focus();
				//	return false;
				// }
			});
		});
	</script>
</body>
</html>