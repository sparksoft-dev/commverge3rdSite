<!DOCTYPE html>
<html lang="en" style="height:100%;">
<head>
    <title>Globelines Universal Access</title>
    <?php require 'head_includes.php'; ?>
</head>
<body style="height:100%;">
    <div id="wrap">
        <div id="main" style="padding-top:100px;">
            <form name="login" method="post">
                <table cellpadding="0" cellspacing="2" border="0" align="center" width="50%" style="background-color:#90B364;">
                    <tr>
                        <td align="center" height="35"><font class="regularB" color="#ffffff">Globelines Universal Access</font><br /></td>
                    </tr>
                    <tr>
                        <td>
                            <table cellpadding="5" cellspacing="1" border="0" align="center" class="white-bg" style="width:100%; height:100px;">
                                <tr>
                                    <td class="regular" align="center">
                                        <strong>Warning: Another user is currently logged-in using your username and password.</strong><br />
                                        To protect your account from unauthorized use, it is advisable that you change your password now. Do you want to change your password now?
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="smallFontGRYBG" height="40" align="center">
                            <input class="button" name="changepass" type="button" value="Yes, take me to the 'Change Password' page." />
                            <input class="button" name="welcome" type="button" value="No, thanks." />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table cellpadding="1" cellspacing="0" border="0" width="100%" align="center">
                                <tr>
                                    <td class="regular" align="center" height="20"><font color="#990000">Globelines Broadband Legal Notice</font></td>
                                </tr>
                                <tr>
                                    <td>
                                        <table cellpadding="2" cellspacing="1" border="0" class="white-bg" style="width:100%;">
                                            <tr>
                                                <td class="xsmallFont">
                                                    WARNING: Only authorized users are allowed to access this system<br /><br />
                                                    The programs and data stored in this system are licensed, private property of Globe Telecom Inc. 
                                                    All login attempts, access and system activities are recorded and verified. 
                                                    If you are not an authorized user,<br /><br />
                                                    <center>Do not Attempt to Login</center><br />
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </form>
            <br /><br />
        </div>
    </div>
    <?php include 'footer.php'; ?>
    <script type="text/javascript">
        $(document).ready(function () {
            $('input[name="changepass"]').on('click', function (event) {
                window.location = '<?php echo base_url("admin/concurrentGoPassword"); ?>';
            });
            $('input[name="welcome"]').on('click', function (event) {
                window.location = '<?php echo base_url("admin"); ?>';
            });
        });
    </script>
</body>
</html>
