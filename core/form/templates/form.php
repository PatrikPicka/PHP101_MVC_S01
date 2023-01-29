<form action="<?= $this->getAction() ?>" method="<?= $this->getMethod() ?>">
	<?php
	foreach ($this->fields as $field) {
		$field->render();
	}
	?>
</form>