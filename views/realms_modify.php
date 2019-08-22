<!DOCTYPE html>
<html lang="en">
<head>
    <title>Globelines Universal Access</title>
    <?php require 'head_includes.php'; ?>
</head>
<body style="padding-right:20px; padding-bottom:20px;">
    <div>
        <h3 class="style1">Realms: Modify</h3>
    </div>
    <div align="right" class="smallFontB">
        <a href="<?php echo base_url('main/showRealmsIndex'); ?>">Back to index</a>
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
        <form name="frmMain" action="<?php echo base_url('main/processRealmModification'); ?>" method="post">
            <table cellspacing="1" cellpadding="2" align="left">
                <tbody>
                    <tr>
                        <td class="smallFontWHTBG" align="left"><strong>Realm</strong></td>
                        <td class="smallFontWHTBG" align="left">
                            <input type="text" name="realm" value="<?php echo $realm; ?>" style="width:250px;" />
                            <input type="hidden" name="realmid" value="<?php echo $realmId; ?>" />
                            <input type="hidden" name="realmOrigName" value="<?php echo $realmOrigName; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="smallFontWHTBG" align="left"><strong>Remarks</strong></td>
                        <td class="smallFontWHTBG" align="left">
                            <input type="text" name="location" value="<?php echo isset($location) && !is_null($location) ? $location : ''; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td align="right" colspan="2">
                            <input class="button" type="submit" value="modify" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            $('form[name="frmMain"]').on('submit', function (event) {
                var name = $('input[name="realm"]'),
                    location = $('select[name="location"]');
                if (name.val().trim() == '') {
                    alert("Please fill in the Realm field");
                    $('input[name="realm"]').focus();
                    return false;
                }
                if ($('select[name="location"]').val() == '') {
                    alert("Please fill in the Default Location field");
                    $('select[name="location"]').focus();
                    return false;
                }
                return true;
            });
        });
    </script>
</body>
</html>