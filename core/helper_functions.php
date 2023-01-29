<?php

function dd(mixed $param): void
{
	echo "<pre>";
	var_dump($param);
	die();
}