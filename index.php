<?php

use superGlobal\Get;

const BASE_PATH = __DIR__ . DIRECTORY_SEPARATOR;

spl_autoload_register(static function ($className) {
	include BASE_PATH . $className . '.php';
});

$path = BASE_PATH . 'view/' . preg_replace("/[^0-9A-Za-z\\\\_]/", "", str_replace('.php', '', Get::getNullableString('path'))) . '.php';

echo '<script src="js/jquery-1.11.3.min.js"></script>';

if (file_exists($path)) {
	include_once $path;
}
