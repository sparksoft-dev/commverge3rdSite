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
        <font color="#cc0000"><strong>${confirmationmessage}</strong></FONT>
        <br /><br /><br />
        <table border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td align="left">
                    <form action="#"><!--deleteSysuserSession.process-->
                        <input type="hidden" name="user" value="${user}" />
                        <input type="hidden" name="force" value="true" />
                        <input type="hidden" name="ipaddress" value="${ipaddress}" />
                        <input type="hidden" name="username" value="${username}" />
                        <input type="hidden" name="sessionid" value="${sessionid}" />
                        <input type="hidden" name="ipv4address" value="${ipv4address}" />
                        <input type="hidden" name="nasname" value="${nasname}" />
                        <input name="submit" type="submit" value="Yes" class="button">
                    </form>
                </td>
                <td>&nbsp;</td>
                <td align="left">
                    <form action="#"><!--deleteSysuserSession.process-->
                        <input type="hidden" name="user" value="${user}" />
                        <input type="hidden" name="force" value="false" />
                        <input name="submit" type="submit" value="No" class="button" />
                    </form>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>