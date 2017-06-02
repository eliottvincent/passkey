<?php

/**
 * Created by PhpStorm.
 * User: eliottvincent
 * Date: 01/06/2017
 * Time: 13:59
 */
class implementationUserService_Dummy implements interfaceUserService
{

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

	/**
	 * Constructeur de la classe
	 *
	 * @param void
	 * @return void
	 */
	private function __construct()
	{

		// instantiating the DAOs we need
		$this->_userDAO = implementationUserDAO_Dummy::getInstance();

		// getting data we need
		$this->_xmlUsers = $this->_userDAO->getUsers();
		if (isset($_SESSION["USERS"])) {
			$this->_sessionUsers = $_SESSION["USERS"];
		}

		// if we got users in session
		if ($this->_sessionUsers !== null) {

			// we have to add the SORT_REGULAR flag
			// so it compares items normally without changing their types
			// otherwise, it'll try to convert types to String, and we cannot do it (Object of class UserVO could not be converted to string)
			// $this->_users = array_unique(array_merge($this->_sessionUsers, $this->_xmlUsers), SORT_REGULAR);

			// updating the session["USERS"]
			// unset($_SESSION["USERS"]);

			$this->_users = $this->_sessionUsers;
		}
		// else that means there are no users in session (first use)
		else {

			$this->_users = $this->_xmlUsers;

			// we set our session["USERS"] to match the users present in XML
			$_SESSION["USERS"] = $this->_users;
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

	public function getUsers() {

		return $this->_users;
	}

	public function getUser($enssatPrimaryKey)
	{
		foreach ($this->_users as $user ) {
			if ($user->getEnssatPrimaryKey() == (int) $enssatPrimaryKey) {
				return $user;
			}
		}
	}

	public function deleteUser($enssatPrimaryKey)
	{
		foreach($this->_users as $user) {
			if ($user->getEnssatPrimaryKey() == (int) $enssatPrimaryKey) {

				// deleting the user in the session
				$nb =  array_search($user, $this->_users);
				unset($_SESSION["USERS"][$nb]);
				unset($this->_sessionUsers[$nb]);
				unset($this->_users[$nb]);

				// updating service vars
				return true;
			}
		}

		return false;
	}

	public function checkUnicity($enssatPrimaryKey)
	{
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

	public function saveUser($userArray)
	{
		$userToSave = new UserVO;
		$userToSave->setEnssatPrimaryKey((float) $userArray['user_enssatPrimaryKey']);
		$userToSave->setUr1Identifier((int) $userArray['user_ur1identifier']);
		$userToSave->setUsername((string) $userArray['user_username']);
		$userToSave->setName((string) $userArray['user_name']);
		$userToSave->setSurname((string) $userArray['user_surname']);
		$userToSave->setPhone((int) $userArray['user_phone']);
		$userToSave->setStatus((string) $userArray['user_status']);
		$userToSave->setEmail((string) $userArray['user_email']);

		// for the moment we only save the user in session
		// if we move to DB storage, we'll have to handle the save in DB HERE

		if (isset($_SESSION["USERS"])) {
			$_SESSION['USERS'][] = $userToSave;
		}
		else {
			array_push($_SESSION["USERS"], $userToSave);
		}
	}
}
