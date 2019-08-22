<!DOCTYPE html>
<html lang="en">
<head>
    <title>Globelines Universal Access</title>
    <?php require 'head_includes.php'; ?>
</head>
<body style="padding-right:20px; padding-bottom:20px;">
    <div align="left">
        <h3 class="style1">Change Subscriber Password</h3>
    </div>
    <div align="center">
        <form name="changepassword" action="<?php echo base_url('main/processChangeUserPassword'); ?>" method="POST">
            <table cellspacing="0" cellpadding="3" border="0">
                <tbody>
                    <tr>
                        <td class="xsmallFontWHTBG">Realm</TD>
                        <td><?php include 'allowed_realms.php'; ?></td>
                    </tr>
                    <tr>
                        <td class="xsmallFontWHTBG">User ID</td>
                        <td><input class="textstyle" size="15" name="userid" type="text" value="<?php echo isset($username) && !is_null($username) ? $username : ''; ?>" /></td>
                    </tr>
                    <tr>
                        <td class="xsmallFontWHTBG">New Password</td>
                        <td><input type="password" class="textstyle" size="15" name="password" /></td>
                    </tr>
                    <tr>
                        <td class="xsmallFontWHTBG">Retype New Password</td>
                        <td><input type="password" class="textstyle" size="15" name="password2" /></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="xsmallFontWHTBG" align="middle" colspan="2">
                            <br /><input class="button2" type="submit" value="change password" />
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
            $('form[name="changepassword"]').on('submit', function () {
                var userid = $('input[name="userid"]'),
                    password = $('input[name="password"]'),
                    password2 = $('input[name="password2"]');
                if (userid.val().trim() == '') {
                    alert('Please fill in the User ID field.');
                    userid.focus();
                    return false;
                }
                if (password.val().trim() == '') {
                    alert('Please fill in new password field.');
                    password.focus();
                    return false;
                }
                if (password2.val().trim() == '') {
                    alert('Please retype the new password.');
                    password2.focus();
                    return false;
                }
                if ((password.val().trim() != '' && password2.val().trim() != '' && password.val().trim() != password2.val().trim()) || password.val().length != password2.val().length) {
                    alert('Please retype your password. Passwords did not match.');
                    password.focus();
                    return false;
                }
                return true;
            });
        });
    </script>
</body>
</html>