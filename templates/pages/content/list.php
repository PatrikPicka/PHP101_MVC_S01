<?php use Core\Router;

$this->start('css'); ?>
<link rel="stylesheet" href="<?=PROOT?>assets/css/content.css">
<?php $this->end(); ?>

<?php $this->start('body'); ?>
<div class="menu-spacer"></div>
<div class="container mt-5">
	<div class="category-title">
		<h1 class="text-center"><?=$category->name?></h1>
	</div>
	<hr>
	<div class="category-description">
		<p>
			<?=$category->description?>
		</p>
	</div>
	<div class="row mt-5">
		<?php foreach ($contents as $video): ?>
			<div class="card col-sm-6 col-md-4 col-xl-3">
				<div class="card-body">
					<h5 class="card-title"><?=$video->title?></h5>
					<p class="card-text"><?=$video->description?></p>
					<a href="<?=Router::getLink(data: ['controller' => 'content', 'action' => 'detail', 'params' => [$video->getId()]])?>" class="card-link">Play</a>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>
<?php $this->end(); ?>
