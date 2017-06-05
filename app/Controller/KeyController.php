<?php

/**
 * Created by PhpStorm.
 * User: Basile Bruhat
 * Date: 12/05/2017
 * Time: 15:21
 */

class KeyController {

	//================================================================================

	/**
	 * KeyController constructor.
	 */
	public function __construct() {
		$this->_keyService = implementationKeyService_Dummy::getInstance();
		$this->_lockService = implementationLockService_Dummy::getInstance();
	}


	//================================================================================
	// LIST
	//================================================================================

	/**
	 * use to list keys
	 */
	public function list() {

		$keys = $this->getKeys();

		if (!empty($keys)) {
			$this->displayList(true);
		}
		else {
			$message['type'] = 'danger';
			$message['message'] = 'Nous n\'avons aucune clé d\'enregistrée.';
			$this->displayList(false, array($message));
		}
	}

	/**
	 * Display list of keys.
	 * @param $state boolean if file datas/datas.xlsx exists
	 * @param null $message array of the message displays
	 */
	public function displayList($state, $messages = null) {
		if ($state) {
			$keys = $this->getKeys();
		} else {
			$keys = null;
		}

		$compositeView = new CompositeView(
			true,
			'Liste des clés',
			'Cette page permet de modifier et/ou supprimer des clés.',
			"key",
			array("sweetAlert" => "https://cdn.jsdelivr.net/sweetalert2/6.6.2/sweetalert2.min.css"),
			array("deleteKeyScript" => "app/View/assets/custom/scripts/deleteKey.js",
				"sweetAlert" => "https://cdn.jsdelivr.net/sweetalert2/6.6.2/sweetalert2.min.js"));

		if ($messages != null) {
			foreach ($messages as $message) {
				if (!empty($message['type']) && !empty($message['message'])) {
					$submit_message = new View("submit_message.html.twig", array("alert_type" => $message['type'] , "alert_message" => $message['message']));
					$compositeView->attachContentView($submit_message);
				}
			}
		}

		$list_keys = new View("keys/list_keys.html.twig", array('keys' => $keys));
		$compositeView->attachContentView($list_keys);

		echo $compositeView->render();
	}


	//================================================================================
	// CREATE
	//================================================================================

	/**
	 * to create a new key
	 */
	public function create() {

		// if no values are posted -> displaying the form
		if (!isset($_POST['key_name']) &&
			!isset($_POST['key_type']) &&
			!isset($_POST['key_locks']) &&
			!isset($_POST['key_copies'])) {

			$this->displayForm();
		}

		// if some (but not all) values are posted -> error message
		elseif (empty($_POST['key_name']) ||
			empty($_POST['key_type']) ||
			empty($_POST['key_locks']) ||
			empty($_POST['key_copies'])) {

			$m_type = "danger";
			$m_message = "Toutes les valeurs nécessaires n'ont pas été trouvées. Merci de compléter tous les champs.";
			$message['type'] = $m_type;
			$message['message'] = $m_message;

			$this->displayForm(array($message));
		}

		// if we have all values, we can create the key
		else {

			// id generation
			$id = 'k_' . strtolower(str_replace(' ', '_', addslashes($_POST['key_name'])));

			// unicity check
			$exist = $this->checkUnicity($id);

			if (!$exist) {
				$keyToSave = array(
					'key_id' => $id,
					'key_name' => addslashes($_POST['key_name']),
					'key_type' => addslashes($_POST['key_type']),
					'key_locks' => addslashes($_POST['key_locks']),
					'key_copies' => addslashes($_POST['key_copies'])
				);

				$this->saveKey($keyToSave);

				$m_type = "success";
				$m_message = "La clé a bien été créée.";
				$message['type'] = $m_type;
				$message['message'] = $m_message;

				//$this->displayList(true, array($message));
				$this->displayForm(array($message));

			}
			else {
				$m_type = "danger";
				$m_message = "Une clé avec le même nom existe déjà.";
				$message['type'] = $m_type;
				$message['message'] = $m_message;

				$this->displayForm(array($message));
			}
		}
	}

