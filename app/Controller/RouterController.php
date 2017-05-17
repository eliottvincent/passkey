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
		$compositeView = new CompositeView();

		$headView 	= new View(null, null, "head.html.twig", array('title' => "Login"));
		$bodyView 	= new View(null, null, "login_body.html.twig");
		$footView 	= new View(null, null, "foot.html.twig");

		$compositeView->attachView($headView)
			->attachView($bodyView)
			->attachView($footView);

		echo $compositeView->render();
	}

	function login() {
		$authentificationController = new AuthentificationController();
		$authentificationController->login();
	}

	function logout() {
		$authentificationController = new AuthentificationController();
		$authentificationController->logout();
	}

	/**
	 * DOORS
	 */

	function createDoor() {
		// authentication check
		$authentificationController = new AuthentificationController();
		$authentificationController->check();

		$door = new DoorController();
		$door->create();
	}

	function listDoors() {
		// authentication check
		$authentificationController = new AuthentificationController();
		$authentificationController->check();

		$door = new DoorController();
		$door->list();
	}

	/**
	 * LOCKS
	 */

	function createLock() {
		// authentication check
		$authentificationController = new AuthentificationController();
		$authentificationController->check();

		$lock = new LockController();
		$lock->create();
	}

	function listLocks() {
		// authentication check
		$authentificationController = new AuthentificationController();
		$authentificationController->check();

		$lock = new LockController();
		$lock->list();
	}

	function updateLock() {
		// authentication check
		$authentificationController = new AuthentificationController();
		$authentificationController->check();

		$lock = new LockController();
		$lock->update();
	}

	/**
	 * KEYS
	 */

	function createKey() {
		// authentication check
		$authentificationController = new AuthentificationController();
		$authentificationController->check();
		$key = new KeyController();
		$key->create();
	}

	function updateKey() {
		// authentication check
		$authentificationController = new AuthentificationController();
		$authentificationController->check();
		$key = new KeyController();
		$key->update();
	}

	function listKeys() {
		// authentication check
		$authentificationController = new AuthentificationController();
		$authentificationController->check();

		$key = new KeyController();
		$key->list();

	}

	function listBorrowings() {
		// authentication check
		$authentificationController = new AuthentificationController();
		$authentificationController->check();

		$borrows = new BorrowingsController();
		$borrows->list();
	}

	function createBorrowing() {
		// authentication check
		$authentificationController = new AuthentificationController();
		$authentificationController->check();

		$borrows = new BorrowingsController();
		$borrows->create();
	}

	function deleteKeyAjax() {
		// no need of authentification
		$keyController = new KeyController();
		$keyController->deleteKeyAjax();
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

		// creating a default CompositeView
		$compositeView = new CompositeView(true);

		// creating our content, as a View object
		$blankContent = new View(null, null, 'default_content.html.twig');

		// adding the content to our CompositeView
		// here we use attachContentView() rather than attachView()...
		// because the content view always needs to be between content_start and content_end
		$compositeView->attachContentView($blankContent);

		echo $compositeView->render();
	}

	function createLoginPage() {
		$html = new View(null, null,'partials/page_user_login_1.php');

		return $html;
	}
}
