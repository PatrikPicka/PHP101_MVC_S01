<?php $this->start('body'); ?>
<div class="wrapper d-flex align-items-center" style="height: 100vh;">
	<div class="container">
		<div class="card">
			<h5 class="card-header">Přihlášení</h5>
			<div class="card-body">
							<?php $form->render(); ?>
				<p>Nemáš učet?
					<a
						href="<?= \Core\Router::getLink(['controller' => 'user', 'action' => 'register']); ?>"
				  		class="btn btn-primary">Registruj se zde.</a>
				</p>
			</div>
		</div>
	</div>
</div>
<?php $this->end(); ?>
