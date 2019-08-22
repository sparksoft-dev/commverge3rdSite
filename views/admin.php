<!DOCTYPE html>
<html lang="en" style="height: 100%;">
<head>
    <title>Globelines Universal Access</title>
    <?php require 'head_includes.php'; ?>
</head>
<body style="height: 100%;">
    <div id="wrap">
        <div id="main">
            <!-- onload="resizeContentArea()" -->
            <table cellspacing="0" cellpadding="0" width="100%" border="0">
                <tbody>
                    <tr>
                        <td height="30"><img src="<?php echo base_url('static/img/globe-logo-admin.jpg'); ?>" align="absmiddle" border="0"></td>
                        <td class="header" align="right">Globelines Universal Access&nbsp;&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="background-color:#90b364;" colspan="2"><img height="1" src="<?php echo base_url('static/img/blank.gif'); ?>" width="1" border="0"></td>
                    </tr>
                    <tr>
                        <td class="myFontSM" align="right" style="background-color:#90b364;" colspan="2" height="20">
                            Logged in as <font color="#000000"><strong><?php echo $username; ?></strong></font>&nbsp;&nbsp;
                            [<a href="<?php echo base_url('main/logout'); ?>"><font color="#000000">SIGN OUT</font></a>]&nbsp;&nbsp;
                        </td>
                    </tr>
                </tbody>
            </table>
            <table cellspacing="0" cellpadding="0" width="100%" border="0" style="background-color:ffffff;" id="maintable">
                <tbody>
                    <tr>
                        <td valign="top" id="menutd" width="200px" style="background-color:#ededed;">
                            <!-- main content -->
                            <table cellspacing="0" cellpadding="10" align="left" style="background-color:#ededed;" border="0">
                                <tbody>
                                    <tr>
                                        <td valign="top" width="200px" style="background-color:#ededed;">
                                            <?php include 'admin_menu.php'; ?>
                                            <table cellspacing="0" cellpadding="0" width="15" align="left" border="0">
                                                <tbody>
                                                    <tr>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <!-- end of main content-->
                        </td>
                        <td style="background-color:#ffffff; width:20px;"></td>
                        <td style="background-color:#ffffff;" valign="top" id="contenttd" height="750px" align="center">
                            <iframe id="content" name="content" src="<?php echo isset($thePage) && !is_null($thePage) ? base_url($thePage) : ''; ?>" width="100%" height="100%" marginwidth="5" marginheight="5" frameborder="0" border="0" scrolling="auto"></iframe>
                        </td>
                    </tr>
            </table>
        </div>
    </div>
    <?php include 'footer.php'; ?>
    <script type="text/javascript">
        function checkTimeout() {
            $.ajax({
                url:'<?php echo base_url("main/checkTimeout"); ?>',
                type: 'POST',
                success: function (resp) {
                    var response = $.parseJSON(resp);
                    if (parseInt(response.expired) == 1 || parseInt(response.logged_out) == 1) {
                        window.location = '<?php echo base_url("admin"); ?>';
                    }
                },
                error: function (resp) {
                    console.log('ajax session expiry check error: ' + resp);
                }
            });
        }
        //setInterval(checkTimeout, 300000);
        setInterval(checkTimeout, 1800000);
        /*
        // Firefox worked fine. Internet Explorer shows scrollbar because of frameborder
        function resizeContentArea () {
            var tablewidth = document.getElementById('maintable').offsetWidth;
            var menuwidth = document.getElementById('menutd').offsetWidth;
            var contentwidth = document.getElementById('contenttd').offsetWidth;
            document.getElementById('contenttd').style.width = tablewidth-menuwidth + 'px';
        }
        function changeUrl (url) {
            document.location=url;
        }
        */
    </script>
    </body>
</html>