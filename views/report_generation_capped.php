<!DOCTYPE html>
<html lang="en">
<head>
	<title>Globelines Universal Access</title>
	<?php require 'head_includes.php'; ?>
</head>
<body style="padding-right:20px; padding-bottom:20px;">
	<div align="left">
		<h3 class="style1">Report Generation: Capped Users</h3>
	</div>
	<div class="smallFontB" align="right">
		<a href="<?php echo base_url('reports/showReportGenerationPage'); ?>">Back to index</a>
	</div>
	<div align="left">
		<table>
			<tr>
				<td align="left" class="smallFont" colspan="2">
					<span class="notificationMsg">Info as of <strong><?php echo $yesterday; ?></strong></span>
				</td>
			</tr>
		</table>
		<form name="frmMain" action="<?php echo base_url('reports/generateSubscriberCappedReport'); ?>" method="post">
			<table cellspacing="0" cellpadding="3" border="0">
				<tbody>
					<tr>
						<td class="xsmallFontWHTBG">
							Date Period&nbsp;&nbsp;
						</td>
						<td class="xsmallFontWHTBG">
							<?php
							$datestartParts = is_null($datestart) ? null : explode('-', $datestart);
							$start_month = is_null($datestart) ? date('m') : $datestartParts[1];
							$start_day = is_null($datestart) ? 1 : $datestartParts[2];
							$start_year = is_null($datestart) ? date('Y') : $datestartParts[0];
							$dateendParts = is_null($dateend) ? null : explode('-', $dateend);
							$end_day = is_null($dateend) ? date('d') : $dateendParts[2];
							?>
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
							&nbsp;to&nbsp;
							<select class="textstyle" size="1" name="end_day">
								<option value="0">-day-</option>
								<?php
								for ($i = 1; $i < 32; $i++) {
									echo intval($end_day) == $i ? '<option value="'.$i.'" selected>'.$i.'</option>' : '<option value="'.$i.'">'.$i.'</option>';
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
						<td class="xsmallFontWHTBG" align="middle">
							<input type="hidden" name="max" value="<?php echo $max; ?>" />
							<input type="hidden" name="start" value="<?php echo (!is_null($count) && $count != 0) ? $start : '0'; ?>" />
							<input class="button2" type="submit" name="submit" value="list" />
							<input id="extractBtn" class="button2" type="button" name="extract" value="extract" />
						</td>
						<td class="xsmallFontWHTBG" align="middle" colspan="3">
							Count <input class="xsmallFontWHTBG" readonly value="<?php echo is_null($count) ? '' : $count; ?>" size="3" name="resultCount" />
							<?php
							if (is_null($count)) {
								$hiddenCount = '';
							} else {
								if (intval($count) == 0) {
									$hiddenCount = '';
								} else {
									$hiddenCount = $count;
								}
							}
							?>
							<input type="hidden" name="hiddenCount" value="<?php echo $hiddenCount; ?>" />
							<input class="button2" type="submit" name="submit" value="count" />
						</td>
					</tr>
				</tbody>
			</table>	
		</form>
		<table cellspacing="1" cellpadding="2" width="100%" align="center">
			<tr>
				<td>
					<span class="notificationMsg"><?php echo is_null($count) ? '' : ($count == 0 ? 'No records found.' : ''); ?></span>
					<span class="errorMsg"><?php echo $error; ?></span>
				</td>
			</tr>
		</table>
	</div>
	<?php
	if (!is_null($count) && $count != 0) {
	?>
	<div>
		<table cellpadding="1" cellspacing="2" border="0" width="100%">
			<tr>
				<td align="left" class="smallFont">
					Total accounts: <strong><?php echo $count; ?></strong>
				</td>
				<td align="right" class="smallFont">
					Records <strong><?php echo strval($start + 1); ?> to <?php echo strval($start + count($subscribers)); ?></strong> of <?php echo $count; ?>
				</td>
			</tr>
			<tr>
				<td align="right" colspan="2">
					<?php include 'form_select_max.php'; ?>
				</td>
			</tr>
		</table>
	</div>
	<div>
		<table cellspacing="1" cellpadding="3" width="100%" style="background-color:#000000;" border="0">
			<tr>
				<td class="smallFontGRYBG" noWrap align="middle">Username</td>
				<td class="smallFontGRYBG" noWrap align="middle">Service</td>
				<td class="smallFontGRYBG" noWrap align="middle">Upload Data (GB)</td>
				<td class="smallFontGRYBG" noWrap align="middle">Download Data (GB)</td>
				<td class="smallFontGRYBG" noWrap align="middle">Total Data (GB)</td>
				<td class="smallFontGRYBG" noWrap align="middle">Quota (GB)</td>
				<td class="smallFontGRYBG" noWrap align="middle">Event Timestamp</td>
			</tr>
			<?php
			for ($i = 0; $i < count($subscribers); $i++) {
				$subs = $subscribers[$i];
			?>
			<tr>
				<td class="xsmallFontWHTBG"><?php echo isset($subs['username']) && !is_null($subs['username']) ? $subs['username'] : ''; ?></td>
				<td class="xsmallFontWHTBG"><?php echo isset($subs['radiuspolicy']) && !is_null($subs['radiuspolicy']) ? $subs['radiuspolicy'] : ''; ?></td>
				<td class="xsmallFontWHTBG">
					<?php 
					if (isset($subs['upload_octets'])) {
						$val = $subs['upload_octets'] / (1024 * 1024 * 1024);
						$parts = explode('.', strval($val));
						echo $parts[0].(isset($parts[1]) ? '.'.substr($parts[1], 0, 2) : '');
					} else {
						echo '';
					}
					?>
				</td>
				<td class="xsmallFontWHTBG">
					<?php 
					if (isset($subs['download_octets'])) {
						$val = $subs['download_octets'] / (1024 * 1024 * 1024);
						$parts = explode('.', strval($val));
						echo $parts[0].(isset($parts[1]) ? '.'.substr($parts[1], 0, 2) : '');
					} else {
						echo '';
					}
					?>
				</td>
				<td class="xsmallFontWHTBG">
					<?php
					if (isset($subs['total_octets'])) {
						$val = $subs['total_octets'] / (1024 * 1024 * 1024);
						$parts = explode('.', strval($val));
						echo $parts[0].(isset($parts[1]) ? '.'.substr($parts[1], 0, 2) : '');
					} else {
						echo '';
					}
					?>
				</td>
				<td class="xsmallFontWHTBG">
					<?php
					if (isset($subs['hsqvalue'])) {
						$val = $subs['hsqvalue'] / (1024 * 1024 * 1024);
						$parts = explode('.', strval($val));
						echo $parts[0].(isset($parts[1]) ? '.'.substr($parts[1], 0, 2) : '');
					} else {
						echo '';
					}
					?>
				</td>
				<td class="xsmallFontWHTBG">
					<?php
					if (isset($subs['capped_date'])) {
						echo $subs['capped_date'];
						// $parts = explode(' ', $subs['capped_date']);
						// $timeParts = explode('.', $parts[1]);
						// echo $parts[0].' '.$timeParts[0].':'.$timeParts[1].':'.$timeParts[2].' '.$parts[2];
					} else {
						echo '';
					}
					?>
				</td>
			</tr>
			<?php
			}
			?>
		</table>
		<br />
		<table width="100%">
			<tr>
				<td colspan="10">
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
						echo '<a href="'.base_url("reports/generateSubscriberCappedReport/1/count/".
							$start_year."-".$start_month."-".$start_day."/".$start_year."-".$start_month."-".$end_day."/".($j * $max)."/".$max).'">&lt;&lt;</a>';
						echo ' |';
					}
					for ($j = $pageStart; $j < $pageEnd; $j++) {
						echo '<a href="'.base_url("reports/generateSubscriberCappedReport/1/count/".
							$start_year."-".$start_month."-".$start_day."/".$start_year."-".$start_month."-".$end_day."/".($j * $max)."/".$max).'">'.($j + 1).'</a>';
						echo ' | ';
					}
					if ($pageEnd < $pages) {
						$j = $pageEnd;
						echo '<a href="'.base_url("reports/generateSubscriberCappedReport/1/count/".
							$start_year."-".$start_month."-".$start_day."/".$start_year."-".$start_month."-".$end_day."/".($j * $max)."/".$max).'">&gt;&gt;</a>';
						echo ' |';
					}
					?>
				</td>
			</tr>
		</table>
	</div>
	<?php
	}
	?>
	<script type="text/javascript">
		<?php
		if (!is_null($count) && $count != 0) {
		?>
		$('select[name="max"]').on('change', function (event) {
			 var _this = $(this),
				max = _this.val(),
				location = '<?php echo base_url("reports/generateSubscriberCappedReport/1/count/".
					$start_year."-".$start_month."-".$start_day."/".$start_year."-".$start_month."-".$end_day."/0"); ?>';
			location = location + '/' + max;
			console.log(location);
			window.location = location;
		});
		<?php
		}
		?>
		$('form[name="frmMain"]').submit(function (event) {
			var startMonth = $('select[name="start_month"]').val(),
				startDay = $('select[name="start_day"]').val(),
				endDay = $('select[name="end_day"]').val(),
				startYear = $('select[name="start_year"]').val();
			if (parseInt(startMonth) == 0 || parseInt(startDay) == 0 || parseInt(endDay) == 0 || parseInt(startYear) == 0 || parseInt(startDay) > parseInt(endDay)) {
				alert('Please enter correct date period.');
				return false;
			}
			return true;
		});
		$(document).ready(function () {
			$('#extractBtn').on('click', function (evt) {
				var _this = $(this),
					startMonth = $('select[name="start_month"]').val(),
					startDay = $('select[name="start_day"]').val(),
					startYear = $('select[name="start_year"]').val(),
					endDay = $('select[name="end_day"]').val(),
					count = 0,
					interval = setInterval(function () {
						if (count == 0) {
							_this.val('extracting.');
							count++;
						} else if (count == 1) {
							_this.val('extracting..');
							count++;
						} else if (count == 2) {
							_this.val('extracting...');
							count++;
						} else if (count == 3) {
							count = 0;
						}
					}, 1000);
				_this.css('background-color', '#aaa');
				_this.prop('disabled', 'disabled');
				$.ajax({
					url: '<?php echo base_url("reports/ajaxGenerateSubscriberCappedReport"); ?>',
					type: 'POST',
					data: {
						'startMonth': startMonth,
						'startDay': startDay,
						'endDay': endDay,
						'startYear': startYear
					},
					timeout: 600000, //(milliseconds) 10 minutes
					success: function (resp) {
						var response = $.parseJSON(resp);
						if (parseInt(response.status) == 1) {
							console.log(response.filename);
							window.location.assign('<?php echo base_url("reports/sendFileToClient/' + response.filename + '"); ?>');
						} else {
							alert('Failed to generate file.\n\nPlease try again later.');
						}
						clearInterval(interval);
						_this.val('extract');
						_this.css('background-color', '#ccc');
						_this.prop('disabled', '');
					},
					error: function (error) {
						console.log(error);
						clearInterval(interval);
						_this.val('extract');
						_this.css('background-color', '#ccc');
						_this.prop('disabled', '');
						alert('An unexpected error occurred during the request.\n\nPlease try again later.');
					}
				});
			});
		});
	</script>
</body>
</html>