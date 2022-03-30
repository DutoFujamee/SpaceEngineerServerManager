<?php

use parser\SandBoxParser;

spl_autoload_register(function ($className) {
	include $className . '.php';
});

$world = SandBoxParser::getWorldFromFilePath('C:\wamp64\www\SpaceEngineerTools\tmp\Sandbox.sbc');
