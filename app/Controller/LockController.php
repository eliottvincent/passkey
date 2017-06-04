<?php


class LockController {


	//================================================================================
	// constructor
	//================================================================================

	/**
	 * LockController constructor.
	 */
	public function __construct() {
		$this->_lockService = implementationLockService_Dummy::getInstance();
		$this->_doorService = implementationDoorService_Dummy::getInstance();
	}


	//================================================================================
	// LIST
	//================================================================================

	/**
	 *  used to list all locks
	 */
	public function list() {

		$locks = $this->getLocks();

		if (!empty($locks)) {

			if (isset($_GET["update"]) && $_GET["update"] == true) {

				$alert['type'] = 'success';
				$alert['message'] = "Le canon a bien été modifiée.";
				$alerts[] = $alert;

				$this->displayList(true, $alerts);
			}
			else {

				$this->displayList(true);
			}
		}
		else {
			$alert['type'] = 'danger';
			$alert['message'] = 'Nous n\'avons aucun canon d\'enregistré.';
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
			$locks = LockController::getLocks();
		} else {
			$locks = null;
		}
		$composite = new CompositeView(
			true,
			'Liste des canons',
			'Cette page permet de modifier et/ou supprimer des canons.',
			"lock",
			array("sweetAlert" => "https://cdn.jsdelivr.net/sweetalert2/6.6.2/sweetalert2.min.css"),
			array("deleteLockScript" => "app/View/assets/custom/scripts/deleteLock.js",
				"sweetAlert" => "https://cdn.jsdelivr.net/sweetalert2/6.6.2/sweetalert2.min.js"));

		if ($messages != null) {
			foreach ($messages as $message) {
				if (!empty($message['type']) && !empty($message['message'])) {
					$submit_message = new View("submit_message.html.twig", array("alert_type" => $message['type'] , "alert_message" => $message['message']));
					$composite->attachContentView($submit_message);
				}
			}
		}
		$list_locks = new View("locks/list_locks.html.twig", array('locks' => $locks));
		$composite->attachContentView($list_locks);

		echo $composite->render();
	}


	//================================================================================
	// CREATE
	//================================================================================

	/**
	 * To create a lock.
	 */
	public function create() {

		// if no values are posted
		if (!isset($_POST['lock_name']) &&
			!isset($_POST['lock_door'])) {

			$this->displayForm();

		}

		// if some (but not all) values are posted
		elseif (empty($_POST['lock_name']) ||
			empty($_POST['lock_door'])) {

			$m_type = "danger";
			$m_message = "Toutes les valeurs nécessaires n'ont pas été trouvées. Merci de compléter tous les champs.";
			$message['type'] = $m_type;
			$message['message'] = $m_message;

			$this->displayForm(array($message));
		}

		// if we have all values, we can create a lock
		else {

			// id generation
			$id = 'l_' . strtolower(str_replace(' ', '_', addslashes($_POST['lock_name'])));

			// unicity check
			$exist = $this->checkUnicity($id);

			if (!$exist) {
				$lockToSave = array(
					'lock_id' => $id,
					'lock_name' => addslashes($_POST['lock_name']),
					'lock_door' => addslashes($_POST['lock_door'])
				);

				$this->saveLock($lockToSave);

				$m_type = "success";
				$m_message = "Le canon a bien été enregistré.";
				$message['type'] = $m_type;
				$message['message'] = $m_message;

				$this->displayForm(array($message));
			}
			else {
				$m_type = "danger";
				$m_message = "Un canon avec le même nom existe déjà.";
				$message['type'] = $m_type;
				$message['message'] = $m_message;

				$this->displayForm(array($message));
			}
		}
	}

	/**
	 * To display the form used to create lock.
	 * @param null $message array The type and the text of the message
	 */
	public function displayForm($messages = null) {

		$doors = $this->getDoors();

		$compositeView = new CompositeView(
			true,
			'Ajouter un canon',
			null,
			"lock");

		if ($messages != null) {
			foreach ($messages as $message) {
				if (!empty($message['type']) && !empty($message['message'])) {
					$message = new View("submit_message.html.twig", array("alert_type" => $message['type'] , "alert_message" => $message['message']));
					$compositeView->attachContentView($message);
				}
			}
		}

		$create_lock = new View("locks/create_lock.html.twig", array('doors' => $doors, 'previousUrl' => getPreviousUrl()));
		$compositeView->attachContentView($create_lock);

		echo $compositeView->render();
	}


	//================================================================================
	// DELETE
	//================================================================================

	/**
	 *
	 */
	public function deleteLockAjax() {

		session_start();

		if (isset($_POST['value'])) {

			if ($this->deleteLock($_POST['value']) == true) {
				$response['locks'] = $this->getLocks();
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

	public function displayUpdateForm($state, $datas, $messages = null) {
		if ($state) {
			$doors = DoorController::getDoors();
		} else {
			$locks = null;
		}

		$composite = new CompositeView(true, 'Mettre à jour un canon', null, "lock");

		if ($messages != null) {
			foreach ($messages as $message) {
				if (!empty($message['type']) && !empty($message['message'])) {
					$message = new View("submit_message.html.twig", array("alert_type" => $message['type'] , "alert_message" => $message['message']));
					$composite->attachContentView($message);
				}
			}
		}

		$update_lock = new View('locks/update_lock.html.twig', array('doors' => $doors, 'lock' => $datas, 'previousUrl' => getPreviousUrl()));
		$composite->attachContentView($update_lock);

		echo $composite->render();
	}


	public function deleteLockAjax() {
		session_start();
		if (isset($_POST['value'])) {

			$first = substr($_POST['value'], 0, 1);

			if ($first == 'l') {
				$lock = new LockController();
				$lock->deleteLock($_POST['value']);
				$locks = LockController::getLocks();
			}
			$response['locks'] = $locks;
			$response['status'] = 'success';
			$response['message'] = 'This was successful';
		} else {
			$response['status'] = 'error';
			$response['message'] = 'This failed';
		}

		echo json_encode($response);
	}


	//================================================================================
	// calls to Service
	//================================================================================

	/**
	 * To get all locks
	 * @return null
	 */
	public function getLocks() {

		return $this->_lockService->getLocks();
	}

	/**
	 * To get all doors
	 * @return array
	 */
	public function getDoors() {

		return $this->_doorService->getDoors();
	}

	/**
	 * @param $lockToSave
	 */
	private function saveLock($lockToSave) {

		$this->_lockService->saveLock($lockToSave);
	}

	/**
	 * @param $id
	 * @return bool
	 */
	public function deleteLock($id) {

		return $this->_lockService->deleteLock($id);
	}

	/**
	 * @param $id
	 * @return bool
	 */
	private function checkUnicity($id) {

		return $this->_lockService->checkUnicity($id);
	}

}
