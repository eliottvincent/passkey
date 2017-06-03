<?php

class implementationDoorService_Dummy implements interfaceDoorService {
	/**
	 * @var Singleton
	 * @access private
	 * @static
	 */
	private static $_instance = null;

	private $_doors = array(); // doorId, doorName, doorBuilding, doorFloor

	/**
	 * Constructeur de la classe
	 *
	 * @param void
	 * @return void
	 */
	private function __construct()
	{
		$this->_doors = implementationDoorDAO_Dummy::getInstance();
		if(!isset($_SESSION['DOORS'])) {
			for ($i = 0; $i < sizeof($this->_doors->getDoors()); $i++) {
				$door = $this->_doors->getDoors()[$i];
				$_SESSION['DOORS'][] = [
					'door_id' => $door->getId(),
					'door_name' => $door->getName(),
					'door_building' => $door->getBuilding(),
					'door_floor' => $door->getFloor()
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
			self::$_instance = new implementationDoorService_Dummy();
		}

		return self::$_instance;
	}

	public function checkUnicity($id) {
		$doors = $this->_doors->getDoors();
		foreach ($doors as $door) {
			if ($door->getId() == $id) {
				return true;
			}
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
