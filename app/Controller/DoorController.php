<?php

class DoorController
{
	private $_doorService;

	public function __construct()
	{
		$this->_doorService = implementationDoorService_Dummy::getInstance();
	}

	public function create(){
		if (!isset($_POST['door_name']) && !isset($_POST['door_building']) && !isset($_POST['door_floor'])) {
			// If we have no values, the form is displayed.
			$this->displayForm();
		} elseif (empty($_POST['door_name']) || empty($_POST['door_building']) || empty($_POST['door_floor'])){
			// If we have not all values, error message display and form.
			$m_type = "danger";
			$m_message = "Toutes les valeurs nécessaires n'ont pas été trouvées. Merci de compléter tous les champs.";
			$message['type'] = $m_type;
			$message['message'] = $m_message;
			$this->displayForm( $message);
		} else {
			// If we have all values, the door is created.
			$id = 'd_' . strtolower(str_replace(' ', '_', addslashes($_POST['door_name'])));

			// Check unicity.
			$exist = $this->_doorService->checkUnicity($id);

			/**if ($doors) {
				foreach ($doors as $door) {
					if ($door['door_id'] == $id) {
						$exist = true;
					}
				}
			}**/

			if (!$exist) {
				$datas = array(
					'door_id' => $id,
					'door_name' => addslashes($_POST['door_name']),
					'door_building' => addslashes($_POST['door_building']),
					'door_floor' => addslashes($_POST['door_floor'])
				);

				/**$_SESSION['DOORS'][] = $datas;**/
				// Create the door.
				$this->_doorService->create($datas);

				$m_type = "success";
				$m_message = "La porte a bien été créée.";
				$message['type'] = $m_type;
				$message['message'] = $m_message;
				$this->displayForm($message);
			} else {
				$m_type = "danger";
				$m_message = "Une porte avec le même nom existe déjà.";
				$message['type'] = $m_type;
				$message['message'] = $m_message;
				$this->displayForm($message);
			}


		}
	}

	public function displayForm($message = null) {
		$composite = new CompositeView(true, 'Ajouter une porte', null, "door");

		if ($message != null && !empty($message['type']) && !empty($message['message'])) {
			$message = new View("submit_message.html.twig", array("alert_type" => $message['type'] , "alert_message" => $message['message']));
			$composite->attachContentView($message);
		}

		$create_door = new View('doors/create_door.html.twig', array('previousUrl' => getPreviousUrl()));
		$composite->attachContentView($create_door);

		echo $composite->render();
	}

	public function list() {
		$doors = $this::getDoors();
		if (!empty($doors)) {
			$this->displayList(true);
		} else {
			$alert['type'] = 'danger';
			$alert['message'] = 'Nous n\'avons aucune porte d\'enregistrée.';
			$alerts[] = $alert;
			$this->displayList(false, $alerts);
		}
	}

	public function displayList($state, $messages = null) {
		if ($state) {
			$doors = $this::getDoors();
		} else {
			$doors = null;
		}
		$composite = new CompositeView(true, 'Liste des portes', null, "door");

		if ($messages != null) {
			foreach ($messages as $message) {
				if (!empty($message['type']) && !empty($message['message'])) {
					$submit_message = new View("submit_message.html.twig", array("alert_type" => $message['type'] , "alert_message" => $message['message']));
					$composite->attachContentView($submit_message);
				}
			}
		}
		$list_doors = new View("doors/list_doors.html.twig", array('doors' => $doors));
		$composite->attachContentView($list_doors);

		echo $composite->render();
	}

	/**
	 * Used to get all doors created.
	 * @return null
	 */
	public static function getDoors() {
		if (isset($_SESSION['DOORS'])) {
			$doors = $_SESSION['DOORS'];
			return $doors;
		}

		return null;
	}
}
