<!DOCTYPE html>
<html lang="en">
<head>
    <title>Globelines Universal Access</title>
    <?php require 'head_includes.php'; ?>
</head>
<body style="padding-right:20px; padding-bottom:20px;">
    <div>
        <h3 class="style1">Net Addresses: Create</h3>
    </div>
    <div align="right" class="smallFontB">
        <a href="<?php echo base_url('main/showNetaddressesIndex/1'); ?>">Back to index</a>
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
        <form name="frmMain" action="<?php echo base_url('main/createNetaddressProcess'); ?>" method="post">
            <table cellpadding="2">
                <tr>
                    <td class="smallFontWHTBG" align="left"><strong>Location</strong></td>
                    <td class="smallFontWHTBG" align="left">
                        <select name="location" <?php echo $set ? 'disabled' : ''; ?>>
                            <option value="">-select location-</value>
                                <?php
                                for ($i = 0; $i < count($locations); $i++) {
                                    echo '<option value="'.$locations[$i].'" '.($locations[$i] == $location ? 'selected' : '').'>'.$locations[$i].'</option>';
                                }
                                ?>
                        </select>
                        <?php
                        if ($set) {
                            echo '<input type="hidden" name="location" value="'.$location.'" />';
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="smallFontWHTBG" align="left"><strong>Net Address</strong></td>
                    <td class="smallFontWHTBG" align="left">
                        <input type="text" name="netaddress" value="<?php echo isset($netaddress) && !is_null($netaddress) ? $netaddress : ''; ?>" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="right"><input class="button" type="submit" value="create" /></td>
                </tr>
            </table>
        </form>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            $('form[name="frmMain"]').on('submit', function (event) {
                <?php
                if (!$set) {
                ?>
                if ($('select[name="location"]').val() == '') {
                    alert("Please select a location.");
                    $('select[name="location"]').focus();
                    return false;
                }
                <?php
                }
                ?>
                if ($('input[name="netaddress"]').val().trim() == '') {
                    alert("Please fill up Network Address field.");
                    $('input[name="ipaddress"]').focus();
                    return false;
                }
            });
        });
    </script>
</body>
</html>