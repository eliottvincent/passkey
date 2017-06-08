<?php


class DoorController {


	//================================================================================
	// constructor
	//================================================================================

	/**
	 * DoorController constructor.
	 */
	public function __construct() {
		$this->_doorService = implementationDoorService_Dummy::getInstance();
		$this->_roomService= implementatioRoomService_Dummy::getInstance();
	}


	//================================================================================
	// LIST
	//================================================================================

	/**
	 * used to list all doors
	 */
	public function list() {

		$doors = $this->getDoors();

		if (!empty($doors)) {
			$this->displayList();
		}
		else {
			$message['type'] = 'danger';
			$message['message'] = 'Nous n\'avons aucune porte d\'enregistrée.';
			$this->displayList(array($message));
		}
	}

	/**
	 * @param null $messages
	 * @internal param $state
	 */
	public function displayList($messages = null) {
		$doors = $this->getDoors();

		$compositeView = new CompositeView(
			true,
			'Liste des portes',
			null,
			"door",
			array("sweetAlert" => "https://cdn.jsdelivr.net/sweetalert2/6.6.2/sweetalert2.min.css"),
			array("deleteUserScript" => "app/View/assets/custom/scripts/deleteDoor.js",
				"sweetAlert" => "https://cdn.jsdelivr.net/sweetalert2/6.6.2/sweetalert2.min.js"));

		if ($messages != null) {
			foreach ($messages as $message) {
				if (!empty($message['type']) && !empty($message['message'])) {
					$submit_message = new View("submit_message.html.twig", array("alert_type" => $message['type'] , "alert_message" => $message['message']));
					$compositeView->attachContentView($submit_message);
				}
			}
		}

		$list_doors = new View("doors/list_doors.html.twig", array('doors' => $doors));
		$compositeView->attachContentView($list_doors);

		echo $compositeView->render();
	}


	//================================================================================
	// CREATE
	//================================================================================

	public function create() {

		// if no values are posted -> displaying the form
		if (!isset($_POST['door_name']) &&
			!isset($_POST['door_room'])) {

			$this->displayForm();
		}

		// if some (but not all) values are posted -> error message
		elseif (empty($_POST['door_name']) ||
			empty($_POST['door_room'])) {

			$m_type = "danger";
			$m_message = "Toutes les valeurs nécessaires n'ont pas été trouvées. Merci de compléter tous les champs.";
			$message['type'] = $m_type;
			$message['message'] = $m_message;

			$this->displayForm(array($message));
		}

		// if we have all values, we can create the door
		else {

			// id generation
			$id = 'd_' . strtolower(str_replace(' ', '_', addslashes($_POST['door_name'])));

			// unicity check
			$exist = $this->checkUnicity($id);

			if (!$exist) {
				$doorToSave = array(
					'door_id' => $id,
					'door_name' => addslashes($_POST['door_name']),
					'door_room' => addslashes($_POST['door_room'])
				);

				$this->saveDoor($doorToSave);

				$m_type = "success";
				$m_message = "La porte a bien été créée.";
				$message['type'] = $m_type;
				$message['message'] = $m_message;

				$this->displayForm(array($message));
			}
			else {
				$m_type = "danger";
				$m_message = "Une porte avec le même nom existe déjà.";
				$message['type'] = $m_type;
				$message['message'] = $m_message;

				$this->displayForm(array($message));
			}
		}
	}

	/**
	 * Display form used to create a door
	 * @param null $message
	 */
	public function displayForm($messages = null) {

		$rooms = $this->getRooms();

		$compositeView = new CompositeView(
			true,
			'Ajouter une porte',
			null,
			"door");

		if ($messages != null) {
			foreach ($messages as $message) {
				if (!empty($message['type']) && !empty($message['message'])) {
					$message = new View("submit_message.html.twig", array("alert_type" => $message['type'] , "alert_message" => $message['message']));
					$compositeView->attachContentView($message);
				}
			}
		}

		$create_door = new View('doors/create_door.html.twig', array("rooms" => $rooms, 'previousUrl' => getPreviousUrl()));
		$compositeView->attachContentView($create_door);

		echo $compositeView->render();
	}


	//================================================================================
	// DELETE
	//================================================================================

	/**
	 *
	 */
	public function deleteDoorAjax() {

		session_start();

		if (isset($_POST['value'])) {

			if ($this->deleteDoor(urldecode($_POST['value'])) == true) {
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
	// UPDATE
	//================================================================================

	/**
	 *
	 */
	public function update() {

		if (isset($_POST['update']) && !empty($_POST['update'])) {
			$door = $this->getDoor($_POST['update']);
			$this->displayUpdateForm($door);
		}

		// if all values were posted (= form submission)
		elseif (isset($_POST['door_name']) &&
			isset($_POST['door_room'])) {

			$doorToUpdate = array(
				'door_id' => $_POST['door_id'],
				'door_name' => addslashes($_POST['door_name']),
				'door_room' => addslashes($_POST['door_room']));

			if ($this->updateDoor($doorToUpdate) == false) {
				$message['type'] = 'danger';
				$message['message'] = 'Erreur lors de la modification de la porte.';
				$this->displayList(array($message));
			}
			else {
				$message['type'] = 'success';
				$message['message'] = 'La porte a bien été modifiée.';
				$this->displayList(array($message));
			}
		}

		else {
			$doors = $this->getDoors();

			if (!empty($doors)) {
				$this->displayList();
			}
			else {
				$message['type'] = 'danger';
				$message['message'] = 'Nous n\'avons aucune porte d\'enregistrée.';
				$this->displayList(array($message));
			}
		}
	}

	/**
	 * @param $door
	 * @param null $messages
	 */
	public function displayUpdateForm($door, $messages = null) {

		$rooms = $this->getRooms();

		$compositeView = new CompositeView(
			true,
			"Mettre à jour une porte",
			null,
			"door");

		if ($messages != null) {

			foreach ($messages as $message) {
				if (!empty($message['type']) && !empty($message["message"])) {
					$message = new View("submit_message.html.twig", array("alert_type" => $message["type"] , "alert_message" => $message["message"]));
					$compositeView->attachContentView($message);
				}
			}
		}

		$update_door = new View("doors/update_door.html.twig", array("door" => $door, "rooms" => $rooms, "previousUrl" => getPreviousUrl()));
		$compositeView->attachContentView($update_door);

		echo $compositeView->render();
	}


	//================================================================================
	// calls to Service
	//================================================================================

	/**
	 * To get all doors
	 * @return null
	 */
	public function getDoors() {

		return $this->_doorService->getDoors();
	}

	/**
	 * @param $id
	 * @return mixed
	 */
	private function getDoor($id) {

		return $this->_doorService->getDoor($id);
	}

	/**
	 * @return mixed
	 */
	private function getRooms() {

		return $this->_roomService->getRooms();
	}

	/**
	 * @param $doorToSave
	 */
	private function saveDoor($doorToSave) {

		$this->_doorService->saveDoor($doorToSave);
	}

	/**
	 * Used to delete a door from an id.
	 * @param $enssatPrimaryKey
	 */
	private function deleteDoor($id) {

		return $this->_doorService->deleteDoor($id);
	}

	/**
	 * @param $doorToUpdate
	 */
	private function updateDoor($doorToUpdate) {

		return $this->_doorService->updateDoor($doorToUpdate);
	}

	/**
	 * @param $id
	 * @return mixed
	 */
	private function checkUnicity($id) {

		return $this->_doorService->checkUnicity($id);
	}
}
