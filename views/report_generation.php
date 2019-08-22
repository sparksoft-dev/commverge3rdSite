<!DOCTYPE html>
<html lang="en">
<head>
    <title>Globelines Universal Access</title>
    <?php require 'head_includes.php'; ?>
</head>
<body style="padding-right:20px; padding-bottom:20px;">
    <div align="left">
        <h3 class="style1">Report Generation</h3>
    </div>
    <div align="left">
        <ul>
            <!-- HideLinks 5/16/19 -->
            <li><a href="<?php echo base_url('reports/generateSubscriberStatusReport/1'); ?>">Subscriber Status Report</a></li>
            <!-- <li><a href="<?php //echo base_url('reports/generateSubscriberPackageReport/1'); ?>">Subscriber Package/Bandwidth Report</a></li> -->
            <li><a href="<?php echo base_url('reports/generateSubscriberCreationReport/1') ?>">Subscriber Creation Report</a></li>
            <!--<li><a href="reportGenerationModificationDate.do">Subscriber Modification Report</a></li>-->
            <li><a href="<?php echo base_url('reports/generateSubscriberIpReport/1'); ?>">Subscriber Static IP Report</a></li>
            <li><a href="<?php echo base_url('reports/generateSubscriberIpNetReport/1'); ?>">Subscriber Static Net IP Report</a></li>
            <!--<li><a href="<?php //echo base_url('reports/generateSubscriberBandwidthReport/1'); ?>">Subscriber Bandwidth</a></li>-->
            <!-- <li><a href="<?php //echo base_url('reports/generateSubscriberCappedReport/1'); ?>">Capped Subscribers Report</a></li> -->
        </ul>
    </div>
</body>
</html>