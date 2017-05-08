<?php
require_once 'app/Model/VO/KeychainVO.php';
require_once 'app/Model/DAO/interfaceKeychainDAO.php';

//Timezone Paris.
date_default_timezone_set('Europe/Paris');

class implementationKeychainDAO_Dummy implements interfaceKeyChainDAO
{

	private $_keychains = array();

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
		if (file_exists(dirname(__FILE__).'/keychains.xml')) {
			$keychains = simplexml_load_file(dirname(__FILE__).'/keychains.xml');
			foreach($keychains->children() as $xmlKeychain)
			{
				$keychain = new KeychainVO;

				$keychain->setId((float) $xmlKeychain->id);
				$tDate = new DateTime;
				$tDate->setTimestamp((int)$xmlKeychain->creationDate);
				$keychain->setCreationDate($tDate);
				$tDate->setTimestamp((int)$xmlKeychain->destructionDate);
				$keychain->setDestructionDate($tDate);

				array_push($this->_keychains,$keychain);
			}
		} else {
			exit('Echec lors de l\'ouverture du fichier keychains.xml.');
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
			self::$_instance = new implementationKeychainDAO_Dummy();
		}

		return self::$_instance;
	}

	public function getKeychains()
	{
		return $this->_keychains;
	}

	public function getRandomKeychain()
	{
		return $this->_keychains[array_rand($this->_keychains,1)];
	}


}


?>
