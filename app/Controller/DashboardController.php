<?php

	class DashboardController
	{
		//================================================================================
		// constructor
		//================================================================================

		/**
		 * KeyController constructor.
		 */
		public function __construct() {
			$this->_keyService = implementationKeyService_Dummy::getInstance();
			$this->_borrowingService = implementationBorrowingService_Dummy::getInstance();
			//$this->_keychainService = implementationKeyChainService_Dummy::getInstance();
			$this->_userService = implementationUserService_Dummy::getInstance();
		}

		public function displayDash() {

			$keys = $this->getKeys();
			$borrowings = $this->getBorrowings();
			//$keyChains = $this->getKeyChains();
			$users = $this->getUsers();

			$composite = new CompositeView(
				true,
				"Dashboard",
				null,
				"dashboard",
				null,
				array("waypointsScript" => "app/View/assets/global/plugins/counterup/jquery.waypoints.min.js",
					"counterupScript" => "app/View/assets/global/plugins/counterup/jquery.counterup.min.js")
				);


			$displayDash = new View('dashboard/dashboard.html.twig', array('keyCount' => count($keys),
																			'borrowingCount' => count($borrowings),
																			/*'keychainCount' => count($keyChains),*/
																			'userCount' => count($users))
			);
			$composite->attachContentView($displayDash);

			echo $composite->render();
		}


		//================================================================================
		// calls to Service
		//================================================================================

		/**
		 * To get all keys.
		 * @return null
		 */
		public function getKeys() {

			return $this->_keyService->getKeys();
		}

		/**
		 * To get all borrowings
		 * @return array
		 */
		public function getBorrowings() {

			return $this->_borrowingService->getBorrowings();
		}


		/**
		 * To get all the keychains
		 * @return array
		 */
		 /*
		public function getKeychains() {

			return $this->_keychainService->getKeychains();
		}
		*/

		/**
		 * To get all users
		 * @return array
		 */
		public function getUsers() {

			return $this->_userService->getUsers();
		}
	}
 ?>
