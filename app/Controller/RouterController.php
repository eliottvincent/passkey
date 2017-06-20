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
			$this->displayDashboard();
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
	// ROOMS
	//================================================================================

	function listRooms() {
		// authentication check
		$authenticationController = new AuthenticationController();
		$authenticationController->check();

		$roomController = new RoomController();
		$roomController->list();
	}

	function createRoom() {
		// authentication check
		$authenticationController = new AuthenticationController();
		$authenticationController->check();

		$roomController = new RoomController();
		$roomController->create();
	}

	function updateRoom() {
		// authentication check
		$authenticationController = new AuthenticationController();
		$authenticationController->check();

		$roomController = new RoomController();
		$roomController->update();
	}

	function deleteRoomAjax() {
		$roomController = new RoomController();
		$roomController->deleteRoomAjax();
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
	// KEYCHAINS
	//================================================================================

	function listKeychains() {
		// authentication check
		$authenticationController = new AuthenticationController();
		$authenticationController->check();

		$keychainController = new KeychainController();
		$keychainController->list();

	}

	function createKeychain() {
		// authentication check
		$authenticationController = new AuthenticationController();
		$authenticationController->check();

		$keychainController = new KeychainController();
		$keychainController->create();
	}

	function updateKeychain() {
		// authentication check
		$authenticationController = new AuthenticationController();
		$authenticationController->check();

		$keychainController = new KeychainController();
		$keychainController->update();
	}

	function deleteKeychainAjax() {

		$keychainController = new KeychainController();
		$keychainController->deleteKeychainAjax();
	}

	function duplicateKeychain() {

		$keychainController = new KeychainController();
		$keychainController->duplicateKeychain();
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

	function detailedBorrowing() {
		$authenticationController = new AuthenticationController();
		$authenticationController->check();

		$borrowingController = new BorrowingController();
		if (!empty($_GET['id'])) {
			$borrowingController->detailed($_GET['id']);
		} else {
			$this->createBlankPage();
		}

	}

	function deleteBorrowingAjax() {
		$borrowingController = new BorrowingController();
		$borrowingController->deleteBorrowingAjax();
	}

	function extendBorrowingAjax() {
		$borrowingController = new BorrowingController();
		$borrowingController->extendBorrowingAjax();
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
	// IMPORT FILES
	//================================================================================

	function import(){
		$authenticationController = new AuthenticationController();
		$authenticationController->check();

		$importController = new ImportController();
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

	function createLoginPage() {
		$html = new View('partials/page_user_login_1.php');

		return $html;
	}
}
