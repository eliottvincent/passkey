<?php

/**
 * Created by PhpStorm.
 * User: eliottvincent
 * Date: 04/05/2017
 * Time: 15:07
 */
class RouterController extends Controller
{

	function dispatchRoute() {

		$model = new Model();
		$controller = new Controller($model);

		// handling requests on http://passkey.enssat/?action=some_action
		if (isset($_GET['action']) && !empty($_GET['action'])) {

			// nice trick to call $controller->name_of_action(), or any other action, programmatically
			// TODO : check if the function does exist and is defined
			$this->{$_GET['action']}();
		}

		// handling requests on http://passkey.enssat/
		else if (isset($_REQUEST['url']) && $_REQUEST['url'] === '') {
			echo $this->createBlankPage($controller, $model)->render();
		}

		// handling requests on http://passkey.enssat/something_else
		else {
			echo 'I received a ' . (($_SERVER['REQUEST_METHOD'] === 'GET') ? 'GET' : 'POST') . ' request on "/' . $_REQUEST['url'] . '", what should I do?';
			$this->{$_REQUEST['url']}();
		}
	}

	function showLoginPageTest() {

		echo $this->getLoginPage()->render();
	}

	function login() {

		$loginController = new LoginController();
		$loginController->login();
	}

	function createDoor() {
		new CreateDoorController();
	}

	function createLock() {
		new CreateLockController();
	}

	function createKey() {
		new CreateKeyController();
	}

	function listKeys() {
		new ListKeysController();
	}

	/**
	 * Creates a blank page as a CompositeView
	 *
	 * @param $controller
	 * @param $model
	 * @return CompositeView
	 */
	function createBlankPage($controller, $model) {

		$head = new View(null, null, 'app/View/partials/head.php');

		// create the header as a View
		$header = new View(null, null,"app/View/partials/header.php");
		$header->content = "This is my fancy header section";
		$header->ip = function () {
			return $_SERVER["REMOTE_ADDR"];
		};

		// create the body as a View
		$body = new View($controller, $model,"app/View/partials/body.php");
		$body->content = "This is my fancy body section";

		// create the footer as a View
		$footer = new View(null, null,"app/View/partials/foot.php");
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

	function getLoginPage() {
		$html = new View(null, null,"app/Views/partials/page_user_login_1.php");

		return $html;
	}
}
