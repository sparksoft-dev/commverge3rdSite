<!DOCTYPE html>
<html lang="en" style="height:100%;">
<head>
    <title>Globelines Universal Access</title>
    <?php require 'head_includes.php'; ?>
</head>
<body style="height:100%;">
    <div id="wrap">
        <div id="main" style="padding-top:100px;">
            <form id="login" name="login" method="post" action="#">
                <table cellpadding="0" cellspacing="2" border="0" align="center" width="50%" style="background-color:#90B364;">
                    <tr>
                        <td align="center" height="35"><font class="regularB" color="#ffffff">Globelines Universal Access</font><br /></td>
                    </tr>
                    <tr>
                        <td>
                            <table cellpadding="5" cellspacing="1" border="0" align="center" style="background-color:#ffffff;" width="100%" height="100">
                                <tr>
                                    <td class="regular" align="right" style="width:40%;">Username:</td>
                                    <td style="width:60%;"><input type="text" name="username" value="<?php echo isset($username) && !is_null($username) ? $username : ''; ?>" size="15" autocomplete="off" /></td>
                                </tr>
                                <tr>
                                    <td class="regular" align="right" valign="top" style="width:40%;">Password:</td>
                                    <td valign="top" style="width:60%;"><input type="password" name="password" value="" size="15" /></td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="center" style="height:14px;">
                                        <input type="hidden" name="publicip" value="" />
                                        <font class="regularB" color="#BB0000">
                                            <span id="loginErrorMessage"><?php echo isset($loginErrorMessage) && !is_null($loginErrorMessage) ? $loginErrorMessage : ''; ?></span>
                                        </font>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="smallFontGRYBG" height="40" align="center"><input type="submit" value="Sign In" class="button" name="login" /></td>
                    </tr>
                    <tr>
                        <td>
                            <table cellpadding="1" cellspacing="0" border="0" width="100%" align="center">
                                <tr>
                                    <td class="regular" align="center" height="20"><font color="#990000">Globelines Broadband Legal Notice</font></td>
                                </tr>
                                <tr>
                                    <td>
                                        <table cellpadding="2" cellspacing="1" border="0" width="100%" style="background-color:#ffffff;">
                                            <tr>
                                                <td class="xsmallFont">WARNING: Only authorized users are allowed to access this system<br /><br />The programs and data stored in this system are licensed, private property of Globe Telecom Inc. All login attempts, access and system activities are recorded and verified. If you are not an authorized user,<br /><br /><center>Do not Attempt to Login</center><br /></td>
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
            /*
            // Create the XHR object.
            function createCORSRequest(method, url) {
                var xhr = new XMLHttpRequest();
                if ("withCredentials" in xhr) {
                    // XHR for Chrome/Firefox/Opera/Safari.
                    xhr.open(method, url, true);
                } else if (typeof XDomainRequest != "undefined") {
                    // XDomainRequest for IE.
                    xhr = new XDomainRequest();
                    xhr.open(method, url);
                } else {
                    // CORS not supported.
                    xhr = null;
                }
                return xhr;
            }

            // Helper method to parse the title tag from the response.
            function getTitle(text) {
                return text.match('<title>(.*)?</title>')[1];
            }

            // Make the actual CORS request.
            function makeCorsRequest() {
                // All HTML5 Rocks properties support CORS.
                var url = 'http://updates.html5rocks.com';

                var xhr = createCORSRequest('GET', url);
                if (!xhr) {
                    alert('CORS not supported');
                    return;
                }

                // Response handlers.
                xhr.onload = function() {
                    var text = xhr.responseText;
                    var title = getTitle(text);
                    alert('Response from CORS request to ' + url + ': ' + title);
                };

                xhr.onerror = function() {
                    alert('Woops, there was an error making the request.');
                };

                xhr.send();
            }
            */




            $('input[name="username"]').focus();
            $('#login').submit(function () {
                var username = $('input[name="username"]').val(),
                    password = $('input[name="password"]').val(),
                    loginButton = $('input[name="login"]');
                loginButton.val('Signing in...');
                $('#loginErrorMessage').text('');
                if (username.trim() == '' || password.trim() == '') {
                    $('#loginErrorMessage').text('Please enter a username and password.');
                    loginButton.val('Sign In');
                } else {
                    $.ajax({
                        url: '<?php echo base_url("main/loginProcess"); ?>',
                        type: 'POST',
                        data: {
                            'username': username.trim(),
                            'password': password.trim()
                        },
                        success: function (resp) {
                            var response = $.parseJSON(resp);
                            if (parseInt(response.status) == 1) {
                                if(parseInt(response.conc) == 0) {
                                    window.location = '<?php echo base_url("' + response.redirect + '"); ?>';
                                } else {
                                    window.location = '<?php echo base_url("admin/' + response.concredirect + '"); ?>';
                                }
                            } else {
                                var loginErrorMessage = response.loginErrorMessage;
                                $('#loginErrorMessage').text(loginErrorMessage);
                                loginButton.val('Sign In');                                    
                            }
                        },
                        error: function (resp) {
                            console.log(resp);
                            alert('An unexpected error occurred during the request.\n\nPlease try again later.');
                            loginButton.val('Sign In');
                        }
                    });
                }
                return false;
            });
        });
    </script>
</body>
</html>
