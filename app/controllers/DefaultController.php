<?php

namespace App\Controllers;

use App\Models\UserModel;

class DefaultController
{
	public function indexAction(?array $params = null)
	{
		$user = new UserModel();
		$user->username = 'PTB';
		$user->password = password_hash('password', PASSWORD_DEFAULT);
		$user->email = 'PTB@yahoo.com';

//		$user->email = 'test@mail.com';
//		$user->delete();
		$user->populate();
//		echo  $user->createdAt->format('Y-m-d');
//		var_dump($user->getDb()->select(['id'], ['id' => 8])->getFirstResult());
	}
}