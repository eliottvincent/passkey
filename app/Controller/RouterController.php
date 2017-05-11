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

		echo $this->createLoginPage()->oldRenderMethod();
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

		$head = new View(null, null,"head.html.twig", array('title' => 'Accueil'));
		$header = new View(null, null,"header.html.twig", array('session' => $_SESSION));
		$sidebar = new View(null, null,"sidebar.html.twig");
		$content = new View(null, null,"content.html.twig");
		$quicksidebar = new View(null, null,"quicksidebar.html.twig");
		$footer = new View(null, null,"footer.html.twig");
		$quicknav = new View(null, null,"quicknav.html.twig");
		$foot = new View(null, null,"foot.html.twig");

		$compositeView = new CompositeView;

		$compositeView->attachTemplate($head)
			->attachTemplate($header)
			->attachTemplate($sidebar)
			->attachTemplate($content)
			->attachTemplate($quicksidebar)
			->attachTemplate($footer)
			->attachTemplate($quicknav)
			->attachTemplate($foot);

		$compositeView->render();
	}

	function createLoginPage() {
		$html = new View(null, null,"partials/page_user_login_1.php");

		return $html;
	}
}
