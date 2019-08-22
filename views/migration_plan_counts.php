<!DOCTYPE html>
<html lang="en" style="height: 100%;">
<head>
    <title>Globelines Universal Access</title>
    <?php require 'head_includes.php'; ?>
</head>
<body style="padding:10px; padding-left:50px; padding-right:50px; height:9500px;">
	<style>
		table {
		    border-collapse: collapse;
		}

		td, th {
		    border: 1px solid black;
		    padding: 3px;
		}
	</style>
	<h3 style="text-align:center;">Migration</h3>
	<div style="margin-bottom:30px;">
		<div style="width:44%; float:left; margin-left:20px; margin-right:20px;">
    		<h3 style="text-align:center;">From NPM Input File</h3>
    		<table width="80%" style="padding-left:80px;">
    			<tr>
    				<th style="text-align:center;" width="70%">Service</th>
    				<th style="text-align:center;" width="30%">Count (<?php echo $countsTotal['pcount']; ?>)</th>
    			</tr>
    			<?php
    			//echo json_encode($counts);
    			for ($i = 0; $i < count($counts); $i++) {
    				$item = $counts[$i];
    			?>
    			<tr>
    				<td style="padding-left:10px;">
    					<?php 
    					if (intval($item['pcount']) == 0) {
							echo $item['plan']; 
    					} else {
    						echo '<a href="'.base_url('migration/planMap/file/'.str_replace(' ', '%%', $item['plan']).'/0').'">'.$item['plan'].'</a>';
    					}
    					?>
    				</td>
    				<td style="padding-left:10px;"><?php echo $item['pcount']; ?></td>
    			</tr>
    			<?php
    			}
    			?>
    		</table>
		</div>
		<div style="width:44%; float:left; margin-left:20px; margin-right:20px;">
			<h3 style="text-align:center;">From EliteAAA Database</h3>
			<table width="80%" style="padding-left:80px;">
				<tr>
					<th style="text-align:center;" width="70%">Service</th>
    				<th style="text-align:center;" width="30%">Count (<?php echo $countsOracleTotal; ?>)</th>
				</tr>
				<?php
				//echo json_encode($countsOracle);
				for ($i = 0; $i < count($countsOracle); $i++) {
					$item = $countsOracle[$i];
				?>
				<tr>
					<td style="padding-left:10px;">
						<?php 
						if (intval($item['COUNT']) == 0) {
							echo $item['SERVICE']; 
						} else {
							echo '<a href="'.base_url('migration/planMap/oracle/'.str_replace(' ', '%%', $item['SERVICE']).'/0').'">'.$item['SERVICE'].'</a>';
						}
						?>
					</td>
					<td style="padding-left:10px;"><?php echo $item['COUNT']; ?></td>
				</tr>
				<?php
				}
				?>
			</table>
		</div>
	</div>
</body>
</html>