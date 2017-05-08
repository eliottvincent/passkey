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
			//echo $this->createBlankPage($controller, $model)->render();
			$this->createBlankPage($controller, $model);
		}

		// handling requests on http://passkey.enssat/something_else
		else {
			echo 'I received a ' . (($_SERVER['REQUEST_METHOD'] === 'GET') ? 'GET' : 'POST') . ' request on "/' . $_REQUEST['url'] . '", what should I do?';
			$this->{$_REQUEST['url']}();
		}
	}

	function showLoginPageTest() {

		echo $this->createLoginPage()->render();
	}

	function login() {

		$authentificationController = new AuthentificationController();
		$authentificationController->login();
	}

	function logout() {
		$authentificationController = new AuthentificationController();
		$authentificationController->logout();
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

		// authentication check
		$authentificationController = new AuthentificationController();
		$authentificationController->check();

		$templates[] = array("name" => "head.html.twig", 'variables' => array('title' => 'Accueil'));
		$templates[] = array("name" => "header.html.twig", 'variables' => array('session' => $_SESSION));
		$templates[] = array("name" => "sidebar.html.twig");
		$templates[] = array("name" => "content.html.twig");
		$templates[] = array("name" => "quicksidebar.html.twig");
		$templates[] = array("name" => "footer.html.twig");
		$templates[] = array("name" => "quicknav.html.twig");
		$templates[] = array("name" => "foot.html.twig");

		$compositeView = new CompositeView;
		$compositeView->displayView($templates);

		/*
		$head = new View(null, null, "partials/head.html.twig");
		$header = new View(null, null,"partials/header.html.twig", array('session' => $_SESSION));
		$sidebar = new View($controller, $model,"partials/sidebar.html.twig");
		$content = new View(null, null, "partials/content.html.twig");
		$quicksidebar = new View(null, null, "partials/quicksidebar.html.twig");
		$footer = new View(null, null, "partials/footer.html.twig");
		$quicknav = new View(null, null, "partials/quicknav.html.twig");
		$foot = new View(null, null,"partials/foot.html.twig");


		// creating our final view
		$compositeView = new CompositeView;

		// adding partials to the final view

		$compositeView->attachView($head)
			->attachView($header)
			->attachView($sidebar)
			->attachView($content)
			->attachView($quicksidebar)
			->attachView($footer)
			->attachView($quicknav)
			->attachView($foot);

		return $compositeView;
		*/
	}

	function createLoginPage() {
		$html = new View(null, null,"partials/page_user_login_1.php");

		return $html;
	}
}
