<!DOCTYPE html>
<html lang="en">
<head>
    <title>Globelines Universal Access</title>
    <?php require 'head_includes.php'; ?>
</head>
<body style="padding-right:20px; padding-bottom:20px;">
    <div align="left">
        <h3 class="style1">Reset Subscriber Password</h3>
    </div>
    <div align="center">
        <form name="resetpassword" action="<?php echo base_url('main/processUserPasswordReset'); ?>" method="post">
            <table cellspacing="0" cellpadding="3" border="0">
                <tbody>
                    <tr>
                        <td class="xsmallFontWHTBG" align="middle">
                            Realm:<br /><?php include 'allowed_realms.php'; ?>
                        </td>
                        <td class="xsmallFontWHTBG" align="middle">
                            User ID:<br /><input class="textstyle" size="15" name="userid" value="<?php echo isset($username) && !is_null($username) ? $username : ''; ?>" />
                        </td>
                        <td class="xsmallFontWHTBG" align="middle" colspan="2">
                            <br /><input class="button2" type="submit" value="reset password" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
        <table>
            <tr>
                <td align="middle">
                    <br /><span class="errorMsg"><?php echo isset($error) && !is_null($error) ? $error : ''; ?></span>
                </td>
            </tr>
            <tr>
                <td align="middle">
                    <br /><span class="notificationMsg"><?php echo isset($message) && !is_null($message) ? $message : ''; ?></span>
                </td>
            </tr>
        </table>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            $('form[name="resetpassword"]').on('submit', function (event) {
                var userid = $('input[name="userid"]');
                if (userid.val().trim() == '') {
                    alert('Please fill in the User id field');
                    userid.focus();
                    return false;
                }
                return true;
            });
        });
    </script>
</body>
</html>