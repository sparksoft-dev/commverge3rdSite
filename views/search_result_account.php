<!DOCTYPE html>
<html lang="en">
<head>
    <title>Globelines Universal Access</title>
    <?php require 'head_includes.php'; ?>
</head>
<body style="padding-right:20px; padding-bottom:20px;">
    <div align="left">
        <h3 class="style1">Account Search Result</h3>
    </div>
    <div align="center">
        <table cellspacing="1" cellpadding="2" width="100%" align="center">
            <tr>
                <td align="center">
                    <span class="notificationMsg"><?php echo isset($message) && !is_null($message) ? $message : ''; ?></span>
                    <span class="errorMsg"><?php echo isset($error) && !is_null($error) ? $error : ''; ?></span>
                </td>
            </tr>
        </table>
    </div>
    <div align="center">
        <table cellspacing="1" cellpadding="2" width="100%" align="center">
            <tr>
                <td align="center">
                    Keyword: '<?php echo $keyword; ?>'
                </td>
            </tr>
        </table>
        <br /><br />
        <table cellspacing="1" cellpadding="2" width="100%" align="center" style="background-color:#cccccc;">
            <tr>
                <td class="smallFontGRYBG" align="center">Results</td>
                <td class="smallFontGRYBG" align="center">Description</td>
            </tr>
            <?php
            if ($results !== false) {
                for ($i = 0; $i < count($results); $i++) {
                    $row = $results[$i];
            ?>
            <tr>
                <td class="smallFontWHTBG" align="left">
                    <?php echo strtoupper($row['RBCUSTOMERNAME']); ?><br />
                    Realm: <?php echo $row['RBREALM']; ?><br />
                    <?php
                    $parts = explode('@', $row['USERNAME']);
                    ?>
                    User ID: <a href="<?php echo base_url('subscribers/showSubscriberInfoViaUrl/'.$parts[0].'/'.$row['RBREALM']); ?>"><?php echo $parts[0]; ?></a><br />
                    Customer Status: <?php echo $row['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D'; ?>
                </td>
                <td class="smallFontWHTBG" align="left">
                    <?php
                    $url = base_url('main/showSearchAccountServicesInfo/'.$parts[0].'/'.$parts[1].'/'.str_replace('~', '-', $row['RBACCOUNTPLAN']).'/'.$row['CUSTOMERSTATUS'].'/'.
                        (is_null($row['RBADDITIONALSERVICE1']) ? '-' : $row['RBADDITIONALSERVICE1']).'/'.
                        (is_null($row['RBADDITIONALSERVICE2']) ? '-' : $row['RBADDITIONALSERVICE2']).'/'.
                        (is_null($row['RBIPADDRESS']) ? '-' : $row['RBIPADDRESS']).'/'.(is_null($row['RBMULTISTATIC']) ? '-' : $row['RBMULTISTATIC']));
                    ?>
                    <a href="<?php echo $url; ?>">View List of Services</a><br />
                    <?php echo str_replace('~', '-', $row['RBACCOUNTPLAN']); ?>
                    <?php echo isset($row['RBADDITIONALSERVICE1']) && !is_null($row['RBADDITIONALSERVICE1']) ? ', '.$row['RBADDITIONALSERVICE1'] : ''; ?>
                    <?php echo isset($row['RBADDITIONALSERVICE2']) && !is_null($row['RBADDITIONALSERVICE2']) ? ', '.$row['RBADDITIONALSERVICE2'] : ''; ?>
                </td>
            </tr>
            <?php
                }
            } else {
            ?>
            <tr>
                <td class="smallFontWHTBG" align="center" colspan="2">No records found</td>
            </tr>
            <?php
            }
            ?>
        </table> 
    </div>
</body>
</html>