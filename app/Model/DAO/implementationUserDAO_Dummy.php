<?php
require_once 'app/Model/VO/UserVO.php';
require_once 'app/Model/DAO/interfaceUserDAO.php';



// Implémentation de l'interface
// Ceci va fonctionner
class implementationUserDAO_Dummy implements interfaceUserDAO
{

	private $_users = array();

	/**
	 * @var Singleton
	 * @access private
	 * @static
	 */
	private static $_instance = null;


	/**
	 * Constructeur de la classe
	 *
	 * @param void
	 * @return void
	 */
	private function __construct() {
		if (file_exists(dirname(__FILE__).'/users.xml')) {
			$users = simplexml_load_file(dirname(__FILE__).'/users.xml');
			foreach($users->children() as $xmlUser)
			{
				$user = new UserVO;
				$user->setEnssatPrimaryKey((float) $xmlUser->enssatPrimaryKey);
				$user->setUr1Identifier((int)$xmlUser->ur1identifier);
				$user->setUsername((string)$xmlUser->username);
				$user->setName((string)$xmlUser->name);
				$user->setSurname((string)$xmlUser->surname);
				$user->setPhone((int)$xmlUser->phone);
				$user->setStatus((string)$xmlUser->status);
				$user->setEmail((string)$xmlUser->email);
				$user->setPassword((string)$xmlUser->password);

				array_push($this->_users, $user);
			}
		} else {
			throw new RuntimeException('Echec lors de l\'ouverture du fichier users.xml.');
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
			self::$_instance = new implementationUserDAO_Dummy();
		}

		return self::$_instance;
	}

	public function getUsers()
	{
		return $this->_users;
		/*
        foreach($this->_users as $clef=>$user)
        {
          echo $user->getEnssatPrimaryKey()." ".$user->getUsername()." ".$user->getPhone()."\n";
        }
        */
	}

	public function getUserByEnssatPrimaryKey($enssatPrimaryKey)
	{

	}


}


?>
