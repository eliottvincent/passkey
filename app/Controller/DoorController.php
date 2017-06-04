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

