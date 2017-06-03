<?php

/**
 * Created by PhpStorm.
 * User: eliottvincent
 * Date: 01/06/2017
 * Time: 13:59
 */
class implementationUserService_Dummy implements interfaceUserService {


	//================================================================================
	// properties
	//================================================================================

	/**
	 * @var Singleton
	 * @access private
	 * @static
	 */
	private static $_instance = null;

	private $_userDAO;
	private $_users = array();
	private $_sessionUsers = null;
	private $_xmlUsers = null;


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
		$this->_userDAO = implementationUserDAO_Dummy::getInstance();

		// getting data we need
		$this->_xmlUsers = $this->_userDAO->getUsers();
		if (isset($_SESSION["USERS"])) {
			$this->_sessionUsers = $_SESSION["USERS"];
		}

		// if we got users in session
		if ($this->_sessionUsers !== null) {

			$this->_users = $this->_sessionUsers;
		}
		// else that means there are no users in session (first use)
		else {

			$_SESSION["USERS"] = $this->_xmlUsers;
			$this->_users = $this->_xmlUsers;
			$this->_sessionUsers = $this->_xmlUsers;
		}
	}

	/**
	 * Méthode qui crée l'unique instance de la classe
	 * si elle n'existe pas encore puis la retourne.
	 *
	 * @param void
	 * @return Singleton
	 */

	public static function getInstance() {

		if(is_null(self::$_instance)) {
			self::$_instance = new implementationUserService_Dummy();
		}

		return self::$_instance;
	}


	//================================================================================
	// getters
	//================================================================================

	public function getUsers() {

		return $this->_users;
	}

	public function getUser($enssatPrimaryKey) {
		foreach ($this->_users as $user ) {
			if ($user->getEnssatPrimaryKey() == (int) $enssatPrimaryKey) {
				return $user;
			}
		}
	}


	//================================================================================
	// CREATE
	//================================================================================

	public function saveUser($userArray) {
		$userToSave = new UserVO;
		$userToSave->setEnssatPrimaryKey((float) $userArray['user_enssatPrimaryKey']);
		$userToSave->setUr1Identifier((int) $userArray['user_ur1identifier']);
		$userToSave->setUsername((string) $userArray['user_username']);
		$userToSave->setName((string) $userArray['user_name']);
		$userToSave->setSurname((string) $userArray['user_surname']);
		$userToSave->setPhone((int) $userArray['user_phone']);
		$userToSave->setStatus((string) $userArray['user_status']);
		$userToSave->setEmail((string) $userArray['user_email']);

		array_push($_SESSION["USERS"], $userToSave);
		array_push($this->_users, $userToSave);
		array_push($this->_sessionUsers, $userToSave);
	}


	//================================================================================
	// DELETE
	//================================================================================

	public function deleteUser($enssatPrimaryKey) {

		$this->updateServiceVariables();

		foreach($this->_users as $user) {

			if ($user->getEnssatPrimaryKey() == (int) $enssatPrimaryKey) {

				$nb =  array_search($user, $this->_users);

				// updating service vars
				unset($_SESSION["USERS"][$nb]);
				unset($this->_sessionUsers[$nb]);
				unset($this->_users[$nb]);

				return true;
			}
		}

		return false;
	}


	//================================================================================
	// UPDATE
	//================================================================================

	public function updateUser($userArray) {

		$userToUpdate = new UserVO;
		$userToUpdate->setEnssatPrimaryKey((float) $userArray['user_enssatPrimaryKey']);
		$userToUpdate->setUr1Identifier((int) $userArray['user_ur1identifier']);
		$userToUpdate->setUsername((string) $userArray['user_username']);
		$userToUpdate->setName((string) $userArray['user_name']);
		$userToUpdate->setSurname((string) $userArray['user_surname']);
		$userToUpdate->setPhone((int) $userArray['user_phone']);
		$userToUpdate->setStatus((string) $userArray['user_status']);
		$userToUpdate->setEmail((string) $userArray['user_email']);

		foreach($this->_users as $user) {
			if ($user->getEnssatPrimaryKey() == $userToUpdate->getEnssatPrimaryKey()) {

				// deleting the user in the session
				$nb =  array_search($user, $this->_users);

				$_SESSION["USERS"][$nb] = $userToUpdate;
				$this->_sessionUsers[$nb] = $userToUpdate;
				$this->_users[$nb] = $userToUpdate;

				// updating service vars
				return true;
			}
		}
	}


	//================================================================================
	// OTHER
	//================================================================================

	public function checkUnicity($enssatPrimaryKey) {
		$exist = false;

		if ($this->_users) {
			foreach ($this->_users as $user) {

				if ($user->getEnssatPrimaryKey() == (int) $enssatPrimaryKey) {
					$exist = true;
				}
			}
		}

		echo $exist;
		return $exist;
	}

	private function updateServiceVariables() {

		if (isset($_SESSION["USERS"])) {
			$this->_sessionUsers = $_SESSION["USERS"];
			$this->_users = $_SESSION["USERS"];
		}

	}


}
