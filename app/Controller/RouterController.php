<?php

/**
 * Created by PhpStorm.
 * User: eliottvincent
 * Date: 04/05/2017
 * Time: 15:07
 */


class RouterController
{

	function dispatchRoute() {

		// handling requests on http://passkey.enssat/?action=some_action
		if (isset($_GET['action']) && !empty($_GET['action'])) {

			// nice trick to call $controller->name_of_action(), or any other action, programmatically
			// TODO : check if the function does exist and is defined
			$this->{$_GET['action']}();
		}

		// handling requests on http://passkey.enssat/
		else if (isset($_REQUEST['url']) && $_REQUEST['url'] === '') {
			//echo $this->createBlankPage($controller, $model)->render();
			$this->createBlankPage();
		}

		// handling requests on http://passkey.enssat/something_else
		else {
			echo 'I received a ' . (($_SERVER['REQUEST_METHOD'] === 'GET') ? 'GET' : 'POST') . ' request on "/' . $_REQUEST['url'] . '", what should I do?';
			$this->{$_REQUEST['url']}();
		}
	}

	function showLoginPageTest() {
		$compositeView = new CompositeView();

		$headView 	= new View("head.html.twig", array('title' => "Login"));
		$bodyView 	= new View("login_body.html.twig");
		$footView 	= new View("foot.html.twig");

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


	//================================================================================
	// DOORS
	//================================================================================
	function listDoors() {
		// authentication check
		$authentificationController = new AuthentificationController();
		$authentificationController->check();

		$doorController = new DoorController();
		$doorController->list();
	}

	function createDoor() {
		$authentificationController = new AuthentificationController();
		$authentificationController->check();

		$doorController = new DoorController();
		$doorController->create();
	}

	function updateDoor() {
		// authentication check
		$authentificationController = new AuthentificationController();
		$authentificationController->check();

		$doorController = new DoorController();
		$doorController->update();
	}

	function deleteDoorAjax() {
		$doorController = new DoorController();
		$doorController->deleteDoorAjax();
	}


	//================================================================================
	// LOCKS
	//================================================================================

	function listLocks() {
		// authentication check
		$authentificationController = new AuthentificationController();
		$authentificationController->check();

		$lockController = new LockController();
		$lockController->list();
	}

	function createLock() {
		// authentication check
		$authentificationController = new AuthentificationController();
		$authentificationController->check();

		$lockController = new LockController();
		$lockController->create();
	}

	function updateLock() {
		// authentication check
		$authentificationController = new AuthentificationController();
		$authentificationController->check();

		$lockController = new LockController();
		$lockController->update();
	}

	function deleteLockAjax() {
		$lockController = new LockController();
		$lockController->deleteLockAjax();
	}



	//================================================================================
	// KEYS
	//================================================================================

	function listKeys() {
		// authentication check
		$authentificationController = new AuthentificationController();
		$authentificationController->check();

		$keyController = new KeyController();
		$keyController->list();

	}

	function createKey() {
		// authentication check
		$authentificationController = new AuthentificationController();
		$authentificationController->check();
		$keyController = new KeyController();
		$keyController->create();
	}

	function updateKey() {
		// authentication check
		$authentificationController = new AuthentificationController();
		$authentificationController->check();
		$keyController = new KeyController();
		$keyController->update();
	}

	function deleteKeyAjax() {
		$keyController = new KeyController();
		$keyController->deleteKeyAjax();
	}


	//================================================================================
	// USERS
	//================================================================================

	function listUsers() {
		$authentificationController = new AuthentificationController();
		$authentificationController->check();

		$userController = new UserController();
		$userController->list();
	}

	function createUser() {
		$authentificationController = new AuthentificationController();
		$authentificationController->check();
		$userController = new UserController();
		$userController->create();
	}

	function updateUser()
	{
		$authentificationController = new AuthentificationController();
		$authentificationController->check();
		$userController = new UserController();
		$userController->update();
	}

	function deleteUserAjax() {
		$userController = new UserController();
		$userController->deleteUserAjax();
	}


	//================================================================================
	// BORROWINGS
	//================================================================================

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


	/**
	 * Creates a blank page as a CompositeView
	 *
	 * @param $controller
	 * @param $model
	 * @return CompositeView
	 */
	function createBlankPage() {

		// authentication check
		$authentificationController = new AuthentificationController();
		$authentificationController->check();

		// creating a default CompositeView
		$compositeView = new CompositeView(true);

		// creating our content, as a View object
		$blankContent = new View('default_content.html.twig');

		// adding the content to our CompositeView
		// here we use attachContentView() rather than attachView()...
		// because the content view always needs to be between content_start and content_end
		$compositeView->attachContentView($blankContent);

		echo $compositeView->render();
	}

	function createLoginPage() {
		$html = new View('partials/page_user_login_1.php');

		return $html;
	}
}
