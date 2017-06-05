<?php

class BorrowingController {


	//================================================================================
	// constructor
	//================================================================================

	/**
	 * BorrowingController constructor.
	 */
	public function __construct() {
		$this->_borrowingService = implementationBorrowingService_Dummy::getInstance();
	}

	//================================================================================
	// LIST
	//================================================================================

	/**
	 * used to list borrowings
	 */
	public function list() {

		$borrowings = $this->getBorrowings();

		if (!empty($borrowings)) {
			$this->displayList(true);
		}
		else {
			$message['type'] = 'danger';
			$message['message'] = 'Nous n\'avons aucun emprunt d\'enregistré.';
			$this->displayList(false, array($message));
		}
	}

	/**
	 * Display list of borrowings.
	 * @param null $message array of the message displays
	 */
	public function displayList($state, $messages = null) {

		if ($state) {
			$borrowings = $this->getBorrowings();
		} else {
			$borrowings = null;
		}

		$compositeView = new CompositeView(
			true,
			'Liste des emprunts',
			'Cette page permet de modifier et/ou supprimer des emprunts.',
			"borrowing",
			array("sweetAlert" => "https://cdn.jsdelivr.net/sweetalert2/6.6.2/sweetalert2.min.css"),
			array("deleteKeyScript" => "app/View/assets/custom/scripts/deleteBorrowing.js",
				"sweetAlert" => "https://cdn.jsdelivr.net/sweetalert2/6.6.2/sweetalert2.min.js"));

		if ($messages != null) {
			foreach ($messages as $message) {
				if (!empty($message['type']) && !empty($message['message'])) {
					$submit_message = new View("submit_message.html.twig", array("alert_type" => $message['type'] , "alert_message" => $message['message']));
					$compositeView->attachContentView($submit_message);
				}
			}
		}

		$list_borrowings = new View("borrowings/list_borrowings.html.twig", array('borrowings' => $borrowings));
		$compositeView->attachContentView($list_borrowings);

		echo $compositeView->render();
	}


	//================================================================================
	// CREATE
	//================================================================================

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
	// TODO

	public function deleteKey($id) {

	}

	//================================================================================
	// calls to Service
	//================================================================================

	/**
	 * @return array
	 */
	public function getBorrowings() {

		return $this->_borrowingService->getBorrowings();
	}

	/**
	 * @param $id
	 * @return mixed
	 */
	public function getBorrowing($id) {

		return $this->_borrowingService->getBorrowing($id);
	}
}
