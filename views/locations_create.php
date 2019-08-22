<!DOCTYPE html>
<html lang="en">
<head>
	<title>Globelines Universal Access</title>
	<?php require 'head_includes.php'; ?>
</head>
<body style="padding-right:20px; padding-bottom:20px;">
	<div>
		<h3 class="style1">Locations: Create</h3>
	</div>
	<div align="right" class="smallFontB">
		<a href="<?php echo base_url('main/showLocationsIndex/1'); ?>">Back to index</a>
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
		<form name="createForm" action="<?php echo base_url('main/processLocationCreation'); ?>" method="post">
			<table cellpadding="2">
				<tr>
					<td class="smallFontWHTBG" align="left"><strong>NAS Tag</strong></td>
					<td class="smallFontWHTBG" align="left">
						<input type="text" name="location" id="location" value="<?php echo isset($location) && !is_null($location) ? $location : ''; ?>" />
						<font face="verdana" color="#990000" size="1">*required</font>
					</td>
				</tr>
				<tr>
					<td class="smallFontWHTBG" align="left"><strong>NAS Name</strong></td>
					<td class="smallFontWHTBG" align="left">
						<input type="text" name="nasName" id="nasName" value="<?php echo isset($nasName) && !is_null($nasName) ? $nasName : ''; ?>" />
						<font face="verdana" color="#990000" size="1">*required</font>
					</td>
				</tr>
				<tr>
					<td class="smallFontWHTBG" align="left"><strong>NAS IP</strong></td>
					<td class="smallFontWHTBG" align="left">
						<input type="text" name="nasIp" id="nasIp" value="<?php echo isset($nasIp) && !is_null($nasIp) ? $nasIp : ''; ?>" />
						<font face="verdana" color="#990000" size="1">*required</font>
					</td>
				</tr>
				<tr>
					<td class="smallFontWHTBG" align="left"><strong>NAS Code</strong></td>
					<td class="smallFontWHTBG" align="left">
						<input type="text" name="nasCode" id="nasCode" value="<?php echo isset($nasCode) && !is_null($nasCode) ? $nasCode : ''; ?>" />
						<font face="verdana" color="#990000" size="1">*required</font>
					</td>
				</tr>
				<tr>
					<td class="smallFontWHTBG" align="left"><strong>RM Location</strong></td>
					<td class="smallFontWHTBG" align="left">
						<input type="text" name="rmLocation" id="rmLocation" value="<?php echo isset($rmLocation) && !is_null($rmLocation) ? $rmLocation : ''; ?>" />
						<font face="verdana" color="#990000" size="1">*required</font>
					</td>
				</tr>
				<tr>
					<td class="smallFontWHTBG" align="left"><strong>NAS Description</strong></td>
					<td class="smallFontWHTBG" align="left">
						<input type="text" name="nasDescription" id="nasDescription" style="width:225px;" 
							value="<?php echo isset($nasDescription) && !is_null($nasDescription) ? $nasDescription : ''; ?>" />
					</td>
				</tr>
				<tr>
					<td class="smallFontWHTBG" align="left"><strong>RM Description</strong></td>
					<td class="smallFontWHTBG" align="left">
						<input type="text" name="rmDescription" id="rmDescription" style="width:225px;" 
							value="<?php echo isset($rmDescription) && !is_null($rmDescription) ? $rmDescription : ''; ?>" />
					</td>
				</tr>
				<tr>
					<td align="right" colspan="2">
						<input class="button" type="submit" value="create" />
					</td>
				</tr>
			</table>
		</form>
	</div>
	<script type="text/javascript">
		$(document).ready(function () {
			$('form[name="createForm"]').on('submit', function (event) {
				if ($('#location').val().trim() == '') {
					$('span.errorMsg').empty().append('Please enter NAS tag.');
					$('#location').focus();
					return false;
				}
				if ($('#nasName').val().trim() == '') {
					$('span.errorMsg').empty().append('Please enter NAS name.');
					$('#nasName').focus();
					return false;
				}
				if ($('#nasIp').val().trim() == '') {
					$('span.errorMsg').empty().append('Please enter NAS IP.');
					$('#nasIp').focus();
					return false;
				}
				if ($('#nasCode').val().trim() == '') {
					$('span.errorMsg').empty().append('Please enter NAS code.');
					$('#nasName').focus();
					return false;
				}
				if ($('#rmLocation').val().trim() == '') {
					$('span.errorMsg').empty().append('Please enter RM location.');
					$('#rmLocation').focus();
					return false;
				}
			});
		});
	</script>
</body>
</html>