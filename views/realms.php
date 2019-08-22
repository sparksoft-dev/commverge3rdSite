<!DOCTYPE html>
<html lang="en">
<head>
    <title>Globelines Universal Access</title>
    <?php require 'head_includes.php'; ?>
</head>
<body style="padding-right:20px; padding-bottom:20px;">
    <div align="left">
        <h3 class="style1">Realms</h3>
    </div>
    <div class="smallFontB" align="right">
        <a href="<?php echo base_url('main/showCreateRealmForm'); ?>">Add Record</a>
        <!--
        &nbsp;|&nbsp;
        [<a href="<?php //echo base_url('main/showRealmsIndex'); ?>">Reload Realms</a>]
        -->
    </div>
    <div align="center">
        <table cellspacing="1" cellpadding="2" width="100%" align="center">
            <tr>
                <td align="center">
                    <span class="notificationMsg"><?php echo isset($message) && !is_null($message) ? $message : ''; ?></span>
                    <span class="errorMsg"><?php echo isset($error) && !is_null($error) ? $error : ''; ?></span>
                </td>
            </tr>
        </table>
    </div>
    <div>
        <table cellspacing="1" cellpadding="3" style="background-color:#cccccc;" border="0" width="100%" id="realmtable">
            <tbody>
                <tr>
                    <td class="smallFontGRYBG" align="left" width="15%"></td>
                    <td class="smallFontGRYBG" align="left" width="45%">Realms</td>
                    <td class="smallFontGRYBG" align="left" width="40%">Remarks</td>
                </tr>
                <?php
                if ($realms !== false) {
                    for ($i = 0; $i < count($realms); $i++) {
                        $realm = $realms[$i];
                ?>
                <tr class="realmrow">
                    <td class="smallFontWHTBG">
                        <a href="<?php echo base_url('main/showModifyRealmForm/'.$realm['REALMNAME'].'/'.str_replace(' ', '_', $realm['REMARKS'])); ?>">Modify</a>&nbsp;
                        <a href="#" class="deleterealm" realm="<?php echo $realm['REALMNAME']; ?>">Delete</a>
                    </td>
                    <td class="smallFontWHTBG"><?php echo $realm['REALMNAME']; ?></td>
                    <td class="smallFontWHTBG"><?php echo $realm['REMARKS']; ?></td>
                </tr>
                <?php
                    }
                } else {
                ?>
                <tr>
                    <tr align="center"><td class="smallFontWHTBG" colspan="3"><font color="red">No Records Found</font></td></tr>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#realmtable').on('click', 'a.deleterealm', function (event) {
                event.preventDefault();
                var _this = $(this),
                    thisRow = _this.closest('tr'),
                    realm = _this.attr('realm');
                var proceed = confirm('You are about to delete realm: ' + realm + '.\n\nAre you sure?');
                if (proceed) {
                    _this.text('Deleting...');
                    $.ajax({
                        url: '<?php echo base_url("main/processRealmDeletion"); ?>',
                        type: 'POST',
                        data: {
                            'realm': realm
                        },
                        success: function (resp) {
                            var response = $.parseJSON(resp);
                            if (parseInt(response.status) == 1) {
                                _this.parent().empty().append('<span class="errorMsg">DELETED</span>');
                                setTimeout(function () {
                                    thisRow.fadeOut('slow', function () {
                                        thisRow.remove();
                                    });
                                }, 250);
                            } else {
                                _this.text('Delete');
                                alert('Failed to delete realm: ' + realm + '.\n\nPlease try again later.');    
                            }
                        },
                        error: function (resp) {
                            _this.text('Delete');
                            alert('An unexpected error occurred during the request.\n\nPlease try again later.');
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>