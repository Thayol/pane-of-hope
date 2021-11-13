<?php if (!empty($notice_error)): ?>
<div class="notice notice-error"><?php echo $notice_error; ?></div>
<?php endif; ?>

<?php if (!empty($notice_successful)): ?>
<div class="notice notice-success"><?php echo $notice_successful; ?></div>
<?php endif; ?>

<?php if (!empty($notice_neutral)): ?>
<div class="notice notice-neutral"><?php echo $notice_neutral; ?></div>
<?php endif; ?>