<?php

/**
 * Created by PhpStorm.
 * User: eliottvincent
 * Date: 08/06/2017
 * Time: 15:13
 */
class implementationRoomDAO_Dummy implements interfaceRoomDAO {

	private $_rooms = array();

	/**
	 * @var null
	 */
	private static $_instance = null;

	/**
	 * implementationRoomDAO_Dummy constructor.
	 */
	public function __construct() {
		if (file_exists(dirname(__FILE__).'/rooms.xml')) {
			$rooms = simplexml_load_file(dirname(__FILE__).'/rooms.xml');
			foreach($rooms->children() as $xmlRoom)
			{
				$room = new RoomVO();

				$room->setId((string)$xmlRoom->id);
				$room->setName((string)$xmlRoom->name);
				$room->setBuilding((string)$xmlRoom->building);
				$room->setFloor((string)$xmlRoom->floor);

				$room->setDoors(array());
				foreach ($xmlRoom->doors->children() as $door) {
					$room->addDoor((string) $door);
				}

				array_push($this->_rooms, $room);
			}
		} else {
			exit('Echec lors de l\'ouverture du fichier rooms.xml.');
		}
	}


	public static function getInstance()
	{

		if (is_null(self::$_instance)) {
			self::$_instance = new implementationRoomDAO_Dummy();
		}

		return self::$_instance;
	}

	public function getRooms()
	{
		return $this->_rooms;
	}
}
