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
	 * used to list all rooms
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

		$compositeView = new CompositeView(
			true,
			'Liste des trousseaux',
			null,
			"keychain",
			array("sweetAlert" => "https://cdn.jsdelivr.net/sweetalert2/6.6.2/sweetalert2.min.css"),
			array("deleteKeychainScript" => "app/View/assets/custom/scripts/deleteKeychain.js",
				"sweetAlert" => "https://cdn.jsdelivr.net/sweetalert2/6.6.2/sweetalert2.min.js"));

		if ($messages != null) {
			foreach ($messages as $message) {
				if (!empty($message['type']) && !empty($message['message'])) {
					$submit_message = new View("submit_message.html.twig", array("alert_type" => $message['type'] , "alert_message" => $message['message']));
					$compositeView->attachContentView($submit_message);
				}
			}
		}

		$list_keychains = new View("keychains/list_keychains.html.twig", array('keychains' => $keychains));
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

		// if we have all values, we can create the borrowing
		else {

			// id generation
			$id = 'kc_';
			foreach ($_POST['keychain_keys'] as $keyId) {
				$id .= $keyId . "_";
			}

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
				$m_message = "Le trousseau a bien été créée.";
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
	 * Display form used to create a borrowing
	 * @param null $message array of the message displays
	 */
	public function displayForm($messages = null) {

		$keys = $this->getKeys();

		$compositeView = new CompositeView(
			true,
			'Ajouter un trousseau',
			null,
			"keychain");

		if ($messages != null) {
			foreach ($messages as $message) {
				if (!empty($message['type']) && !empty($message['message'])) {
					$message = new View("submit_message.html.twig", array("alert_type" => $message['type'] , "alert_message" => $message['message']));
					$compositeView->attachContentView($message);
				}
			}
		}

		$create_borrowing = new View('keychains/create_keychain.html.twig', array('keys' => $keys, 'previousUrl' => getPreviousUrl()));
		$compositeView->attachContentView($create_borrowing);

		echo $compositeView->render();
	}
	// calls to Service
	//================================================================================

	/**
	 * To get all keychains
	 * @return null
	 */
	public function getKeychains() {

		return $this->_keychainService->getKeychains();
	}
}
