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
}
