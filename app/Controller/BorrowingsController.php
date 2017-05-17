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


	/**
	 * use to list keys
	 */
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
		$composite = new CompositeView(true, 'Liste des emprunts');

		if ($message != null && !empty($message['type']) && !empty($message['message'])) {
			$submit_message = new View(null, null, "submit_message.html.twig", array("alert_type" => $message['type'] , "alert_message" => $message['message']));
			$composite->attachContentView($submit_message);
		}
		$list_borrowings = new View(null, null,"borrowings/list_borrowings.html.twig", array('borrowings' => $borrowings));
		$composite->attachContentView($list_borrowings);

		echo $composite->render();
	}
}
