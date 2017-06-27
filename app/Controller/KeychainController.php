<?php


class KeychainController
{

	//================================================================================
	// constructor
	//================================================================================


	/**
	 * KeychainController constructor.
	 */
	public function __construct() {

		$this->_keychainService = implementationKeychainService_Dummy::getInstance();
		$this->_keyService = implementationKeyService_Dummy::getInstance();
	}


	//================================================================================
	// LIST
	//================================================================================


	/**
	 * used to list all keychains
	 */
	public function list() {

		$keychains = $this->getKeychains();

		if (!empty($keychains)) {
			$this->displayList();
		}
		else {
			$message['type'] = 'danger';
			$message['message'] = 'Nous n\'avons aucun trousseau d\'enregistré.';
			$this->displayList(array($message));
		}
	}


	/**
	 * @param null $messages
	 * @internal param $state
	 */
	public function displayList($messages = null) {

		$keychains = $this->getKeychains();
		$keys = $this->getKeys();

		$compositeView = new CompositeView(
			true,
			'Liste des trousseaux',
			null,
			"keychains",
			array("sweetAlert" => "https://cdn.jsdelivr.net/sweetalert2/6.6.2/sweetalert2.min.css"),
			array("keychainButtonsScript" => "app/View/assets/custom/scripts/keychainButtons.js",
				"tableFilterScript" => "app/View/assets/custom/scripts/table-filter.js",
				"sweetAlert" => "https://cdn.jsdelivr.net/sweetalert2/6.6.2/sweetalert2.min.js"));

		if ($messages != null) {
			foreach ($messages as $message) {
				if (!empty($message['type']) && !empty($message['message'])) {
					$submit_message = new View("submit_message.html.twig", array("alert_type" => $message['type'] , "alert_message" => $message['message']));
					$compositeView->attachContentView($submit_message);
				}
			}
		}

		$list_keychains = new View("keychains/list_keychains.html.twig", array('keychains' => $keychains, 'keys' => $keys));
		$compositeView->attachContentView($list_keychains);

		echo $compositeView->render();
	}


	//================================================================================
	// CREATE
	//================================================================================

	public function create() {

		// if no values are posted -> displaying the form
		if (!isset($_POST['keychain_name']) &&
			!isset($_POST['keychain_keys'])) {

			$this->displayForm();
		}

		// if some (but not all) values are posted -> error message
		elseif (empty($_POST['keychain_name']) ||
			empty($_POST['keychain_keys'])) {

			$m_type = "danger";
			$m_message = "Toutes les valeurs nécessaires n'ont pas été trouvées. Merci de compléter tous les champs.";
			$message['type'] = $m_type;
			$message['message'] = $m_message;

			$this->displayForm(array($message));
		}

		// if we have all values, we can create the keychain
		else {

			// id generation
			$id = 'kc_' . strtolower(str_replace(' ', '_', addslashes($_POST['keychain_name'])));

			// unicity check
			$exist = $this->checkUnicity($id);

			if (!$exist) {
				$keychainToSave = array(
					'keychain_id' => $id,
					'keychain_name' => addslashes($_POST['keychain_name']),
					'keychain_keys' => $_POST['keychain_keys']
				);

				$this->saveKeychain($keychainToSave);

				$m_type = "success";
				$m_message = "Le trousseau a bien été créé.";
				$message['type'] = $m_type;
				$message['message'] = $m_message;

				$this->displayForm(array($message));

			}
			else {
				$m_type = "danger";
				$m_message = "Un trousseau avec le même nom existe déjà.";
				$message['type'] = $m_type;
				$message['message'] = $m_message;

				$this->displayForm(array($message));
			}
		}
	}

	/**
	 * Display form used to create a keychain
	 * @param null $message array of the message displays
	 */
	public function displayForm($messages = null) {

		$keys = $this->getKeys();

		$compositeView = new CompositeView(
			true,
			'Ajouter un trousseau',
			null,
			"keychains",
			array(
				"select2minCss" => "app/View/assets/custom/scripts/select2/css/select2.min.css",
				"select2bootstrap" => "app/View/assets/custom/scripts/select2/css/select2-bootstrap.min.css"
			),
			array("select2min" => "app/View/assets/custom/scripts/select2/js/select2.full.min.js",
				"customselect2" => "app/View/assets/custom/scripts/components-select2.js"));

		if ($messages != null) {
			foreach ($messages as $message) {
				if (!empty($message['type']) && !empty($message['message'])) {
					$message = new View("submit_message.html.twig", array("alert_type" => $message['type'] , "alert_message" => $message['message']));
					$compositeView->attachContentView($message);
				}
			}
		}

		$create_keychain = new View('keychains/create_keychain.html.twig', array('keys' => $keys, 'previousUrl' => getPreviousUrl()));
		$compositeView->attachContentView($create_keychain);

		echo $compositeView->render();
	}

