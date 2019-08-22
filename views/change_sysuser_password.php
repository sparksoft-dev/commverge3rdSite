<!DOCTYPE html>
<html lang="en">
<head>
    <title>Globelines Universal Access</title>
    <?php require 'head_includes.php'; ?>
</head>
<body style="padding-right:20px; padding-bottom:20px;">
    <div align="left">
        <h3 class="style1">Change Account Password</h3>
    </div>
    <div align="center">
        <form name="changepassword" action="<?php echo base_url('main/processPasswordChange'); ?>" method="POST">
            <table cellspacing="1" cellpadding="3" border="0" class="white-bg">
                <tbody>
                    <tr>
                        <td class="xsmallFontWHTBG">Username</td>
                        <td class="xsmallFontWHTBG"><input class="textstyle" type="text" name="username" value="<?php echo isset($username) && !is_null($username) ? $username : ''; ?>" autocomplete="off" /></td>
                    </tr>
                    <tr>
                        <td class="xsmallFontWHTBG">New Password</td>
                        <td class="xsmallFontWHTBG"><INPUT class="textstyle" type="password" name="newpassword1" /></td>
                    </tr>
                    <tr>
                        <td class="xsmallFontWHTBG">Retype New Password</td>
                        <td class="xsmallFontWHTBG"><input class="textstyle" type="password" name="newpassword2" /></td>
                    </tr>
                    <tr>
                        <td align="middle" colspan="2" class="xsmallFontWHTBG"><input class="button2" type="submit" value="change password" /></td>
                    </tr>
                </tbody>
            </table>
        </form>
        <table>
            <tr>
                <td align="middle" colspan="2"><span class="errorMsg"><?php echo isset($error) && !is_null($error) ? $error : ''; ?></span></td>
            </tr>
            <tr>
                <td align="middle" colspan="2"><span class="notificationMsg"><?php echo isset($message) && !is_null($message) ? $message : ''; ?></span></td>
            </tr>
        </table>
    </div>
    <script type="text/javascript">
         $(document).ready(function () {
            $('form[name="changepassword"]').on('submit', function () {
                var username = $('input[name="username"]'),
                    password = $('input[name="newpassword1"]'),
                    password2 = $('input[name="newpassword2"]');
                if (username.val().trim() == '') {
                    alert('Please fill in the User ID field.');
                    username.focus();
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