<?php


class implementationBorrowingService_Dummy implements interfaceBorrowingService {

	//================================================================================
	// properties
	//================================================================================

	/**
	 * @var Singleton
	 * @access private
	 * @static
	 */
	private static $_instance = null;

	private $_borrowingDAO;
	private $_borrowings = array();
	private $_sessionBorrowings = null;
	private $_xmlBorrowings;


	//================================================================================
	// constructor and initialization
	//================================================================================

	/**
	 * Constructeur de la classe
	 *
	 * @param void
	 * @return void
	 */
	private function __construct() {
		// instantiating the DAOs we need
		$this->_borrowingDAO = implementationBorrowingDAO_Dummy::getInstance();

		// instantiating the services we need
		$this->_keychainService = implementationKeychainService_Dummy::getInstance();
		$this->_keyService = implementationKeyService_Dummy::getInstance();
		$this->_doorService = implementationDoorService_Dummy::getInstance();
		$this->_lockService = implementationLockService_Dummy::getInstance();
		$this->_roomService = implementationRoomService_Dummy::getInstance();



		// getting the data we need
		$this->_xmlBorrowings = $this->_borrowingDAO->getBorrowings();

		if (isset($_SESSION["BORROWINGS"])) {
			$this->_sessionBorrowings = $_SESSION["BORROWINGS"];
		}

		// if we got borrowings in session
		if ($this->_sessionBorrowings !== null) {

			$this->_borrowings = $this->_sessionBorrowings;
		}

		// else that means there are no borrowings in session (first use)
		else {

			$_SESSION["BORROWINGS"] = $this->_xmlBorrowings;
			$this->_borrowings = $this->_xmlBorrowings;
			$this->_sessionBorrowings = $this->_xmlBorrowings;
		}
	}

	/**
	 * Méthode qui crée l'unique instance de la classe
	 * si elle n'existe pas encore puis la retourne.
	 *
	 * @param void
	 * @return implementationBorrowingService_Dummy|Singleton
	 */
	public static function getInstance() {

		if(is_null(self::$_instance)) {
			self::$_instance = new implementationBorrowingService_Dummy();
		}

		return self::$_instance;
	}


	//================================================================================
	// Getters
	//================================================================================

	public function getBorrowings() {

		return $this->_borrowings;
	}

	public function getBorrowing($id) {

		foreach ($this->_borrowings as $borrowing) {
			if ($borrowing->getId() == (string) $id) {
				return $borrowing;
			}
		}
	}


	//================================================================================
	// CREATE
	//================================================================================

	public function saveBorrowing($borrowingArray) {

		$tDate = new DateTime;
		$tDate->setTimestamp(time());

		$borrowingToSave = new BorrowingVO();
		$borrowingToSave->setId((string) $borrowingArray['borrowing_id']);
		$borrowingToSave->setBorrowDate((string) $tDate->format('d/m/Y'));
		$borrowingToSave->setDueDate((string) $tDate->modify('+ 20 day')->format('d/m/Y'));
		$borrowingToSave->setReturnDate(null);
		$borrowingToSave->setLostDate(null);
		$borrowingToSave->setKeychain((string) $borrowingArray['borrowing_keychain']);
		$borrowingToSave->setUser((string) $borrowingArray['borrowing_user']);
		$borrowingToSave->setStatus('borrowed');

		array_push($_SESSION['BORROWINGS'],$borrowingToSave);
		array_push($this->_borrowings ,$borrowingToSave);
		array_push($this->_sessionBorrowings ,$borrowingToSave);
	}


	//================================================================================
	// DELETE
	//================================================================================

	public function deleteBorrowing($id) {

		$this->updateServiceVariables();

		foreach ($this->_borrowings as $key=>$borrowing) {

			if ($borrowing->getId() == (string) $id) {

				unset($_SESSION["BORROWINGS"][$key]);
				unset($this->_sessionBorrowings[$key]);
				unset($this->_borrowings[$key]);

				return true;
			}
		}

		return false;
	}

	//================================================================================
	// EXTEND
	//================================================================================

	public function extendBorrowing($id, $number) {

		$this->updateServiceVariables();
		$newBorrow = $this->getBorrowing($id);
		$newDate = new DateTime($newBorrow->getDueDate());
		$modifiedDate = $newDate->modify('+ ' . $number . ' days');
		$modifiedDateString = $modifiedDate->format('Y-m-d');
		$newBorrow->setDueDate($modifiedDateString);

		foreach ($this->_borrowings as $key=>$borrowing) {

			if ($borrowing->getId() == $newBorrow->getId()) {

				$_SESSION["BORROWINGS"][$key] = $newBorrow;
				$this->_sessionBorrowings[$key] = $newBorrow;
				$this->_borrowings[$key] = $newBorrow;

				return true;
			}
		}
		return false;
	}


