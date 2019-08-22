<!DOCTYPE html>
<html lang="en">
<head>
    <title>Globelines Universal Access</title>
    <?php require 'head_includes.php'; ?>
</head>
<body style="padding-right:20px; padding-bottom:20px;">
    <div align="left">
        <h3 class="style1">Error</h3>
    </div>
    <table cellspacing="1" cellpadding="2" width="100%" align="center" valign="middle">
        <tbody valign="middle">
            <tr>
                <td align="center" id="msg"><font color="#BB0000"><strong>Page not found.</strong></font></td>
            </tr>
        </tbody>
    </table>
    <script type="text/javascript">
        <?php
        if (isset($reload_parent) && intval($reload_parent) == 1) {
        ?>
        $('#msg').text('You have been logged out.');
        window.top.location = '<?php echo base_url($portal); ?>';
        <?php
        }
        ?>
    </script>
</body>
</html>