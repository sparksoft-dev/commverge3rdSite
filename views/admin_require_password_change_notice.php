<!DOCTYPE html>
<html lang="en" style="height:100%;">
<head>
    <title>Globelines Universal Access</title>
    <?php require 'head_includes.php'; ?>
</head>
<body style="background-color:#ffffff; height:100%;">
    <div id="wrap">
        <div id="main" style="padding-top:100px;">
            <form name="changepassword" method="post" action="<?php echo base_url('main/processRequiredPasswordChange'); ?>">
                <table cellpadding="0" cellspacing="2" border="0" align="center" width="50%" style="background-color:#90B364;">
                    <tr>
                        <td align="center" height="35"><font class="regularB" color="#ffffff">Globelines Universal Access</font><br /></td>
                    </tr>
                    <tr>
                        <td>
                            <table cellpadding="5" cellspacing="1" border="0" align="center" style="background-color:#ffffff;" width="100%" height="100">
                                <tr>
                                    <td colspan="2" align="center">
                                        <font class="regularB" color="#000000"><?php echo isset($message) && !is_null($message) ? $message : ''; ?></font>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="regular" align="right">Username:</td>
                                    <td class="regular" align="left"><?php echo isset($username) && !is_null($username) ? $username : ''; ?></td>
                                    <input type="hidden" name="username" value="<?php echo isset($username) && !is_null($username) ? $username : ''; ?>" autocomplete="off" />
                                </tr>
                                <tr>
                                    <td class="regular" align="right">New Password:</td>
                                    <td valign="top"><input type="password" name="password1" value="" size="15" /></td>
                                </tr>
                                <tr>
                                    <td class="regular" align="right" valign="top">Confirm New Password:</td>
                                    <td valign="top"><input type="password" name="password2" value="" size="15" /></td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="center"><span class="errorMsg"><?php echo isset($error) && !is_null($error) ? $error : ''; ?></span></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="smallFontGRYBG" height="40" align="center"><input type="submit" class="button" value="change password" /></td>
                    </tr>
                </table>
            </form>
            <br /><br />
        </div>
    </div>
    <?php include 'footer.php'; ?>
    <script type="text/javascript">
        $(document).ready(function () {
            $('input[name="password1"]').focus();
            $('form[name="changepassword"]').on('submit', function (event) {
                var password1 = $('input[name="password1"]'),
                    password2 = $('input[name="password2"]');
                if (password1.val().trim() == '' || password2.val().trim() == '') {
                    alert('Please enter both password fields');
                    password1.focus();
                    return false;
                }
                if (password1.val().trim() != password2.val().trim()) {
                    alert("Your passwords do not match. Please re-enter your password.");
                    password1.focus();
                    return false;    
                }
                return true;
            });
        });
    </script>
</body>
</html>
