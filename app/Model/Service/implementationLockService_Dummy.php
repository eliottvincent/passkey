<?php

class implementationLockService_Dummy implements interfaceLockService {
	/**
	 * @var Singleton
	 * @access private
	 * @static
	 */
	private static $_instance = null;

	private $_locks = array();

	/**
	 * Constructeur de la classe
	 *
	 * @param void
	 * @return void
	 */
	private function __construct()
	{
		$this->_locks = implementationLockDAO_Dummy::getInstance();
		if(!isset($_SESSION['LOCKS'])) {
			for ($i = 0; $i < sizeof($this->_locks->getLocks()); $i++) {
				$lock = $this->_locks->getLocks()[$i];
				$_SESSION['LOCKS'][] = [
					'lock_id' => $lock->getId(),
					'lock_name' => $lock->getName(),
					'lock_door' => $lock->getDoor()
				];
			}
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
			self::$_instance = new implementationLockService_Dummy();
		}

		return self::$_instance;
	}
}
