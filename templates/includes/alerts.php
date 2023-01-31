<?php

use Core\Session;

$displayAlert = Session::exists(ALERT_NAME);
?>

<?php if ($displayAlert): ?>
	<div class="alert alert-<?= Session::get(ALERT_NAME)['type']; ?>" role="alert">
		<?= Session::flash(ALERT_NAME)['message']; ?>
	</div>
<?php endif; ?>

<script>
	<?php if ($displayAlert): ?>
  $(document).ready(() => {
	  $('.alert').show();

	  setTimeout(() => {
		  $('.alert').hide();
	  }, 5000);
  });
	<?php endif; ?>
</script>