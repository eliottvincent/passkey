<?php

class implementationDoorService_Dummy implements interfaceDoorService {


	//================================================================================
	// properties
	//================================================================================

	/**
	 * @var Singleton
	 * @access private
	 * @static
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

		/*if(!isset($_SESSION['DOORS'])) {
			for ($i = 0; $i < sizeof($this->_doors->getDoors()); $i++) {
				$door = $this->_doors->getDoors()[$i];
				$_SESSION['DOORS'][] = [
					'door_id' => $door->getId(),
					'door_name' => $door->getName(),
					'door_building' => $door->getBuilding(),
					'door_floor' => $door->getFloor()
				];
			}
		}*/
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
	//================================================================================
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
		return false;
	}

	public function create($datas) {
		$_SESSION['DOORS'][] = $datas;
		$door = new DoorVO(null, null, null, null);

		$door->setId($datas['door_id']);
		$door->setName($datas['door_name']);
		$door->setBuilding($datas['door_building']);
		$door->setFloor($datas['door_floor']);


		// TODO : push $d is not an array
		$d = $this->_doors->getDoors();
		array_push($d, $door);

	}
}
