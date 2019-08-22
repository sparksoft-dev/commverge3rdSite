<!DOCTYPE html>
<html lang="en" style="height: 100%;">
<head>
	<title>Globelines Universal Access</title>
	<?php require 'head_includes.php'; ?>
</head>
<body style="padding-right:20px; padding-bottom:20px;">
	<div align=left>
		<h3 class="style1">Account Transaction</h3>
	</div>
	<div>
		<table cellspacing="0" cellpadding="0" width="100%" border="0">
			<tbody>
				<tr>
					<td class="smallFont" align="middle">
						<form name="customer" action="<?php echo base_url('usages/accountTransactionProcess'); ?>" method="POST">
							<table cellspacing="1" cellpadding="3" style="background-color:#cccccc;" border="0">
								<tbody>
									<tr>
										<!--
										<td class="xsmallFontWHTBG">Realm</td>
										<td class="smallFontWHTBG" align="left">
											<?php //include 'allowed_realms.php'; ?>
										</td>
										-->
										<td class="xsmallFontWHTBG">User ID</td>
										<td class="smallFontWHTBG">
											<input class="textstyle" size="30" name="userid" />&nbsp;&nbsp;
										</td>
										<td class="xsmallFontWHTBG">
											&nbsp;Start Date&nbsp;&nbsp;
										</td>
										<td class="xsmallFontWHTBG">
											<select class="textstyle" size="1" name="start_month">
												<option value="0">-month-</option>
												<?php
												$months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
												for ($i = 0; $i < count($months); $i++) {
													echo intval($start_month) == ($i + 1) ? '<option value="'.($i + 1).'" selected>'.$months[$i].'</option>' : '<option value="'.($i + 1).'">'.$months[$i].'</option>';
												}
												?>
											</select>
											&nbsp;
											<select class="textstyle" size="1" name="start_day">
												<option value="0">-day-</option>
												<?php
												for ($i = 1; $i < 32; $i++) {
													echo intval($start_day) == $i ? '<option value="'.$i.'" selected>'.$i.'</option>' : '<option value="'.$i.'" '.($i == 1 ? 'selected' : '').'>'.$i.'</option>';
												}
												?>
											</select>
											&nbsp;
											<select class="textstyle" size="1" name="start_year">
												<option value="0">-year-</option>
												<?php
												for ($i = 2020; $i >= 2001; $i--) {
													echo intval($start_year) == $i ? '<option value="'.$i.'" selected>'.$i.'</option>' : '<option value="'.$i.'">'.$i.'</option>';
												}
												?>
											</select>
										</td>
									</tr>
									<tr>
										<!--
										<td class="xsmallFontWHTBG">User ID</td>
										<td class="smallFontWHTBG">
											<input class="textstyle" size="30" name="userid" />&nbsp;&nbsp;
										</td>
										-->
										<td class="smallFontWHTBG"></td>
										<td class="smallFontWHTBG"></td>
										<td class="xsmallFontWHTBG">
											&nbsp;End Date&nbsp;&nbsp;
										</td>
										<td class="xsmallFontWHTBG">
											<select class="textstyle" size="1" name="end_month">
												<option value="0">-month-</option>
												<?php
												$months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
												for ($i = 0; $i < count($months); $i++) {
													echo intval($end_month) == ($i + 1) ? '<option value="'.($i + 1).'" selected>'.$months[$i].'</option>' : '<option value="'.($i + 1).'">'.$months[$i].'</option>';
												}
												?>
											</select>
											&nbsp;
											<select class="textstyle" size="1" name="end_day">
												<option value="0">-day-</option>
												<?php
												for ($i = 1; $i < 32; $i++) {
													echo intval($end_day) == $i ? '<option value="'.$i.'" selected>'.$i.'</option>' : '<option value="'.$i.'" '.($i == 1 ? 'selected' : '').'>'.$i.'</option>';
												}
												?>
											</select>
											&nbsp;
											<select class="textstyle" size="1" name="end_year">
												<option value="0">-year-</option>
												<?php
												for ($i = 2020; $i >= 2001; $i--) {
													echo intval($end_year) == $i ? '<option value="'.$i.'" selected>'.$i.'</option>' : '<option value="'.$i.'">'.$i.'</option>';
												}
												?>
											</select>
										</td>
									</tr>
									<tr>
										<td class="smallFontWHTBG" colspan="4" align="center">
											<input type="hidden" name="realm" value="null" /><!-- remove this once the function of realm has been determined -->
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
	<div align="center">
		<span class="notificationMsg"><?php echo isset($message) && !is_null($message) ? $message : ''; ?></span>
	</div>
	<?php
	if (!$init) {
		if (count($transactions) > 0) {
	?>
	<br />
	<div>
		<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
			<tr>
				<td class="smallFont">
					Transaction History of <strong><?php echo isset($username) && !is_null($username) ? $username : ''; ?></strong> 
					from <?php echo $start_month.'/'.$start_day.'/'.$start_year.' to '.$end_month.'/'.$end_day.'/'.$end_year; ?>
				</td>
			</tr>
		</table>
		<br />
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<tr>
				<td colspan="1" align="right">
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
				<td class="smallFontGRNBG" align="center">Transaction Date</td>
				<td class="smallFontGRNBG" align="center">Transaction Mode</td>
				<td class="smallFontGRNBG" align="center">IP Address</td>
				<td class="smallFontGRNBG" align="center">Transaction By</td>
				<td class="smallFontGRNBG" align="center">Transaction Type</td>
				<td class="smallFontGRNBG" align="center">Remarks</td>
			</tr>
			<?php
			if (count($transactions) > 0) {
				for ($i = 0; $i < count($transactions); $i++) {
					$u = $transactions[$i];
			?>
			<tr>
				<td class="smallFontWHTBG" align="center"><?php echo isset($u['timestamp']) ? $u['timestamp'] : ''; ?></td>
				<td class="smallFontWHTBG" align="center"><?php echo isset($u['datatype']) ? $u['datatype'] : ''; ?></td>
				<td class="smallFontWHTBG" align="center"><?php echo isset($u['ipaddress']) ? $u['ipaddress'] : ''; ?></td>
				<td class="smallFontWHTBG" align="center"><?php echo isset($u['sysuser']) ? $u['sysuser'] : ''; ?></td>
				<td class="smallFontWHTBG" align="center"><?php echo isset($u['action']) ? $u['action'] : ''; ?></td>
				<td class="smallFontWHTBG" align="center"><?php echo isset($u['info']) ? $u['info'] : ''; ?></td>
			</tr>
			<?php
				}
			} else {
			?>
			<tr align="center">
				<td class="smallFontWHTBG" colspan="6"><font color="red">No Records Found</font></td>
			</tr>
			<?php
			}
			?>
		</table>
		<table cellpadding="0" cellspacing="0" border="0" width="100%" align="left">
			<tr>
				<td class="smallFont" align="left">
					Total Record(s): <strong><?php echo isset($count) && !is_null($count) ? $count : ''; ?></strong>&nbsp;&nbsp;&nbsp;
				</td>
				<?php
				if ($count != 0) {
				?>
				<td class="smallFont" align="right">
					Records <strong><?php echo strval($start + 1); ?> to <?php echo strval($start + count($transactions)); ?></strong> of <?php echo $count; ?>
				</td>
				<?php
				}
				?>
			</tr>
		</table>
	</div>
	<br /><br />
	<!-- pages control -->
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
						echo '<a href="'.base_url("usages/accountTransactionProcess/1/".$username."/".$start_month."/".$start_day."/".$start_year."/".
							$end_month."/".$end_day."/".$end_year."/".($j * $max)."/".$max).'">&lt;&lt;</a>';
						echo ' |';
					}
					for ($j = $pageStart; $j < $pageEnd; $j++) {
						echo '<a href="'.base_url("usages/accountTransactionProcess/1/".$username."/".$start_month."/".$start_day."/".$start_year."/".
							$end_month."/".$end_day."/".$end_year."/".($j * $max)."/".$max).'">'.($j + 1).'</a>';
						echo ' | ';
					}
					if ($pageEnd < $pages) {
						$j = $pageEnd;
						echo '<a href="'.base_url("usages/accountTransactionProcess/1/".$username."/".$start_month."/".$start_day."/".$start_year."/".
							$end_month."/".$end_day."/".$end_year."/".($j * $max)."/".$max).'">&gt;&gt;</a>';
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
	<script type="text/javascript">
		<?php
		if (!$init) {
		?>
		$('select[name="max"]').on('change', function (event) {
				var _this = $(this),
					max = _this.val(),
					location = '<?php echo base_url("usages/accountTransactionProcess/1/".$username."/".$start_month."/".$start_day."/".$start_year."/".
						$end_month."/".$end_day."/".$end_year."/0"); ?>';
					location = location + '/' + max;
					window.location = location;
			});
		<?php
		}
		?>
		$('form[name="customer"]').submit(function (event) {
			var username = $('input[name="userid"]'),
				startMonth = $('select[name="start_month"]').val(),
				startDay = $('select[name="start_day"]').val(),
				startYear = $('select[name="start_year"]').val(),
				endMonth = $('select[name="end_month"]').val(),
				endDay = $('select[name="end_day"]').val(),
				endYear = $('select[name="end_year"]').val();
			if (username.val().trim() == '') {
				alert("Please fill in the User ID field");
				username.focus();
				return false;
			}
			if (startMonth == '0' || startDay == '0' || startYear == '0') {
				alert("Please fill in the start date period correctly");
				return false;
			}
			if (endMonth == '0' || endDay == '0' || endYear == '0') {
				alert("Please fill in the end date period correctly");
				return false;
			}
			sDate = new Date(startYear, startMonth, startDay, 0, 0, 0, 0);
			eDate = new Date(endYear, endMonth, endDay, 0, 0, 0, 0);
			if (sDate > eDate) {
				alert('Start date should be before end date');
				return false;
			}
			return true;
		});
	</script>
</body>
</html>