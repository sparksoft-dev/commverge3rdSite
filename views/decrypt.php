<!DOCTYPE html>
<html lang="en">
<head>
    <title>Globelines Universal Access</title>
    <?php require 'head_includes.php'; ?>
</head>
<body style="padding-top:20px; padding-left:20px;">
	<form action="<?php echo base_url('test/decryptpassword'); ?>" method="POST">
		Enter encrypted password:&nbsp;<input type="text" name="enc" />&nbsp;
		<input type="submit" name="view" value="view" />
	</form>
	<br />
	<span><?php echo isset($plaintext) && !is_null($plaintext) ? $plaintext : ''; ?></span>
</body>
</html>