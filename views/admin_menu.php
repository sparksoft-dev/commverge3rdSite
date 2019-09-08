<script type="text/javascript">
	$(document).ready(function () {
		$('form[name="searchbox"]').on('submit', function (event) {
			var _this = $(this),
				option = _this.find('select[name="option"]'),
				keyword = _this.find('input[name="keyword"]');
			if (keyword.val().trim() == '') {
				alert('Please type text to search');
				keyword.focus();
			} else {
				var str = keyword.val(),
					ok = false,
					cnt = 0;
				for (i = 0; i < str.length; i++) {
					if (str[i] != "*" && str[i] != " ") {
						ok = true;
						cnt++;
					}
				}
				if (!ok || cnt < 3) {
					alert("please enter at least three non-wildcard characters");
				} else {
					var iframe = $('iframe[name="content"]'),
						keywordStr = keyword.val().trim().replace(/ /g, '++');
						keywordStr = keywordStr.replace(/\*/g, '~');
						keywordStr = keywordStr.replace(/@/g, '-----');
					iframe.attr('src', '<?php echo base_url("main/searchProcess/' + keywordStr + '/' + option.val() + '"); ?>');
				}
			}
			return false;
		});
	});
</script>
<table cellspacing="0" cellpadding="10" align="left" style="background-color:#ededed;" border="0">
	<tr>
		<td>
			<?php
			$allowed = false;
			$pageCount = count($allowedPages);
			for ($i = 0; $i < $pageCount; $i++) {
				$page = $allowedPages[$i];
				if ($page['NM'] == 'Search') {
					$allowed = true;
				}
			}
			if ($allowed) {
			?>
			<table cellspacing="1" cellpadding="5" border="0" width="100%" class="white-bg" style="border:1px solid #000;">
				<tbody>
					<tr>
						<td class="xsmallFont" style="background-color:#ffffff;">
							<form name="searchbox" method="post" action="#">
								<img alt="" src="<?php echo base_url('static/img/view.gif'); ?>" align="absmiddle" border="0" />&nbsp;
								<strong>SEARCH:</strong><br />
								<img height="5" alt="" src="<?php echo base_url('static/img/blank.gif'); ?>" width="1" border="0" />
								<table border="0">
									<tr>
										<td class="xsmallFont">Category:</td>
										<td class="xsmallFont">
											<select class="textstyle" size="1" name="option">
												<option value="1" selected>ACCOUNT</option>
												<option value="2">REALM</option>
												<!-- Remove Option 5/20/19 -->
												<!-- <option value="3">SERVICE</option> -->
												<!--<option value="4">IP ADDRESS</option>-->
											</select>
										</td>
									</tr>
									<tr>
										<td class="xsmallFont">Keyword:</td>
										<td class="xsmallFont"><input class="textstyle" size="12" name="keyword" /></td>
									</tr>
									<tr>
										<td align="center" colspan="2"><input name="search" class="button2" type="submit" value="search" /></td>
									</tr>
								</table>
							</form>
						</td>
					</tr>
				</tbody>
			</table>
			<?php
			}
			?>
		</td>
	</tr>
	<tr>


		<td class="myFontSM" valign="top">
			<?php
			$showGeneralDetailsHeader = false;
			for ($i = 0; $i < $pageCount; $i++) {
				$page = $allowedPages[$i];
				if ($page['NM'] == 'Search Online Session' || $page['NM'] == 'Search Concurrent Session' || $page['NM'] == 'Account Usages' || $page['NM'] == 'Account transaction' || 
					$page['NM'] == 'Verify Account Password' || $page['NM'] == 'Reset Subscriber Password' || $page['NM'] == 'Change Subscriber Password' || 
					$page['NM'] == 'Usage by IP Address' || $page['NM'] == 'Usages' || $page['NM'] == 'Authentication Log' || $page['NM'] == 'Change My Password' || 
					$page['NM'] == 'Change Account Password' || $page['NM'] == 'Search Online Session, NAS Update') {
					$showGeneralDetailsHeader = true;
					break;
				}
			}
			if ($showGeneralDetailsHeader) {
			?>
			<div class="smallFontGRYBG">General Details</div>
			<?php
			}
			?>
			<?php
			$allowed = false;
			for ($i = 0; $i < $pageCount; $i++) {
				$page = $allowedPages[$i];
				if ($page['NM'] == 'Search Online Session') {
					$allowed = true;
				}
			}
			if ($allowed) {
			?>
			<a href="<?php echo base_url('main/showOnlineSessionForm'); ?>" target="content">Search Online Session</a><br />
			<?php
			}
			?>
			<?php
			$allowed = false;
			// for ($i = 0; $i < $pageCount; $i++) {
			// 	$page = $allowedPages[$i];
			// 	if ($page['NM'] == 'Search Online Session, NAS Update') {
			// 		$allowed = true;
			// 	}
			// }
			if ($allowed) {
			?>
			<a href="<?php echo base_url('main/showOnlineSessionForm2'); ?>" target="content">Search Online Session (NAS Update)</a><br />
			<?php
			}
			?>
			
			<?php
			$allowed = false;
			for ($i = 0; $i < $pageCount; $i++) {
				$page = $allowedPages[$i];
				if ($page['NM'] == 'Display Quota') {
					$allowed = true;
				}
			}
			if ($allowed) {
			?>
			<a href="<?php echo base_url('main/displayQuota'); ?>" target="content">Display Quota</a><br />
			<?php
			}
			?>
			<?php
			$allowed = false;
			for ($i = 0; $i < $pageCount; $i++) {
				$page = $allowedPages[$i];
				if ($page['NM'] == 'Search Online Session') {
					$allowed = true;
				}
			}
			if (false) {
			//if ($allowed) { //uncomment to enable
			?>
			<a href="<?php echo base_url('main/showOnlineSessionFormNPM'); ?>" target="content">Search Online Session in NPM</a><br />
			<?php
			}
			?>
			<?php
			$allowed = false;
			for ($i = 0; $i < $pageCount; $i++) {
				$page = $allowedPages[$i];
				if ($page['NM'] == 'Search Concurrent Session') {
					$allowed = true;
				}
			}
			if (false) {
			//if ($allowed) { //uncomment to enable
			?>
			<a href="<?php echo base_url('main/showConcurrentSessionForm'); ?>" target="content">Search Concurrent Session</a><br />
			<?php
			}
			?>
			<?php
			$allowed = false;
			for ($i = 0; $i < $pageCount; $i++) {
				$page = $allowedPages[$i];
				if ($page['NM'] == 'Account Usages') {
					$allowed = true;
				}
			}
			if ($allowed) {
			?>
			<a href="<?php echo base_url('usages/showAccountUsagesForm2'); ?>" target="content">Session History</a><br />
			<?php
			}
			?>
			<?php
			$allowed = false;
			// for ($i = 0; $i < $pageCount; $i++) {
			// 	$page = $allowedPages[$i];
			// 	if ($page['NM'] == 'Account Transaction') {
			// 		$allowed = true;
			// 	}
			// }
			if ($allowed) {
			?>
			<a href="<?php echo base_url('usages/showAccountTransactionForm'); ?>" target="content">Account Transaction</a><br />
			<?php
			}
			?>
			<?php
			$allowed = false;
			for ($i = 0; $i < $pageCount; $i++) {
				$page = $allowedPages[$i];
				if ($page['NM'] == 'Verify Account Password') {
					$allowed = true;
				}
			}
			if ($allowed) {
			?>
			<a href="<?php echo base_url('main/showUserPasswordVerificationForm'); ?>" target="content">Verify Account Password</a><br />
			<?php
			}
			?>
			<?php
			$allowed = false;
			for ($i = 0; $i < $pageCount; $i++) {
				$page = $allowedPages[$i];
				if ($page['NM'] == 'Reset Subscriber Password') {
					$allowed = true;
				}
			}
			if ($allowed) {
			?>
			<a href="<?php echo base_url('main/showUserPasswordResetForm'); ?>" target="content">Reset Subscriber Password</a><br />
			<?php
			}
			?>
			<?php
			$allowed = false;
			for ($i = 0; $i < $pageCount; $i++) {
				$page = $allowedPages[$i];
				if ($page['NM'] == 'Change Subscriber Password') {
					$allowed = true;
				}
			}
			if ($allowed) {
			?>
			<a href="<?php echo base_url('main/showChangeUserPasswordForm'); ?>" target="content">Change Subscriber Password</a><br />
			<?php
			}
			?>
			<?php
			$allowed = false;
			for ($i = 0; $i < $pageCount; $i++) {
				$page = $allowedPages[$i];
				if ($page['NM'] == 'Usage by IP Address') {
					$allowed = true;
				}
			}
			if ($allowed) {
			?>
			<a href="<?php echo base_url('usages/showUsageByIpAddressForm2'); ?>" target="content">Usage by IP Address</a><br />
			<?php
			}
			?>
			<?php
			$allowed = false;
			for ($i = 0; $i < $pageCount; $i++) {
				$page = $allowedPages[$i];
				if ($page['NM'] == 'Usages') {
					$allowed = true;
				}
			}
			if ($allowed) {
			?>
			<a href="<?php echo base_url('usages/showUsagesForm2'); ?>" target="content">Usages</a><br />
			<?php
			}
			?>
			<?php
			$allowed = false;
			// for ($i = 0; $i < $pageCount; $i++) {
			// 	$page = $allowedPages[$i];
			// 	if ($page['NM'] == 'Authentication Log') {
			// 		$allowed = true;
			// 	}
			// }
			if ($allowed) {
			?>
			<a href="<?php echo base_url('usages/showAuthenticationLogForm'); ?>" target="content">Authentication Log</a><br />
			<?php
			}
			?>
			<?php
			$allowed = false;
			for ($i = 0; $i < $pageCount; $i++) {
				$page = $allowedPages[$i];
				if ($page['NM'] == 'Change My Password') {
					$allowed = true;
				}
			}
			if ($allowed) {
			?>
			<a href="<?php echo base_url('main/showChangeMyPasswordForm'); ?>" target="content">Change My Password</a><br />
			<?php
			}
			?>
			<?php
			$allowed = false;
			for ($i = 0; $i < $pageCount; $i++) {
				$page = $allowedPages[$i];
				if ($page['NM'] == 'Change Account Password') {
					$allowed = true;
				}
			}
			if ($allowed) {
			?>
			<a href="<?php echo base_url('main/showChangePasswordForm'); ?>" target="content">Change Account Password</a><br />
			<?php
			}
			?>
		</td>
	</tr>
	<tr>
		<td class="myFontSM" valign="top">
			<?php
			$showSystemAdministrationHeader = false;
			for ($i = 0; $i < count($allowedPages); $i++) {
				$page = $allowedPages[$i];
				if ($page['NM'] == 'System User Account' || $page['NM'] == 'System Account Usages' || $page['NM'] == 'Account Usages' || $page['NM'] == 'System User Group' || $page['NM'] == 'Create Primary Account' || 
					$page['NM'] == 'Modify Account' || $page['NM'] == 'Modify Account (Admin)' || $page['NM'] == 'Delete Account' || $page['NM'] == 'System IP Account' || $page['NM'] == 'Services' || 
					$page['NM'] == 'Realm' || $page['NM'] == 'Realm User Account' || $page['NM'] == 'Manage IP Addresses' || $page['NM'] == 'Manage Network Addresses' || 
					$page['NM'] == 'Manage Cabinets' || $page['NM'] == 'Manage Vod Params' || $page['NM'] == 'Manage Locations' || $page['NM'] == 'Report Generation' || 
					$page['NM'] == 'System User Activity Logs') {
					$showSystemAdministrationHeader = true;
					break;
				}
			}
			if ($showSystemAdministrationHeader) {
			?>
			<div class="smallFontGRYBG">System Administration</div>
			<?php
			}
			?>
			<?php
			$allowed = false;
			for ($i = 0; $i < $pageCount; $i++) {
				$page = $allowedPages[$i];
				if ($page['NM'] == 'System User Account') {
					$allowed = true;
				}
			}
			if ($allowed) {
			?>
			<a href="<?php echo base_url('main/showSysuserAccountsIndex'); ?>" target="content">System User Account</a><br />
			<?php
			}
			?>
			<?php
			$allowed = false;
			for ($i = 0; $i < $pageCount; $i++) {
				$page = $allowedPages[$i];
				if ($page['NM'] == 'System Account Usages') {
					$allowed = true;
				}
			}
			if ($allowed) {
			?>
			<a href="<?php echo base_url('usages/searchSysuserUsagesByIpAddressForm'); ?>" target="content">System Account Usages</a><br />
			<?php
			}
			?>
			<?php
			$allowed = false;
			for ($i = 0; $i < $pageCount; $i++) {
				$page = $allowedPages[$i];
				if ($page['NM'] == 'System User Group') {
					$allowed = true;
				}
			}
			if ($allowed) {
			?>
			<a href="<?php echo base_url('main/showSysUserGroupsIndex'); ?>" target="content">System User Group</a><br />
			<?php
			}
			?>
			<?php
			$allowed = false;
			for ($i = 0; $i < $pageCount; $i++) {
				$page = $allowedPages[$i];
				if ($page['NM'] == 'Create Primary Account') {
					$allowed = true;
				}
			}
			if ($allowed) {
			?>
			<a href="<?php echo base_url('subscribers/showCreateSubscriberForm'); ?>" target="content">Create Primary Account</a><br />
			<?php
			}
			?>
			<?php
			$allowed = false;
			for ($i = 0; $i < $pageCount; $i++) {
				$page = $allowedPages[$i];
				if ($page['NM'] == 'Modify Account') {
					$allowed = true;
				}
			}
			if ($allowed) {
			?>
			<a href="<?php echo base_url('subscribers/showUpdateSubscriberForm'); ?>" target="content">Modify Account</a><br />
			<?php
			}
			?>

			<!-- Add New Link for Admin -->
			
			<?php
			$allowed = false;
			for ($i = 0; $i < $pageCount; $i++) {
				$page = $allowedPages[$i];
				if ($page['NM'] == 'Modify Account (Admin)') {
					$allowed = true;
				}
			}
			if ($allowed) {
			?>
			<a href="<?php echo base_url('subscribers/showUpdateSubscriberFormAdmin'); ?>" target="content">Modify Account (Admin)</a><br />
			<?php
			}
			?>
			
			<?php
			$allowed = false;
			for ($i = 0; $i < $pageCount; $i++) {
				$page = $allowedPages[$i];
				if ($page['NM'] == 'Delete Account') {
					$allowed = true;
				}
			}
			if ($allowed) {
			?>
			<a href="<?php echo base_url('subscribers/showDeleteSubscriberForm'); ?>" target="content">Delete Account</a><br />
			<?php
			}
			?>
			<?php
			$allowed = false;
			for ($i = 0; $i < $pageCount; $i++) {
				$page = $allowedPages[$i];
				if ($page['NM'] == 'System IP Account') {
					$allowed = true;
				}
			}
			if ($allowed) {
			?>
			<a href="<?php echo base_url('main/showSysipaddressesIndex'); ?>" target="content">System IP Account</a><br />
			<?php
			}
			?><?php
			$allowed = false;
			// for ($i = 0; $i < $pageCount; $i++) {
			// 	$page = $allowedPages[$i];
			// 	if ($page['NM'] == 'Services') {
			// 		$allowed = true;
			// 	}
			// }
			if ($allowed) {
			?>
			<a href="<?php echo base_url('admin/showServicesIndex'); ?>" target="content">Services</a><br />
			<?php
			}
			?>
			<?php
			$allowed = false;
			for ($i = 0; $i < $pageCount; $i++) {
				$page = $allowedPages[$i];
				if ($page['NM'] == 'Realm') {
					$allowed = true;
				}
			}
			if ($allowed) {
			?>
			<a href="<?php echo base_url('main/showRealmsIndex'); ?>" target="content">Realm</a><br />
			<?php
			}
			?>
			<?php
			$allowed = false;
			for ($i = 0; $i < $pageCount; $i++) {
				$page = $allowedPages[$i];
				if ($page['NM'] == 'Realm User Account') {
					$allowed = true;
				}
			}
			if ($allowed) {
			?>
			<a href="<?php echo base_url('main/realmUserAccountsForm'); ?>" target="content">Realm User Account</a><br />
			<?php
			}
			?>
			<?php
			$allowed = false;
			for ($i = 0; $i < $pageCount; $i++) {
				$page = $allowedPages[$i];
				if ($page['NM'] == 'Manage IP Addresses') {
					$allowed = true;
				}
			}
			if ($allowed) {
			?>
			<a href="<?php echo base_url('main/showIpaddressesIndex/1'); ?>" target="content">Manage IP Addresses</a><br />
			<?php
			}
			?>
			<?php
			$allowed = false;
			for ($i = 0; $i < $pageCount; $i++) {
				$page = $allowedPages[$i];
				if ($page['NM'] == 'Manage Network Addresses') {
					$allowed = true;
				}
			}
			if ($allowed) {
			?>
			<a href="<?php echo base_url('main/showNetaddressesIndex/1'); ?>" target="content">Manage Network Addresses</a><br />
			<?php
			}
			?>
			<?php
			$allowed = false;
			for ($i = 0; $i < $pageCount; $i++) {
				$page = $allowedPages[$i];
				if ($page['NM'] == 'Manage Cabinets') {
					$allowed = true;
				}
			}
			if ($allowed) {
			?>
			<a href="<?php echo base_url('main/showCabinetsIndex/1'); ?>" target="content">Manage Cabinets</a><br />
			<?php
			}
			?>
			<?php
			$allowed = false;
			for ($i = 0; $i < $pageCount; $i++) {
				$page = $allowedPages[$i];
				if ($page['NM'] == 'Manage Vod Params') {
					$allowed = true;
				}
			}
			//remove next line to enable vod params
			$allowed = false;
			if ($allowed) {
			?>
			<a href="<?php echo base_url('main/showVodparamsIndex/1'); ?>" target="content">Manage VOD Params</a><br />
			<?php
			}
			?>
			<?php
			$allowed = false;
			for ($i = 0; $i < $pageCount; $i++) {
				$page = $allowedPages[$i];
				if ($page['NM'] == 'Manage Locations') {
					$allowed = true;
				}
			}
			//remove next line to enable locations
			$allowed = false;
			if ($allowed) {
			?>
			<a href="<?php echo base_url('main/showLocationsIndex/1'); ?>" target="content">Manage Locations</a><br />
			<?php
			}
			?>
			<?php
			$allowed = false;
			for ($i = 0; $i < $pageCount; $i++) {
				$page = $allowedPages[$i];
				if ($page['NM'] == 'Report Generation') {
					$allowed = true;
				}
			}
			if ($allowed) {
			?>
			<a href="<?php echo base_url('reports/showReportGenerationPage'); ?>" target="content">Report Generation</a><br />
			<?php
			}
			?>
			<?php
			$allowed = false;
			for ($i = 0; $i < $pageCount; $i++) {
				$page = $allowedPages[$i];
				if ($page['NM'] == 'System User Activity Logs') {
					$allowed = true;
				}
			}
			if ($allowed) {
			?>
			<a href="<?php echo base_url('activitylogs/showActivityLogsMainPage'); ?>" target="content">System User Activity Logs</a><br />
			<?php
			}
			?>
			<br />
			<?php include 'service_search.php'; ?>
		</td>
	</tr>
</table>
