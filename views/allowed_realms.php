<?php
if (!isset($hideDropdown)) {
?>
<select class="textstyle" size="1" name="realm" <?php echo isset($disableRealm) && !is_null($disableRealm) ? ($disableRealm == true ? 'disabled' : '') : ''; ?>>
    <?php
    if (isset($allowBlankInRealm) && !is_null($allowBlankInRealm) && $allowBlankInRealm) {
        echo '<option value="">- select realm -</option>';
    }
    for ($i = 0; $i < count($realms); $i++) {
        echo '<option value="'.$realms[$i].'" '.(isset($realm) && !is_null($realm) ? ($realms[$i] == $realm ? 'selected' : '') : '').'>'.$realms[$i].'</option>';
    }
    ?>
</select>
<?php
}
?>
<?php
if (isset($hideDropdown) && $hideDropdown) {
	echo '&nbsp;<span style="text-decoration:underline;">'.$realm.'</span>';
}
?>
<?php
if (isset($disableRealm) && !is_null($disableRealm) && $disableRealm) {
	echo '<input type="hidden" name="realm" value="'.$realm.'" />';
}
?>