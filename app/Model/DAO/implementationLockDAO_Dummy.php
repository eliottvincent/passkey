<?php

class implementationLockDAO_Dummy implements interfaceLockDAO {

	private $_locks = array();

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
		if (file_exists(dirname(__FILE__).'/locks.xml')) {
			$locks = simplexml_load_file(dirname(__FILE__).'/locks.xml');
			foreach($locks->children() as $xmlLock)
			{
				$lock = new LockVO(null, null, null);

				$lock->setId((string)$xmlLock->id);
				$lock->setName((string)$xmlLock->name);
				$lock->setDoor((string)$xmlLock->door);

				array_push($this->_locks, $lock);
			}
		} else {
			exit('Echec lors de l\'ouverture du fichier locks.xml.');
		}

	}

	public static function getInstance()
	{
		if(is_null(self::$_instance)) {
			self::$_instance = new implementationLockDAO_Dummy();
		}

		return self::$_instance;
	}

	public function getLocks()
	{
		return $this->_locks;
	}

	public function getRandomLock()
	{
		return $this->_locks[array_rand($this->_locks,1)];

	}
}
