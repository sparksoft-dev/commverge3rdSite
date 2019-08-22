<!DOCTYPE html>
<html lang="en">
<head>
	<title>Globelines Universal Access</title>
	<?php require 'head_includes.php'; ?>
</head>
<body style="padding-right:20px; padding-bottom:20px;">
	<div>
		<h3 class="style1"><a href="<?php echo base_url('main/showIpaddressesIndex/1'); ?>" style="font-size:14px;">IP Addresses</a>&nbsp;|&nbsp;IPv6 Addresses</h3>
	</div>
	<div align="right" class="smallFontB">
		<a href="<?php echo base_url('main/createIpV6AddressForm'); ?>">Add Record</a>&nbsp;|&nbsp;
		<a href="<?php echo base_url('main/processBulkIpV6AddressCreation/upload'); ?>">Add Bulk</a>&nbsp;|&nbsp;
		<a href="<?php echo base_url('main/manageBngCounts'); ?>">Manage BNG</a>
	</div>
	<br />
	<div>
		<form name="formloc" action="<?php echo base_url('main/showIpV6AddressesIndex'); ?>" method="POST">
			<table cellspacing="1" cellpadding="3" border="0">
				<tbody>
					<tr>
						<td align="center" class="xsmallFontWHTBG">
							<br />
							<select name="location">
								<option value="">-select location-</option>
								<?php
								for ($i = 0; $i < count($locations); $i++) {
									echo '<option value="'.$locations[$i].'" '.($location == $locations[$i] ? 'selected' : '').'>'.$locations[$i].'</option>';
								}
								?>
							</select>
						</td>
						<td align="center" class="xsmallFontWHTBG"> 
							IPv6 Address Filter<br /><input name="ipaddress" value="<?php echo isset($ipaddress) && !is_null($ipaddress) ? $ipaddress : ''; ?>" />
						</td>
						<td align="center" class="xsmallFontWHTBG">
							Username Filter<br /><input name="username" value="<?php echo isset($username) && !is_null($username) ? $username : ''; ?>" autocomplete="off" />
						</td>
						<td align="left" class="xsmallFontWHTBG">
							<br />
							<?php
							if (is_null($order)) {
								$orderStr = 'IPV6USED-asc';
								$order = array('column' => 'IPV6USED', 'dir' => 'asc');
							} else {
								$orderStr = $order['column'].'-'.$order['dir'];
							}
							?>
							<input type="hidden" name="order" value="<?php echo $orderStr; ?>" />
							<input type="hidden" name="start" value="<?php echo $start; ?>" />
							<input type="hidden" name="max" value="<?php echo $max; ?>" />
							<input type="submit" valign="center" class="button2" name="btnRefresh" value="refresh" />
						</td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
	<div align="center">
		<table cellspacing="1" cellpadding="2" width="100%" align="center">
			<tr>
				<td align ="center">
					<span class="notificationMsg"><?php echo isset($message) && !is_null($message) ? $message : ''; ?></span>
					<span class="errorMsg"><?php echo isset($error) && !is_null($error) ? $error : ''; ?></span>
				</td>
			</tr>
		</table>
	</div>
	<div>
		<table cellpadding="1" cellspacing="2" border="0" width="100%">
			<tr>
				<td align="left">
					Total records: <strong><?php echo $count; ?></strong>
				</td>
				<td align="right">
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
	</div>
	<div>
		<table width="100%" align="center">
			<tbody>
				<tr>
					<td>
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

						if (!is_null($username)) {
							$usernameStr = str_replace('*', '~', $username);
							$usernameStr = str_replace('@', '%', $usernameStr);
							$usernameStr = str_replace(' ', '_', $usernameStr);
						} else {
							$usernameStr = 'null';
						}

						if ($pageStart != 0) {
							$j = $pageStart - 1;
							echo '<a href="'.base_url("main/showIpV6AddressesIndex/1/".$order['column']."-".$order['dir']."/".
								(is_null($ipaddress) ? "null" : str_replace('*', '~', str_replace(" ", "_", $ipaddress)))."/".$usernameStr."/".
								(is_null($location) ? "null" : str_replace(" ", "_", $location))."/".($j * $max)."/".$max).'">&lt;&lt;</a>';
							echo ' |';
						}
						for ($j = $pageStart; $j < $pageEnd; $j++) {
							echo '<a href="'.base_url("main/showIpV6AddressesIndex/1/".$order['column']."-".$order['dir']."/".
								(is_null($ipaddress) ? "null" : str_replace('*', '~', str_replace(" ", "_", $ipaddress)))."/".$usernameStr."/".
								(is_null($location) ? "null" : str_replace(" ", "_", $location))."/".($j * $max)."/".$max).'">'.($j + 1).'</a>';
							echo ' | ';
						}
						if ($pageEnd < $pages) {
							$j = $pageEnd;
							echo '<a href="'.base_url("main/showIpV6AddressesIndex/1/".$order['column']."-".$order['dir']."/".
								(is_null($ipaddress) ? "null" : str_replace('*', '~', str_replace(" ", "_", $ipaddress)))."/".$usernameStr."/".
								(is_null($location) ? "null" : str_replace(" ", "_", $location))."/".($j * $max)."/".$max).'">&gt;&gt;</a>';
							echo ' |';
						}
						?>
					</td>
					<?php
					if ($count > 0) {
					?>
					<td align="right">
						Records <strong><?php echo strval($start + 1); ?> to <?php echo strval($start + count($ipaddresses)); ?></strong> of <?php echo $count; ?>
					</td>
					<?php
					}
					?>
				</tr>
			</tbody>
		</table>
		<table cellspacing="1" cellpadding="3" style="background-color:#cccccc;" border="0" width="100%" id="iptable">
			<tbody>
				<tr>
					<td class="smallFontGRYBG" align="left" nowrap width="5%">Action</td>
					<td class="smallFontGRYBG" align="left" nowrap>IPv6 Address&nbsp;
						<a href="<?php echo base_url('main/showIpV6AddressesIndex/1/IPV6ADDR-asc/'.
							(is_null($ipaddress) ? 'null' : str_replace('*', '~', str_replace(' ', '_', $ipaddress))).
							'/'.(is_null($username) ? 'null' : str_replace(' ', '_', $username)).'/'.(is_null($location) ? 'null' : str_replace(' ', '_', $location)).
							'/'.$start.'/'.$max); ?>">
							<img src ="<?php echo base_url('static/img/up.gif'); ?>" border="0" alt="ascending" />
						</a>
						<a href="<?php echo base_url('main/showIpV6AddressesIndex/1/IPV6ADDR-desc/'.
							(is_null($ipaddress) ? 'null' : str_replace('*', '~', str_replace(' ', '_', $ipaddress))).
							'/'.(is_null($username) ? 'null' : str_replace(' ', '_', $username)).'/'.(is_null($location) ? 'null' : str_replace(' ', '_', $location)).
							'/'.$start.'/'.$max); ?>">
							<img src ="<?php echo base_url('static/img/down.gif'); ?>" border="0" alt="descending" />
						</a>
					</td>
					<td class="smallFontGRYBG" align="left" nowrap>Location&nbsp;
						<a href="<?php echo base_url('main/showIpV6AddressesIndex/1/LOCATION-asc/'.
							(is_null($ipaddress) ? 'null' : str_replace('*', '~', str_replace(' ', '_', $ipaddress))).
							'/'.(is_null($username) ? 'null' : str_replace(' ', '_', $username)).'/'.(is_null($location) ? 'null' : str_replace(' ', '_', $location)).
							'/'.$start.'/'.$max); ?>">
							<img src ="<?php echo base_url('static/img/up.gif'); ?>" border="0" alt="ascending" />
						</a>
						<a href="<?php echo base_url('main/showIpV6AddressesIndex/1/LOCATION-desc/'.
							(is_null($ipaddress) ? 'null' : str_replace('*', '~', str_replace(' ', '_', $ipaddress))).
							'/'.(is_null($username) ? 'null' : str_replace(' ', '_', $username)).'/'.(is_null($location) ? 'null' : str_replace(' ', '_', $location)).
							'/'.$start.'/'.$max); ?>">
							<img src ="<?php echo base_url('static/img/down.gif'); ?>" border="0" alt="descending" />
						</a>
					</td>
					<td class="smallFontGRYBG" align="left" nowrap>Username&nbsp;
						<a href="<?php echo base_url('main/showIpV6AddressesIndex/1/USERNAME-asc/'.
							(is_null($ipaddress) ? 'null' : str_replace('*', '~', str_replace(' ', '_', $ipaddress))).
							'/'.(is_null($username) ? 'null' : str_replace(' ', '_', $username)).'/'.(is_null($location) ? 'null' : str_replace(' ', '_', $location)).
							'/'.$start.'/'.$max); ?>">
							<img src ="<?php echo base_url('static/img/up.gif'); ?>" border="0" alt="ascending" />
						</a>
						<a href="<?php echo base_url('main/showIpV6AddressesIndex/1/USERNAME-desc/'.
							(is_null($ipaddress) ? 'null' : str_replace('*', '~', str_replace(' ', '_', $ipaddress))).
							'/'.(is_null($username) ? 'null' : str_replace(' ', '_', $username)).'/'.(is_null($location) ? 'null' : str_replace(' ', '_', $location)).
							'/'.$start.'/'.$max); ?>">
							<img src ="<?php echo base_url('static/img/down.gif'); ?>" border="0" alt="descending" />
						</a>
					</td>
					<td class="smallFontGRYBG" align="left" nowrap>Used&nbsp;
						<a href="<?php echo base_url('main/showIpV6AddressesIndex/1/IPV6USED-asc/'.
							(is_null($ipaddress) ? 'null' : str_replace('*', '~', str_replace(' ', '_', $ipaddress))).
							'/'.(is_null($username) ? 'null' : str_replace(' ', '_', $username)).'/'.(is_null($location) ? 'null' : str_replace(' ', '_', $location)).
							'/'.$start.'/'.$max); ?>">
							<img src ="<?php echo base_url('static/img/up.gif'); ?>" border="0" alt="ascending" />
						</a>
						<a href="<?php echo base_url('main/showIpV6AddressesIndex/1/IPV6USED-desc/'.
							(is_null($ipaddress) ? 'null' : str_replace('*', '~', str_replace(' ', '_', $ipaddress))).
							'/'.(is_null($username) ? 'null' : str_replace(' ', '_', $username)).'/'.(is_null($location) ? 'null' : str_replace(' ', '_', $location)).
							'/'.$start.'/'.$max); ?>">
							<img src ="<?php echo base_url('static/img/down.gif'); ?>" border="0" alt="descending" />
						</a>
					</td>
					<td class="smallFontGRYBG" align="left" nowrap>Status&nbsp;
						<a href="<?php echo base_url('main/showIpV6AddressesIndex/1/STATUS-asc/'.
							(is_null($ipaddress) ? 'null' : str_replace('*', '~', str_replace(' ', '_', $ipaddress))).
							'/'.(is_null($username) ? 'null' : str_replace(' ', '_', $username)).'/'.(is_null($location) ? 'null' : str_replace(' ', '_', $location)).
							'/'.$start.'/'.$max); ?>">
							<img src ="<?php echo base_url('static/img/up.gif'); ?>" border="0" alt="ascending" />
						</a>
						<a href="<?php echo base_url('main/showIpV6AddressesIndex/1/STATUS-desc/'.
							(is_null($ipaddress) ? 'null' : str_replace('*', '~', str_replace(' ', '_', $ipaddress))).
							'/'.(is_null($username) ? 'null' : str_replace(' ', '_', $username)).'/'.(is_null($location) ? 'null' : str_replace(' ', '_', $location)).
							'/'.$start.'/'.$max); ?>">
							<img src ="<?php echo base_url('static/img/down.gif'); ?>" border="0" alt="descending" />
						</a>
					</td>
					<td class="smallFontGRYBG" align="left" nowrap>Date Modified
						<a href="<?php echo base_url('main/showIpV6AddressesIndex/1/MODIFIED_DATE-asc/'.
							(is_null($ipaddress) ? 'null' : str_replace('*', '~', str_replace(' ', '_', $ipaddress))).
							'/'.(is_null($username) ? 'null' : str_replace(' ', '_', $username)).'/'.(is_null($location) ? 'null' : str_replace(' ', '_', $location)).
							'/'.$start.'/'.$max); ?>">
							<img src ="<?php echo base_url('static/img/up.gif'); ?>" border="0" alt="ascending" />
						</a>
						<a href="<?php echo base_url('main/showIpV6AddressesIndex/1/MODIFIED_DATE-desc/'.
							(is_null($ipaddress) ? 'null' : str_replace('*', '~', str_replace(' ', '_', $ipaddress))).
							'/'.(is_null($username) ? 'null' : str_replace(' ', '_', $username)).'/'.(is_null($location) ? 'null' : str_replace(' ', '_', $location)).
							'/'.$start.'/'.$max); ?>">
							<img src ="<?php echo base_url('static/img/down.gif'); ?>" border="0" alt="descending" />
						</a>
					</td>
				</tr>
				<?php
				if ($ipaddresses !== false) {
					$ipaddressCount = count($ipaddresses);
					for ($i = 0; $i < $ipaddressCount; $i++) {
						$ip = $ipaddresses[$i];
				?>
				<tr>
					<td class="smallFontWHTBG" width="15%">
						<a class="freeip" href="#" ip="<?php echo $ip['IPV6ADDR']; ?>" username="<?php echo $ip['USERNAME']; ?>" 
							style="<?php echo $ip['STATUS'] == 'D' ? '' : 'display:none;'; ?>">Free up</a>
						<?php echo $ip['STATUS'] == 'D' ? '<br />' : ''; ?>
						<a class="deleteip" href="#" ip="<?php echo $ip['IPV6ADDR']; ?>" style="<?php echo $ip['IPV6USED'] == 'N' ? '' : 'display:none;'; ?>">Delete</a>
						<?php echo $ip['IPV6USED'] == 'N' ? '<br />' : ''; ?>
					</td>
					<td class="smallFontWHTBG">
						<?php echo isset($ip['IPV6ADDR']) && !is_null($ip['IPV6ADDR']) ? $ip['IPV6ADDR'] : ''; ?>
					</td>
					<td class="smallFontWHTBG" align="center">
						<?php echo isset($ip['LOCATION']) &&  !is_null($ip['LOCATION']) ? $ip['LOCATION'] : ''; ?>
					</td>
					<td class="smallFontWHTBG usernameColumn">
						<?php echo isset($ip['USERNAME']) && !is_null($ip['USERNAME']) ? ($ip['USERNAME'] == '-' ? '' : $ip['USERNAME']) : ''; ?>
					</td>
					<td class="smallFontWHTBG ipusedColumn" align="center">
						<?php echo isset($ip['IPV6USED']) && !is_null($ip['IPV6USED']) ? $ip['IPV6USED'] : ''; ?>
					</td>
					<td class="smallFontWHTBG statusColumn" align="center">
						<?php echo isset($ip['STATUS']) && !is_null($ip['STATUS']) ? $ip['STATUS'] : ''; ?>
					</td>
					<td class="smallFontWHTBG modifiedDateColumn">
						<?php echo isset($ip['MODIFIED_DATE']) && !is_null($ip['MODIFIED_DATE']) ? $ip['MODIFIED_DATE'] : ''; ?>
					</td>
				</tr>
				<?php
					}
				} else {
				?>
				<tr align="center"><td class="smallFontWHTBG" colspan="7"><font color="red">No Records Found</font></td></tr>
				<?php
				}
				?>
			</tbody>
		</table>
	</div>
	<script type="text/javascript">
		$(document).ready(function () {
			$('select[name="max"]').on('change', function (event) {
				var _this = $(this),
					max = _this.val(),
					location = '<?php echo base_url("main/showIpV6AddressesIndex/1/".$order["column"]."-".$order["dir"]."/".
						(is_null($ipaddress) ? "null" : str_replace("*", "~", str_replace(" ", "_", $ipaddress)))."/".(is_null($username) ? "null" : str_replace(" ", "_", $username))."/".
						(is_null($location) ? "null" : str_replace(" ", "_", $location))."/0"); ?>';
				location = location + '/' + max;
				console.log(location);
				window.location = location;
	        });
			$('#iptable').on('click', 'a.freeip', function (event) {
				event.preventDefault();
				var _this = $(this),
					ip = _this.attr('ip'),
					username = _this.attr('username');
				var proceed = confirm('You are about to free up ipv6 address: ' + ip + '.\n\nAre you sure?');
				if (proceed) {
					_this.text('Releasing...');
					$.ajax({
						url: '<?php echo base_url("main/freeupIpV6AddressProcess"); ?>',
						type: 'POST',
						data: {
							'ip': ip,
							'username': username
						},
						success: function (resp) {
							var response = $.parseJSON(resp);
							if (parseInt(response.status) == 1) {
								_this.fadeOut('fast', function () {
									_this.next().remove();
									_this.closest('tr').find('a.deleteip').fadeIn('fast');
									_this.closest('tr').find('.usernameColumn').empty();
									_this.closest('tr').find('.ipusedColumn').text('N');
									_this.closest('tr').find('.statusColumn').empty();
									_this.closest('tr').find('.modifiedDateColumn').text(response.time);
								});
							} else {
								_this.text('Free up');
								alert('Failed to free up ipv6: ' + ip + '.\n\nPlease try again later.');
							}
						},
						error: function (resp) {
							_this.text('Free up');
							alert('An unexpected error occurred during the request.\n\nPlease try again later.');
						}
					});
				}
			});
			$('#iptable').on('click', 'a.deleteip', function (event) {
				event.preventDefault();
				var _this = $(this),
					ip = _this.attr('ip');
				var proceed = confirm('You are about to delete ipv6 address: ' + ip + '.\n\nAre you sure?');
				if (proceed) {
					_this.text('Deleting...');
					$.ajax({
						url: '<?php echo base_url("main/deleteIpV6AddressProcess"); ?>',
						type: 'POST',
						data: {
							'ip': ip
						},
						success: function (resp) {
							var response = $.parseJSON(resp);
							if (parseInt(response.status) == 1) {
								_this.fadeOut('fast', function () {
									_this.next().remove();
									_this.closest('tr').find('.ipusedColumn').empty();
									_this.closest('tr').find('.statusColumn').empty();
									_this.closest('tr').find('.modifiedDateColumn').empty();
									_this.closest('td').append('<span class="errorMsg">DELETED</span>');
								});
							} else {
								_this.text('Delete');
								alert('Failed to delete ipv6: ' + ip + '.\n\nPlease try again later.');
							}
						},
						error: function (resp) {
							_this.text('Delete');
							alert('An unexpected error occurred during the request.\n\nPlease try again later.');
						}
					});
				}
			});
		});
	</script>
</body>
</html>