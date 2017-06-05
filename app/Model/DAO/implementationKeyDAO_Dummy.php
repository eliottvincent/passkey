<?php
require_once 'app/Model/VO/KeyVO.php';
require_once 'app/Model/DAO/interfaceKeyDAO.php';


class implementationKeyDAO_Dummy implements interfaceKeyDAO {

	private $_keys = array();

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
		if (file_exists(dirname(__FILE__).'/keys.xml')) {
			$keys = simplexml_load_file(dirname(__FILE__).'/keys.xml');
			foreach($keys->children() as $xmlkey)
			{
				$key = new KeyVO();

				$key->setId((string) $xmlkey->id);
				$key->setLocks($xmlkey->locks[0]);	// TODO : find a better way to assign the array 🤔
				$key->setType((string) $xmlkey->type);
				$key->setName((string) $xmlkey->name);
				$key->setCopies((int) $xmlkey->copies);

				array_push($this->_keys, $key);
			}
		} else {
			exit('Echec lors de l\'ouverture du fichier keys.xml.');
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
			self::$_instance = new implementationKeyDAO_Dummy();
		}

		return self::$_instance;
	}

	public function getKeys()
	{
		return $this->_keys;
	}

}


?>
