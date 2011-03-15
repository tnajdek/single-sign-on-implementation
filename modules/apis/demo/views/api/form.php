<?php echo Form::open(NULL, empty($uploads) ? NULL : array('enctype' => 'multipart/form-data')) ?>

<?php if ( ! empty($message)): ?><aside><?php echo $message ?></aside><?php endif ?>

<dl>
<?php foreach ($inputs as $label => $input): ?>
	<dt><?php echo $label ?></dt>
	<dd><?php echo $input ?></dd>
<?php endforeach ?>
	<dd class="submit"><?php echo Form::submit(NULL, 'Submit') ?></dd>
</dl>

<?php echo Form::close() ?>
