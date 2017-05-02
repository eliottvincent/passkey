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

	// creating a new Model, Controller and View
	$model = new Model();
	$controller = new Controller($model);
	$view = new View($controller, $model);

	// if the request contains the parameter 'action'
	if (isset($_GET['action']) && !empty($_GET['action'])) {

		// nice trick to call $controller->clicked(), or any other action, programmatically
		$controller->{$_GET['action']}();
	}

	echo $view->output();
});

$klein->dispatch();
