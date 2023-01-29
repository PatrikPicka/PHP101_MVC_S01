<div class="form-group <?=$this->getFieldWrapperClasses()?>">
	<label for="<?=$this->getName();?>"><?=$this->getLabel();?></label>
	<input
		type="<?=$this->getType();?>"
		class="form-control <?=$this->getFieldClasses();?> <?php if ($this->isInvalid()): ?>invalid<?php endif; ?>"
		id="<?=$this->getName();?>"
		name="<?=$this->getName();?>"
		placeholder="<?=$this->getPlaceHolder();?>"
		value="<?=$this->getValue();?>"
	>
	<?php if ($this->isInvalid()): ?>
		<div class="invalid-feedback d-block">
			<p><?= $this->displayErrorMessage() ?></p>
		</div>
	<?php endif; ?>
</div>