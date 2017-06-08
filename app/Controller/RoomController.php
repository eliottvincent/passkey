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
		$this->_doorService = implementationDoorService_Dummy::getInstance();
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
			"room",
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

		$list_rooms = new View("rooms/list_rooms.html.twig", array('rooms' => $rooms));
		$compositeView->attachContentView($list_rooms);

		echo $compositeView->render();
	}


	//================================================================================
	// CREATE
	//================================================================================

	public function create() {

		// if no values are posted -> displaying the form
		if (!isset($_POST['room_name']) &&
			!isset($_POST['room_building']) &&
			!isset($_POST['room_floor'])) {

			$this->displayForm();
		}

		// if some (but not all) values are posted -> error message
		elseif (empty($_POST['room_name']) ||
			empty($_POST['room_building']) ||
			empty($_POST['room_floor'])) {

			$m_type = "danger";
			$m_message = "Toutes les valeurs nécessaires n'ont pas été trouvées. Merci de compléter tous les champs.";
			$message['type'] = $m_type;
			$message['message'] = $m_message;

			$this->displayForm(array($message));
		}

		// if we have all values, we can create the room
		else {

			// id generation
			$id = 'r_' . strtolower(str_replace(' ', '_', addslashes($_POST['room_name'])));

			// unicity check
			$exist = $this->checkUnicity($id);

			if (!$exist) {
				$roomToSave = array(
					'room_id' => $id,
					'room_name' => addslashes($_POST['room_name']),
					'room_building' => addslashes($_POST['room_building']),
					'room_floor' => addslashes($_POST['room_floor'])
				);

				$this->saveRoom($roomToSave);

				$m_type = "success";
				$m_message = "La salle a bien été créée.";
				$message['type'] = $m_type;
				$message['message'] = $m_message;

				$this->displayForm(array($message));
			}
			else {
				$m_type = "danger";
				$m_message = "Une salle avec le même nom existe déjà.";
				$message['type'] = $m_type;
				$message['message'] = $m_message;

				$this->displayForm(array($message));
			}
		}
	}

	/**
	 * Display form used to create a room
	 * @param null $message
	 */
	public function displayForm($messages = null) {

		$compositeView = new CompositeView(
			true,
			'Ajouter une salle',
			null,
			"room");

		if ($messages != null) {
			foreach ($messages as $message) {
				if (!empty($message['type']) && !empty($message['message'])) {
					$message = new View("submit_message.html.twig", array("alert_type" => $message['type'] , "alert_message" => $message['message']));
					$compositeView->attachContentView($message);
				}
			}
		}

		$create_room = new View('rooms/create_room.html.twig', array('previousUrl' => getPreviousUrl()));
		$compositeView->attachContentView($create_room);

		echo $compositeView->render();
	}


	//================================================================================
	// DELETE
	//================================================================================

	/**
	 *
	 */
	public function deleteRoomAjax() {

		session_start();

		if (isset($_POST['value'])) {

			if ($this->deleteRoom(urldecode($_POST['value'])) == true) {
				$response['status'] = 'success';
				$response['message'] = 'This was successful';
			}
			else {
				$response['status'] = 'error';
				$response['message'] = 'This failed';
			}
		}
		else {
			$response['status'] = 'error';
			$response['message'] = 'This failed';
		}

		echo json_encode($response);
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

	/**
	 * To get all doors
	 * @return null
	 */
	public function getDoors() {

		return $this->_doorService->getDoors();
	}

	/**
	 * @param $roomToSave
	 */
	private function saveRoom($roomToSave) {

		$this->_roomService->saveRoom($roomToSave);
	}

	/**
	 * Used to delete a door from an id.
	 * @param $enssatPrimaryKey
	 */
	private function deleteRoom($id) {

		return $this->_roomService->deleteRoom($id);
	}

	/**
	 * @param $id
	 * @return mixed
	 */
	private function checkUnicity($id) {

		return $this->_roomService->checkUnicity($id);
	}
}
