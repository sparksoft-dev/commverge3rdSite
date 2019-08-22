<!DOCTYPE html>
<html lang="en">
<head>
    <title>Globelines Universal Access</title>
    <?php require 'head_includes.php'; ?>
</head>
<body style="padding-right:20px; padding-bottom:20px;">
    <div>
        <h3 class="style1">Realm User Accounts</h3>
    </div>
    <div align="left">
        <form name="frmMain" action="<?php echo base_url('main/showRealmUserAccountsIndex'); ?>" method="POST">
            <?php include 'allowed_realms.php'; ?>
            <input class="button2" type="submit" value="display" />
        </form>
    </div>
    <?php
    if ($show) {
    ?>
    <div align="center">
        <h4 class="style1">Accounts under <?php echo $realm; ?> realm </h4>
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
        <table cellpadding="1" cellspacing="2" border="0" width="100%">
            <tr>
                <td align="center" class="notificationMsg" colspan="2">
                    Info as of <strong><?php echo $yesterday; ?></strong>
                </td>
            </tr>
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
                        if ($pageStart != 0) {
                            $j = $pageStart - 1;
                            echo '<a href="'.base_url("main/showRealmUserAccountsIndex/1/".$realm."/".($j * $max)."/".$max).'">&lt;&lt;</a>';
                            echo ' |';
                        }
                        for ($j = $pageStart; $j < $pageEnd; $j++) {
                             echo '<a href="'.base_url("main/showRealmUserAccountsIndex/1/".$realm."/".($j * $max)."/".$max).'">'.($j + 1).'</a>';
                            echo ' | ';
                        }
                        if ($pageEnd < $pages) {
                            $j = $pageEnd;
                            echo '<a href="'.base_url("main/showRealmUserAccountsIndex/1/".$realm."/".($j * $max)."/".$max).'">&gt;&gt;</a>';
                            echo ' |';
                        }
                        ?>
                    </td>
                    <?php
                    if ($count > 0) {
                    ?>
                    <td align="right">
                        Records <strong><?php echo strval($start + 1); ?> to <?php echo strval($start + count($subscribers)); ?></strong> of <?php echo $count; ?>
                    </td>
                    <?php
                    }
                    ?>
                </tr>
            </tbody>
        </table>
        <table cellspacing="1" cellpadding="3" width="100%" style="background-color:#000000;" border="0">
            <tr>
                <td class="smallFontGRYBG" align="middle">&nbsp;</td>
                <td class="smallFontGRYBG" noWrap align="middle">Username</td>
                <td class="smallFontGRYBG" align="middle">Name</td>
                <td class="smallFontGRYBG" noWrap align="middle">Service No.</td>
                <td class="smallFontGRYBG" noWrap align="middle">Status</td>
                <td class="smallFontGRYBG" noWrap align="middle">Service ID</td>
                <td class="smallFontGRYBG" noWrap align="middle">Static IP</td>
                <td class="smallFontGRYBG" noWrap align="middle">Multi Static IP</td>
                <td class="smallFontGRYBG" noWrap align="middle">Order No.</td>
                <td class="smallFontGRYBG" noWrap align="middle">Customer Type</td>
            </tr>
            <?php
            if ($subscribers !== false) {
                for ($i = 0; $i < count($subscribers); $i++) {
                    $subs = $subscribers[$i];
            ?>
            <tr>
                <td class="xsmallFontWHTBG">
                    <?php
                    $parts = explode('@', $subs['USER_IDENTITY']);
                    $username = $parts[0];
                    ?>
                    <a href="<?php echo base_url('main/showRealmUserAccountInfo/'.$username.'/'.$realm); ?>">View</a>
                </td>
                <td class="xsmallFontWHTBG"><?php echo $subs['USERNAME']; ?></td>
                <td class="xsmallFontWHTBG"><?php strtoupper($subs['RBCUSTOMERNAME']); ?></td>
                <td class="xsmallFontWHTBG"><?php echo $subs['RBSERVICENUMBER']; ?></td>
                <td class="xsmallFontWHTBG" align="center"><?php echo $subs['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D'; ?></td>
                <td width="5%" class="xsmallFontWHTBG">
                    <?php echo str_replace('~', '-', $subs['RBACCOUNTPLAN']); ?>
                    <?php echo !is_null($subs['RBADDITIONALSERVICE1']) ? '<br />'.$subs['RBADDITIONALSERVICE1'] : ''; ?>
                    <?php echo !is_null($subs['RBADDITIONALSERVICE2']) ? '<br />'.$subs['RBADDITIONALSERVICE2'] : ''; ?>
                </td>
                <td class="xsmallFontWHTBG"><?php echo !is_null($subs['RBIPADDRESS']) ? $subs['RBIPADDRESS'] : ''; ?></td>
                <td class="xsmallFontWHTBG"><?php echo !is_null($subs['RBMULTISTATIC']) ? $subs['RBMULTISTATIC'] : ''; ?></td>
                <td class="xsmallFontWHTBG" align="center"><?php echo !is_null($subs['RBORDERNUMBER']) ? $subs['RBORDERNUMBER'] : ''; ?></td>
                <td class="xsmallFontWHTBG" align="center"><?php echo $subs['CUSTOMERTYPE']; ?></td>
            </tr>
            <?php
                }
            } else {
            ?>
            <tr>
                <td class="xsmallFontWHTBG" colspan="10" align="center">No records found.</td>
            </tr>
            <?php
            }
            ?>
        </table>
    </div>
    <?php
    }
    ?>
    <script type="text/javascript">
        $(document).ready(function () {
            $('form[name="frmMain"]').on('submit', function (event) {
                var realm = $('select[name="realm"]');
                if (realm.val() == '') {
                    alert('Please select a realm.');
                    realm.focus();
                    return false;
                }
            });
            <?php
            if (isset($subscribers) && $subscribers !== false) {
            ?>
            $('select[name="max"]').on('change', function (event) {
                var _this = $(this),
                    max = _this.val(),
                    location = '<?php echo base_url("main/showRealmUserAccountsIndex/1/".$realm."/0"); ?>';
                location = location + '/' + max;
                console.log(location);
                window.location = location;
            });
            <?php
            }
            ?>
        });
    </script>
</body>
</html>