	//================================================================================
	// CREATE
	//================================================================================

	public function create() {

		// if no values are posted -> displaying the form
		if (!isset($_POST['door_name']) &&
			!isset($_POST['door_building']) &&
			!isset($_POST['door_floor'])) {

			$this->displayForm();
		}

		// if some (but not all) values are posted -> error message
		elseif (empty($_POST['door_name']) ||
			empty($_POST['door_building']) ||
			empty($_POST['door_floor'])) {

			$m_type = "danger";
			$m_message = "Toutes les valeurs nécessaires n'ont pas été trouvées. Merci de compléter tous les champs.";
			$message['type'] = $m_type;
			$message['message'] = $m_message;
			$this->displayForm( $message);
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
					'door_building' => addslashes($_POST['door_building']),
					'door_floor' => addslashes($_POST['door_floor'])
				);

				$this->saveDoor($doorToSave);

				$m_type = "success";
				$m_message = "La porte a bien été créée.";
				$message['type'] = $m_type;
				$message['message'] = $m_message;

				$this->displayForm($message);
			}
			else {
				$m_type = "danger";
				$m_message = "Une porte avec le même nom existe déjà.";
				$message['type'] = $m_type;
				$message['message'] = $m_message;

				$this->displayForm($message);
			}


		}
	}

	/**
	 * Display form used to create a door
	 * @param null $message
	 */
	public function displayForm($messages = null) {
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

		$create_door = new View('doors/create_door.html.twig', array('previousUrl' => getPreviousUrl()));
		$compositeView->attachContentView($create_door);

		echo $compositeView->render();
	}

	/**
	 * used to list all doors
	 */
	public function list() {

		$doors = $this->getDoors();

		if (!empty($doors)) {

			if (isset($_GET["update"]) && $_GET["update"] == true) {

				$alert['type'] = 'success';
				$alert['message'] = "La porte a bien été modifiée.";
				$alerts[] = $alert;

				$this->displayList(true, $alerts);
			}
			else {

				$this->displayList(true);
			}
		}
		else {
			$alert['type'] = 'danger';
			$alert['message'] = 'Nous n\'avons aucune porte d\'enregistrée.';
			$alerts[] = $alert;
			$this->displayList(false, $alerts);
		}
	}

	/**
	 * @param $state
	 * @param null $messages
	 */
	public function displayList($state, $messages = null) {
		if ($state) {
			$doors = $this->getDoors();
		} else {
			$doors = null;
		}
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
	// DELETE
	//================================================================================

	/**
	 *
	 */
	public function deleteDoorAjax() {

		session_start();

		if (isset($_POST['value'])) {

			if ($this->deleteDoor($_POST['value']) == true) {
				$response['doors'] = $this->getDoors();
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

	}

