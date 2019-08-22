<!DOCTYPE html>
<html lang="en">
<head>
	<title>Globelines Subscriber Utility</title>
	<?php require 'head_includes.php'; ?>
	<style type="text/css">
		.button {
			font-weight: bold;
			font-size: 12px;
			color: #666;
			border-style: outset;
			font-family: verdana;
			background-color: #CEE373;
		}
		.regularColor {
			font-size: 10pt;
			color: #10188C;
			font-family: Verdana,Helvetica;
		}
		.smallFontREDB {
			font-weight: bold;
			font-size: 8pt;
			color: #C00;
			font-family: Verdana,Helvetica;
		}
		.regularGRYBG {
			font-size: 10pt;
			background: none repeat scroll 0% 0% #CEE373;
			font-family: Verdana,Helvetica;
		}
	</style>
</head>
<body statictyle="height:100%; background-color:white;">
	<div id="wrap">
		<div id="main">
			<table cellspacing="0" cellpadding="0" width="99%" align="center" border="0">
				<tbody>
					<tr>
						<td>
							<a href="http://site.globe.com.ph/web/broadband"><img alt="" src="<?php echo base_url('static/img/globe-logo-util.jpg'); ?>" border="0" /></a>
						</td>
						<td align="right">
							<a href="http://site.globe.com.ph/web/broadband"><img alt="" src="<?php echo base_url('static/img/glbb.gif'); ?>" border="0" /></a>
						</td>
					</tr>
				</tbody>
			</table>
			<table cellspacing="0" cellpadding="0" width="99%" align="center" border="0">
				<tbody>
					<tr>
						<td style="background-color:#66cc33; font-size:0px;" colspan="2"><img height="2" alt="" src="<?php echo base_url('static/img/blank.gif'); ?>" width="1" border="0" /></td>
					</tr>
					<tr>
						<td align="right">[<a class="smallFontREDB" href="<?php echo base_url('utility/logout'); ?>">Sign Out</a>]</td>
					</tr>
					<tr>
						<td valign="top" height="350" colspan="2">
							<?php include 'customer_utility_menu.php'; ?>
							<table width="80%" cellspacing="0" cellpadding="4" border="0" align="center">
								<tbody>
									<tr>
										<td align="center">
											<img border="0" src="<?php echo base_url('static/img/usginq.gif') ?>" />
										</td>
									</tr>
									<tr>
										<td class="regular">
											This utility provides subscribers with an online record of their monthly Internet usage. 
											Please take note that the official bill statement is the printed invoice provided to your billing address every month. 
											<!--Your current ipaddress is 120.28.76.214-->
										</td>
									</tr>
									<tr>
										<td>
											<br /><br />
											<?php
											if ($init) {
											?>
											<form method="POST" action="<?php echo base_url('utility/usage'); ?>" name="usages">
												<input type="hidden" name="action" value="display" />
												<table cellspacing="0" cellpadding="3" border="0" align="center">
													<tbody>
														<tr>
															<td class="regular" valign="top">Month</td>
															<td>
																<select name="start_month" size="1">
																	<option value="0">-month-</option>
																	<?php
																	$months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
																	for ($i = 0; $i < count($months); $i++) {
																		echo intval($month) == ($i + 1) ? '<option value="'.($i + 1).'" selected>'.$months[$i].'</option>' : '<option value="'.($i + 1).'">'.$months[$i].'</option>';
																	}
																	?>
																</select>
															</td>
														</tr>
														<tr>
															<td class="regular" valign="top">Date Range</td>
															<td class="regular">
																<select name="start_day" size="1">
																	<option value="0">-day-</option>
																	<?php
																	for ($i = 1; $i < 32; $i++) {
																		echo intval($start_day) == $i ? '<option value="'.$i.'" selected>'.$i.'</option>' : '<option value="'.$i.'" '.($i == 1 ? 'selected' : '').'>'.$i.'</option>';
																	}
																	?>
																</select>
																&nbsp;to&nbsp;
																<select name="end_day" size="1">
																	<option value="0">-day-</option>
																	<?php
																	for ($i = 1; $i < 32; $i++) {
																		echo intval($end_day) == $i ? '<option value="'.$i.'" selected>'.$i.'</option>' : '<option value="'.$i.'" '.($i == 1 ? 'selected' : '').'>'.$i.'</option>';
																	}
																	?>
																</select>
															</td>
														</tr>
														<tr>
															<td class="regular" valign="top">Year</td>
															<td>
																<select name="start_year" size="1">
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
															<td align="center" colspan="2">
																<br /><br />
																<?php
																if ($init) {
																	$orderStr = 'event_timestamp-desc';
																} else {
																	$orderStr = $order['column'].'-'.$order['dir'];
																}
																?>
																<input type="hidden" name="order" value="<?php echo $orderStr; ?>" />
																<input class="button" type="submit" value="Display" />
															</td>
														</tr>
													</tbody>
												</table>
											</form>
											<?php
											} else {
											?>
											<div class="smallFont" align="center">Usage for the month of <?php echo $monthText.' '.$start_day.' to '.$end_day.', '.$year; ?></div>
											<?php
											$totalMins = intval($totalSessionTime / 60);
											$totalSecs = $totalSessionTime % 60;
											?>	
											<div class="smallFontREDB" align="center">Total Session Time&nbsp;&nbsp;<strong><?php echo $totalMins.':'.$totalSecs; ?></strong></div>
											<br />
											<div class="smallFont"><strong><?php echo $count; ?> records found</strong></div> 
											<table style="background-color:#9ccfff;" border="0" cellpadding="4" cellspacing="1" width="100%">
												<tbody>
													<tr>		
														<td class="regularGRYBG" align="center" nowrap="">Login&nbsp;
															<a href="<?php echo base_url('utility/usage/1/start_time-asc/'.$month.'/'.$start_day.'/'.$end_day.'/'.$year); ?>">
																<img src ="<?php echo base_url('static/img/up.gif') ?>" border="0" alt="ascending" />
															</a>
															<a href="<?php echo base_url('utility/usage/1/start_time-desc/'.$month.'/'.$start_day.'/'.$end_day.'/'.$year); ?>">
																<img src ="<?php echo base_url('static/img/down.gif'); ?>" border="0" alt="descending" />
															</a>
														</td>
														<td class="regularGRYBG" align="center" nowrap="">Logout</td>
														<td class="regularGRYBG" align="center" nowrap="">Source&nbsp;
															<a href="<?php echo base_url('utility/usage/1/innove_source_access-asc/'.$month.'/'.$start_day.'/'.$end_day.'/'.$year); ?>">
																<img src ="<?php echo base_url('static/img/up.gif') ?>" border="0" alt="ascending" />
															</a>
															<a href="<?php echo base_url('utility/usage/1/innove_source_access-desc/'.$month.'/'.$start_day.'/'.$end_day.'/'.$year); ?>">
																<img src ="<?php echo base_url('static/img/down.gif'); ?>" border="0" alt="descending" />
															</a>
														</td>
														<td class="regularGRYBG" align="center" nowrap="">Access</td>
														<td class="regularGRYBG" align="center" nowrap="">Caller ID&nbsp;
															<a href="<?php echo base_url('utility/usage/1/calling_station_id-asc/'.$month.'/'.$start_day.'/'.$end_day.'/'.$year); ?>">
																<img src ="<?php echo base_url('static/img/up.gif') ?>" border="0" alt="ascending" />
															</a>
															<a href="<?php echo base_url('utility/usage/1/calling_station_id-desc/'.$month.'/'.$start_day.'/'.$end_day.'/'.$year); ?>">
																<img src ="<?php echo base_url('static/img/down.gif'); ?>" border="0" alt="descending" />
															</a>
														</td>
														<td class="regularGRYBG" align="center" nowrap="">IP Address</td>
														<td class="regularGRYBG" align="center" nowrap="">Duration&nbsp;
															<a href="<?php echo base_url('utility/usage/1/acct_session_time-asc/'.$month.'/'.$start_day.'/'.$end_day.'/'.$year); ?>">
																<img src ="<?php echo base_url('static/img/up.gif') ?>" border="0" alt="ascending" />
															</a>
															<a href="<?php echo base_url('utility/usage/1/acct_session_time-desc/'.$month.'/'.$start_day.'/'.$end_day.'/'.$year); ?>">
																<img src ="<?php echo base_url('static/img/down.gif'); ?>" border="0" alt="descending" />
															</a>
														</td>

													</tr>
													<?php
													if ($count > 0) {
														for ($i = 0; $i < count($usages); $i++) {
															$u = $usages[$i];
													?>
													<tr>
														<td class="smallFontWHTBG">
															<?php echo isset($u['start_time']) && !is_null($u['start_time']) ? date('Y-m-d H:i:s', intval($u['start_time'])) : ''; ?>
														</td>
														<td class="smallFontWHTBG">
															<?php echo isset($u['stop_time']) && !is_null($u['stop_time']) ? date('Y-m-d H:i:s', intval($u['stop_time'])) : ''; ?>
														</td>
														<td class="smallFontWHTBG">
															<?php echo isset($u['innove_source_access']) && !is_null($u['innove_source_access']) ? ($u['innove_source_access'] == '' ? 'DSL' : $u['innove_source_access']) : 'DSL'; ?>
														</td>
														<td class="smallFontWHTBG">
															<?php echo isset($u['nas_ip_address']) && !is_null($u['nas_ip_address']) ? $u['nas_ip_address'] : ''; ?>
														</td>
														<td class="smallFontWHTBG">
															<?php echo isset($u['calling_station_id']) && !is_null($u['calling_station_id']) ? $u['calling_station_id'] : ''; ?>
														</td>
														<td class="smallFontWHTBG">
															<?php echo isset($u['framed_ip_address']) && !is_null($u['framed_ip_address']) ? $u['framed_ip_address'] : ''; ?>
														</td>
														<td class="smallFontWHTBG">
															<?php
															if (isset($u['acct_session_time']) && !is_null($u['acct_session_time'])) {
																$totalSeconds = intval($u['acct_session_time']);
																$mins = intval($totalSeconds / 60);
																$secs = $totalSeconds % 60;
																echo $mins.':'.$secs;
															}
															?>
														</td>
													</tr>
													<?php
														}
													} else {
													?>
													<tr>
														<td colspan="7">No records found.</td>
													</tr>
													<?php
													}
													?>
												</tbody>
											</table>
											<?php
											}
											?>
										</td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div id="footer" style="margin-top:-20px;">
		<div style="padding-right:5px;" vertical-align="center">
			<img alt="" src="<?php echo base_url('static/img/globe-logo-util.jpg'); ?>" border="0" width="90px" />
			<span style="position:relative; top:-15px;">&copy; 2014 Globelines Broadband - Globe Telecom, Inc. All Rights Reserved</span>
		</div>
	</div>
	<script type="text/javascript">
	$(document).ready(function () {
		<?php
		if ($init) {
		?>
		$('form[name="usages"]').submit(function (event) {
			var startMonth = $('select[name="start_month"]').val(),
				startDay = $('select[name="start_day"]').val(),
				endDay = $('select[name="end_day"]').val(),
				startYear = $('select[name="start_year"]').val();
			if (parseInt(startMonth) == 0 || parseInt(startDay) == 0 || parseInt(endDay) == 0 || parseInt(startYear) == 0) {
				alert('Invalid date range');
				return false;
			} else {
				if (parseInt(startDay) > parseInt(endDay)) {
					alert('Start date must not be before end date');
					return false;
				}
			}
			return true;
		});
		<?php
		}
		?>
	});
	</script>
</body>
</html>


