<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>OpenID authorisation in progress...</title>
</head>
<body>
<form id="openid-submit" action="<?php echo($action); ?>" method="post">
<?php foreach($params as $k => $v): ?>
<input name="<?php echo($k); ?>" value="<?php echo($v); ?>" type="hidden" />
<?php endforeach; ?>
</form>
<script type="text/javascript">
document.getElementById('openid-submit').submit();
</script>
</body>
</html>