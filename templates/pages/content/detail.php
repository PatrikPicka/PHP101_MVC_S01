<?php $this->start('css'); ?>
	<link rel="stylesheet" href="<?= PROOT ?>assets/css/content.css">
<?php $this->end(); ?>

<?php $this->start('body'); ?>
	<div class="menu-spacer"></div>
	<div class="container mt-5">
		<h1 class="text-center"><?= $content->title ?></h1>
		<hr>
		<p><?= $content->description ?></p>

		<div id="content-video-iframe"></div>
	</div>
<?php $this->end(); ?>

<?php $this->start('js'); ?>
	<script>
	  let updateUserLectureUrl = '<?=PROOT?>content/ajaxUpdates';
	  let userId = <?=$user->getId()?>;
	  let contentId = <?=$content->getId()?>;
	  let youtubeVideoIdentifier = '<?=$content->videoIdentifier?>';
	</script>

	<script src="<?= PROOT ?>assets/js/content.js"></script>
<?php $this->end(); ?>