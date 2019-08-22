<!DOCTYPE html>
<html lang="en">
<head>
    <title>Globelines Universal Access</title>
    <?php require 'head_includes.php'; ?>
</head>
<body style="padding-right:20px; padding-bottom:20px">
    <div align="left">
        <h3 class="style1">Search Concurrent Sessions</h3>
    </div>
    <div align="center">
        <form name="frmMain" action="#" method="get">
            <table cellspacing="1" cellpadding="3" border="0" class="white-bg">
                <tr>
                    <td class="xsmallFontWHTBG" align="center">
                        User ID:<br /><input class="textstyle" size="30" type="text" name="user" />
                    </td>
                    <td class="xsmallFontWHTBG" align="center">
                        Realm:<br /><?php include 'allowed_realms.php'; ?>
                    </td>
                    <td class="xsmallFontWHTBG" align="center">
                        <br /><input name="submit" type="submit" value="search" class="button2" />
                    </td>
                </tr>
            </table>
        </form>
        <?php
        $selector1 = true;
        $selector2 = true;
        if ($selector1) {
            if ($selector2) {
        ?>
        <table cellspacing="1" cellpadding="3" border="0" style="background-color:#cccccc;">
            <tr>
                <td class="xsmallFontWHTBG" colspan="2" width="35%" nowrap><strong>ID</strong></td>
                <td class="xsmallFontWHTBG" width="65%" nowrap>[ID]</td>
            </tr>
            <tr>
                <td class="xsmallFontWHTBG" colspan="2" width="35%" nowrap><strong>Count</strong></td>
                <td class="xsmallFontWHTBG" width="65%" nowrap>[COUNT]</td>
            </tr>
        </table>
        <table border="0">
            <tr>
                <td align="right">
                    <form action="#">
                        <input type="hidden" name="user" value="" />
                        <input type="hidden" name="back" value="" />
                        <input name="submit" type="submit" value="Delete Session" class="button" />
                    </form>
                </td>
            </tr>
        </table>
        <?php
            } else {

            }
        ?>
        <br>
        <table border="0" cellspacing="0" cellpadding="0">
            <tr><td width="30%" colspan="2" class="xsmallFontWHTBG" class="regularB">Record(s) Found:</td>
                <td width="65%">&nbsp;&nbsp;N</td>
            </tr>
        </table>
        <?php
        }
        ?>
    </div>
    <script type="text/javascript">
        function validateInput() {
            var name = $('input[name="user"]').val().trim(),
                errors = [];
            if (name == '') {
                errors.push('Please fill in the User ID field');
            }
            if (name.indexOf(' ') != -1) {
                errors.push('User ID must not contain spaces');
            }
            if (errors.length != 0) {
                var errorStr = 'Please fix the following errors:\n\n';
                for (var i = 0; i < errors.length; i++) {
                    errorStr = errorStr + errors[i] + '\n';
                }
                alert(errorStr);
                $('input[name="user"]').focus();
                return false;
            } else {
                return true;
            }
        }
        $('form[name="frmMain"]').submit(validateInput);
    </script>
</BODY>
</HTML>
