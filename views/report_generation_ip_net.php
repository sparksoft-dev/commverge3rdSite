<!DOCTYPE html>
<html lang="en">
<head>
	<title>Globelines Universal Access</title>
	<?php require 'head_includes.php'; ?>
</head>
<body style="padding-right:20px; padding-bottom:20px;">
	<div align="left">
		<h3 class="style1">Report Generation: Static and Multistatic IP Addresses</h3>
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
		<form name="frmMain" action="<?php echo base_url('reports/generateSubscriberIpNetReport'); ?>" method="post">
			<table cellspacing="0" cellpadding="3" border="0">
				<tbody>
					<tr>
						<td class="xsmallFontWHTBG" align="middle">Subscribers with Static and Multistatic IP Address</td>
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
							<input class=button2 type=submit name="submit" value="count" />
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
			<!--
			<tr>
				<td align="center" class=smallFont colspan="2">
					Info as of &nbsp; <strong>${date}</strong>
				</td>
			</tr>
			-->
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
				<td class="smallFontGRYBG" align="middle">&nbsp;</td>
				<td class="smallFontGRYBG" noWrap align="middle">Username</td>
				<td class="smallFontGRYBG" align="middle">Name</td>
				<td class="smallFontGRYBG" noWrap align="middle">Service No.</td>
				<td class="smallFontGRYBG" noWrap align="middle">Service</td>
				<!-- Remove Attributes 5/20/19 -->
				<!-- <td class="smallFontGRYBG" noWrap align="middle">Service </td> -->
				<td class="smallFontGRYBG" noWrap align="middle">Static IP</td>
				<td class="smallFontGRYBG" noWrap align="middle">Multi Static IP</td>
				<td class="smallFontGRYBG" noWrap align="middle">Homing BNG</td>
				<td class="smallFontGRYBG" noWrap align="middle">Order No.</td>
				<!-- <td class="smallFontGRYBG" noWrap align="middle">Account</td> -->
			</tr>
			<?php
			for ($i = 0; $i < count($subscribers); $i++) {
				$subs = $subscribers[$i];
				$username = $subs['USERNAME'];
				$parts = explode('@', $username);
				$cn = count($parts) == 2 ? $parts[0] : $username;
			?>
			<tr>
				<td class="xsmallFontWHTBG"><a href="<?php echo base_url('subscribers/showSubscriberInfoViaUrl/'.$parts[0].'/'.$subs['RBREALM']); ?>">View</a></td>
				<td class="xsmallFontWHTBG"><?php echo $cn; ?></td>
				<td class="xsmallFontWHTBG"><?php echo strtoupper($subs['RBCUSTOMERNAME']); ?></td>
				<td class="xsmallFontWHTBG"><?php echo $subs['RBSERVICENUMBER']; ?></td>
				<td class="xsmallFontWHTBG" align="center"><?php echo $subs['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D'; ?></td>
				<!-- <td width="5%" class="xsmallFontWHTBG">
					<?php /*echo str_replace('~', '-', $subs['RBACCOUNTPLAN']); ?><br />
					<?php echo is_null($subs['RBADDITIONALSERVICE1']) ? '' : $subs['RBADDITIONALSERVICE1'].'<br />'; ?>
					<?php echo is_null($subs['RBADDITIONALSERVICE2']) ? '' : $subs['RBADDITIONALSERVICE2'].'<br />'; */?>
				</td> -->
				<td class="xsmallFontWHTBG"><?php echo $subs['RBIPADDRESS']; ?></td>
				<td class="xsmallFontWHTBG"><?php echo $subs['RBMULTISTATIC']; ?></td>
				<td class="xsmallFontWHTBG"><?php echo $subs['LOCATION']; ?></td>
				<td class="xsmallFontWHTBG" align="center"><?php echo $subs['RBORDERNUMBER']; ?></td>
				<!-- <td class="xsmallFontWHTBG" align="center"><?php //echo $subs['CUSTOMERTYPE']; ?></td> -->
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
						echo '<a href="'.base_url("reports/generateSubscriberIpNetReport/1/count/".($j * $max)."/".$max).'">&lt;&lt;</a>';
						echo ' |';
					}
					for ($j = $pageStart; $j < $pageEnd; $j++) {
						echo '<a href="'.base_url("reports/generateSubscriberIpNetReport/1/count/".($j * $max)."/".$max).'">'.($j + 1).'</a>';
						echo ' | ';
					}
					if ($pageEnd < $pages) {
						$j = $pageEnd;
						echo '<a href="'.base_url("reports/generateSubscriberIpNetReport/1/count/".($j * $max)."/".$max).'">&gt;&gt;</a>';
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
				location = '<?php echo base_url("reports/generateSubscriberIpNetReport/1/count/0"); ?>';
			location = location + '/' + max;
			window.location = location;
		});
		<?php
		}
		?>
		$(document).ready(function () {
			$('#extractBtn').on('click', function (evt) {
				var _this = $(this),
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
					url: '<?php echo base_url("reports/ajaxGenerateSubscriberIpNetReport"); ?>',
					type: 'POST',
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