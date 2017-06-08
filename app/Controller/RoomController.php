<?php

/**
 * Created by PhpStorm.
 * User: eliottvincent
 * Date: 08/06/2017
 * Time: 15:34
 */
class RoomController {


	//================================================================================
	// constructor
	//================================================================================

	public function __construct() {
		$this->_roomService = implementatioRoomService_Dummy::getInstance();
	}


	//================================================================================
	// LIST
	//================================================================================

	/**
	 * used to list all rooms
	 */
	public function list() {

		$rooms = $this->getRooms();

		if (!empty($rooms)) {
			$this->displayList();
		}
		else {
			$message['type'] = 'danger';
			$message['message'] = 'Nous n\'avons aucune salle d\'enregistrée.';
			$this->displayList(array($message));
		}
	}

	/**
	 * @param null $messages
	 * @internal param $state
	 */
	public function displayList($messages = null) {
		$rooms = $this->getRooms();

		$compositeView = new CompositeView(
			true,
			'Liste des salles',
			null,
			"door",
			array("sweetAlert" => "https://cdn.jsdelivr.net/sweetalert2/6.6.2/sweetalert2.min.css"),
			array("deleteRoomScript" => "app/View/assets/custom/scripts/deleteRoom.js",
				"sweetAlert" => "https://cdn.jsdelivr.net/sweetalert2/6.6.2/sweetalert2.min.js"));

		if ($messages != null) {
			foreach ($messages as $message) {
				if (!empty($message['type']) && !empty($message['message'])) {
					$submit_message = new View("submit_message.html.twig", array("alert_type" => $message['type'] , "alert_message" => $message['message']));
					$compositeView->attachContentView($submit_message);
				}
			}
		}

		$list_doors = new View("rooms/list_rooms.html.twig", array('rooms' => $rooms));
		$compositeView->attachContentView($list_doors);

		echo $compositeView->render();
	}

	//================================================================================
	// calls to Service
	//================================================================================

	/**
	 * To get all rooms
	 * @return null
	 */
	public function getRooms() {

		return $this->_roomService->getRooms();
	}
}
