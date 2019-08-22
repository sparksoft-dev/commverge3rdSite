<!DOCTYPE html>
<html lang="en">
<head>
    <title>Globelines Universal Access</title>
    <?php require 'head_includes.php'; ?>
</head>
<BODY style="padding-right:20px; padding-bottom:20px;">
    <div align="left">
        <h3 class="style1">Create Secondary Account</h3>
    </div>
    <div class="smallFontB" align="center">
        <form name="globeacct" action="<?php echo base_url('subscribers/processCreateSubscriber2'); ?>" method="post">
            <table cellspacing="0" cellpadding="5" border="0" align="middle">
                <tbody>
                    <tr>
                        <td class="regular">Realm</td>
                        <td class="smallFontWHTBG" align="left"><?php include 'allowed_realms.php'; ?></td>
                    </tr>
                    <tr>
                        <td class="regular">Primary Username:</td>
                        <td>
                            <input maxlength="30" name="username" value="<?php echo isset($username) && !is_null($username) ? $username: ''; ?>" autocomplete="off" />&nbsp;
                            <font face="verdana" color="#990000" size="1">*required</font>&nbsp;
                            <font class="smallFontGRAY">(maximum of 30 characters)</font><br />
                        </td>
                    </tr>
                    <tr>
                        <td class="regular">Assigned Secondary Username:</td>
                        <td>
                            <input maxlength="30" name="username2" value="<?php echo isset($username2) && !is_null($username2) ? $username2 : ''; ?>" />&nbsp;
                            <font face="verdana" color="#990000" size="1">*required</font>&nbsp;
                            <font class="smallFontGRAY">(maximum of 30 characters)</font><br />
                        </td>
                    </tr>
                    <tr>
                        <td align="middle" colspan="2"><input class="button" type="submit" value="create" /></td>
                    </tr>
                </tbody>
            </table>
        </form>
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
    <script type="text/javascript">
        var proceed = '<?php echo $proceed ? 1 : 0; ?>';
        $(document).ready(function () {
            $('form[name="globeacct"]').on('submit', function (event) {
                if (parseInt(proceed) == 0) {
                    alert('There are connection problems.\n\nPlease reload page to re-check connection.');
                    return false;
                }
                var username = $('input[name="username"]'),
                    username2 = $('input[name="username2"]');
                if (username.val().trim() == '') {
                    alert("Please fill in the primary username field");
                    username.focus();
                    return false;
                }
                if (username2.val().trim() == '') {
                    alert("Please fill in the secondary username field");
                    username2.focus();
                    return false;    
                }
            });
        });
    </script>
</body>
</html>
