<?php

/**
 * CCROISLE
 */
 require_once 'app/Model/Service/implementationBorrowService_Dummy.php';


class KeychainController
{//Todo : tous sauf list
	private $_keychainService;

	public function __construct()
	{
		$this->_keychainService = implementationKeyChainService_Dummy::getInstance();
	}

public function create(){
	if (!isset($_POST['keychain_name']) && !isset($_POST['key_keychain'])) {
		// If we have no values, the form is displayed.
		$this->displayForm();
	} elseif (empty($_POST['keychain_name']) || empty($_POST['key_keychain'])){
		// If we have not all values, error message display and form.
		$m_type = "danger";
		$m_message = "Toutes les valeurs nécessaires n'ont pas été trouvées. Merci de compléter tous les champs.";
		$message['type'] = $m_type;
		$message['message'] = $m_message;
		$this->displayForm( $message);
	} else {
		// If we have all values, the form is displayed.

			$this->_keychainService->createKeychain($_POST['keychain_name'], $_POST['key_keychain']);

			$m_type = "success";
			$m_message = "L'emprunt a bien été créée.";
			$message['type'] = $m_type;
			$message['message'] = $m_message;
			$this->displayForm($message);
	}
}

public function displayForm($message = null) {
	$composite = new CompositeView(true, 'Créer un nouveau trousseau');

	if ($message != null && !empty($message['type']) && !empty($message['message'])) {
		$message = new View(null, null, "submit_message.html.twig", array("alert_type" => $message['type'] , "alert_message" => $message['message']));
		$composite->attachContentView($message);
	}

	$create_keychain = new View(null,null, 'keychains/create_keychain.html.twig');
	$composite->attachContentView($create_keychain);

	echo $composite->render();
}

	public function list(){
		if (isset($_GET['delete']) && !empty($_GET['delete'])) {
			$id = explode('delete_k', $_GET['delete'])[1];
			$delete = $this->deleteKey($id);

			if ($delete) {
				$this->displayDeleteKey('success', 'La clé a bien été supprimée');
			} else {
				$this->displayDeleteKey('danger', 'La clé n\'existe pas.');
			}

		} else {
			if (true) {
				$this->displayList(true);
			} else {
				$alert['type'] = 'danger';
				$alert['message'] = 'Aucun emprunt n\'a été fait.';
				$this->displayList(false, $alert);
			}
		}
	}

	// TODO
	public function deleteKey($id) {

	}

	/**
	 * Display list of borrowings.
	 * @param null $message array of the message displays
	 */
	public function displayList($message = null) {
		$keychains = $this->_keychainService->getKeychains();
		$composite = new CompositeView(true, 'Liste des trousseaux', null, "keychains");

		if ($message != null && !empty($message['type']) && !empty($message['message'])) {
			$submit_message = new View(null, null, "submit_message.html.twig", array("alert_type" => $message['type'] , "alert_message" => $message['message']));
			$composite->attachContentView($submit_message);
		}
		$list_keychains = new View(null, null,"keychains/list_keychains.html.twig", array('keychains' => $this->_keychainService->getKeychains()));
		$composite->attachContentView($list_keychains);

		echo $composite->render();
	}
}
