<!DOCTYPE html>
<html lang="en">
<head>
	<title>Globelines Universal Access</title>
	<?php require 'head_includes.php'; ?>
</head>
<body style="padding-right:20px; padding-bottom:20px;">
	<div align="left">
		<h3 class="style1">Account Usages</h3>
	</div>
	<div>
		<table cellspacing="0" cellpadding="0" width="100%" border="0">
			<tbody>
				<tr>
					<td class="smallFont" align="middle">
						<form name="customer" action="<?php echo base_url('usages/accountUsagesProcess'); ?>" method="POST">
							<table cellspacing="1" cellpadding="3" style="background-color:#cccccc;" border="0">
								<tbody>
									<tr>
										<td class="xsmallFontWHTBG">Realm</td>
										<td class="smallFontWHTBG" align="left">
											<?php include 'allowed_realms.php'; ?>
										</td>
										<td class="xsmallFontWHTBG">
											Date Period&nbsp;&nbsp;
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
									</tr>
									<tr>
										<td class="xsmallFontWHTBG">Username</td>
										<td class="smallFontWHTBG" colspan="3">
											<?php
				                            if ($init) {
				                                $orderStr = 'id-asc';
				                            } else {
				                                $orderStr = $order['column'].'-'.$order['dir'];
				                            }
				                            ?>
				                            <input type="hidden" name="order" value="<?php echo $orderStr; ?>" />
				                            <input type="hidden" name="start" value="<?php echo $init ? '0' : $start; ?>" />
				                            <input type="hidden" name="max" value="<?php echo $max; ?>" />
											<input class="textstyle" size="30" name="userid" />&nbsp;&nbsp;
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
		if (count($usages) > 0) {
	?>
	<div>
		<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
			<tr>
				<td>
					<div class="smallFont">User: <strong><?php echo isset($username) ? $username.'@'.$realm : ''; ?></strong></div>
				</td>
			</tr>
			<tr>
				<td class="smallFont">
					Account usages for <strong><?php echo $start_month.'/'.$start_day.'-'.$end_day.'/'.$start_year; ?></strong>
					<?php
					$totalSeconds = $totalSessionTime;
					$mins = intval($totalSeconds / 60);
					$secs = $totalSeconds % 60;
					$secs = '0'.$secs;
					?>
					Total Session Time: <strong><?php echo isset($totalSessionTime) ? $mins.':'.substr($secs, -2) : ''; ?></strong>
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
				<td class="smallFontGRNBG" align="center">Time Login&nbsp;
					<a href="<?php echo base_url('usages/accountUsagesProcess/1/start_time-asc/'.($username == '' ? 'null' : $username).'/'.
						($realm == '' ? 'null' : $realm).'/'.
						$start_month.'/'.$start_day.'/'.$end_day.'/'.$start_year.'/'.$start.'/'.$max); ?>">
						<img src ="<?php echo base_url('static/img/up.gif') ?>" border="0" alt="ascending" />
					</a>
					<a href="<?php echo base_url('usages/accountUsagesProcess/1/start_time-desc/'.($username == '' ? 'null' : $username).'/'.
						($realm == '' ? 'null' : $realm).'/'.
						$start_month.'/'.$start_day.'/'.$end_day.'/'.$start_year.'/'.$start.'/'.$max); ?>">
						<img src ="<?php echo base_url('static/img/down.gif'); ?>" border="0" alt="descending" />
					</a>
				</td>
				<td class="smallFontGRNBG" align="center">Time Logout</td>
				<td class="smallFontGRNBG" align="center">Duration (mins)&nbsp;
					<a href="<?php echo base_url('usages/accountUsagesProcess/1/acct_session_time-asc/'.($username == '' ? 'null' : $username).'/'.
						($realm == '' ? 'null' : $realm).'/'.
						$start_month.'/'.$start_day.'/'.$end_day.'/'.$start_year.'/'.$start.'/'.$max); ?>">
						<img src ="<?php echo base_url('static/img/up.gif') ?>" border="0" alt="ascending" />
					</a>
					<a href="<?php echo base_url('usages/accountUsagesProcess/1/acct_session_time-desc/'.($username == '' ? 'null' : $username).'/'.
						($realm == '' ? 'null' : $realm).'/'.
						$start_month.'/'.$start_day.'/'.$end_day.'/'.$start_year.'/'.$start.'/'.$max); ?>">
						<img src ="<?php echo base_url('static/img/down.gif'); ?>" border="0" alt="descending" />
					</a>
				</td>
				<td class="smallFontGRNBG" align="center">IP Address</td>
				<td class="smallFontGRNBG" align="center">Caller ID</td>
				<td class="smallFontGRNBG" align="center">NAS IP Address</td>
				<!--
				<td class="smallFontGRNBG" align="center">Port Type</td>
				<td class="smallFontGRNBG" align="center">Source Access</td>
				-->
				<td class="smallFontGRNBG" align="center">Download</td>
				<td class="smallFontGRNBG" align="center">Upload</td>
			</tr>
			<?php
			if (count($usages) > 0) {
				for ($i = 0; $i < count($usages); $i++) {
					$u = $usages[$i];
			?>
			<tr>
				<td class="smallFontWHTBG" align="center">
					<?php echo isset($u['start_time']) && !is_null($u['start_time']) ? date('Y-m-d H:i:s', intval($u['start_time'])) : ''; ?>
				</td>
				<td class="smallFontWHTBG" align="center">
					<?php echo isset($u['stop_time']) && !is_null($u['stop_time']) ? date('Y-m-d H:i:s', intval($u['stop_time'])) : ''; ?>
				</td>
				<td class="smallFontWHTBG" align="center">
					<?php
					if (isset($u['acct_session_time']) && !is_null($u['acct_session_time'])) {
						$totalSeconds = intval($u['acct_session_time']);
						$mins = intval($totalSeconds / 60);
						$secs = $totalSeconds % 60;
						$secs = '0'.$secs;
						echo $mins.':'.substr($secs, -2);
					}
					?>
				</td>
				<td class="smallFontWHTBG" align="center">
					<?php echo isset($u['framed_ip_address']) && !is_null($u['framed_ip_address']) ? $u['framed_ip_address'] : ''; ?>
				</td>
				<td class="smallFontWHTBG" align="center">
					<?php echo isset($u['calling_station_id']) && !is_null($u['calling_station_id']) ? $u['calling_station_id'] : ''; ?>
				</td>
				<td class="smallFontWHTBG" align="center">
					<?php echo isset($u['nas_ip_address']) && !is_null($u['nas_ip_address']) ? $u['nas_ip_address'] : ''; ?>
				</td>
				<!--
				<td class="smallFontWHTBG" align="center">
					<?php //echo isset($u['nas_port_type']) && !is_null($u['nas_port_type']) ? $u['nas_port_type'] : ''; ?>
				</td>
				<td class="smallFontWHTBG" align="center">
					<?php //echo isset($u['innove_source_access']) && !is_null($u['innove_source_access']) ? ($u['innove_source_access'] == '' ? 'DSL' : $u['innove_source_access']) : 'DSL'; ?>
				</td>
				-->
				<td class="smallFontWHTBG" align="center">
					<?php
					$gb = 1000 * 1000 * 1000;
					$mb = 1000 * 1000;
					$kb = 1000;
					$dl = '';
					$num = $u['acct_input_octets'];
					if (floatval($num) >= $gb) {
						$tmp = floatval($num) / 1000000000;
						$tmp = round($tmp, 2);
						$dl = strval($tmp).' GB';
					} else if (floatval($num) < $gb && floatval($num) >= $mb) {
						$tmp = floatval($num) / 1000000;
						$tmp = round($tmp, 2);
						$dl = strval($tmp).' MB';
					} else if (floatval($num) < $mb && floatval($num) >= $kb) {
						$tmp = floatval($num) / 1000;
						$tmp = round($tmp, 2);
						$dl = strval($tmp).' KB';
					} else if (floatval($num) < $kb) {
						$dl = strval($num);
					}
					echo $dl;
					?>
				</td>
				<td class="smallFontWHTBG" align="center">
					<?php
					$ul = '';
					$num = $u['acct_output_octets'];
					if (floatval($num) >= $gb) {
						$tmp = floatval($num) / 1000000000;
						$tmp = round($tmp, 2);
						$ul = strval($tmp).' GB';
					} else if (floatval($num) < $gb && floatval($num) >= $mb) {
						$tmp = floatval($num) / 1000000;
						$tmp = round($tmp, 2);
						$ul = strval($tmp).' MB';
					} else if (floatval($num) < $mb && floatval($num) >= $kb) {
						$tmp = floatval($num) / 1000;
						$tmp = round($tmp, 2);
						$ul = strval($tmp).' KB';
					} else if (floatval($num) < $kb) {
						$ul = strval($num);
					}
					echo $ul;
					?>
				</td>
			</tr>
			<?php
				}
			} else {
			?>
			<tr>
				<td class="smallFontWHTBG" colspan="8"><font color="red">No Records Found</font></td>
			</tr>
			<?php
			}
			?>
		</table>
		<table cellpadding="0" cellspacing="0" border="0" width="100%" align="left">
			<tr>
				<td class="smallFont" align="left">
					Total Record(s): <strong><?php echo isset($count) ? $count : ''; ?></strong>&nbsp;&nbsp;&nbsp;
				</td>
				<td class="smallFont" align="right">
					Records <strong><?php echo strval($start + 1); ?> to <?php echo strval($start + count($usages)); ?></strong> of <?php echo $count; ?>
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
						echo '<a href="'.base_url("usages/accountUsagesProcess/1/".$order['column']."-".$order['dir']."/".
							($username == "" ? "null" : $username)."/".($realm == "" ? "null" : $realm)."/".
							$start_month."/".$start_day."/".$end_day."/".$start_year."/".($j * $max)."/".$max).'">&lt;&lt;</a>';
						echo ' |';
					}
					for ($j = $pageStart; $j < $pageEnd; $j++) {
						echo '<a href="'.base_url("usages/accountUsagesProcess/1/".$order['column']."-".$order['dir']."/".
							($username == "" ? "null" : $username)."/".($realm == "" ? "null" : $realm)."/".
							$start_month."/".$start_day."/".$end_day."/".$start_year."/".($j * $max)."/".$max).'">'.($j + 1).'</a>';
						echo ' | ';
					}
					if ($pageEnd < $pages) {
						$j = $pageEnd;
						echo '<a href="'.base_url("usages/accountUsagesProcess/1/".$order['column']."-".$order['dir']."/".
							($username == "" ? "null" : $username)."/".($realm == "" ? "null" : $realm)."/".
							$start_month."/".$start_day."/".$end_day."/".$start_year."/".($j * $max)."/".$max).'">&gt;&gt;</a>';
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
				location = '<?php echo base_url("usages/accountUsagesProcess/1/".$order["column"]."-".$order["dir"]."/".
					($username == "" ? "null" : $username)."/".($realm == "" ? "null" : $realm)."/".
					$start_month."/".$start_day."/".$end_day."/".$start_year."/0"); ?>';
				location = location + '/' + max;
				window.location = location;
		});
		<?php
		}
		?>
		$('form[name="customer"]').submit(function (event) {
			var startMonth = $('select[name="start_month"]').val(),
				startDay = $('select[name="start_day"]').val(),
				endDay = $('select[name="end_day"]').val(),
				startYear = $('select[name="start_year"]').val(),
				username = $('input[name="userid"]');
			if (username.val().trim() == '') {
				alert('Please fill in the username field.');
				username.focus();
				return false;
			}
			if (parseInt(startMonth) == 0 || parseInt(startDay) == 0 || parseInt(endDay) == 0 || parseInt(startYear) == 0 || parseInt(startDay) > parseInt(endDay)) {
				alert('Please enter correct date period.');
				return false;
			}
			return true;
		});
	</script>
</body>
</html>