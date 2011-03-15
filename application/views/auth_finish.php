<?php include('components/header.php') ?>
<h2>whatever.ly</h2>
<?php echo($message); ?>
<?php if(isset($showform) && $showform): ?>
<?php include('components/openidform.php') ?>
<?php endif; ?>
<?php include('components/footer.php') ?>