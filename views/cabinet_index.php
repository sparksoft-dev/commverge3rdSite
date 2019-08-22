<!DOCTYPE html>
<html lang="en">
<head>
	<title>Globelines Universal Access</title>
	<?php require 'head_includes.php'; ?>
</head>
<body style="padding-right:20px; padding-bottom:20px;">
	<div>
		<h3 class="style1">Cabinets</h3>
	</div>
	<div align="right" class="smallFontB">
		<a href="<?php echo base_url('main/showCabinetCreateForm'); ?>">Add Cabinet</a>&nbsp;|&nbsp;
		<a href="<?php echo base_url('main/processBulkLoadCabinets/upload'); ?>">Bulk Add</a>&nbsp;|&nbsp;
		<a href="<?php echo base_url('main/processBulkModifyCabinets/upload'); ?>">Bulk Edit</a>&nbsp;|&nbsp;
		<a href="<?php echo base_url('main/processBulkDeleteCabinets/upload'); ?>">Bulk Delete</a>
	</div>
	<br />
	<div>
		<form name="formloc" action="<?php echo base_url('main/showCabinetsIndex'); ?>" method="POST">
			<table cellspacing="1" cellpadding="3" border="0">
				<tbody>
					<tr>
						<td align="center" class="xsmallFontWHTBG">
							Cabinet Name Filter<br /><input name="cabinetName" value="<?php echo isset($cabinetName) && !is_null($cabinetName) ? $cabinetName : ''; ?>" />
						</td>
						<td align="center" class="xsmallFontWHTBG">
							Homing Bng Filter<br /><input name="homingBng" value="<?php echo isset($homingBng) && !is_null($homingBng) ? $homingBng : ''; ?>" />
						</td>
						<td align="left" class="xsmallFontWHTBG">
							<br />
							<?php
							if (is_null($order)) {
								$orderStr = 'name-asc';
								$order = array('column' => 'name', 'dir' => 'asc');
							} else {
								$orderStr = $order['column'].'-'.$order['dir'];
							}
							?>
							<input type="hidden" name="order" value="<?php echo $orderStr; ?>" />
							<input type="hidden" name="start" value="<?php echo $start; ?>" />
							<input type="hidden" name="max" value="<?php echo $max; ?>" />
							<input type="submit" valign="center" class="button2" name="btnRefresh" value="refresh" />
							<input type="button" name="extract" class="button2" value="extract" style="margin-left: 5px;" />
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
					<span class="errorMsg" id="errorMsgContainer"><?php echo isset($error) && !is_null($error) ? $error : ''; ?></span>
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

						if (!is_null($cabinetName)) {
							$cabinetNameUrlFriendly = str_replace(' ', '~~', $cabinetName);
						} else {
							$cabinetNameUrlFriendly = 'null';
						}
						if (!is_null($homingBng)) {
							$homingBngUrlFriendly = str_replace(' ', '~~', $homingBng);
						} else {
							$homingBngUrlFriendly = 'null';
						}

						if ($pageStart != 0) {
							$j = $pageStart - 1;
							echo '<a href="'.base_url("main/showCabinetsIndex/1/".$order['column']."-".$order['dir']."/".
								$cabinetNameUrlFriendly."/".$homingBngUrlFriendly."/".($j * $max)."/".$max).'">&lt;&lt;</a>';
							echo ' |';
						}
						for ($j = $pageStart; $j < $pageEnd; $j++) {
							echo '<a href="'.base_url("main/showCabinetsIndex/1/".$order['column']."-".$order['dir']."/".
								$cabinetNameUrlFriendly."/".$homingBngUrlFriendly."/".($j * $max)."/".$max).'">'.($j + 1).'</a>';
							echo ' | ';
						}
						if ($pageEnd < $pages) {
							$j = $pageEnd;
							echo '<a href="'.base_url("main/showCabinetsIndex/1/".$order['column']."-".$order['dir']."/".
								$cabinetNameUrlFriendly."/".$homingBngUrlFriendly."/".($j * $max)."/".$max).'">&gt;&gt;</a>';
						}
						?>
					</td>
					<?php
					if ($count > 0) {
					?>
					<td align="right">
						Records <strong><?php echo strval($start + 1); ?> to <?php echo strval($start + count($cabinets)); ?></strong> of <?php echo $count; ?>
					</td>
					<?php
					}
					?>
				</tr>
			</tbody>
		</table>
	</div>
	<div>
		<table cellspacing="1" cellpadding="3" style="background-color:#cccccc;" border="0" width="100%" id="cabinetTable">
			<tbody>
				<tr>
					<td class="smallFontGRYBG" align="left" nowrap width="15%">Action</td>
					<td class="smallFontGRYBG" align="left" nowrap width="35%">
						Cabinet Name&nbsp;
						<a href="<?php echo base_url('main/showCabinetsIndex/1/name-asc/'.$cabinetNameUrlFriendly.'/'.$homingBngUrlFriendly.'/'.$start.'/'.$max); ?>">
							<img src ="<?php echo base_url("static/img/up.gif"); ?>" border="0" alt="ascending" />
						</a>
						<a href="<?php echo base_url('main/showCabinetsIndex/1/name-desc/'.$cabinetNameUrlFriendly.'/'.$homingBngUrlFriendly.'/'.$start.'/'.$max); ?>">
							<img src ="<?php echo base_url('static/img/down.gif'); ?>" border="0" alt="descending" />
						</a>
					</td>
					<td class="smallFontGRYBG" align="left" nowrap width="25%">
						Homing Bng&nbsp;
						<a href="<?php echo base_url('main/showCabinetsIndex/1/homing_bng-asc/'.$cabinetNameUrlFriendly.'/'.$homingBngUrlFriendly.'/'.$start.'/'.$max); ?>">
							<img src ="<?php echo base_url("static/img/up.gif"); ?>" border="0" alt="ascending" />
						</a>
						<a href="<?php echo base_url('main/showCabinetsIndex/1/homing_bng-desc/'.$cabinetNameUrlFriendly.'/'.$homingBngUrlFriendly.'/'.$start.'/'.$max); ?>">
							<img src ="<?php echo base_url('static/img/down.gif'); ?>" border="0" alt="descending" />
						</a>
					</td>
					<td class="smallFontGRYBG" align="left" nowrap width="25%">Data VLAN</td>
				</tr>
				<?php
				if (isset($cabinets) && $cabinets !== false) {
					$cabinetCount = count($cabinets);
					if ($cabinetCount != 0) {
						for ($i = 0; $i < $cabinetCount; $i++) {
							$cabinet = $cabinets[$i];
							$id = $cabinet['id'];
				?>
				<tr>
					<td class="smallFontWHTBG">
						<a href="<?php echo base_url('main/showCabinetModifyForm/'.$id); ?>">Modify</a>&nbsp;&nbsp;
						<a href="#" cabinetId="<?php echo $id; ?>" cabinetName="<?php echo $cabinet['name']; ?>" class="deleteCabinet">Delete</a>
					</td>
					<td class="smallFontWHTBG nameColumn">
						<?php
						echo isset($cabinet['name']) && !is_null($cabinet['name']) ? $cabinet['name'] : '';
						?>
					</td>
					<td class="smallFontWHTBG bngColumn">
						<?php
						echo isset($cabinet['homing_bng']) && !is_null($cabinet['homing_bng']) ? $cabinet['homing_bng'] : '';
						?>
					</td>
					<td class="smallFontWHTBG vlanColumn">
						<?php
						echo isset($cabinet['data_vlan']) && !is_null($cabinet['data_vlan']) ? $cabinet['data_vlan'] : '';
						?>
					</td>
				</tr>
				<?php
						}
					} else {
				?>
				<tr>
					<td colspan="4">No Cabinets Found</td>
				</tr>
				<?php
					}
				} else {
				?>
				<tr>
					<td colspan="4">No Cabinets Found</td>
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
					location = '<?php echo base_url("main/showCabinetsIndex/1/".$order['column']."-".$order['dir']."/".
						$cabinetNameUrlFriendly."/".$homingBngUrlFriendly."/0"); ?>';
				location = location + '/' + max;
				window.location = location;
			});
			$('#cabinetTable').on('click', 'a.deleteCabinet', function (event) {
				event.preventDefault();
				var _this = $(this),
					cabinetId = _this.attr('cabinetid'),
					cabinetName = _this.attr('cabinetname'),
					proceed = confirm('You are about to delete ' + cabinetName + '.\n\nAre you sure?');
				if (proceed) {
					_this.text('Deleting...');
					$.ajax({
						url: '<?php echo base_url("main/deleteCabinetProcess"); ?>',
						type: 'post', 
						data: {
							'id': cabinetId
						},
						success: function (resp) {
							var response = $.parseJSON(resp);
							if (parseInt(response.status) == 1) {
								_this.fadeOut('fast', function () {
									_this.closest('tr').find('.nameColumn').empty();
									_this.closest('tr').find('.bngColumn').empty();
									_this.closest('tr').find('.vlanColumn').empty();
									_this.closest('td').empty().append('<span class="errorMsg">DELETED</span>');
								});
							} else {
								_this.closest('td').children().first().attr('href', '<?php echo base_url("main/showCabinetModifyForm"); ?>' + '/' + response.id);
								_this.attr('cabinetid', response.id);
								_this.text('Delete');
								alert('Failed to delete cabinet: ' + cabinetName + '.' + 
									(response.error === undefined ? '' : '\nCause: ' + response.error) + '\n\nPlease try again later.');
							}
						},
						error: function (resp) {
							_this.text('Delete');
							alert('An unexpected error occurred during the request.\n\nPlease try again later.');
						}
					});
				}
			});
			$('input[name="extract"]').on('click', function (event) {
				var col = '<?php echo $order['column']; ?>',
					dir = '<?php echo $order['dir'] ?>',
					_this = $(this),
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
					url: '<?php echo base_url("reports/ajaxGenerateCabinetReport"); ?>',
					type: 'POST',
					data: {
						'column': col,
						'dir': dir
					},
					timeout: 600000, //(milliseconds) 10 minutes
					success: function (resp) {
						var response = $.parseJSON(resp);
						if (parseInt(response.status) == 1) {
							console.log(response.full_path);
							// window.location.href = response.full_path;
							window.location.assign('<?php echo base_url("reports/sendFileToClient/' + response.filename + '"); ?>');
						} else {
							alert('Failed to generate file.\n\nPlease try again later.');
						}
						clearInterval(interval);
						_this.val('extract');
						_this.css('background-color', '#ccc');
						_this.prop('disabled', '');
					},
					error: function (resp) {
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