<!DOCTYPE html>
<html lang="en">
<head>
	<title>Globelines Universal Access</title>
	<?php require 'head_includes.php'; ?>
</head>
<body style="padding-right:20px; padding-bottom:20px;">
	<div>
		<h3 class="style1">Locations</h3>
	</div>
	<div align="right" class="smallFontB">
		<a href="<?php echo base_url('main/showLocationCreateForm'); ?>">Add Location</a><!--&nbsp;|&nbsp;
		<a href="<?php //echo '#'; ?>">Add Bulk</a>&nbsp;|&nbsp;
		<a href="<?php //echo '#'; ?>">Edit Bulk</a>&nbsp;|&nbsp;
		<a href="<?php //echo '#'; ?>">Delete Bulk</a>-->
	</div>
	<br />
	<div>
		<form name="formloc" action="<?php echo base_url('main/showLocationsIndex'); ?>" method="POST">
			<table cellspacing="1" cellpadding="3" border="0">
				<tbody>
					<tr>
						<td align="center" class="xsmallFontWHTBG">
							NAS Tag Filter<br /><input name="location" value="<?php echo isset($location) && !is_null($location) ? $location : ''; ?>" />
						</td>
						<td align="center" class="xsmallFontWHTBG">
							NAS Name Filter<br /><input name="nasName" value="<?php echo isset($nasName) && !is_null($nasName) ? $nasName : ''; ?>" />
						</td>
						<td align="center" class="xsmallFontWHTBG">
							NAS IP Filter<br /><input name="nasIp" value="<?php echo isset($nasIp) && !is_null($nasIp) ? $nasIp : ''; ?>" />
						</td>
						<td align="center" class="xsmallFontWHTBG">
							NAS Code Filter<br /><input name="nasCode" value="<?php echo isset($nasCode) && !is_null($nasCode) ? $nasCode : ''; ?>" />
						</td>
						<td align="center" class="xsmallFontWHTBG">
							RM Location Filter<br /><input name="rmLocation" value="<?php echo isset($rmLocation) && !is_null($rmLocation) ? $rmLocation : ''; ?>" />
						</td>
						<td align="left" class="xsmallFontWHTBG">
							<br />
							<?php
							if (is_null($order)) {
								$orderStr = 'l.location-asc';
								$order = array('column' => 'l.location', 'dir' => 'asc');
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
				<td align="center">
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

						if (!is_null($location)) {
							$locationUrlFriendly = str_replace(' ', '~~', $location);
						} else {
							$locationUrlFriendly = 'null';
						}
						if (!is_null($nasName)) {
							$nasNameUrlFriendly = str_replace(' ', '~~', $nasName);
						} else {
							$nasNameUrlFriendly = 'null';
						}
						if (!is_null($nasIp)) {
							$nasIpUrlFriendly = str_replace(' ', '~~', $nasIp);
						} else {
							$nasIpUrlFriendly = 'null';
						}
						if (!is_null($rmLocation)) {
							$rmLocationUrlFriendly = str_replace(' ', '~~', $rmLocation);
							$rmLocationUrlFriendly = str_replace('#', '---', $rmLocationUrlFriendly);
						} else {
							$rmLocationUrlFriendly = 'null';
						}
						if (!is_null($nasCode)) {
							$nasCodeUrlFriendly = str_replace(' ', '~~', $nasCode);
						} else {
							$nasCodeUrlFriendly = 'null';
						}

						if ($pageStart != 0) {
							$j = $pageStart - 1;
							echo '<a href="'.base_url("main/showLocationsIndex/1/".$order['column']."-".$order['dir']."/".
								$locationUrlFriendly."/".$nasNameUrlFriendly."/".$nasIpUrlFriendly."/".$rmLocationUrlFriendly."/".$nasCodeUrlFriendly."/".
								($j * $max)."/".$max).'">&lt;&lt;</a>';
							echo ' |';
						}
						for ($j = $pageStart; $j < $pageEnd; $j++) {
							echo '<a href="'.base_url("main/showLocationsIndex/1/".$order['column']."-".$order['dir']."/".
								$locationUrlFriendly."/".$nasNameUrlFriendly."/".$nasIpUrlFriendly."/".$rmLocationUrlFriendly."/".$nasCodeUrlFriendly."/".
								($j * $max)."/".$max).'">'.($j + 1).'</a>';
							echo ' | ';
						}
						if ($pageEnd < $pages) {
							$j = $pageEnd;
							echo '<a href="'.base_url("main/showLocationsIndex/1/".$order['column']."-".$order['dir']."/".
								$locationUrlFriendly."/".$nasNameUrlFriendly."/".$nasIpUrlFriendly."/".$rmLocationUrlFriendly."/".$nasCodeUrlFriendly."/".
								($j * $max)."/".$max).'">&gt;&gt;</a>';
						}
						?>
					</td>
					<?php
					if ($count > 0) {
					?>
					<td align="right">
						Records <strong><?php echo strval($start + 1); ?> to <?php echo strval($start + count($locations)); ?></strong> of <?php echo $count; ?>
					</td>
					<?php
					}
					?>
				</tr>
			</tbody>
		</table>
	</div>
	<div>
		<table cellspacing="1" cellpadding="3" style="background-color:#cccccc;" border="0" width="100%" id="locationTable">
			<tbody>
				<tr>
					<td class="smallFontGRYBG" align="left" nowrap width="12%">Action</td>
					<td class="smallFontGRYBG" align="left" nowrap width="12%">
						NAS Tag&nbsp;
						<a href="<?php echo base_url('main/showLocationsIndex/1/l.location-asc/'.$locationUrlFriendly.'/'.$nasNameUrlFriendly.'/'.$nasIpUrlFriendly.'/'.
							$rmLocationUrlFriendly.'/'.$nasCodeUrlFriendly.'/'.$start.'/'.$max); ?>">
							<img src ="<?php echo base_url("static/img/up.gif"); ?>" border="0" alt="ascending" />
						</a>
						<a href="<?php echo base_url('main/showLocationsIndex/1/l.location-desc/'.$locationUrlFriendly.'/'.$nasNameUrlFriendly.'/'.$nasIpUrlFriendly.'/'.
							$rmLocationUrlFriendly.'/'.$nasCodeUrlFriendly.'/'.$start.'/'.$max); ?>">
							<img src ="<?php echo base_url('static/img/down.gif'); ?>" border="0" alt="descending" />
						</a>
					</td>
					<td class="smallFontGRYBG" align="left" nowrap width="12%">
						NAS Name&nbsp;
						<a href="<?php echo base_url('main/showLocationsIndex/1/nl.nas_name-asc/'.$locationUrlFriendly.'/'.$nasNameUrlFriendly.'/'.$nasIpUrlFriendly.'/'.
							$rmLocationUrlFriendly.'/'.$nasCodeUrlFriendly.'/'.$start.'/'.$max); ?>">
							<img src ="<?php echo base_url("static/img/up.gif"); ?>" border="0" alt="ascending" />
						</a>
						<a href="<?php echo base_url('main/showLocationsIndex/1/nl.nas_name-desc/'.$locationUrlFriendly.'/'.$nasNameUrlFriendly.'/'.$nasIpUrlFriendly.'/'.
							$rmLocationUrlFriendly.'/'.$nasCodeUrlFriendly.'/'.$start.'/'.$max); ?>">
							<img src ="<?php echo base_url('static/img/down.gif'); ?>" border="0" alt="descending" />
						</a>
					</td>
					<td class="smallFontGRYBG" align="left" nowrap width="12%">
						NAS IP&nbsp;
						<a href="<?php echo base_url('main/showLocationsIndex/1/nl.nas_ip-asc/'.$locationUrlFriendly.'/'.$nasNameUrlFriendly.'/'.$nasIpUrlFriendly.'/'.
							$rmLocationUrlFriendly.'/'.$nasCodeUrlFriendly.'/'.$start.'/'.$max); ?>">
							<img src ="<?php echo base_url("static/img/up.gif"); ?>" border="0" alt="ascending" />
						</a>
						<a href="<?php echo base_url('main/showLocationsIndex/1/nl.nas_ip-desc/'.$locationUrlFriendly.'/'.$nasNameUrlFriendly.'/'.$nasIpUrlFriendly.'/'.
							$rmLocationUrlFriendly.'/'.$nasCodeUrlFriendly.'/'.$start.'/'.$max); ?>">
							<img src ="<?php echo base_url('static/img/down.gif'); ?>" border="0" alt="descending" />
						</a>
					</td>
					<td class="smallFontGRYBG" align="left" nowrap width="8%">
						NAS Code&nbsp;
						<a href="<?php echo base_url('main/showLocationsIndex/1/nl.nas_code-asc/'.$locationUrlFriendly.'/'.$nasNameUrlFriendly.'/'.$nasIpUrlFriendly.'/'.
							$rmLocationUrlFriendly.'/'.$nasCodeUrlFriendly.'/'.$start.'/'.$max); ?>">
							<img src ="<?php echo base_url("static/img/up.gif"); ?>" border="0" alt="ascending" />
						</a>
						<a href="<?php echo base_url('main/showLocationsIndex/1/nl.nas_code-desc/'.$locationUrlFriendly.'/'.$nasNameUrlFriendly.'/'.$nasIpUrlFriendly.'/'.
							$rmLocationUrlFriendly.'/'.$nasCodeUrlFriendly.'/'.$start.'/'.$max); ?>">
							<img src ="<?php echo base_url('static/img/down.gif'); ?>" border="0" alt="descending" />
						</a>
					</td>
					<td class="smallFontGRYBG" align="left" nowrap width="24%">
						NAS Description
					</td>
					<td class="smallFontGRYBG" align="left" nowrap width="8%">
						RM Location&nbsp;
						<a href="<?php echo base_url('main/showLocationsIndex/1/nl.rm_location-asc/'.$locationUrlFriendly.'/'.$nasNameUrlFriendly.'/'.$nasIpUrlFriendly.'/'.
							$rmLocationUrlFriendly.'/'.$nasCodeUrlFriendly.'/'.$start.'/'.$max); ?>">
							<img src ="<?php echo base_url("static/img/up.gif"); ?>" border="0" alt="ascending" />
						</a>
						<a href="<?php echo base_url('main/showLocationsIndex/1/nl.rm_location-desc/'.$locationUrlFriendly.'/'.$nasNameUrlFriendly.'/'.$nasIpUrlFriendly.'/'.
							$rmLocationUrlFriendly.'/'.$nasCodeUrlFriendly.'/'.$start.'/'.$max); ?>">
							<img src ="<?php echo base_url('static/img/down.gif'); ?>" border="0" alt="descending" />
						</a>
					</td>
					<td class="smallFontGRYBG" align="left" nowrap width="12%">
						RM Description
					</td>
				</tr>
				<?php
				if (isset($locations) && $locations !== false) {
					$locationCount = count($locations);
					if ($locationCount != 0) {
						for ($i = 0; $i < $locationCount; $i++) {
							$thisLocation = $locations[$i];
							$id = $thisLocation['id'];
				?>
				<tr>
					<td class="smallFontWHTBG">
						<a href="<?php echo base_url('main/showLocationModifyForm/'.$id); ?>">Modify</a>&nbsp;&nbsp;
						<a href="#" 
							locationId="<?php echo $id; ?>" 
							location="<?php echo $thisLocation['location']; ?>" 
							nasName="<?php echo $thisLocation['nas_name']; ?>" 
							nasIp="<?php echo $thisLocation['nas_ip']; ?>" class="deleteLocation">Delete</a>
					</td>
					<td class="smallFontWHTBG nasTagColumn">
						<?php
						echo isset($thisLocation['location']) && !is_null($thisLocation['location']) ? $thisLocation['location'] : '';
						?>
					</td>
					<td class="smallFontWHTBG nasNameColumn">
						<?php
						echo isset($thisLocation['nas_name']) && !is_null($thisLocation['nas_name']) ? $thisLocation['nas_name'] : '';
						?>
					</td>
					<td class="smallFontWHTBG nasIpColumn">
						<?php
						echo isset($thisLocation['nas_ip']) && !is_null($thisLocation['nas_ip']) ? $thisLocation['nas_ip'] : '';
						?>
					</td>
					<td class="smallFontWHTBG nasCodeColumn">
						<?php
						echo isset($thisLocation['nas_code']) && !is_null($thisLocation['nas_code']) ? $thisLocation['nas_code'] : '';
						?>
					</td>
					<td class="smallFontWHTBG nasDescriptionColumn">
						<?php
						echo isset($thisLocation['nas_description']) && !is_null($thisLocation['nas_description']) ? $thisLocation['nas_description'] : '';
						?>
					</td>
					<td class="smallFontWHTBG rmLocationColumn">
						<?php
						echo isset($thisLocation['rm_location']) && !is_null($thisLocation['rm_location']) ? $thisLocation['rm_location'] : '';
						?>
					</td>
					<td class="smallFontWHTBG rmDescriptionColumn">
						<?php
						echo isset($thisLocation['rm_description']) && !is_null($thisLocation['rm_description']) ? $thisLocation['rm_description'] : '';
						?>
					</td>
				</tr>
				<?php
						}
					} else {
				?>
				<tr>
					<td colspan="8">No Locations Found</td>
				</tr>
				<?php
					}
				} else {
				?>
				<tr>
					<td colspan="8">No Locations Found</td>
				</tr>
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
					location = '<?php echo base_url("main/showLocationsIndex/1/".$order['column']."-".$order['dir']."/".
						$locationUrlFriendly."/".$nasNameUrlFriendly."/".$nasIpUrlFriendly."/".$rmLocationUrlFriendly."/".$nasCodeUrlFriendly."/0"); ?>';
				location = location + '/' + max;
				// console.log(location);
				window.location = location;
			});
			$('#locationTable').on('click', 'a.deleteLocation', function (event) {
				event.preventDefault();
				var _this = $(this),
					locationId = _this.attr('locationid'),
					location = _this.attr('location'),
					nasName = _this.attr('nasname'),
					nasIp = _this.attr('nasip'),
					proceed = confirm('You are about to delete ' + location + ' (' + nasName + ', ' + nasIp + ').\n\nAre you sure?');
				if (proceed) {
					_this.text('Deleting...');
					$.ajax({
						url: '<?php echo base_url("main/deleteLocationProcess"); ?>',
						type: 'post',
						data: {
							'id': locationId
						},
						success: function (resp) {
							console.log(resp);
							var response = $.parseJSON(resp);
							console.log(response);
							if (parseInt(response.status) == 1) {
								console.log('                     1');
								_this.fadeOut('fast', function () {
									_this.closest('tr').find('.nasTagColumn').empty();
									_this.closest('tr').find('.nasNameColumn').empty();
									_this.closest('tr').find('.nasIpColumn').empty();
									_this.closest('tr').find('.nasCodeColumn').empty();
									_this.closest('tr').find('.nasDescriptionColumn').empty();
									_this.closest('tr').find('.rmLocationColumn').empty();
									_this.closest('tr').find('.rmDescriptionColumn').empty();
									_this.closest('td').empty().append('<span class="errorMsg">DELETED</span>');
								});
								console.log('                     2');
							} else {
								_this.closest('td').children().first().attr('href', '<?php echo base_url("main/showLocationModifyForm"); ?>' + '/' + response.id);
								_this.attr('locationid', response.id);
								_this.text('Delete');
								alert('Failed to delete location: ' + location + '(' + nasName + ', ' + nasIp + ').\n\nPlease try again later.');
							}
							console.log('                     3');
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