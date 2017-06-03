<?php

/**
 * CCROISLE
 */
 require_once 'app/Model/Service/implementationBorrowService_Dummy.php';


class BorrowingsController
{//Todo : tous sauf list
	private $_borrowService;

	public function __construct()
	{
		$this->_borrowService = implementationBorrowService_Dummy::getInstance();
	}

public function create(){
	if (!isset($_POST['borrower']) && !isset($_POST['keychain'])) {
		// If we have no values, the form is displayed.
		$this->displayForm();
	} elseif (empty($_POST['borrower']) || empty($_POST['keychain'])){
		// If we have not all values, error message display and form.
		$m_type = "danger";
		$m_message = "Toutes les valeurs nécessaires n'ont pas été trouvées. Merci de compléter tous les champs.";
		$message['type'] = $m_type;
		$message['message'] = $m_message;
		$this->displayForm( $message);
	} else {
		// If we have all values, the form is displayed.


			$borrowings = $this->_borrowService->getBorrowings();

			$this->_borrowService->borrowKeychain($_POST['borrower'], $_POST['keychain']);

			$m_type = "success";
			$m_message = "L'emprunt a bien été créée.";
			$message['type'] = $m_type;
			$message['message'] = $m_message;
			$this->displayForm($message);
	}
}

public function displayForm($message = null) {
	$composite = new CompositeView(true, 'Créer un nouvel emprunt');

	if ($message != null && !empty($message['type']) && !empty($message['message'])) {
		$message = new View("submit_message.html.twig", array("alert_type" => $message['type'] , "alert_message" => $message['message']));
		$composite->attachContentView($message);
	}

	$create_door = new View('borrowings/create_borrowing.html.twig');
	$composite->attachContentView($create_door);

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
		$borrowings = $this->_borrowService->getBorrowings();
		$composite = new CompositeView(true, 'Liste des emprunts', null, "borrowings");

		if ($message != null && !empty($message['type']) && !empty($message['message'])) {
			$submit_message = new View("submit_message.html.twig", array("alert_type" => $message['type'] , "alert_message" => $message['message']));
			$composite->attachContentView($submit_message);
		}
		$list_borrowings = new View("borrowings/list_borrowings.html.twig", array('borrowings' => $this->_borrowService->getBorrowings()));
		$composite->attachContentView($list_borrowings);

		echo $composite->render();
	}
}
