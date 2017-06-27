<?php
//Timezone Paris.
date_default_timezone_set('Europe/Paris');

/**
 * Created by PhpStorm.
 * User: chloecorfmat
 * Date: 30/05/2017
 * Time: 15:13
 */
class implementationDoorDAO_Dummy implements interfaceDoorDAO {

	private $_doors = array();

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
		if (file_exists(dirname(__FILE__).'/doors.xml')) {
			$doors = simplexml_load_file(dirname(__FILE__).'/doors.xml');
			foreach($doors->children() as $xmlDoor)
			{
				$door = new DoorVO(null, null, null, null);

				$door->setId((string)$xmlDoor->id);
				$door->setName((string)$xmlDoor->name);
				$door->setRoom((string)$xmlDoor->room);

				array_push($this->_doors, $door);
			}
		} else {
			exit('Echec lors de l\'ouverture du fichier doors.xml.');
		}

	}

	public static function getInstance()
	{
		if(is_null(self::$_instance)) {
			self::$_instance = new implementationDoorDAO_Dummy();
		}

		return self::$_instance;
	}

	public function getDoors()
	{
		return $this->_doors;
	}

}
