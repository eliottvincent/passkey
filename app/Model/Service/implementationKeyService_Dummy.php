<?php

/**
 * Created by PhpStorm.
 * User: eliottvincent
 * Date: 04/06/2017
 * Time: 15:10
 */
class implementationKeyService_Dummy implements interfaceKeyService {


	//================================================================================
	// properties
	//================================================================================

	/**
	 * @var Singleton
	 * @access private
	 * @static
	 */
	private static $_instance = null;

	private $_keyDAO;
	private $_keys = array();
	private $_sessionKeys = null;
	private $_xmlKeys;


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
		$this->_keyDAO = implementationKeyDAO_Dummy::getInstance();

		// getting the data we need
		$this->_xmlKeys = $this->_keyDAO->getKeys();

		if (isset($_SESSION["KEYS"])) {
			$this->_sessionKeys = $_SESSION["KEYS"];
		}

		// if we got keys in session
		if ($this->_sessionKeys !== null) {

			$this->_keys = $this->_sessionKeys;
		}

		// else that means there are no keys in session (first use)
		else {

			$_SESSION["KEYS"] = $this->_xmlKeys;
			$this->_keys = $this->_xmlKeys;
			$this->_sessionKeys = $this->_xmlKeys;
		}
	}

	/**
	 * Méthode qui crée l'unique instance de la classe
	 * si elle n'existe pas encore puis la retourne.
	 *
	 * @param void
	 * @return implementationKeyService_Dummy
	 */
	public static function getInstance() {

		if(is_null(self::$_instance)) {
			self::$_instance = new implementationKeyService_Dummy();
		}

		return self::$_instance;
	}


	//================================================================================
	// Getters
	//================================================================================

	public function getKeys() {

		return $this->_keys;
	}

	public function getKey($id) {

		foreach ($this->_keys as $key) {
			if ($key->getId() == (string) $id) {
				return $key;
			}
		}
	}


	//================================================================================
	// CREATE
	//================================================================================

	public function saveKey($keyArray) {

		$keyToSave = new KeyVO();
		$keyToSave->setId((string) $keyArray['key_id']);
		$keyToSave->setName((string) $keyArray['key_name']);
		$keyToSave->setType((string) $keyArray['key_type']);
		$keyToSave->setLocks((array) $keyArray['key_locks']);
		$keyToSave->setCopies((int) $keyArray['key_copies']);
		$keyToSave->setSupplier((string) $keyArray['key_supplier']);

		array_push($_SESSION["KEYS"], $keyToSave);
		array_push($this->_keys, $keyToSave);
		array_push($this->_sessionKeys, $keyToSave);

	}


	//================================================================================
	// DELETE
	//================================================================================

	public function deleteKey($id) {

		$this->updateServiceVariables();

		foreach ($this->_keys as $key=>$currentKey) {

			if ($currentKey->getId() == (string) $id) {

				unset($_SESSION["KEYS"][$key]);
				unset($this->_sessionKeys[$key]);
				unset($this->_keys[$key]);

				return true;
			}
		}

		return false;
	}


	//================================================================================
	// UPDATE
	//================================================================================

	public function updateKey($keyArray) {

		$keyToUpdate = new KeyVO();
		$keyToUpdate->setId((string) $keyArray['key_id']);
		$keyToUpdate->setName((string) $keyArray['key_name']);
		$keyToUpdate->setType((string) $keyArray['key_type']);
		$keyToUpdate->setLocks((array) $keyArray['key_locks']);
		$keyToUpdate->setCopies((int) $keyArray['key_copies']);
		$keyToUpdate->setSupplier((string) $keyArray['key_supplier']);

		foreach ($this->_keys as $key=>$currentKey) {

			if ($currentKey->getId() == $keyToUpdate->getId()) {

				$_SESSION["KEYS"][$key] = $keyToUpdate;
				$this->_sessionKeys[$key] = $keyToUpdate;
				$this->_keys[$key] = $keyToUpdate;

				return true;
			}
		}
		return false;
	}



	//================================================================================
	// OTHER
	//================================================================================

	public function checkUnicity($id) {

		if ($this->_keys) {

			foreach ($this->_keys as $key) {

				if ($key->getId() == (string) $id) {

					return true;
				}
			}
		}

		return false;
	}


	private function updateServiceVariables() {

		if (isset($_SESSION["KEYS"])) {
			$this->_sessionKeys = $_SESSION["KEYS"];
			$this->_keys= $_SESSION["KEYS"];
		}

	}

	public function getKeychains($key) {

		$allKeychains = $this->_keychainService->getKeychains();
		$finalKeychains = array();

		foreach ($allKeychains as $keychain) {

			foreach ($keychain->getKeys() as $keyId) {

				if ($keyId == $key->getId()) {

					array_push($finalKeychains, $keychain);
				}
			}
		}

		return $finalKeychains;
	}

	public function getBorrowings($key) {

		$allBorrowings = $this->_borrowingService->getBorrowings();
		$finalBorrowings = array();

		$keyKeychains = $this->getKeychains($key);

		foreach ($allBorrowings as $borrowing) {

			foreach ($keyKeychains as $keychain) {

				if ($keychain->getId() == $borrowing->getKeychain()) {

					array_push($finalBorrowings, $borrowing);
				}
			}
		}

		return $finalBorrowings;
	}
}
