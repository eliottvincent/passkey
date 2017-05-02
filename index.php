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

	$header = new View(null, null,"partials/header.php");
	$header->content = "This is my fancy header section";
	$header->ip = function () {
		return $_SERVER["REMOTE_ADDR"];
	};

	$body = new View(null, null,"partials/body.php");
	$body->content = "This is my fancy body section";

	$footer = new View(null, null,"partials/footer.php");
	$footer->content = "This is my fancy footer section";

	$compositeView = new CompositeView;

	echo $compositeView->attachView($header)
		->attachView($body)
		->attachView($footer)
		->render();
});

$klein->dispatch();
