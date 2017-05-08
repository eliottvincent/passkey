<?php
/**
 * Created by PhpStorm.
 * User: eliottvincent
 * Date: 30/04/2017
 * Time: 16:33
 */

require_once 'core/Autoloader.php';
Autoloader::register();

$klein = new \Klein\Klein();

$klein->respond(function () {

	// creating a new Model, RouterController
	$model = new Model();
	$controller = new RouterController($model);
	$controller->dispatchRoute();
});

$klein->dispatch();