	//================================================================================
	// DELETE
	//================================================================================

	/**
	 *
	 */
	public function deleteKeychainAjax() {

		session_start();

		if (isset($_POST['value'])) {

			if ($this->deleteKeychain(urldecode($_POST['value'])) == true) {
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
			$keychain = $this->getKeychain($_POST['update']);
			$this->displayUpdateForm($keychain);
		}

		// if all values were posted (= form submission)
		elseif (isset($_POST['keychain_id']) &&
			isset($_POST['keychain_name']) &&
			isset($_POST['keychain_keys'])) {

			$keychainToUpdate = array(
				'keychain_id' => $_POST['keychain_id'],
				'keychain_name' => addslashes($_POST['keychain_name']),
				'keychain_keys' => $_POST['keychain_keys'],
				'keychain_creationdate' => addslashes($_POST['keychain_creationdate']),
				'keychain_destructiondate' => addslashes($_POST['keychain_destructiondate'])
			);

			if ($this->updateKeychain($keychainToUpdate) == false) {
				$message['type'] = 'danger';
				$message['message'] = 'Erreur lors de la modification du trousseau.';
				$this->displayList(array($message));
			}
			else {
				$message['type'] = 'success';
				$message['message'] = 'Le trousseau a bien été modifié.';
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
	public function displayUpdateForm($keychain, $messages = null) {

		$keys = $this->getKeys();

		$composite = new CompositeView(
			true,
			'Mettre à jour un trousseau',
			null,
			"keychains",
			array("bootstrap-datetimepicker" => "app/View/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css",
				"select2minCss" => "app/View/assets/custom/scripts/select2/css/select2.min.css",
				"select2bootstrap" => "app/View/assets/custom/scripts/select2/css/select2-bootstrap.min.css"
			),
			array(
				"chooseKey" => "app/View/assets/custom/scripts/chooseKey.js",
				"select2min" => "app/View/assets/custom/scripts/select2/js/select2.full.min.js",
				"customselect2" => "app/View/assets/custom/scripts/components-select2.js",
				"form-datetime-picker" => "app/View/assets/custom/scripts/update-forms-datetime-picker.js",
				"bootstrap-datetimepicker" => "app/View/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js")
		);

		if ($messages != null) {
			foreach ($messages as $message) {
				if (!empty($message['type']) && !empty($message['message'])) {
					$message = new View("submit_message.html.twig", array("alert_type" => $message['type'] , "alert_message" => $message['message']));
					$composite->attachContentView($message);
				}
			}
		}

		$update_keychain= new View('keychains/update_keychain.html.twig', array('keychain' => $keychain, 'keys' => $keys, 'previousUrl' => getPreviousUrl()));
		$composite->attachContentView($update_keychain);

		echo $composite->render();
	}


	//================================================================================
	// DUPLICATE
	//================================================================================


	public function duplicateKeychainAjax() {

		if (isset($_POST['value'])) {

			if ($this->duplicateKeychain(urldecode($_POST['value']), urldecode($_POST['name'])) == true) {
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
	 * To get all keychains
	 * @return null
	 */
	public function getKeychains() {

		return $this->_keychainService->getKeychains();
	}

	/**
	 * @param $id
	 * @return mixed
	 */
	public function getKeychain($id) {

		return $this->_keychainService->getKeychain($id);
	}


	/**
	 * @param $id
	 * @return mixed
	 */
	public function getKeys() {

		return $this->_keyService->getKeys();
	}

	/**
	 * @param $keychainToSave
	 */
	private function saveKeychain($keychainToSave) {

		$this->_keychainService->saveKeychain($keychainToSave);
	}


	/**
	 * Used to delete a keychain from an id.
	 * @param $id
	 */
	private function deleteKeychain($id) {

		return $this->_keychainService->deleteKeychain($id);
	}

	/**
	 * @param $keychainToUpdate
	 */
	private function updateKeychain($keychainToUpdate) {

		return $this->_keychainService->updateKeychain($keychainToUpdate);
	}

	/**
	 * @param $id
	 * @return mixed
	 */
	private function duplicateKeychain($id, $name) {

		return $this->_keychainService->duplicationKeychain($id, $name);
	}


	/**
	 * @param $id
	 * @return mixed
	 */
	private function checkUnicity($id) {

		return $this->_keychainService->checkUnicity($id);
	}

}
