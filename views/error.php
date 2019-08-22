<!DOCTYPE html>
<html lang="en">
<head>
    <title>Globelines Universal Access</title>
    <?php require 'head_includes.php'; ?>
</head>
<body>
    <div align="left">
        <h3 class="style1"><?php echo isset($title) && !is_null($title) ? $title : ''; ?></h3>
    </div>
    <table cellspacing="1" cellpadding="2" width="100%" align="center" valign="middle">
        <tbody valign="middle">
            <tr>
                <td align="center"><font color="#BB0000">
                    <strong><?php echo isset($errorMessage) && !is_null($errorMessage) ? $errorMessage : ''; ?></strong></font>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <?php
                if (isset($backurl) && !is_null($backurl)) {
                ?>
                <td align="center"><a href="<?php echo base_url($backurl); ?>">BACK</a></td>
                <?php
                }
                ?>
            </tr>
        </tbody>
    </table>
</body>
</html>