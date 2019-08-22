<!DOCTYPE html>
<html lang="en">
<head>
	<title>Globelines Universal Access</title>
	<?php require 'head_includes.php'; ?>
</head>
<body style="padding-right:20px; padding-bottom:20px;">
	<div>
		<h3 class="style1">Manage BNG</h3>
	</div>
	<div class="smallFontB" align="right">
		<a href="<?php echo base_url('main/showIpaddressesIndex/1'); ?>">Back to index</a>
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
		<div id="addControls">
			<a href="#" id="addFormToggle"><strong>Add</strong></a>&nbsp;|&nbsp;
			<a href="#" id="syncLink"><strong>Sync</strong></a><span id="syncLinkDummy" style="display:none;"><strong>Sync</strong></span>
		</div>
		<br />
		<div id="addFormDiv" style="display:none;">
			<form id="addBNGForm">
				<table cellspacing="1" cellpadding="3" border="0">
					<tbody>
						<tr>
							<td align="center" class="xsmallFontWHTBG">
								BNG<br />
								<input name="newBNG" value="" />
							</td>
							<td align="center" class="xsmallFontWHTBG">
								Max IP<br />
								<input name="newMaxIP" value="" />
							</td>
							<td align="center" class="xsmallFontWHTBG">
								<br />
								<input type="button" valign="center" class="button2" id="addBNGBtn" value="Add BNG" />
							</td>
						</tr>
					</tbody>
				</table>
			</form>
		</div>
		<br />
	</div>
	<div>
		<table id="BNGData" cellspacing="1" cellpadding="3" style="background-color:#cccccc;" border="0" width="80%">
			<thead>
				<tr>
					<td class="smallFontGRYBG" align="left" nowrap width="50%">BNG</td>
					<td class="smallFontGRYBG" align="left" nowrap width="30%">Max IP</td>
					<td class="smallFontGRYBG" align="left" nowrap width="15%">Assigned</td>
					<td class="smallFontGRYBG" align="left" nowrap width="5%"></td>
				</tr>
			</thead>
			<tbody>
				<?php
				for ($i = 0; $i < count($BNGData); $i++) {
					$row = $BNGData[$i];
				?>
				<tr>
					<td class="smallFontWHTBG" align="center" style="padding-right:15px; padding-left:10px;">
						<span class="cellLabel" style="float:left;"><?php echo $row['BNG']; ?></span>
						<input type="text" style="display:none; float:left;" class="inputField" bng-ref="<?php echo $row['BNG']; ?>" col="bng" />
						<a href="#" class="editLink" style="float:right;">Edit</a>
						<span class="controls" style="display:none; float:right;">
							<a href="#" class="saveLink">Save</a>&nbsp;&nbsp;
							<a href="#" class="cancelLink">Cancel</a>
						</span>
						<span class="controlsDummy" style="display:none; float:right;">
							Save&nbsp;&nbsp;Cancel
						</span>
					</td>
					<td class="smallFontWHTBG" align="center" style="padding-right:15px; padding-left:5px;">
						<span class="cellLabel"><?php echo $row['MAX_IP']; ?></span>
						<input type="text" style="display:none; float:left;" class="inputField" bng-ref="<?php echo $row['BNG']; ?>" col="max-ip" />
						<a href="#" class="editLink" style="float:right;">Edit</a>
						<span class="controls" style="display:none; float:right;">
							<a href="#" class="saveLink">Save</a>&nbsp;&nbsp;
							<a href="#" class="cancelLink">Cancel</a>
						</span>
						<span class="controlsDummy" style="display:none; float:right;">
							Save&nbsp;&nbsp;Cancel
						</span>
					</td>
					<td class="smallFontWHTBG" align="center">
						<span class="label"><?php echo $row['ASSIGNED_IP']; ?></span>
					</td>
					<td class="smallFontWHTBG" align="center">
						<a href="#" class="deleteBNG" bng-ref="<?php echo $row['BNG']; ?>">Delete</a>
						<span id="deleteDummy" style="display:none;">Delete</span>
					</td>
				</tr>
				<?php
				}
				?>
			</tbody>
		</table>
	</div>
	<script type="text/javascript">
		$(document).ready(function () {
			/**************************************************
			 * add new BNG
			 **************************************************/
			$('#addFormToggle').on('click', function (event) {
				event.preventDefault();
				var addFormDiv = $('#addFormDiv');
				if (addFormDiv.is(':visible')) {
					addFormDiv.hide();
				} else {
					addFormDiv.show();
					$('input[name="newBNG"]').focus();
				}
			});
			$('#addBNGBtn').on('click', function (event) {
				event.preventDefault;
				var _this = $(this),
					newBNG = $('input[name="newBNG"]'),
					newMaxIP = $('input[name="newMaxIP"]'),
					msg = $('.notificationMsg'),
					errorMsg = $('.errorMsg');
				if (newBNG.val().trim() == '') {
					errorMsg.empty().text('BNG name must not be blank.');
					newBNG.focus();
					return false;
				}
				if (newMaxIP.val().trim() == '') {
					errorMsg.empty().text('Max IP must not be blank.');
					newMaxIP.focus();
					return false;
				} else {
					var pattern = /\D/,
						found = newMaxIP.val().trim().search(pattern);
					if (found !== -1) {
						errorMsg.empty().text('Max IP field only accepts numbers.');
						newMaxIP.focus();
						return false;
					}
				}
				msg.empty().text('Creating new BNG');
				errorMsg.empty();
				var count = 0,
					newBNGInterval = setInterval(function () {
						if (count == 0) {
							msg.text('Creating new BNG.');
							count++;
						} else if (count == 1) {
							msg.text('Creating new BNG..');
							count++;
						} else if (count == 2) {
							msg.text('Creating new BNG...');
							count++;
						} else if (count == 3) {
							msg.text('Creating new BNG');
							count = 0;
						}
					}, 500);
				_this.prop('disabled', 'disabled');
				$.ajax({
					url: '<?php echo base_url("main/ajaxCreateBNG"); ?>',
					type: 'POST',
					data: {
						'newBNG': newBNG.val().trim(),
						'newMaxIP': newMaxIP.val().trim()
					},
					success: function (resp) {
						clearInterval(newBNGInterval);
						msg.empty();
						var response = $.parseJSON(resp);
						if (parseInt(response.result) == 1) {
							errorMsg.empty();
							msg.empty().text('Added new BNG: ' + newBNG.val().trim());
							var data = Array(),
								row = response.BNGRow;
							data.push(row);
							populateTable(data, false);
							newBNG.val('');
							newMaxIP.val('');
							_this.prop('disabled', '');
							setTimeout(function () {
								msg.empty();
							}, 1000);
						} else {
							errorMsg.empty().text('Failed to add new BNG. Please try again.');
							_this.prop('disabled', '');
						}
					},
					error: function (resp) {
						clearInterval(newBNGInterval);
						msg.empty();
						errorMsg.empty().text('An error has occurred. Please try again.');
						_this.prop('disabled', '');
					}
				});
			});
			/**************************************************
			 * update BNG counts
			 **************************************************/
			$('#syncLink').on('click', function (event) {
				event.preventDefault();
				var _this = $(this),
					linkDummy = $('#syncLinkDummy'),
					msg = $('.notificationMsg'),
					errorMsg = $('.errorMsg');
				msg.empty().text('Updating counts');
				var count = 0,
					syncInterval = setInterval(function () {
						if (count == 0) {
							msg.text('Updating counts.');
							count++;
						} else if (count == 1) {
							msg.text('Updating counts..');
							count++;
						} else if (count == 2) {
							msg.text('Updating counts...');
							count++;
						} else if (count == 3) {
							msg.text('Updating counts');
							count = 0;
						}
					}, 500);
				_this.hide();
				linkDummy.show();
				$.ajax({
					url: '<?php echo base_url("main/ajaxSyncBNGCount"); ?>',
					type: 'POST',
					success: function (resp) {
						clearInterval(syncInterval);
						msg.empty();
						var response = $.parseJSON(resp);
						if (parseInt(response.result) == 1) {
							errorMsg.empty();
							populateTable(response.newData, true);
							_this.show();
							linkDummy.hide();
							msg.text('BNG data refreshed.');
							setTimeout(function () {
								msg.empty();
							}, 1000);
						} else {
							errorMsg.empty().text('Failed to update counts. Please try again.');
							_this.show();
							linkDummy.hide();	
						}
					},
					error: function (resp) {
						clearInterval(syncInterval);
						msg.empty();
						errorMsg.empty().text('An error has occurred. Please try again.');
						_this.show();
						linkDummy.hide();
					}
				})
			});


			/**************************************************
			 * edit BNG
			 **************************************************/
			$('#BNGData').on('click', '.editLink', function (event) {
				event.preventDefault();
				var _this = $(this),
					cellLabel = _this.closest('td').find('.cellLabel'),
					inputField = _this.closest('td').find('.inputField'),
					controls = _this.closest('td').find('.controls'),
					ref = inputField.attr('bng-ref'),
					col = inputField.attr('col'),
					cellContent = cellLabel.text();
				inputField.val(cellContent);
				_this.hide();
				cellLabel.hide();
				inputField.show();
				controls.show();
			});
			$('#BNGData').on('click', '.cancelLink', function (event) {
				event.preventDefault();
				var _this = $(this),
					cellLabel = _this.closest('td').find('.cellLabel'),
					inputField = _this.closest('td').find('.inputField'),
					controls = _this.closest('td').find('.controls'),
					editLink = _this.closest('td').find('.editLink');
				controls.hide();
				inputField.hide();
				cellLabel.show();
				editLink.show();
			});
			$('#BNGData').on('click', '.saveLink', function (event) {
				event.preventDefault();
				var _this = $(this),
					cellLabel = _this.closest('td').find('.cellLabel'),
					inputField = _this.closest('td').find('.inputField'),
					editLink = _this.closest('td').find('.editLink'),
					controls = _this.closest('td').find('.controls'),
					controlsDummy = _this.closest('td').find('.controlsDummy'),
					ref = inputField.attr('bng-ref'),
					col = inputField.attr('col'),
					cellContent = cellLabel.text(),
					newCellContent = inputField.val(),
					msgContainer = $('.notificationMsg'),
					errorMsg = $('.errorMsg'),
					msg = '';
				if (newCellContent.trim() == '') {
					msg = col == 'bng' ? 'BNG field must not be empty.' : 'Max IP field must not be empty.';
					errorMsg.empty().text(msg);
					inputField.focus();
					return false;
				} else {
					if (col == 'max-ip') {
						var pattern = /\D/,
							found = newCellContent.search(pattern);
						if (found !== -1) {
							msg = 'Max IP field only accepts numbers.';
							errorMsg.empty().text(msg);
							inputField.focus();
							return false;
						}
					}
				}
				if (cellContent == newCellContent.trim()) {
					cellLabel.show();
					inputField.hide();
					editLink.show();
					controls.hide();
					controlsDummy.hide();
					return false;
				}
				msgContainer.empty().text('Updating ' + ref);
				errorMsg.empty();
				var count = 0,
					updateInterval = setInterval(function () {
						if (count == 0) {
							msgContainer.text('Updating ' + ref + '.');
							count++;
						} else if (count == 1) {
							msgContainer.text('Updating ' + ref + '..');
							count++;
						} else if (count == 2) {
							msgContainer.text('Updating ' + ref + '...');
							count++;
						} else if (count == 3) {
							msgContainer.text('Updating ' + ref);
							count = 0;
						}
					}, 500);
				controls.hide();
				controlsDummy.show();
				//*
				$.ajax({
					url: '<?php echo base_url("main/ajaxEditBNG"); ?>',
					type: 'POST',
					data: {
						'ref': ref,
						'column': col,
						'newValue': newCellContent.trim()
					},
					success: function (resp) {
						clearInterval(updateInterval);
						var response = $.parseJSON(resp);
						if (parseInt(response.result) == 1) {
							if (col == 'bng') {
								_this.closest('tr').find('.inputField').each(function (event) {
									var _this = $(this);
									_this.attr('bng-ref', newCellContent.trim());
								});
								_this.closest('tr').find('.deleteBNG').attr('bng-ref', newCellContent.trim());
							}
							cellLabel.empty().text(newCellContent.trim());
							cellLabel.show();
							inputField.hide();
							editLink.show();
							controlsDummy.hide();
							msgContainer.empty().text(ref + ' updated.');
							errorMsg.empty();
							setTimeout(function () {
								msgContainer.empty();
							}, 1000);
						} else {
							clearInterval(updateInterval);
							msgContainer.empty();
							if (response.error !== undefined) {
								errorMsg.empty().text(response.error);
							} else {
								errorMsg.empty().text('Failed to update. Please try again.');
							}
							controls.show();
							controlsDummy.hide();
						}
					},
					error: function (resp) {
						clearInterval(updateInterval);
						msgContainer.empty();
						errorMsg.empty().text('An error has occurred. Please try again.');
						controls.show();
						controlsDummy.hide();
					}
				});
				//*/
			});

			/**************************************************
			 * delete BNG
			 **************************************************/
			$('#BNGData').on('click', '.deleteBNG', function (event) {
				event.preventDefault();
				var _this = $(this),
					thisDummy = _this.closest('td').find('#deleteDummy'),
					confirmFirst = true,
					ref = _this.attr('bng-ref'),
					msg = $('.notificationMsg'),
					errorMsg = $('.errorMsg');
				if (confirmFirst) {
					var doConfirm = confirm('You are about to delete ' + ref + '.\n\nContinue?');
					if (!doConfirm) {
						return false;
					}
				}
				_this.hide();
				thisDummy.show();
				errorMsg.empty();
				msg.empty().text('Deleting ' + ref);
				var count = 0,
					deleteInterval = setInterval(function () {
						if (count == 0) {
							msg.text('Deleting ' + ref + '.');
							count++;
						} else if (count == 1) {
							msg.text('Deleting ' + ref + '..');
							count++;
						} else if (count == 2) {
							msg.text('Deleting ' + ref + '...');
							count++;
						} else if (count == 3) {
							msg.text('Deleting ' + ref);
							count = 0;
						}
					}, 500);
				$.ajax({
					url: '<?php echo base_url("main/ajaxDeleteBNG"); ?>',
					type: 'POST',
					data: {
						'ref': ref
					},
					success: function (resp) {
						clearInterval(deleteInterval);
						msg.empty();
						var response = $.parseJSON(resp);
						if (parseInt(response.result) == 1) {
							_this.closest('tr').empty().remove();
							msg.text(ref + ' deleted.');
							setTimeout(function () {
								msg.empty();
							}, 1000);
						} else {
							errorMsg.empty().text('Failed to delete ' + ref + '. Please try again.');
							_this.show();
							thisDummy.hide();
						}
					},
					error: function (resp) {
						clearInterval(deleteInterval);
						errorMsg.empty().text('An error has occurred. Please try again.');
						msg.empty();
						_this.show();
						thisDummy.hide();
					}
				});
			});

			function populateTable(data, clear) {
				var container = $('#BNGData').find('tbody'),
					rowStr = '';
				if (clear) {
					container.empty();
				}
				for (i = 0; i < data.length; i++) {
					var row = data[i];
					rowStr = '<tr>' +
							'<td class="smallFontWHTBG" align="center" style="padding-right:15px; padding-left:10px;">' +
								'<span class="cellLabel" style="float:left;">' + row.BNG + '</span>' +
								'<input type="text" style="display:none; float:left;" class="inputField" bng-ref="' + row.BNG + '" col="bng" />' +
								'<a href="#" class="editLink" style="float:right;">Edit</a>' +
								'<span class="controls" style="display:none; float:right;">' +
									'<a href="#" class="saveLink">Save</a>&nbsp;&nbsp;' +
									'<a href="#" class="cancelLink">Cancel</a>' +
								'</span>' +
								'<span class="controlsDummy" style="display:none; float:right;">' +
									'Save&nbsp;&nbsp;Cancel' +
								'</span>' +
							'</td>' +
							'<td class="smallFontWHTBG" align="center" style="padding-right:15px; padding-left:5px;">' +
								'<span class="cellLabel">' + row.MAX_IP + '</span>' +
								'<input type="text" style="display:none; float:left;" class="inputField" bng-ref="' + row.BNG + '" col="max-ip" />' +
								'<a href="#" class="editLink" style="float:right;">Edit</a>' +
								'<span class="controls" style="display:none; float:right;">' +
									'<a href="#" class="saveLink">Save</a>&nbsp;&nbsp;' +
									'<a href="#" class="cancelLink">Cancel</a>' +
								'</span>' +
								'<span class="controlsDummy" style="display:none; float:right;">' +
									'Save&nbsp;&nbsp;Cancel' +
								'</span>' +
							'</td>' +
							'<td class="smallFontWHTBG" align="center">' +
								'<span class="label">' + row.ASSIGNED_IP + '</span>' +
							'</td>' +
							'<td class="smallFontWHTBG" align="center">' +
								'<a href="#" class="deleteBNG" bng-ref="' + row.BNG + '">Delete</a>' +
								'<span id="deleteDummy" style="display:none;">Delete</span>' +
							'</td>' +
						'</tr>';
					container.append(rowStr);
				}
			}
		});
	</script>
</body>
</html>