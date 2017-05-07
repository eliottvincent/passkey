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

<<<<<<<

=======
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
	$header = new View(null, null,"partials/header.html.twig");
	$header->content = "This is my fancy header section";
	$header->ip = function () {
		return $_SERVER["REMOTE_ADDR"];
	};

	// create the sidebar as a View
	$sidebar = new View($controller, $model,"partials/sidebar.html.twig");
	$sidebar->content = "This is my fancy sidebar section";

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
		->attachView($sidebar)
		->attachView($body)
		->attachView($footer);

	return $compositeView;
}

>>>>>>>
$klein->dispatch();
