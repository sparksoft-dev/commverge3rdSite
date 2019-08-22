<!DOCTYPE html>
<html lang="en">
<head>
    <title>Globelines Universal Access</title>
    <?php require 'head_includes.php'; ?>
</head>
<body>
    <table cellspacing="1" cellpadding="0" width="100%" align="center" style="background-color:#639ACE;" border="0">
        <tbody>
            <tr>
                 <td class="regularB" align="middle" height="25"><font color="#ffffff">Assign Network Address</font></td>
            </tr>
            <tr>
                <form name="assignnet">
                    <table cellspacing="1" cellpadding="4" width="100%" align="center" style="background-color:#ffffff;" border="0">
                        <tbody>
                            <tr>
                                <td height="25" colspan="4"></td>
                            </tr>
                            <tr>
                                <td class="regular" align="right">Username:</td>
                                <td class="regular" colspan="3">
                                    <?php
                                    if ((isset($username) && !is_null($username) && $username != '') && (isset($realm) && !is_null($realm))){
                                        echo $username.'@'.$realm;
                                    } else {
                                        echo '';
                                    }
                                    ?>
                                    <span id="username" style="display:none;"><?php echo isset($username) && !is_null($username) ? $username : ''; ?></span>
                                    <span id="realm" style="display:none;"><?php echo isset($realm) && !is_null($realm) ? $realm : ''; ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="regular" align="right">Search IP:</td>
                                <td class="regular" colspan="3">
                                    <?php
                                    $ipValue = ((isset($ip) && !is_null($ip) && $ip != '-') && (isset($subnet) && !is_null($subnet) && $subnet != '-')) ? $ip.'/'.$subnet : '';
                                    ?>
                                    <input type="text" name="findnet" id="findnet" value="<?php echo $ipValue; ?>" style="margin-right:10px; width:150px;" />
                                    <input type="button" class="button" value="Search" id="findnetbtn" />
                                    <span id="findnetmsg" style="margin-left:10px;"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="regular" align="right">Search Cabinet:</td>
                                <td class="regular" colspan="3">
                                    <?php
                                    $cabinetName = (isset($cabinet) && !is_null($cabinet) && $cabinet != '-') ? $cabinet : '';
                                    ?>
                                    <input type="text" name="findcabinet" id="findcabinet" value="<?php echo $cabinetName; ?>" style="margin-right:10px; width:150px;" />
                                    <input type="button" class="button" value="Search" id="findcabinetbtn" />
                                    <span id="findcabinetmsg" style="margin-left:10px;"></span>
                                </td>
                            </tr>
                            <tr>
                                <td height="20" colspan="4"></td>
                            </tr>
                            <tr>
                                <td class="regular" align="right" style="width:24%;">Location:</td>
                                <td style="width:26%;">
                                    <select name="iplocation">
                                        <option value=""></option>
                                        <?php
                                        if (isset($locations) && !is_null($locations)) {
                                            $locationCount = count($locations);
                                            for ($i = 0; $i < $locationCount; $i++) {
                                                echo '<option value="'.$locations[$i].'" '.($locations[$i] == $location ? 'selected' : '').'>'.
                                                    $locations[$i].
                                                '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td class="regular" align="right" style="width:24%;">Cabinet Name:</td>
                                <td style="width:26%;">
                                    <select name="cabinets">
                                        <option value="" location=""></option>
                                        <?php
                                        if (isset($cabinets) && !is_null($cabinets)) {
                                            $cabinetCount = count($cabinets);
                                            for ($i = 0; $i < $cabinetCount; $i++) {
                                                $selected = false;
                                                if (isset($cabinetForDropdown) && !is_null($cabinetForDropdown) && $cabinetForDropdown != '-') {
                                                    // $selected = intval($cabinets[$i]['id']) == intval($cabinetId);
                                                    $selected = strtolower($cabinets[$i]['name']) == strtolower($cabinetForDropdown);
                                                }
                                                echo '<option value="'.$cabinets[$i]['id'].'" location="'.$cabinets[$i]['homing_bng'].'"'.($selected ? ' selected' : '').'>'.
                                                    $cabinets[$i]['name'].
                                                '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="regular" align="right">Net Address:</td>
                                <td>
                                    <select id="ipid" name="popipaddress">
                                        <?php
                                        if (isset($netaddresses) && !is_null($netaddresses)) {
                                            if ($location != '') {
                                                $ipCount = count($netaddresses);
                                                for ($i = 0; $i < $ipCount; $i++) {
                                                    $selected = false;
                                                    if (isset($ip) && !is_null($ip) && $ip != '-') {
                                                        $selected = $netaddresses[$i]['NETADDRESS'] == $ip.'/'.$subnet;
                                                    }
                                                    echo '<option value="'.$netaddresses[$i]['NETADDRESS'].'"'.($selected ? ' selected' : '').'>'.
                                                        $netaddresses[$i]['NETADDRESS'].
                                                    '</option>';
                                                } 
                                            }
                                        }                                    
                                        ?>
                                    </select>
                                </td>
                                <td colspan="2"></td>
                            </tr>
                            <tr>
                                <td align="middle" colspan="2"><br />
                                    <input class="button" name="selectnet" type="button" value="Select" />&nbsp;
                                    <input class="button" name="closewindow" type="button" value="Cancel" />
                                </td>
                                <td colspan="2"></td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </tr>
        </tbody>
    </table>
    <script type="text/javascript">
        var netLocationDropdown = $('select[name="iplocation"]'),
            netAddressDropdown = $('select[name="popipaddress"]'),
            usernameContainer = $('#username'),
            realmContainer = $('#realm'),
            selectNetButton = $('input[name="selectnet"]'),
            closeWindowButton = $('input[name="closewindow"]'),
            cabinetDropdown = $('select[name="cabinets"]'),
            findNetField = $('#findnet'),
            findNetButton = $('#findnetbtn'),
            findNetMsg = $('#findnetmsg'),
            findCabinetField = $('#findcabinet'),
            findCabinetButton = $('#findcabinetbtn'),
            findCabinetMsg = $('#findcabinetmsg');
        $(document).ready(function () {
            netLocationDropdown.on('change', function (event) {
                var _this = $(this),
                    username = $('#username').text().trim(),
                    realm = $('#realm').text().trim(),
                    location = _this.val(),
                    cabinetId = '',
                    url = '<?php echo base_url("main/showAssignNetFormOld"); ?>';
                username = username == '' ? '-' : username;
                realm = realm == '' ? '-' : realm;
                location = location == '' ? '-' : location;
                cabinet = location == '-' ? '-' : cabinetDropdown.find('option[location="' + location + '"]').first().text();
                url = url + '/' + username + '/' + realm + '/' + location.replace(/ /g, '~') + '/' + cabinet.replace(/ /g, '~') + '/-/-/-';
                top.location.href = url;
            });
            selectNetButton.on('click', function (event) {
                var ip = netAddressDropdown.val();
                if (ip.trim() != '') {
                    window.opener.document.globeacct.netaddress.value = ip;
                }
                window.close();
            });
            closeWindowButton.on('click', function (event) {
                window.close();
            });
            cabinetDropdown.on('change', function (event) {
                var _this = $(this),
                    location = _this.find('option:selected').attr('location');
                netLocationDropdown.val(location).trigger('change');
            });
            findNetField.on('keypress', function (event) {
                if (event.which == 13) {
                    findNetButton.trigger('click');
                }
            });
            findNetButton.on('click', function (event) {
                var _this = $(this),
                    netToFind = findNetField.val().trim(),
                    parts = null,
                    username = usernameContainer.text().trim(),
                    realm = realmContainer.text().trim(),
                    url = '<?php echo base_url("main/showAssignNetFormOld"); ?>';
                username = username == '' ? '-' : username;
                realm = realm == '' ? '-' : realm;
                if (netToFind == '') {
                    findNetMsg.empty().append('Enter a net address to find.');
                    findNetField.focus();
                    return false;
                }
                if (netToFind.indexOf('/') == -1) {
                    findNetMsg.empty().append('Incorrect net address format.');
                    findNetField.focus();
                    return false;
                } else {
                    parts = netToFind.split('/');
                    if (parts.length != 2) {
                        findnetmsg.empty().append('Needs an address and a subnet.');
                        findNetField.focus();
                        return false;
                    }
                }
                _this.prop('disabled', 'disabled');
                findNetField.prop('disabled', 'disabled');
                findCabinetButton.prop('disabled', 'disabled');
                findCabinetField.prop('disabled', 'disabled');
                selectNetButton.prop('disabled', 'disabled');
                findNetMsg.empty().append('Finding net address...');
                $.ajax({
                    url: '<?php echo base_url("main/findNetAddressToAssign"); ?>',
                    type: 'post', 
                    data: {
                        'ip': parts[0],
                        'subnet': parts[1]
                    },
                    success: function (resp) {
                        var response = JSON.parse(resp);
                        if (parseInt(response.found) == 0) {
                            _this.prop('disabled', '');
                            findNetField.prop('disabled', '');
                            findCabinetButton.prop('disabled', '');
                            findCabinetField.prop('disabled', '');
                            selectNetButton.prop('disabled', '');
                            findNetMsg.empty().append(netToFind + ' not found.');
                            findCabinetField.val('');
                            findCabinetMsg.empty();
                            netLocationDropdown.val('');
                            cabinetDropdown.val('');
                            netAddressDropdown.empty();
                        } else {
                            if (response.used == 'Y') {
                                _this.prop('disabled', '');
                                findNetField.prop('disabled', '');
                                findCabinetButton.prop('disabled', '');
                                findCabinetField.prop('disabled', '');
                                selectNetButton.prop('disabled', '');
                                findNetMsg.empty().append(netToFind + ' is already used.');
                                findCabinetField.val('');
                                findCabinetMsg.empty();
                                netLocationDropdown.val('');
                                cabinetDropdown.val('');
                                netAddressDropdown.empty();
                            } else {
                                url = url + '/' + username + '/' + realm + '/' + 
                                    response.location.replace(/ /g, '~') + '/' + response.cabinetForDropdown + '/' + response.ip + '/' + response.subnet + '/-';
                                top.location.href = url;
                            }
                        }
                    },
                    error: function (resp) {
                        _this.prop('disabled', '');
                        findNetField.prop('disabled', '');
                        findCabinetButton.prop('disabled', '');
                        findCabinetField.prop('disabled', '');
                        selectNetButton.prop('disabled', '');
                        findNetMsg.empty().append('An error occurred. Please try again.');
                    }
                });
            });
            findCabinetField.on('keypress', function (event) {
                if (event.which == 13) {
                    findCabinetButton.trigger('click');
                }
            });
            findCabinetButton.on('click', function (event) {
                var _this = $(this),
                    cabinetToFind = findCabinetField.val().trim(),
                    username = usernameContainer.text().trim(),
                    realm = realmContainer.text().trim(),
                    gpon = '<?php echo isset($isgpon) && !is_null($isgpon) ? $isgpon : "N"; ?>',
                    url = '<?php echo base_url("main/showAssignNetFormOld"); ?>';
                username = username == '' ? '-' : username;
                realm = realm == '' ? '-' : realm;
                if (cabinetToFind == '') {
                    findCabinetMsg.empty().append('Enter a cabinet to find.');
                    findCabinetField.focus();
                    return false;
                }
                _this.prop('disabled', 'disabled');
                findCabinetField.prop('disabled', 'disabled');
                findNetButton.prop('disabled', 'disabled');
                findNetField.prop('disabled', 'disabled');
                selectNetButton.prop('disabled', 'disabled');
                findCabinetMsg.empty().append('Finding cabinet...');
                $.ajax({
                    url: '<?php echo base_url("main/findCabinetToAssign"); ?>',
                    type: 'post',
                    data: {
                        'cabinet': cabinetToFind
                    },
                    success: function (resp) {
                        var response = JSON.parse(resp);
                        if (parseInt(response.found) == 0) {
                            _this.prop('disabled', '');
                            findCabinetField.prop('disabled', '');
                            findNetButton.prop('disabled', '');
                            findNetField.prop('disabled', '');
                            selectNetButton.prop('disabled', '');
                            findCabinetMsg.empty().append(cabinetToFind + ' not found.');
                            findNetField.val('');
                            findNetMsg.empty();
                            netLocationDropdown.val('');
                            cabinetDropdown.val('');
                            netAddressDropdown.empty();
                        } else {
                            url = url + '/' + username + '/' + realm + '/' + response.location.replace(/ /g, '~') + '/' + response.cabinetForDropdown.replace(/ /g, '~') + '/-/-/' + cabinetToFind.replace(/ /g, '~');
                            top.location.href = url;
                        }
                    },
                    error: function (resp) {
                        _this.prop('disabled', '');
                        findCabinetField.prop('disabled', '');
                        findNetButton.prop('disabled', '');
                        findNetField.prop('disabled', '');
                        selectNetButton.prop('disabled', '');
                        findCabinetMsg.empty().append('An error occurred. Please try again.');
                    }
                });
            });
        });
    </script>
</body>
</html>