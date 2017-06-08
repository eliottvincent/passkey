<?php

/**
 * Created by PhpStorm.
 * User: eliottvincent
 * Date: 08/06/2017
 * Time: 15:27
 */
class implementatioRoomService_Dummy implements interfaceRoomService
{

	//================================================================================
	// properties
	//================================================================================

	/**
	 * @var null
	 */
	private static $_instance = null;

	private $_roomDAO;
	private $_rooms = array();
	private $_sessionRooms = null;
	private $_xmlRooms;


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
		$this->_roomDAO = implementationRoomDAO_Dummy::getInstance();

		// getting the data we need
		$this->_xmlRooms = $this->_roomDAO->getRooms();
		if (isset($_SESSION["ROOMS"])) {
			$this->_sessionRooms = $_SESSION["ROOMS"];
		}

		// if we got doors in session
		if ($this->_sessionRooms !== null) {

			$this->_rooms = $this->_sessionRooms;
		}

		// else that means there are no doors in session (first use)
		else {

			$_SESSION["ROOMS"] = $this->_xmlRooms;
			$this->_rooms = $this->_xmlRooms;
			$this->_sessionRooms = $this->_xmlRooms;
		}
	}

	/**
	 * Méthode qui crée l'unique instance de la classe
	 * si elle n'existe pas encore puis la retourne.
	 *
	 * @param void
	 * @return implementatioRoomService_Dummy
	 */
	public static function getInstance() {

		if(is_null(self::$_instance)) {
			self::$_instance = new implementatioRoomService_Dummy();
		}

		return self::$_instance;
	}


	//================================================================================
	// Getters
	//================================================================================

	public function getRooms()
	{
		return $this->_rooms;
	}

	public function getRoom($id)
	{

		foreach ($this->_rooms as $room) {
			if ($room->getId() == (string) $id) {
				return $room;
			}
		}
	}


	//================================================================================
	// CREATE
	//================================================================================

	public function saveRoom($roomArray)
	{

		$roomToSave = new RoomVO();
		$roomToSave->setId((string) $roomArray['room_id']);
		$roomToSave->setName((string) $roomArray['room_name']);
		$roomToSave->setBuilding((string) $roomArray['room_building']);
		$roomToSave->setFloor((string) $roomArray['room_floor']);

		array_push($_SESSION["ROOMS"], $roomToSave);
		array_push($this->_rooms, $roomToSave);
		array_push($this->_sessionRooms, $roomToSave);

	}

	public function deleteRoom($id)
	{
		// TODO: Implement deleteRoom() method.
	}

	public function updateRoom($roomArray)
	{
		// TODO: Implement updateRoom() method.
	}

	public function checkUnicity($id)
	{
		// TODO: Implement checkUnicity() method.
	}
}
