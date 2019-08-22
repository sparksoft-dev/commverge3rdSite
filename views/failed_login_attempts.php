<!DOCTYPE html>
<html lang="en">
<head>
    <title>Globelines Universal Access</title>
    <?php require 'head_includes.php'; ?>
</head>
<body style="padding-right:20px; padding-bottom:20px;">
    <div>
        <h3 class="style1">Failed System User Login Attempts</h3>
    </div>
    <div class="smallFontB" align="right">
        <a href="<?php echo base_url('activitylogs/showActivityLogsMainPage'); ?>">Back to index</a>
    </div>
    <div align="center">
        <table cellspacing="1" cellpadding="2" width="100%" align="center">
            <tr>
                <td>
                    <span class="notificationMsg"></span>
                    <span class="errorMsg"><?php echo is_null($error) ? '' : $error; ?></span>
                </td>
            </tr>
        </table>
    </div>
    <div align="center">
        <form name="frmMain" action="<?php echo base_url('activitylogs/showFailedSysuserLoginAttempts'); ?>" method="post">
            <table cellspacing="1" cellpadding="3" style="background-color:#cccccc;" border="0">
                <tbody>
                    <tr>
                        <td class="xsmallFontWHTBG">
                            Date Range
                        </td>
                        <td class="xsmallFontWHTBG">
                            <select class="textstyle" size="1" name="start_month">
                                <option value="0">-month-</option>
                                <?php
                                $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                                for ($i = 0; $i < count($months); $i++) {
                                    echo intval($start_month) == ($i + 1) ? '<option value="'.($i + 1).'" selected>'.$months[$i].'</option>' : '<option value="'.($i + 1).'">'.$months[$i].'</option>';
                                }
                                ?>
                            </select>
                            &nbsp;
                            <select class="textstyle" size="1" name="start_day">
                                <option value="0">-day-</option>
                                <?php
                                for ($i = 1; $i < 32; $i++) {
                                    echo intval($start_day) == $i ? '<option value="'.$i.'" selected>'.$i.'</option>' : '<option value="'.$i.'">'.$i.'</option>';
                                }
                                ?>
                            </select>
                            &nbsp;
                            <select class="textstyle" size="1" name="start_year">
                                <option value="0">-year-</option>
                                <?php
                                for ($i = 2020; $i >= 2001; $i--) {
                                    echo intval($start_year) == $i ? '<option value="'.$i.'" selected>'.$i.'</option>' : '<option value="'.$i.'">'.$i.'</option>';
                                }
                                ?>
                            </select>
                            &nbsp; to &nbsp;
                            <select class="textstyle" size="1" name="end_month">
                                <option value="0">-month-</option>
                                <?php
                                for ($i = 0; $i < count($months); $i++) {
                                    echo intval($end_month) == ($i + 1) ? '<option value="'.($i + 1).'" selected>'.$months[$i].'</option>' : '<option value="'.($i + 1).'">'.$months[$i].'</option>';
                                }
                                ?>
                            </select>
                            &nbsp;
                            <select class="textstyle" size="1" name="end_day">
                                <option value="0">-day-</option>
                                <?php
                                for ($i = 1; $i < 32; $i++) {
                                    echo intval($end_day) == $i ? '<option value="'.$i.'" selected>'.$i.'</option>' : '<option value="'.$i.'">'.$i.'</option>';
                                }
                                ?>
                            </select>
                            &nbsp;
                            <select class="textstyle" size="1" name="end_year">
                                <option value="0">-year-</option>
                                <?php
                                for ($i = 2020; $i >= 2001; $i--) {
                                    echo intval($end_year) == $i ? '<option value="'.$i.'" selected>'.$i.'</option>' : '<option value="'.$i.'">'.$i.'</option>';
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="xsmallFontWHTBG">IP Address filter</td>
                        <td class="smallFontWHTBG">
                            <input class="textstyle" size="30" name="ipaddress" value="<?php echo $init ? '' : $ipaddress; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="xsmallFontWHTBG">Username filter</td>
                        <td class="smallFontWHTBG">
                            <input class="textstyle" size="30" name="username" value="<?php echo $init ? '' : $username; ?>" autocomplete="off" />
                        </td>
                    </tr>
                    <tr>
                        <td class="xsmallFontWHTBG">Reason filter</td>
                        <td class="smallFontWHTBG">
                            <select class="textstyle" size="1" name="reason">
                                <option value="">-ALL-</option>
                                <?php
                                for ($i = 0; $i < count($reasonValueFilter); $i++) {
                                    echo '<option value="'.$reasonValueFilter[$i].'" '.($init ? '' : (intval($reasonValueFilter[$i]) == intval($reason) ? 'selected' : '')).'>'.
                                            $reasonStringFilter[$i].
                                        '</option>';
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="smallFontWHTBG" colspan="4" align="center">
                            <?php
                            if ($init) {
                                $orderStr = 'timestamp-desc';
                            } else {
                                $orderStr = $order['column'].'-'.$order['dir'];
                            }
                            ?>
                            <input type="hidden" name="order" value="<?php echo $orderStr; ?>" />
                            <input type="hidden" name="start" value="<?php echo $init ? '0' : $start; ?>" />
                            <input type="hidden" name="max" value="<?php echo $max; ?>" />
                            <input class="button2" type="submit" value="display" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
        <span class="msg">* Leave a filter field blank to disable the associated filter.</span>
        <br /><br /><br /><br />
    </div>
    <?php
    if (!$init) {
        if (count($attempts) > 0) {
    ?>
    <div align="center">
        <table cellspacing="1" cellpadding="3" style="background-color:#cccccc;" border="0">
            <tr>
                <td class="xsmallFontWHTBG" colspan="4" align="left"><strong>Date range</strong></td>
                <td class="xsmallFontWHTBG" colspan="4" align="left">
                    <?php echo $start_month.'/'.$start_day.'/'.$start_year.' - '.$end_month.'/'.$end_day.'/'.$end_year; ?>
                </td>
            </tr>
            <tr>
                <td class="xsmallFontWHTBG" colspan="4" align="left"><strong>IP Address filter</strong></td>
                <td class="xsmallFontWHTBG" colspan="4" align="left"><?php echo is_null($ipaddress) ? '' : $ipaddress; ?></td>
            </tr>
            <tr>
                <td class="xsmallFontWHTBG" colspan="4" align="left"><strong>Username filter</strong></td>
                <td class="xsmallFontWHTBG" colspan="4" align="left"><?php echo is_null($username) ? '' : $username; ?></td>
            </tr>
            <tr>
                <td class="xsmallFontWHTBG" colspan="4" align="left"><strong>Reason filter</strong></td>
                <td class="xsmallFontWHTBG" colspan="4" align="left">
                    <?php 
                    if ($reason == '') {
                        echo $reason;
                    } else {
                        $key = array_search($reason, $reasonValueFilter);
                        echo $reasonStringFilter[$key];
                    }
                    ?>
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
        <table width="100%">
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
                        if ($pageStart != 0) {
                            $j = $pageStart - 1;
                            echo '<a href="'.base_url("activitylogs/showFailedSysuserLoginAttempts/1/".$order['column']."-".$order['dir']."/".
                                ($ipaddress == "" ? "null" : str_replace(" ", "_", $ipaddress))."/".($username == "" ? "null" : str_replace(" ", "_", $username))."/".
                                ($reason == "" ? "null" : str_replace(" ", "_", $reason))."/".
                                $start_month."/".$start_year."/".$start_day."/".$end_month."/".$end_year."/".$end_day."/".($j * $max)."/".$max).'">&lt;&lt;</a>';
                            echo ' |';
                        }
                        for ($j = $pageStart; $j < $pageEnd; $j++) {
                            echo '<a href="'.base_url("activitylogs/showFailedSysuserLoginAttempts/1/".$order['column']."-".$order['dir']."/".
                                ($ipaddress == "" ? "null" : str_replace(" ", "_", $ipaddress))."/".($username == "" ? "null" : str_replace(" ", "_", $username))."/".
                                ($reason == "" ? "null" : str_replace(" ", "_", $reason))."/".
                                $start_month."/".$start_year."/".$start_day."/".$end_month."/".$end_year."/".$end_day."/".($j * $max)."/".$max).'">'.($j + 1).'</a>';
                            echo ' | ';
                        }
                        if ($pageEnd < $pages) {
                            $j = $pageEnd;
                            echo '<a href="'.base_url("activitylogs/showFailedSysuserLoginAttempts/1/".$order['column']."-".$order['dir']."/".
                                ($ipaddress == "" ? "null" : str_replace(" ", "_", $ipaddress))."/".($username == "" ? "null" : str_replace(" ", "_", $username))."/".
                                ($reason == "" ? "null" : str_replace(" ", "_", $reason))."/".
                                $start_month."/".$start_year."/".$start_day."/".$end_month."/".$end_year."/".$end_day."/".($j * $max)."/".$max).'">&gt;&gt;</a>';
                            echo ' |';
                        }
                        ?>
                    </td>
                    <?php
                    if ($count > 0) {
                    ?>
                    <td align="right">
                        Records <strong><?php echo strval($start + 1); ?> to <?php echo strval($start + count($attempts)); ?></strong> of <?php echo $count; ?>
                    </td>
                    <?php
                    }
                    ?>
                </tr>
            </tbody>
        </table>
        <br />
        <table cellspacing="1" cellpadding="2" width="100%" align="center" style="background-color:#cccccc;" border="0">
            <tbody>
                <tr>
                    <td class="smallFontGRNBG" align="left" nowrap width="20%">Timestamp
                        <a href="<?php echo base_url('activitylogs/showFailedSysuserLoginAttempts/1/timestamp-asc/'.($ipaddress == '' ? 'null' : str_replace(' ', '_', $ipaddress)).'/'.
                            ($username == '' ? 'null' : str_replace(' ', '_', $username)).'/'.($reason == '' ? 'null' : str_replace(' ', '_', $reason)).'/'.
                            $start_month.'/'.$start_year.'/'.$start_day.'/'.$end_month.'/'.$end_year.'/'.$end_day.'/'.$start.'/'.$max); ?>">
                            <img src ="<?php echo base_url('static/img/up.gif'); ?>" border="0" alt="ascending" />
                        </a>
                        <a href="<?php echo base_url('activitylogs/showFailedSysuserLoginAttempts/1/timestamp-desc/'.($ipaddress == '' ? 'null' : str_replace(' ', '_', $ipaddress)).'/'.
                            ($username == '' ? 'null' : str_replace(' ', '_', $username)).'/'.($reason == '' ? 'null' : str_replace(' ', '_', $reason)).'/'.
                            $start_month.'/'.$start_year.'/'.$start_day.'/'.$end_month.'/'.$end_year.'/'.$end_day.'/'.$start.'/'.$max); ?>">
                            <img src ="<?php echo base_url('static/img/down.gif'); ?>" border="0" alt="descending" />
                        </a>
                    </td>
                    <td class="smallFontGRNBG" align="left" nowrap width="15%">Username
                        <a href="<?php echo base_url('activitylogs/showFailedSysuserLoginAttempts/1/username-asc/'.($ipaddress == '' ? 'null' : str_replace(' ', '_', $ipaddress)).'/'.
                            ($username == '' ? 'null' : str_replace(' ', '_', $username)).'/'.($reason == '' ? 'null' : str_replace(' ', '_', $reason)).'/'.
                            $start_month.'/'.$start_year.'/'.$start_day.'/'.$end_month.'/'.$end_year.'/'.$end_day.'/'.$start.'/'.$max); ?>">
                            <img src ="<?php echo base_url('static/img/up.gif'); ?>" border="0" alt="ascending" />
                        </a>
                        <a href="<?php echo base_url('activitylogs/showFailedSysuserLoginAttempts/1/username-desc/'.($ipaddress == '' ? 'null' : str_replace(' ', '_', $ipaddress)).'/'.
                            ($username == '' ? 'null' : str_replace(' ', '_', $username)).'/'.($reason == '' ? 'null' : str_replace(' ', '_', $reason)).'/'.
                            $start_month.'/'.$start_year.'/'.$start_day.'/'.$end_month.'/'.$end_year.'/'.$end_day.'/'.$start.'/'.$max); ?>">
                            <img src ="<?php echo base_url('static/img/down.gif'); ?>" border="0" alt="descending" />
                        </a>
                    </td>
                    <td class="smallFontGRNBG" align="left" nowrap width="20%">Password</td>
                    <td class="smallFontGRNBG" align="left" nowrap width="20%">IP Address
                        <a href="<?php echo base_url('activitylogs/showFailedSysuserLoginAttempts/1/ipaddress-asc/'.($ipaddress == '' ? 'null' : str_replace(' ', '_', $ipaddress)).'/'.
                            ($username == '' ? 'null' : str_replace(' ', '_', $username)).'/'.($reason == '' ? 'null' : str_replace(' ', '_', $reason)).'/'.
                            $start_month.'/'.$start_year.'/'.$start_day.'/'.$end_month.'/'.$end_year.'/'.$end_day.'/'.$start.'/'.$max); ?>">
                            <img src ="<?php echo base_url('static/img/up.gif'); ?>" border="0" alt="ascending" />
                        </a>
                        <a href="<?php echo base_url('activitylogs/showFailedSysuserLoginAttempts/1/ipaddress-desc/'.($ipaddress == '' ? 'null' : str_replace(' ', '_', $ipaddress)).'/'.
                            ($username == '' ? 'null' : str_replace(' ', '_', $username)).'/'.($reason == '' ? 'null' : str_replace(' ', '_', $reason)).'/'.
                            $start_month.'/'.$start_year.'/'.$start_day.'/'.$end_month.'/'.$end_year.'/'.$end_day.'/'.$start.'/'.$max); ?>">
                            <img src ="<?php echo base_url('static/img/down.gif'); ?>" border="0" alt="descending" />
                        </a>
                    </td>
                    <td class="smallFontGRNBG" align="left" nowrap width="25%">Reason</td>
                </tr>
                <?php
                if (count($attempts) > 0) {
                    for ($i = 0; $i < count($attempts); $i++) {
                        $attempt = $attempts[$i];
                ?>
                <tr>
                    <td class="smallFontWHTBG" nowrap><?php echo $attempt['timestamp']; ?></td>
                    <td class="smallFontWHTBG" nowrap><?php echo $attempt['username']; ?></td>
                    <td class="smallFontWHTBG" nowrap><?php echo $attempt['password']; ?></td>
                    <td class="smallFontWHTBG" nowrap><?php echo $attempt['ipaddress']; ?></td>
                    <td class="smallFontWHTBG" nowrap>
                        <?php 
                        $key = array_search($attempt['error_code'], $reasonValueFilter);
                        echo $reasonStringFilter[$key];
                        ?>
                    </td>
                </tr>
                <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php
        } else {
    ?>
    <br /><br />No records found.
    <?php
        }
    }
    ?>
    <script type="text/javascript">
        <?php
        if (!$init) {
        ?>
        $('select[name="max"]').on('change', function (event) {
            var _this = $(this),
                max = _this.val(),
                location = '<?php echo base_url("activitylogs/showFailedSysuserLoginAttempts/1/".$order["column"]."-".$order["dir"]."/".
                    ($ipaddress == "" ? "null" : str_replace(" ", "_", $ipaddress))."/".($username == "" ? "null" : str_replace(" ", "_", $username))."/".
                    ($reason == "" ? "null" : str_replace(" ", "_", $reason))."/".
                    $start_month."/".$start_year."/".$start_day."/".$end_month."/".$end_year."/".$end_day."/0"); ?>';
            location = location + '/' + max;
            window.location = location;
        });
        <?php
        }
        ?>
        $('form[name="frmMain"]').submit(function (event) {
            var startMonth = $('select[name="start_month"]').val(),
                startDay = $('select[name="start_day"]').val(),
                startYear = $('select[name="start_year"]').val(),
                endMonth = $('select[name="end_month"]').val(),
                endDay = $('select[name="end_day"]').val(),
                endYear = $('select[name="end_year"]').val(),
                startdate = new Date(startYear, startMonth, startDay),
                enddate = new Date(endYear, endMonth, endDay);
            if (parseInt(startMonth) == 0 || parseInt(startDay) == 0 || parseInt(startYear) == 0) {
                alert('Please enter a correct start date.');
                return false;
            } else if (parseInt(endMonth) == 0 || parseInt(endDay) == 0 || parseInt(endYear) == 0) {
                alert('Please enter a correct end date.');
                return false;
            } else {
                if (startdate.getTime() > enddate.getTime()) {
                    alert('End date must be after the start date.');
                    return false;
                } else {
                    return true;
                }
            }
        });
    </script>
</body>
</html>
