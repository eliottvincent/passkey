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
			$this->_keychainService = implementationKeychainService_Dummy::getInstance();
			$this->_userService = implementationUserService_Dummy::getInstance();
		}

		public function displayDash() {

			$keys = $this->getKeys();
			$borrowings = $this->getBorrowings();
			$keyChains = $this->getKeychains();
			$users = $this->getUsers();
			$emails = array();

			$lateBorrowings = $this->_borrowingService->getLateBorrowings();

			foreach ($lateBorrowings as $lateBorrowing) {
				$u = $this->_userService->getUser($lateBorrowing->getUser());
				$u_adr = $u->getEmail();
				$subject = "Vous avez un emprunt en retard";
				$rooms = $this->_borrowingService->getOpenedRooms($lateBorrowing->getId());

				$body = "Bonjour " . $u->getSurname() . ",  %0D%0A %0D%0A";

				if (sizeof($rooms) == 1) {
					$body .= "L'emprunt en retard ouvre la salle : ";
				} else {
					$body .= "Le trousseau en retard ouvre les salles : ";
				}


				for ($i = 0; $i < sizeof($rooms); $i++) {
					$body .= $rooms[$i];
					if ($i != sizeof($rooms)-1) {
						$body .= ", ";
					} else {
						$body .= ".  %0D%0A";
					}
				}

				$keys = sizeof($this->_borrowingService->getKeysInBorrow($lateBorrowing->getId()));
				$body .= "Ce trousseau comporte " . $keys;

				if ($keys == 1) {
					$body .= " clé.  %0D%0A  %0D%0A";
				} else {
					$body .= " clés.  %0D%0A  %0D%0A";
				}

				$body .= "Merci de le rapporter au plus vite. %0D%0A";

				$email = "mailto:" . $u_adr . "?subject=" . $subject . "&body=".$body;
				array_push($emails, $email);
			}

			$composite = new CompositeView(
				true,
				"Tableau de bord",
				null,
				"dashboard",
				null,
				array("waypointsScript" => "app/View/assets/global/plugins/counterup/jquery.waypoints.min.js",
					"counterupScript" => "app/View/assets/global/plugins/counterup/jquery.counterup.min.js",
					"borrowingsScript" => "app/View/assets/custom/scripts/list_borrowings.js"
				)
			);

			$displayDash = new View('dashboard/dashboard.html.twig', array('keyCount' => count($keys),
																			'borrowingCount' => count($borrowings),
																			'keychainCount' => count($keyChains),
																			'userCount' => count($users),
																			'lateCount' => count($lateBorrowings))
			);
			$composite->attachContentView($displayDash);

			if (!empty($lateBorrowings)) {
				$list_borrowings = new View("borrowings/list_late_borrowings.html.twig", array('borrowings' => $lateBorrowings, 'emails' => $emails));
				$composite->attachContentView($list_borrowings);
			}

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
		public function getKeychains() {

			return $this->_keychainService->getKeychains();
		}

		/**
		 * To get all users
		 * @return array
		 */
		public function getUsers() {

			return $this->_userService->getUsers();
		}
	}
 ?>
