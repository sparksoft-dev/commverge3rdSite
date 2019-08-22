<!DOCTYPE html>
<html lang="en" style="height: 100%;">
<head>
	<title>Globelines Universal Access</title>
	<?php require 'head_includes.php'; ?>
</head>
<body style="padding-right:20px; padding-bottom:20px;">
	<div align=left>
		<h3 class="style1">Authentication Log</h3>
	</div>
	<div>
		<table cellspacing="0" cellpadding="0" width="100%" border="0">
			<tbody>
				<tr>
					<td class="smallFont" align="middle">
						<form name="authentication" action="<?php echo base_url('usages/authenticationLogProcess'); ?>" method="POST">
							<table cellspacing="1" cellpadding="3" style="background-color:#cccccc;" border="0">
								<tbody>
									<tr>
										<td class="xsmallFontWHTBG">&nbsp;&nbsp;Realm&nbsp;</td>
										<td class="xsmallFontWHTBG"><?php include 'allowed_realms.php'; ?></td>
									</tr>
									<tr>
										<td class="xsmallFontWHTBG"><font color="red">*</font>&nbsp;Username</td>
										<td class="xsmallFontWHTBG"><input class="textstyle" maxlength="60" size="15" name="userid" /></td>
									</tr>
									<tr>
										<td class="xsmallFontWHTBG" valign="top"><font color="red">*</font>&nbsp;Month</td>
										<td class="xsmallFontWHTBG">
											<select class="textstyle" size="1" name="month">
												<option value="0">-month-</option>
												<?php
												$months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
												for ($i = 0; $i < count($months); $i++) {
													echo intval($month) == ($i + 1) ? '<option value="'.($i + 1).'" selected>'.$months[$i].'</option>' : '<option value="'.($i + 1).'">'.$months[$i].'</option>';
												}
												?>
											</select>
											<select class="textstyle" size="1" name="year">
												<option value="0">-year-</option>
												<?php
												for ($i = 2020; $i >= 2001; $i--) {
													echo intval($year) == $i ? '<option value="'.$i.'" selected>'.$i.'</option>' : '<option value="'.$i.'">'.$i.'</option>';
												}
												?>
											</select>
										</td>
									</tr>
									<tr>
										<td class="xsmallFontWHTBG"><font color="red">*</font>&nbsp;Day</td>
										<td class="xsmallFontWHTBG">
											<select class="textstyle" size="1" name="day">
												<option value="0">-day-</option>
												<?php
												for ($i = 1; $i < 32; $i++) {
													echo intval($day) == $i ? '<option value="'.$i.'" selected>'.$i.'</option>' : '<option value="'.$i.'">'.$i.'</option>';
												}
												?>
											</select>
										</td>
									</tr>
									<tr>
										<td class="xsmallFontWHTBG"><font color="red">*</font>&nbsp;Hours</td>
										<td class="xsmallFontWHTBG"><input class="textstyle" maxlength="2" size="5" name="hour" />&nbsp;(24 hrs format)</td>
									</tr>
									<tr>
										<td align="middle" colspan="2" class="xsmallFontWHTBG">
											<input type="hidden" name="start" value="<?php echo $init ? '0' : $start; ?>" />
				                            <input type="hidden" name="max" value="<?php echo $max; ?>" />
											<input class="button2" type="submit" value="display" />
										</td>
									</tr>
								</tbody>
							</table>
						</form>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<!-- Result -->
	<div align="center">
		<span class="notificationMsg"><?php echo isset($message) && !is_null($message) ? $message : ''; ?></span>
	</div>
	<?php
	if (!$init) {
		if (count($authLogs) > 0) {
	?>
	<div>
		<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
			<tr>
				<td class="smallFont">
					Authentication Logs for <strong><?php echo $username.'@'.$realm; ?></strong> on <?php echo $month.'/'.$day.'/'.$year; ?>, hour <?php echo $hour; ?>
				</td>
			</tr>
		</table>
		<br />
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<tr>
				<td colspan="1" align=right>
					<form name="formmax" method="get">
						Records per page:
						<select class="textstyle" size="1" name="max">
							<option value="1" <?php echo intval($max) == 1 ? 'selected' : ''; ?>>1</option>
							<option value="5" <?php echo intval($max) == 5 ? 'selected' : ''; ?>>5</option>
							<option value="10" <?php echo intval($max) == 10 ? 'selected' : ''; ?>>10</option>
							<option value="20" <?php echo intval($max) == 20 ? 'selected' : ''; ?>>20</option>
							<option value="30" <?php echo intval($max) == 30 ? 'selected' : ''; ?>>30</option>
							<option value="40" <?php echo intval($max) == 40 ? 'selected' : ''; ?>>40</option>
							<option value="50" <?php echo intval($max) == 50 ? 'selected' : ''; ?>>50</option>
							<option value="60" <?php echo intval($max) == 60 ? 'selected' : ''; ?>>60</option>
							<option value="70" <?php echo intval($max) == 70 ? 'selected' : ''; ?>>70</option>
							<option value="80" <?php echo intval($max) == 80 ? 'selected' : ''; ?>>80</option>
							<option value="90" <?php echo intval($max) == 90 ? 'selected' : ''; ?>>90</option>
							<option value="100" <?php echo intval($max) == 100 ? 'selected' : ''; ?>>100</option>
						</select>
					</form>
				</td>
			</tr>
		</table>
		<br />
		<table cellspacing="1" cellpadding="2" width="100%" align="center" style="background-color:#cccccc;" border="0">
			<tr>
				<td class="smallFontGRNBG" align="middle">Date</td>
				<td class="smallFontGRNBG" align="middle">Time</td>
				<td class="smallFontGRNBG" align="middle">Username</td>
				<td class="smallFontGRNBG" align="middle">Reason</td>
				<td class="smallFontGRNBG" align="middle">NAS IP Address</td>
			</tr>
			<?php
			if (count($authLogs) > 0) {
				for ($i = 0; $i < count($authLogs); $i++) {
					$al = $authLogs[$i];
					$timestamp = isset($al['date_and_time']) ? $al['date_and_time'] : null;
					$date = '';
					$time = '';
					if (!is_null($timestamp)) {
						$parts = explode(' ', $timestamp);
						$date = $parts[0];
						$time = $parts[1];
					}
			?>
			<tr>
				<td class="smallFontWHTBG" align="center"><?php echo $date; ?></td>
				<td class="smallFontWHTBG" align="center"><?php echo $time; ?></td>
				<td class="smallFontWHTBG" align="center"><?php echo isset($al['username']) ? $al['username'] : ''; ?></td>
				<td class="smallFontWHTBG" align="center"><?php echo isset($al['reason']) ? $al['reason'] : ''; ?></td>
				<td class="smallFontWHTBG" align="center"><?php echo isset($al['nas_ip_address']) ? $al['nas_ip_address'] : ''; ?></td>
			</tr>
			<?php
				}
			} else {
			?>
			<tr>
				<td class="smallFontWHTBG" colspan="5"><font color="red">No Records Found</font></td>
			</tr>
			<?php
			}
			?>
		</table>
		<table cellpadding="0" cellspacing="0" border="0" width="100%" align="left">
			<tr>
				<td class="smallFont" align="left"> Total Record(s): <strong><?php echo $count; ?></strong>&nbsp;&nbsp;&nbsp;
				</td>
				<td class="smallFont" align="right"> 
					Records <strong><?php echo strval($start + 1); ?> to <?php echo strval($start + count($authLogs)); ?></strong> of <?php echo $count; ?>
				</td>
			</tr>
		</table>
	</div>
	<br /><br />
	<div>
		<table>
			<tr>
				<td colspan="8">
					<?php
					$currentPage = intval($start / $max);
					$pageStart = max($currentPage - 5, 0);
					$pageEnd = intval(min($pageStart + 10, $pages));
					if (intval($pages) >= 1) {
						echo 'Page | ';
					}
					if ($pageEnd - $pageStart < 10 && $pageStart != 0) {
						$pageStart = max($pageEnd - 10, 0);
					}
					if ($pageStart != 0) {
						$j = $pageStart - 1;
						echo '<a href="'.base_url("usages/authenticationLogProcess/1/".$username."/".$realm."/".
							$month."/".$day."/".$year."/".$hour."/".($j * $max)."/".$max).'">&lt;&lt;</a>';
						echo ' |';
					}
					for ($j = $pageStart; $j < $pageEnd; $j++) {
						echo '<a href="'.base_url("usages/authenticationLogProcess/1/".$username."/".$realm."/".
							$month."/".$day."/".$year."/".$hour."/".($j * $max)."/".$max).'">'.($j + 1).'</a>';
						echo ' | ';
					}
					if ($pageEnd < $pages) {
						$j = $pageEnd;
						echo '<a href="'.base_url("usages/authenticationLogProcess/1/".$username."/".$realm."/".
							$month."/".$day."/".$year."/".$hour."/".($j * $max)."/".$max).'">&gt;&gt;</a>';
						echo ' |';
					}
					?>
				</td>
			</tr>
		</table>
	</div>
	<?php
		} else {
	?>
	<br /><br />No records found.
	<?php
		}
	}
	?>
	<script type='text/javascript'>
		<?php
		if (!$init) {
		?>
		$('select[name="max"]').on('change', function (event) {
			var _this = $(this),
				max = _this.val(),
				location = '<?php echo base_url("usages/authenticationLogProcess/1/".$username."/".$realm."/".
					$month."/".$day."/".$year."/".$hour."/0"); ?>';
				location = location + '/' + max;
				window.location = location;
		});
		<?php
		}
		?>
		$('form[name="authentication"]').submit(function () {
			var username = $('input[name="userid"]'),
				month = $('select[name="month"]').val(),
				day = $('select[name="day"]').val(),
				year = $('select[name="year"]').val(),
				hour = $('input[name="hour"]');
			if (username.val().trim() == '') {
				alert('Please fill in the username field.');
				username.focus();
				return false;
			}
			if (parseInt(month) == 0 || parseInt(day) == 0 || parseInt(year) == 0) {
				alert('Please enter correct date period.');
				return false;
			}
			if (hour.val().trim() == '') {
				alert('Please fill in the hour field.');
				hour.focus();
				return false;
			} else {
				if (isNaN(hour.val())) {
					alert('Hour field must have an integer value.');
					hour.focus();
					return false;
				} else {
					if (parseInt(hour.val()) < 0 || parseInt(hour.val()) > 23) {
						alert('Hour field must be between 0 and 23.');
						hour.focus();
						return false;
					}
				}
			}
			return true;
		});
	</script>
</body>
</html>