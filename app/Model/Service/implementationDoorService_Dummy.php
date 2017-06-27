<?php

class implementationDoorService_Dummy implements interfaceDoorService {


	//================================================================================
	// properties
	//================================================================================

	/**
	 * @var null
	 */
	private static $_instance = null;

	private $_doorDAO;
	private $_doors = array();
	private $_sessionDoors = null;
	private $_xmlDoors;


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
		$this->_doorDAO = implementationDoorDAO_Dummy::getInstance();

		// getting the data we need
		$this->_xmlDoors = $this->_doorDAO->getDoors();
		if (isset($_SESSION["DOORS"])) {
			$this->_sessionDoors = $_SESSION["DOORS"];
		}

		// if we got doors in session
		if ($this->_sessionDoors !== null) {

			$this->_doors = $this->_sessionDoors;
		}

		// else that means there are no doors in session (first use)
		else {

			$_SESSION["DOORS"] = $this->_xmlDoors;
			$this->_doors = $this->_xmlDoors;
			$this->_sessionDoors = $this->_xmlDoors;
		}
	}

	/**
	 * Méthode qui crée l'unique instance de la classe
	 * si elle n'existe pas encore puis la retourne.
	 *
	 * @param void
	 * @return implementationDoorService_Dummy
	 */
	public static function getInstance() {

		if(is_null(self::$_instance)) {
			self::$_instance = new implementationDoorService_Dummy();
		}

		return self::$_instance;
	}


	//================================================================================
	// Getters
	//================================================================================

	public function getDoors() {

		return $this->_doors;
	}

	public function getDoor($id) {

		foreach ($this->_doors as $door) {
			if ($door->getId() == (string) $id) {
				return $door;
			}
		}
	}


	//================================================================================
	// CREATE
	//================================================================================

	public function saveDoor($doorArray) {

		$doorToSave = new DoorVO();
		$doorToSave->setId((string) $doorArray['door_id']);
		$doorToSave->setName((string) $doorArray['door_name']);
		$doorToSave->setRoom((string) $doorArray['door_room']);

		array_push($_SESSION["DOORS"], $doorToSave);
		array_push($this->_doors, $doorToSave);
		array_push($this->_sessionDoors, $doorToSave);
	}


	//================================================================================
	// DELETE
	//================================================================================

	public function deleteDoor($id) {

		$this->updateServiceVariables();

		foreach ($this->_doors as $key=>$door) {

			if ($door->getId() == (string) $id) {

				unset($_SESSION["DOORS"][$key]);
				unset($this->_sessionDoors[$key]);
				unset($this->_doors[$key]);

				return true;
			}
		}

		return false;
	}


	//================================================================================
	// UPDATE
	//================================================================================

	public function updateDoor($doorArray) {

		$doorToUpdate = new DoorVO();
		$doorToUpdate->setId((string) $doorArray['door_id']);
		$doorToUpdate->setName((string) $doorArray['door_name']);
		$doorToUpdate->setRoom((string) $doorArray['door_room']);

		foreach ($this->_doors as $key=>$door) {

			if ($door->getId() == $doorToUpdate->getId()) {

				$_SESSION["DOORS"][$key] = $doorToUpdate;
				$this->_sessionDoors[$key] = $doorToUpdate;
				$this->_doors[$key] = $doorToUpdate;

				return true;
			}

		}

		return false;
	}


	//================================================================================
	// OTHER
	//================================================================================

	public function checkUnicity($id) {

		if ($this->_doors) {

			foreach ($this->_doors as $door) {

				if ($door->getId() == (string) $id) {

					return true;
				}
			}
		}

		return false;
	}


	private function updateServiceVariables() {

		if (isset($_SESSION["DOORS"])) {
			$this->_sessionDoors = $_SESSION["DOORS"];
			$this->_doors = $_SESSION["DOORS"];
		}

	}
}
