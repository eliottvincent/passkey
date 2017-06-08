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
		$authenticationController = new AuthenticationController();
		$authenticationController->login();
	}

	function logout() {
		$authenticationController = new AuthenticationController();
		$authenticationController->logout();
	}


	//================================================================================
	// DOORS
	//================================================================================
	function listDoors() {
		// authentication check
		$authenticationController = new AuthenticationController();
		$authenticationController->check();

		$doorController = new DoorController();
		$doorController->list();
	}

	function createDoor() {
		$authenticationController = new AuthenticationController();
		$authenticationController->check();

		$doorController = new DoorController();
		$doorController->create();
	}

	function updateDoor() {
		// authentication check
		$authenticationController = new AuthenticationController();
		$authenticationController->check();

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
		$authenticationController = new AuthenticationController();
		$authenticationController->check();

		$lockController = new LockController();
		$lockController->list();
	}

	function createLock() {
		// authentication check
		$authenticationController = new AuthenticationController();
		$authenticationController->check();

		$lockController = new LockController();
		$lockController->create();
	}

	function updateLock() {
		// authentication check
		$authenticationController = new AuthenticationController();
		$authenticationController->check();

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
		$authenticationController = new AuthenticationController();
		$authenticationController->check();

		$keyController = new KeyController();
		$keyController->list();

	}

	function createKey() {
		// authentication check
		$authenticationController = new AuthenticationController();
		$authenticationController->check();

		$keyController = new KeyController();
		$keyController->create();
	}

	function updateKey() {
		// authentication check
		$authenticationController = new AuthenticationController();
		$authenticationController->check();

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
		$authenticationController = new AuthenticationController();
		$authenticationController->check();

		$userController = new UserController();
		$userController->list();
	}

	function createUser() {
		$authenticationController = new AuthenticationController();
		$authenticationController->check();

		$userController = new UserController();
		$userController->create();
	}

	function updateUser() {
		$authenticationController = new AuthenticationController();
		$authenticationController->check();

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
		$authenticationController = new AuthenticationController();
		$authenticationController->check();

		$borrowingController = new BorrowingController();
		$borrowingController->list();
	}

	function createBorrowing() {
		// authentication check
		$authenticationController = new AuthenticationController();
		$authenticationController->check();

		$borrowingController = new BorrowingController();
		$borrowingController->create();
	}

	function updateBorrowing() {
		$authenticationController = new AuthenticationController();
		$authenticationController->check();

		$borrowingController = new BorrowingController();
		$borrowingController->update();
	}

	function deleteBorrowingAjax() {
		$borrowingController = new BorrowingController();
		$borrowingController->deleteBorrowingAjax();
	}

	//================================================================================
	// DASHBOARD
	//================================================================================

	//DashboardController
	function displayDashboard() {
		$authenticationController = new AuthenticationController();
		$authenticationController->check();


		$dashboard = new DashboardController();
		$dashboard->displayDash();
	}

	//================================================================================
	// PDF TEST
	//================================================================================

	function testpdf(){
		$pdfController = new PDFController();
		$pdfController->creationPDF();

	}


	//================================================================================
	// Suite router controller
	//================================================================================
	/**
	 * Creates a blank page as a CompositeView
	 *
	 * @param $controller
	 * @param $model
	 * @return CompositeView
	 */
	function createBlankPage() {

		// authentication check
		$authenticationController = new AuthenticationController();
		$authenticationController->check();

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
