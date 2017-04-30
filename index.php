<?php
/**
 * Created by PhpStorm.
 * User: eliottvincent
 * Date: 30/04/2017
 * Time: 16:33
 */

require_once 'sources/Autoloader.php';
Autoloader::register();

$klein = new \Klein\Klein();

$klein->respond(function () {
	return 'Hello!';
});

$klein->dispatch();
