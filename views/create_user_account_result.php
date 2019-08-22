<!DOCTYPE html>
<html lang="en">
<head>
    <title>Globelines Universal Access</title>
    <?php require 'head_includes.php'; ?>
</head>
<body style="padding-right:20px; padding-bottom:20px;">
    <div align="left">
        <h3 class="style1">Create Primary Account</h3>
    </div>
    <div align="right">
        <a href="<?php echo base_url('subscribers/showCreateSubscriberForm'); ?>">Back to create page</a>
    </div>
    <div align="center">
        <span class="notificationMsg"><?php echo isset($message) && !is_null($message) ? $message : ''; ?></span>
    </div>
    <br />
    <div align="center">
        <table cellspacing="1" cellpadding="3" style="background-color:#cccccc;" border="0">
            <tbody>
                <tr>
                    <td class="xsmallFontWHTBG" width="25%"><strong>Realm:</strong></td>
                    <td class="xsmallFontWHTBG" align="left"><?php echo isset($realm) && !is_null($realm) ? $realm : ''; ?></td>
                </tr>
                <tr>
                    <td class="xsmallFontWHTBG"><strong>Username:</strong></td>
                    <td class="xsmallFontWHTBG"><?php echo isset($username) && !is_null($username) ? $username : ''; ?></td>
                </tr>
                <tr>
                    <td class="xsmallFontWHTBG"><strong>Password:</strong></td>
                    <td class="xsmallFontWHTBG">
                        <?php
                        if (isset($password) && !is_null($password)) {
                            for ($i = 0; $i < strlen($password); $i++) {
                                echo '*';
                            }
                        } else {
                            echo '';
                        }
                        ?>
                     </td>
                </tr>
                <tr>
                    <td class="xsmallFontWHTBG"><strong>Account Plan:</strong></td>
                    <td class="xsmallFontWHTBG"><?php echo isset($acctplan) && !is_null($acctplan) ? $acctplan : ''; ?></td>
                </tr>
                <tr>
                    <td class="xsmallFontWHTBG"><strong>Status:</strong></td>
                    <td class="xsmallFontWHTBG"><?php echo isset($status) && !is_null($status) ? ($status == 'Active' ? 'A' : 'D') : ''; ?></td>
                </tr>
                <tr>
                    <td class="xsmallFontWHTBG"><strong>Customer Name:</strong></td>
                    <td class="xsmallFontWHTBG"><?php echo isset($custname) && !is_null($custname) ? $custname : ''; ?></td>
                </tr>
                <tr>
                    <td class="xsmallFontWHTBG"><strong>Order Number:</strong></td>
                    <td class="xsmallFontWHTBG"><?php echo isset($ordernum) && !is_null($ordernum) ? $ordernum : ''; ?></td>
                </tr>
                <tr>
                    <td class="xsmallFontWHTBG"><strong>Service Number:</strong></td>
                    <td class="xsmallFontWHTBG"><?php echo isset($servicenum) && !is_null($servicenum) ? $servicenum: ''; ?></td>
                </tr>
                <tr>
                    <td class="xsmallFontWHTBG"><strong>Redirection:</strong></td>
                    <td class="xsmallFontWHTBG"><?php echo isset($nonedsl) && !is_null($nonedsl) ? $nonedsl : '';?></td>
                </tr>
                <tr>
                    <td class="xsmallFontWHTBG"><strong>Service:</strong></td>
                    <td class="xsmallFontWHTBG"><?php echo isset($svccode) && !is_null($svccode) ? str_replace('~', '-', $svccode) : ''; ?></td>
                </tr>
                <?php
                if ($useIPv6) {
                ?>
                <tr>
                    <td class="xsmallFontWHTBG"><strong>IPv6 Address:</strong></td>
                    <td class="xsmallFontWHTBG"><?php echo isset($ipv6address) && !is_null($ipv6address) ? $ipv6address : ''; ?></td>
                </tr>
                <?php
                }
                ?>
                <tr>
                    <td class="xsmallFontWHTBG"><strong>IP Address:</strong></td>
                    <td class="xsmallFontWHTBG"><?php echo isset($ipaddress) && !is_null($ipaddress) ? $ipaddress : ''; ?></td>
                </tr>
                <tr>
                    <td class="xsmallFontWHTBG"><strong>Network Address:</strong></td>
                    <td class="xsmallFontWHTBG"><?php echo isset($netaddress) && !is_null($netaddress) ? $netaddress : ''; ?></td>
                </tr>
                <tr>
                    <td class="xsmallFontWHTBG"><strong>Addtional Service 1:</strong></td>
                    <td class="xsmallFontWHTBG"><?php echo isset($svc_add1) && !is_null($svc_add1) ? $svc_add1 : ''; ?></td>
                </tr>
                <tr>
                    <td class="xsmallFontWHTBG"><strong>Addtional Service 2:</strong></td>
                    <td class="xsmallFontWHTBG"><?php echo isset($svc_add2) && !is_null($svc_add2) ? $svc_add2 : ''; ?></td>
                </tr>
                <tr>
                    <td class="xsmallFontWHTBG" valign="top"><strong>Remarks:</strong></td>
                    <td class="xsmallFontWHTBG"><?php echo isset($remarks) && !is_null($remarks) ? $remarks : ''; ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>