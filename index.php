<?php

use Couchbase\DocumentNotFoundException;

session_start();

const DS = DIRECTORY_SEPARATOR;
define('ROOT', dirname(__FILE__));

require_once ROOT . DS . 'config' . DS . 'app.php';

use Core\Router;

/**
 * @param string $namespace
 * @return void
 * @throws DocumentNotFoundException
 */
function loader(string $namespace): void
{
	$parts = explode('\\', $namespace);
	$className = end($parts);
	array_pop($parts);

	$classFolder = strtolower(implode(DS, $parts));

	$path = ROOT . DS . $classFolder . DS . $className . '.php';
	if(file_exists($path)) {
		require $path;
	} else {
		DEBUG ? die('This class does not exists: ' . $path) : throw new DocumentNotFoundException(message: 'Cesta k souboru neexistuje.');
	}
}

spl_autoload_register(callback: 'loader');

$params = isset($_SERVER['PATH_INFO']) ? explode('/', ltrim($_SERVER['PATH_INFO'], '/')) : [];
Router::route(params: $params);