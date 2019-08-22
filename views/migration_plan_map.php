<!DOCTYPE html>
<html lang="en" style="height: 100%;">
<head>
	<title>Globelines Universal Access</title>
	<?php require 'head_includes.php'; ?>
</head>
<body style="padding;10px;">
	<style>
		table {
		    border-collapse: collapse;
		}

		td, th {
		    border: 1px solid black;
		    padding: 3px;
		}
	</style>
	<div align="center" style="margin-top:20px; margin-bottom:20px;">
		<table width="30%">
			<tr>
				<td><strong>Source:</strong></td>
				<td><?php echo $source == 'file' ? 'NPM Input File' : 'EliteAAA Database'; ?></td>
			</tr>
			<tr>
				<td><strong>Service:</strong></td>
				<td><?php echo $plan; ?></td>
			</tr>
			<tr>
				<td><strong>Total:</strong></td>
				<td><?php echo $count; ?></td>
			</tr>
			<tr>
				<td></td>
				<td><a href="<?php echo base_url('migration/planCounts'); ?>">back</a></td>
			</tr>
		</table>
		<br /><br />
		<table width="35%">
			<tr>
				<td style="text-align:right;" colspan="3">
					<?php echo strval($start + 1).' to '.strval($start + count($map)).' of '.$count; ?>
					<br />
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
						echo '<a href="'.base_url("migration/planMap/".$source."/".str_replace(' ', '%%', $plan).'/'.($j * $max)).'">&lt;&lt;</a>';
						echo ' |';
					}
					for ($j = $pageStart; $j < $pageEnd; $j++) {
						echo '<a href="'.base_url("migration/planMap/".$source."/".str_replace(' ', '%%', $plan).'/'.($j * $max)).'">'.($j + 1).'</a>';
						echo ' | ';
					}
					if ($pageEnd < $pages) {
						$j = $pageEnd;
						echo '<a href="'.base_url("migration/planMap/".$source."/".str_replace(' ', '%%', $plan).'/'.($j * $max)).'">&gt;&gt;</a>';
						echo ' |';
					}
					?>
				</td>
			</tr>
			<?php
			for ($i = 0; $i < count($map); $i++) {
			?>
			<tr>
				<td width="10%"><?php echo $start + 1 + $i; ?></td>
				<td colspan="2" style="padding-left:5px;">
					<?php
					if ($source == 'file') {
						echo $map[$i]['username'];
					} else if ($source == 'oracle') {
						echo $map[$i]['USERNAME'];
					}
					?>
				</td>
			</tr>
			<?php
			}
			?>
		</table>
	</div>
</body>
</html>