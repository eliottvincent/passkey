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
			foreach($keys->children() as $xmlKey)
			{
				$key = new KeyVO();

				$key->setId((string) $xmlKey->id);
				$key->setLocks(array());
				foreach ($xmlKey->locks[0] as $lock) {	// TODO : find why we need to access to [0] 🤔
					$key->addLock((string) $lock);
				}

				$key->setType((string) $xmlKey->type);
				$key->setName((string) $xmlKey->name);
				$key->setCopies((int) $xmlKey->copies);

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
