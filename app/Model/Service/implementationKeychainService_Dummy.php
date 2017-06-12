<?php


class implementationKeychainService_Dummy implements interfaceKeychainService
{

	//================================================================================
	// properties
	//================================================================================

	/**
	 * @var null
	 */
	private static $_instance = null;

	private $_keychainDAO;
	private $_keychains = array();
	private $_sessionKeychains = null;
	private $_xmlKeychains;



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
		$this->_keychainDAO = implementationKeychainDAO_Dummy::getInstance();

		// getting the data we need
		$this->_xmlKeychains = $this->_keychainDAO->getKeychains();
		if (isset($_SESSION["KEYCHAINS"])) {
			$this->_sessionKeychains= $_SESSION["KEYCHAINS"];
		}

		// if we got keychains in session
		if ($this->_sessionKeychains !== null) {

			$this->_keychains = $this->_sessionKeychains;
		}

		// else that means there are no keychains in session (first use)
		else {

			$_SESSION["KEYCHAINS"] = $this->_xmlKeychains;
			$this->_keychains = $this->_xmlKeychains;
			$this->_sessionKeychains = $this->_xmlKeychains;
		}
	}


	/**
	 * Méthode qui crée l'unique instance de la classe
	 * si elle n'existe pas encore puis la retourne.
	 *
	 * @param void
	 * @return implementationKeychainService_Dummy
	 */
	public static function getInstance() {

		if(is_null(self::$_instance)) {
			self::$_instance = new implementationKeychainService_Dummy();
		}

		return self::$_instance;
	}


	//================================================================================
	// Getters
	//================================================================================

	public function getKeychains()
	{
		return $this->_keychains;
	}

	public function getKeychain($id)
	{
		foreach ($this->_keychains as $keychain) {
			if ($keychain->getId() == (string) $id) {
				return $keychain;
			}
		}
	}


	//================================================================================
	// CREATE
	//================================================================================

	public function saveKeychain($keychainArray)
	{
		$tDate = new DateTime;
		$tDate->setTimestamp(time());

		$keychainToSave = new KeychainVO();
		$keychainToSave->setId((string) $keychainArray['keychain_id']);
		$keychainToSave->setName((string) $keychainArray['keychain_name']);
		$keychainToSave->setCreationDate((string) $tDate->format('Y-m-d H:i:s'));
		$keychainToSave->setKeys((array) $keychainArray['keychain_keys']);

		array_push($_SESSION["KEYCHAINS"], $keychainToSave);
		array_push($this->_keychains, $keychainToSave);
		array_push($this->_sessionKeychains, $keychainToSave);

	}


	//================================================================================
	// DELETE
	//================================================================================

	public function deleteKeychain($id)
	{
		$this->updateServiceVariables();

		foreach ($this->_keychains as $key=>$keychain) {

			if ($keychain->getId() == (string) $id) {

				unset($_SESSION["KEYCHAINS"][$key]);
				unset($this->_sessionKeychains[$key]);
				unset($this->_keychains[$key]);

				return true;
			}
		}

		return false;
	}


	//================================================================================
	// UPDATE
	//================================================================================

	public function updateKeychain($keychainArray)
	{

		$keychainToUpdate = new KeychainVO();
		$keychainToUpdate->setId((string) $keychainArray['keychain_id']);
		$keychainToUpdate->setName((string) $keychainArray['keychain_name']);
		$keychainToUpdate->setCreationDate((string) $keychainArray['keychain_creation_date']);
		$keychainToUpdate->setDestructionDate((string) $keychainArray['keychain_destruction_date']);

		foreach ($this->_keychains as $key=>$keychain) {

			if ($keychain->getId() == $keychainToUpdate->getId()) {

				$_SESSION["KEYCHAINS"][$key] = $keychainToUpdate;
				$this->_sessionKeychains[$key] = $keychainToUpdate;
				$this->_keychains[$key] = $keychainToUpdate;

				return true;
			}
		}

		return false;
	}


	//================================================================================
	// OTHER
	//================================================================================

	public function checkUnicity($id)
	{
		if ($this->_keychains) {

			foreach ($this->_keychains as $keychain) {

				if ($keychain->getId() == (string) $id) {

					return true;
				}
			}
		}

		return false;
	}

	private function updateServiceVariables() {

		if (isset($_SESSION["KEYCHAINS"])) {
			$this->_sessionKeychains = $_SESSION["KEYCHAINS"];
			$this->_keychains = $_SESSION["KEYCHAINS"];
		}

	}
}

?>
