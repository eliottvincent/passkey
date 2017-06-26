<?php

/**
 * Created by PhpStorm.
 * User: Basile Bruhat
 * Date: 12/05/2017
 * Time: 15:21
 */

class KeyController {

	//================================================================================
	// constructor
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
	 * used to list keys
	 */
	public function list() {

		$keys = $this->getKeys();

		if (!empty($keys)) {
			$this->displayList();
		}
		else {
			$message['type'] = 'danger';
			$message['message'] = 'Nous n\'avons aucune clé d\'enregistrée.';
			$this->displayList(array($message));
		}
	}

	/**
	 * Display list of keys.
	 * @param $state boolean if file datas/datas.xlsx exists
	 * @param null $message array of the message displays
	 */
	public function displayList($messages = null) {

		$keys = $this->getKeys();
		$locks = $this->getLocks();

		$compositeView = new CompositeView(
			true,
			'Liste des clés',
			'Cette page permet de modifier et/ou supprimer des clés.',
			"keys",
			array("sweetAlert" => "https://cdn.jsdelivr.net/sweetalert2/6.6.2/sweetalert2.min.css"),
			array("deleteKeyScript" => "app/View/assets/custom/scripts/deleteKey.js",
				"sweetAlert" => "https://cdn.jsdelivr.net/sweetalert2/6.6.2/sweetalert2.min.js",
				"tableFilterScript" => "app/View/assets/custom/scripts/table-filter.js"
			));

		if ($messages != null) {
			foreach ($messages as $message) {
				if (!empty($message['type']) && !empty($message['message'])) {
					$submit_message = new View("submit_message.html.twig", array("alert_type" => $message['type'] , "alert_message" => $message['message']));
					$compositeView->attachContentView($submit_message);
				}
			}
		}

		$list_keys = new View("keys/list_keys.html.twig", array('keys' => $keys, 'locks' => $locks));
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
			!isset($_POST['key_supplier']) &&
			!isset($_POST['key_copies'])) {

			$this->displayForm();
		}

		// if some (but not all) values are posted -> error message
		elseif (empty($_POST['key_name']) ||
			empty($_POST['key_type']) ||
			empty($_POST['key_locks']) ||
			empty($_POST['key_supplier']) ||
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

			// if the key is total, we add all locks.
			if ( addslashes($_POST['key_type']) == 'total') {
				$locks = $this->_lockService->getLocks();
				$_POST['key_locks'] = $locks;
			}

			// unicity check
			$exist = $this->checkUnicity($id);

			if (!$exist) {

				$keyToSave = array(
					'key_id' => $id,
					'key_name' => addslashes($_POST['key_name']),
					'key_type' => addslashes($_POST['key_type']),
					'key_locks' => $_POST['key_locks'],
					'key_supplier' => addslashes($_POST['key_supplier']),
					'key_copies' => addslashes($_POST['key_copies'])
				);

				$this->saveKey($keyToSave);

				$m_type = "success";
				$m_message = "La clé a bien été créée.";
				$message['type'] = $m_type;
				$message['message'] = $m_message;

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
	 * Display form used to create a key
	 * @param null $message array of the message displays
	 */
	public function displayForm($messages = null) {

		$locks = $this->getLocks();

		$compositeView = new CompositeView(
			true,
			'Ajouter une clé',
			null,
			"keys",
			null,
			array("chooseKey" => "app/View/assets/custom/scripts/chooseKey.js"));

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

			if ($this->deleteKey(urldecode($_POST['value'])) == true) {
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
			$response['message'] = 'This failed ';
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
			$key = $this->getKey($_POST['update']);
			$this->displayUpdateForm($key);
		}

		// if all values were posted (= form submission)
		elseif (isset($_POST['key_name']) &&
			isset($_POST['key_type']) &&
			isset($_POST['key_locks']) &&
			isset($_POST['key_supplier']) &&
			isset($_POST['key_copies'])) {

			$keyToUpdate = array(
				'key_id' => $_POST['key_id'],
				'key_name' => addslashes($_POST['key_name']),
				'key_type' => addslashes($_POST['key_type']),
				'key_locks' => $_POST['key_locks'],
				'key_supplier' => addslashes($_POST['key_supplier']),
				'key_copies' => addslashes($_POST['key_copies']));

			if ($this->updateKey($keyToUpdate) == false) {
				$message['type'] = 'danger';
				$message['message'] = 'Erreur lors de la modification de la clé.';
				$this->displayList(array($message));
			}
			else {
				$message['type'] = 'success';
				$message['message'] = 'La clé a bien été modifiée.';
				$this->displayList(array($message));
			}
		}

		else {

			$this->list();

		}
	}

	/**
	 * @param $state
	 * @param $datas
	 * @param null $messages
	 */
	public function displayUpdateForm($key, $messages = null) {

		$locks = $this->_lockService->getLocks();

		$composite = new CompositeView(
			true,
			'Mettre à jour une clé',
			null,
			"keys");

		if ($messages != null) {

			foreach ($messages as $message) {
				if (!empty($message['type']) && !empty($message['message'])) {
					$message = new View("submit_message.html.twig", array("alert_type" => $message['type'] , "alert_message" => $message['message']));
					$composite->attachContentView($message);
				}
			}
		}

		$update_key = new View('keys/update_key.html.twig', array('locks' => $locks, 'keys' => $key, 'previousUrl' => getPreviousUrl()));
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
	 * @param $keyToUpdate
	 */
	private function updateKey($keyToUpdate) {

		return $this->_keyService->updateKey($keyToUpdate);
	}

	/**
	 * @param $id
	 * @return mixed
	 */
	private function checkUnicity($id) {

		return $this->_keyService->checkUnicity($id);
	}
}
