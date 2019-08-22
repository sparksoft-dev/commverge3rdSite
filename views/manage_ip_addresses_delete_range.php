<!DOCTYPE html>
<html lang="en">
<head>
    <title>Globelines Universal Access</title>
    <?php require 'head_includes.php'; ?>
</head>
<body style="padding-right:20px; padding-bottom:20px;">
    <div>
        <h3 class="style1">IP Addresses: Delete Range</h3>
    </div>
    <div align="right" class="smallFontB">
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
        <form name="frmMain" action="<?php echo base_url('main/deleteIpaddressRangeProcess'); ?>" method="post">
            <table>
                <!--
                <tr>
                    <td class="smallFontGRYBG" align="left">Location</td>
                    <td class="smallFontWHTBG" align="left">${location}<input type="hidden" name="location" value="${location}" /></td>
                </tr>
                -->
                <tr>
                    <td class="smallFontWHTBG" align="left"><strong>IP Address Range</strong></td>
                    <td class="smallFontWHTBG" align="left">
                        <input type="text" name="ipaddress" value="<?php echo isset($ipaddress) && !is_null($ipaddress) ? $ipaddress : ''; ?>" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="right"><input class="button" type="submit" value="delete" /></td>
                </tr>
            </table>
        </form>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            $('input[name="ipaddress"]').focus();
            $('form[name="frmMain"]').on('submit', function (event) {
                if ($('input[name="ipaddress"]').val().trim() == '') {
                    alert("Please fill up IP Address field.");
                    $('input[name="ipaddress"]').focus();
                    return false;
                }
            });
        });
    </script>
</body>
</html>
