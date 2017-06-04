<?php

class implementationLockService_Dummy implements interfaceLockService {


	//================================================================================
	// properties
	//================================================================================

	/**
	 * @var null
	 */
	private static $_instance = null;

	private $_lockDAO;
	private $_locks = array();
	private $_sessionLocks = null;
	private $_xmlLocks;


	//================================================================================
	// constructor and initialization
	//================================================================================

	/**
	 * implementationLockService_Dummy constructor.
	 */
	private function __construct() {

		// instantiating the DAOs we need
		$this->_lockDAO = implementationLockDAO_Dummy::getInstance();

		// getting the data we need
		$this->_xmlLocks = $this->_lockDAO->getLocks();
		if (isset($_SESSION["LOCKS"])) {
			$this->_sessionLocks = $_SESSION["LOCKS"];
		}

		// if we got locks in session
		if ($this->_sessionLocks !== null) {

			$this->_locks = $this->_sessionLocks;
		}

		// else that means there are no locks in session (first use)
		else {

			$_SESSION["LOCKS"] = $this->_xmlLocks;
			$this->_locks = $this->_xmlLocks;
			$this->_sessionLocks = $this->_xmlLocks;
		}
	}

	/**
	 * @return implementationLockService_Dummy
	 */
	public static function getInstance() {

		if(is_null(self::$_instance)) {
			self::$_instance = new implementationLockService_Dummy();
		}

		return self::$_instance;
	}


	//================================================================================
	// Getters
	//================================================================================

	public function getLocks() {

		return $this->_locks;
	}

	public function getLock($id) {

		foreach ($this->_locks as $lock) {
			if ($lock->getId() == (string) $id) {
				return $lock;
			}
		}
	}
	}
}
