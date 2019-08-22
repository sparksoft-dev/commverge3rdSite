<!DOCTYPE html>
<html lang="en">
<head>
    <title>Globelines Universal Access</title>
    <?php require 'head_includes.php'; ?>
</head>
<body style="padding-right:20px; padding-bottom:20px;">
    <div align="left">
        <h3 class="style1">Delete Session</h3>
    </div>
    <div align="center">
        <span class="notificationMsg"><?php echo isset($message) && !is_null($message) ? $message : ''; ?></span>
        <span class="errorMsg"><?php echo isset($error) && !is_null($error) ? $error : ''; ?></span>
        <?php
        $ok = true;
        if ($ok) {
        ?>
        <br /><br /><br />
        <table cellspacing="1" cellpadding="3" style="background-color:#cccccc;" border="0">
            <tr>
                <td class="xsmallFontWHTBG" colspan="2" width="35%"><strong>Username</strong></td>
                <td class="xsmallFontWHTBG" width="65%"><?php echo isset($username) ? $username : ''; ?></td>
            </tr>
            <tr>
                <td class="xsmallFontWHTBG" colspan="2" width="35%"><strong>Unique Session ID</strong></td>
                <td class="xsmallFontWHTBG" width="65%"><?php echo isset($sessionid) ? $sessionid : ''; ?></td>
            </tr>
            <tr>
                <!--<td class="xsmallFontWHTBG" colspan="2" width="35%"><strong>IPV4 Address</strong></td>-->
                <td class="xsmallFontWHTBG" colspan="2" width="35%"><strong>Subscriber IPV4</strong></td>
                <td class="xsmallFontWHTBG" width="65%"><?php echo isset($ipv4) ? $ipv4 : ''; ?></td>
            </tr>
            <!--
            <tr>
                <td class="xsmallFontWHTBG" colspan="2" width="35%"><strong>NAS Name</strong></td>
                <td class="xsmallFontWHTBG" width="65%"><?php //echo isset($nasname) ? $nasname : ''; ?></td>
            </tr>
            -->
        </table>
        <?php
        }
        ?>
    </div>
    <br /><br />
    <div align="center">
        <a href="<?php echo base_url('main/showOnlineSessionForm'); ?>">Back</a>
    </div>
</body>
</html>