	//================================================================================
	// UPDATE
	//================================================================================

	public function updateBorrowing($borrowingArray) {

		$borrowingToUpdate = new BorrowingVO();
		$borrowingToUpdate->setId((string) $borrowingArray['borrowing_id']);
		$borrowingToUpdate->setBorrowDate((string) $borrowingArray['borrowing_borrowdate']);
		$borrowingToUpdate->setDueDate((string) $borrowingArray['borrowing_duedate']);
		$borrowingToUpdate->setReturnDate((string) $borrowingArray['borrowing_returndate']);
		$borrowingToUpdate->setLostDate((string) $borrowingArray['borrowing_lostdate']);
		$borrowingToUpdate->setKeychain((string) $borrowingArray['borrowing_keychain']);
		$borrowingToUpdate->setUser((string) $borrowingArray['borrowing_user']);
		$borrowingToUpdate->setStatus((string) $borrowingArray['borrowing_status']);

		foreach ($this->_borrowings as $key=>$borrowing) {

			if ($borrowing->getId() == $borrowingToUpdate->getId()) {

				$_SESSION["BORROWINGS"][$key] = $borrowingToUpdate;
				$this->_sessionBorrowings[$key] = $borrowingToUpdate;
				$this->_borrowings[$key] = $borrowingToUpdate;

				return true;
			}
		}
		return false;
	}


	//================================================================================
	// OTHER
	//================================================================================

	public function checkUnicity($id) {

		if ($this->_borrowings) {

			foreach ($this->_borrowings as $borrowing) {

				if ($borrowing->getId() == (string) $id) {

					return true;
				}
			}
		}

		return false;
	}


	private function updateServiceVariables() {

		if (isset($_SESSION["BORROWINGS"])) {
			$this->_sessionBorrowings = $_SESSION["BORROWINGS"];
			$this->_borrowings= $_SESSION["BORROWINGS"];
		}

	}

	public function getStatuses() {

		return array(
			"doesnotexist"=>"n\'existe pas",
			"borrowed"=>"en cours",
			"late"=>"en retard",
			"returned"=>"rendu",
			"lost"=>"perdu",
		);
	}

	/**
	 * Get the name of all keys from a borrow
	 * @param $id
	 * @return array
	 */
	public function getKeysInBorrow($id) {
		$keys = array();
		$borrow = $this->getBorrowing($id);
		$kc_id = $borrow->getKeychain();
		$keychain = $this->_keychainService->getKeychain($kc_id);
		$kc_keys = $keychain->getKeys();

		foreach($kc_keys as $kc_key) {
			$key = $this->_keyService->getKey($kc_key)->getName();
			if (!in_array($key, $keys)) {
				array_push($keys, $key);
			}
		}

		return $keys;
	}

	/**
	 * Get the name of all rooms from a borrow
	 * @param $id
	 * @return array
	 */
	public function getOpenedRooms($id) {
		// Rooms.
		$rooms = array();
		$borrow = $this->getBorrowing($id);
		$kc_id = $borrow->getKeychain();
		$keychain = $this->_keychainService->getKeychain($kc_id);
		$keys = $keychain->getKeys();

		foreach ($keys as $key) {
			$k = $this->_keyService->getKey($key);

			if ($k != null) {

				$locks = $k->getLocks();

				if (is_array($locks)) {
					$tmp_locks = array();
					foreach ($locks as $lock) {
						if (is_object($lock)) {
							$lock_id = $lock->getId();
						} else {
							$lock_id = $lock;
						}

						array_push($tmp_locks, $lock_id);
					}

					$locks = $tmp_locks;
				}

				foreach ($locks as $lock) {
					$lock = $this->_lockService->getLock($lock);
					$door_id = $lock->getDoor();
					$door = $this->_doorService->getDoor($door_id);
					$room_id = $door->getRoom();
					$room = $this->_roomService->getRoom($room_id)->getName();

					if (!in_array($room, $rooms)) {
						array_push($rooms, $room);
					}
				}
			}


		}
		return $rooms;
	}


	public function returnKeychain($borrowingId,$comment)
	{
		$this->_cancelBorrowing($borrowingId,"return",$comment);
	}

	public function lostKeychain($borrowingId,$comment)
	{
		$this->_cancelBorrowing($borrowingId,"lost",$comment);
	}
	public function getLateBorrowings() {

		$lateBorrowings = array();
		$borrowings = $this->getBorrowings();

		foreach ($borrowings as $borrowing) {

			if ($borrowing->getStatus() == "en retard") {
				array_push($lateBorrowings, $borrowing);
			}
		}

		return $lateBorrowings;
	}
}

