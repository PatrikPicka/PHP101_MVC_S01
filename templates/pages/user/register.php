<?php $this->start('body'); ?>
<div class="wrapper d-flex align-items-center" style="height: 100vh;">
	<div class="container">
		<div class="card">
			<h5 class="card-header">Registrace</h5>
			<div class="card-body">
				<?php $form->render(); ?>
				<p>Máš již účet?
					<a
						href="<?= \Core\Router::getLink(['controller' => 'user', 'action' => 'login']); ?>"
				  		class="btn btn-primary">Přihlaš se zde.</a>
				</p>
			</div>
		</div>
	</div>
</div>
<?php $this->end(); ?>