	/**
	 * Display form used to create key
	 * @param $state boolean if file datas/datas.xlsx exists
	 * @param null $message array of the message displays
	 */
	public function displayForm($messages = null) {

		$locks = $this->getLocks();

		$compositeView = new CompositeView(
			true,
			'Ajouter une clé',
			null,
			"key");

		if ($messages != null) {
			foreach ($messages as $message) {
				if (!empty($message['type']) && !empty($message['message'])) {
					$message = new View("submit_message.html.twig", array("alert_type" => $message['type'] , "alert_message" => $message['message']));
					$compositeView->attachContentView($message);
				}
			}
		}

		$create_key = new View('keys/create_key.html.twig', array('locks' => $locks, 'previousUrl' => getPreviousUrl()));
		$compositeView->attachContentView($create_key);

		echo $compositeView->render();
	}


	//================================================================================
	// DELETE
	//================================================================================

	/**
	 *
	 */
	public function deleteKeyAjax() {

		session_start();

		if (isset($_POST['value'])) {

			if ($this->deleteKey($_POST['value']) == true) {
				$response['keys'] = $this->getKeys();
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



	public function update() {
		if (isset($_POST['update']) && !empty($_POST['update'])) {
			$key = $this::getKey(addslashes($_POST['update']));
			$this->displayUpdateForm(true, $key);
		} elseif (isset($_POST['key_hidden_name']) || isset($_POST['key_type']) || isset($_POST['key_lock']) || isset($_POST['key_number'])) {
			$id = 'k_' . strtolower(str_replace(' ', '_', addslashes($_POST['key_hidden_name'])));

			for ($i = 0; $i < sizeof($_SESSION['KEYS']); $i++) {
				if ($_SESSION['KEYS'][$i]['key_id'] == $id) {
					if (isset($_POST['key_type']) && ($_POST['key_type'] != $_SESSION['KEYS'][$i]['key_type']) && !empty($_POST['key_type'])) {
						$_SESSION['KEYS'][$i]['key_type'] = addslashes($_POST['key_type']);
					}

					if (isset($_POST['key_lock']) && !empty($_POST['key_lock'])) {
						$_SESSION['KEYS'][$i]['key_locks'] = $_POST['key_lock'];
					}

					if (isset($_POST['key_number']) && ($_POST['key_number'] != $_SESSION['KEYS'][$i]['key_number']) && !empty($_POST['key_number'])) {
						$_SESSION['KEYS'][$i]['key_number'] = addslashes($_POST['key_number']);
					}
				}
			}

			redirectToUrl('./?action=listkeys&update=true');
		} else {
			$keys = $this::getKeys();
			if (!empty($keys)) {
				$this->displayList(true);
			} else {
				$alert['type'] = 'danger';
				$alert['message'] = 'Nous n\'avons aucune clé d\'enregistrée.';
				$alerts[] = $alert;
				$this->displayList(false, $alerts);
			}
		}
	}

	public function displayUpdateForm($state, $datas, $messages = null) {
		if ($state) {
			$locks = LockController::getLocks();
		} else {
			$locks = null;
		}

		$composite = new CompositeView(true, 'Mettre à jour une clé', null, "key");

		if ($messages != null) {
			foreach ($messages as $message) {
				if (!empty($message['type']) && !empty($message['message'])) {
					$message = new View("submit_message.html.twig", array("alert_type" => $message['type'] , "alert_message" => $message['message']));
					$composite->attachContentView($message);
				}
			}
		}

		$update_key = new View('keys/update_key.html.twig', array('locks' => $locks, 'key' => $datas, 'previousUrl' => getPreviousUrl()));
		$composite->attachContentView($update_key);

		echo $composite->render();
	}




	//================================================================================
	// calls to Service
	//================================================================================

	/**
	 * To get all keys.
	 * @return null
	 */
	public function getKeys() {

		return $this->_keyService->getKeys();
	}

	/**
	 * @param $id
	 * @return mixed
	 */
	public function getKey($id) {

		return $this->_keyService->getKey($id);
	}

	/**
	 * @return array
	 */
	public function getLocks() {

		return $this->_lockService->getLocks();
	}

	/**
	 * @param $keyToSave
	 */
	private function saveKey($keyToSave) {

		$this->_keyService->saveKey($keyToSave);
	}

	/**
	 * Used to delete a key from an id.
	 * @param $id
	 */
	private function deleteKey($id) {

		return $this->_keyService->deleteKey($id);
	}


	/**
	 * @param $id
	 * @return mixed
	 */
	private function checkUnicity($id) {

		return $this->_keyService->checkUnicity($id);
	}
}
