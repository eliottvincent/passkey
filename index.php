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

	// creating a new Model, Controller and View
	$model = new Model();
	$controller = new Controller($model);

	// if the request contains the parameter 'action'
	if (isset($_GET['action']) && !empty($_GET['action'])) {

		// nice trick to call $controller->clicked(), or any other action, programmatically
		$controller->{$_GET['action']}();
	}

	echo createBlankPage($controller, $model)->render();
});

/**
 * Creates a blank page as a CompositeView
 *
 * @param $controller
 * @param $model
 * @return CompositeView
 */
function createBlankPage($controller, $model) {

	$head = new View(null, null, 'partials/head.php');

	// create the header as a View
	$header = new View(null, null,"partials/header.php");
	$header->content = "This is my fancy header section";
	$header->ip = function () {
		return $_SERVER["REMOTE_ADDR"];
	};

	// create the body as a View
	$body = new View($controller, $model,"partials/body.php");
	$body->content = "This is my fancy body section";

	// create the footer as a View
	$footer = new View(null, null,"partials/foot.php");
	$footer->content = "This is my fancy footer section";

	// creating our final view
	$compositeView = new CompositeView;

	// adding partials to the final view
	$compositeView->attachView($head)
		->attachView($header)
		->attachView($body)
		->attachView($footer);

	return $compositeView;
}

$klein->dispatch();
