<!DOCTYPE html>
<html lang="en">
<head>
    <title>Globelines Universal Access</title>
    <?php require 'head_includes.php'; ?>
</head>
<body style="padding-right:20px; padding-bottom:20px;">
    <div align="left">
        <h3 class="style1">Report Generation: Bandwidth</h3>
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
        <form name="frmMain" action="<?php echo base_url('main/generateSubscriberBandwidthReport') ?>" method="post">
            <table cellspacing="0" cellpadding="3" border="0">
                <tbody>
                    <tr>
                        <td class="xsmallFontWHTBG" align="middle">Bandwidth</td>
                        <td class="xsmallFontWHTBG" align="middle">
                            <input class="xsmallFontWHTBG" name="bandwidth" value="<?php echo (!is_null($count) /*&& $count != 0*/) ? $bandwidth : ''; ?>" />
                        </td>
                        <td class="xsmallFontWHTBG" align="middle">
                            <input type="hidden" name="max" value="<?php echo $max; ?>" />
                            <input type="hidden" name="start" value="<?php echo (!is_null($count) && $count != 0) ? $start : '0'; ?>" />
                            <input class="button2" type="submit" name="submit" value="list" />
                            <input class="button2" type="submit" name="submit" value="extract" />
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
                            <input class="button2" type="submit" name="submit" value="count" />
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
                <td class="smallFontGRYBG" noWrap align="middle">Status</td>
                <td class="smallFontGRYBG" noWrap align="middle">Service </td>
                <td class="smallFontGRYBG" noWrap align="middle">Static IP</td>
                <td class="smallFontGRYBG" noWrap align="middle">Multi Static IP</td>
                <td class="smallFontGRYBG" noWrap align="middle">Order No.</td>
                <td class="smallFontGRYBG" noWrap align="middle">Account</td>
            </tr>
            <?php
            for ($i = 0; $i < count($subscribers); $i++) {
                $subs = $subscribers[$i];
                $username = $subs['cn'];
                $parts = explode('@', $username);
                $cn = count($parts) == 2 ? $parts[0] : $username;
            ?>
            <tr>
                <td class="xsmallFontWHTBG"><a href="#">View</a></td><!--realmUserAccount.do?username=<%= Util.ObjectToString(username)%>-->
                <td class="xsmallFontWHTBG"><?php echo $cn; ?></td>
                <td class="xsmallFontWHTBG"><?php echo strtoupper($subs['rbcustomername']); ?></td>
                <td class="xsmallFontWHTBG"><?php echo $subs['rbservicenumber']; ?></td>
                <td class="xsmallFontWHTBG" align="center"><?php echo $subs['rbstatus']; ?></td>
                <td width="5%" class="xsmallFontWHTBG">
                    <?php echo str_replace('~', '-', $subs['rbsvccode']); ?><br />
                    <?php echo is_null($subs['rbadditionalservice1']) ? '' : $subs['rbadditionalservice1'].'<br />'; ?>
                    <?php echo is_null($subs['rbadditionalservice2']) ? '' : $subs['rbadditionalservice2'].'<br />'; ?>
                </td>
                <td class="xsmallFontWHTBG"><?php echo $subs['rbipaddress']; ?></td>
                <td class="xsmallFontWHTBG"><?php echo $subs['rbmultistatic']; ?></td>
                <td class="xsmallFontWHTBG" align="center"><?php echo $subs['rbordernumber']; ?></td>
                <td class="xsmallFontWHTBG" align="center"><?php echo $subs['rbaccountstatus']; ?></td>
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
                        echo '<a href="'.base_url("main/generateSubscriberBandwidthReport/1/count/".str_replace(' ', '_', $bandwidth)."/".($j * $max)."/".$max).'">&lt;&lt;</a>';
                        echo ' |';
                    }
                    for ($j = $pageStart; $j < $pageEnd; $j++) {
                        echo '<a href="'.base_url("main/generateSubscriberBandwidthReport/1/count/".str_replace(' ', '_', $bandwidth)."/".($j * $max)."/".$max).'">'.($j + 1).'</a>';
                        echo ' | ';
                    }
                    if ($pageEnd < $pages) {
                        $j = $pageEnd;
                        echo '<a href="'.base_url("main/generateSubscriberBandwidthReport/1/count/".str_replace(' ', '_', $bandwidth)."/".($j * $max)."/".$max).'">&gt;&gt;</a>';
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
                location = '<?php echo base_url("main/generateSubscriberBandwidthReport/1/count/".str_replace(' ', '_', $bandwidth)."/0"); ?>';
            location = location + '/' + max;
            window.location = location;
        });
        <?php
        }
        ?>
        $('form[name="frmMain"]').submit(function (event) {
            var bandwidth = $('input[name="bandwidth"]');
            if (bandwidth.val().trim() == '') {
                alert('Please fill in the Bandwidth field');
                bandwidth.focus();
                return false;
            } else {
                return true;
            }
        });
    </script>
</body>
</html>