<div class="form-group <?=$this->getFieldWrapperClasses()?>">
	<label for="<?=$this->getName();?>"><?=$this->getLabel();?></label>
	<select
		class="form-control <?=$this->getFieldClasses();?>"
		id="<?=$this->getName();?>"
		name="<?=$this->getName();?>"
	>
		<?php foreach ($this->choices as $choiceText => $choiceValue): ?>
			<option <?php if ($choiceValue === $this->getValue()): ?>selected<?php endif; ?> value="<?=$choiceValue?>"><?=$choiceText?></option>
		<?php endforeach; ?>
	</select>
</div>