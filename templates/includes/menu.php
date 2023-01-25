<?php

use App\Models\User;
use Core\Helpers\AppHelpers;
use Core\Router;

$menu = Router::getMenu();
$currentPage = AppHelpers::getCurrentPage();
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top navbar-light bg-light">
	<a class="navbar-brand mr-4" href="<?=PROOT?>home">
		<img src="<?=PROOT?>public/img/logo_white_text.png" alt="<?=SITE_TITLE?>" width="150">
	</a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>

	<div class="collapse navbar-collapse" id="navbarSupportedContent">
		<ul class="navbar-nav mr-auto">
			<?php foreach ($menu as $name => $value):
  				$active = '';
			?>
				<?php if (is_array($value)): ?>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
							Dropdown
						</a>
						<div class="dropdown-menu">
							<?php foreach ($value as $subName => $subLink):
								$active = ($subLink === $currentPage) ? 'active' : '';
							?>
								<?php if ($subName === 'divider'): ?>
									<div class="dropdown-divider"></div>
								<?php else: ?>
									<a class="dropdown-item <?=$active?>" href="<?=$subLink?>"><?=$subName?></a>
								<?php endif; ?>
							<?php endforeach; ?>
						</div>
					</li>
				<?php else:
					$active = ($value === $currentPage) ? 'active' : '';
				?>
					<li class="nav-item <?=$active?>">
						<a class="nav-link" href="<?=$value?>"><?=$name?></a>
					</li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>

		<div class="user-actions">
			<?php if (!User::isUserLoggedIn()): ?>
				<div class="d-flex align-items-center justify-items-center my-2 my-lg-0">
					<a href="<?=PROOT?>user/login" class="mx-3">Login</a>
					<div class="divider-vertical"></div>
					<a href="<?=PROOT?>user/register" class="mx-3">Register</a>
				</div>
			<?php else:
				$user = new User(User::getLoggedInUserId());
				?>
					<div class="d-flex align-items-center justify-items-center my-2 my-lg-0">
						<p class="mx-3 my-0"><?=$user->username?></p>
						<div class="divider-vertical"></div>
						<a href="<?=PROOT?>user/logout" class="mx-3">Logout</a>
					</div>
			<?php endif; ?>
		</div>
	</div>
</nav>