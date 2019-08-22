<!DOCTYPE html>
<html lang="en">
<head>
    <title>Globelines Universal Access</title>
    <?php require 'head_includes.php'; ?>
</head>
<body style="padding-right:20px; padding-bottom:20px;">
    <div>
        <h3 class="style1">Net Addresses: Delete Range</h3>
    </div>
    <div align="right" class="smallFontB">
        <a href="#">Back to index</a><!--netaddresses.do?location=${location}-->
    </div>
    <div align="center">
        <table cellspacing="1" cellpadding="2" width="100%" align="center">
            <tr>
                <td>
                    <span class="notificationMsg">${message}</span>
                    <span class="errorMsg">${error}</span>
                </td>
            </tr>
        </table>
    </div>
    <div>
        <form name="frmMain" action="#" method="post"><!--deleteNetaddressRange.process-->
            <table>
                <!--
                <tr>
                    <td class="smallFontGRYBG" align="left">Location</td>
                    <td class="smallFontWHTBG" align="left">${location}<input type="hidden" name="location" value="${location}" /></td>
                </tr>
                -->
                <tr>
                    <td class="smallFontWHTBG" align="left"><strong>Net Address</strong></td>
                    <td class="smallFontWHTBG" align="left"><input type="text" name="netaddress" value="" /></td>
                </tr>
                <tr>
                    <td colspan="2" align="right"><input class="button" type="submit" value="delete" /></td>
                </tr>
            </table>
        </form>
    </div>
    <script type="text/javascript">
        function validateNet() {
            if ($('input[name="netaddress"]').val().trim() == '') {
                alert("Please fill up Net Address field.");
                $('input[name="netaddress"]').focus();
                return false;
            }
        }
        $('form[name="frmMain"]').submit(validateNet);
    </script>
</body>
</html>
