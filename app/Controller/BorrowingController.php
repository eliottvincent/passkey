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
		$this->_keyService = implementationKeyService_Dummy::getInstance();
		$this->_userService = implementationUserService_Dummy::getInstance();
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

	public function create() {

		// if no values are posted -> displaying the form
		if (!isset($_POST['borrowing_user']) &&
			!isset($_POST['borrowing_keychain'])) {

			$this->displayForm();
		}

		// if some (but not all) values are posted -> error message
		elseif (empty($_POST['borrowing_user']) ||
			empty($_POST['borrowing_keychain'])) {

			$m_type = "danger";
			$m_message = "Toutes les valeurs nécessaires n'ont pas été trouvées. Merci de compléter tous les champs.";
			$message['type'] = $m_type;
			$message['message'] = $m_message;

			$this->displayForm(array($message));
		}

		// if we have all values, we can create the borrowing
		else {

			// id generation
			$id = 'b_'
				. strtolower(str_replace(' ', '_', addslashes($_POST['borrowing_user'])))
				. strtolower(str_replace(' ', '_', addslashes($_POST['borrowing_keychain'])));

				// unicity check
			$exist = $this->checkUnicity($id);

			if (!$exist) {
				$borrowingToSave = array(
					'borrowing_id' => $id,
					'borrowing_user' => addslashes($_POST['borrowing_user']),
					'borrowing_keychain' => addslashes($_POST['borrowing_keychain'])
				);

				$this->saveBorrowing($borrowingToSave);

				$m_type = "success";
				$m_message = "L'emprunt a bien été créée.";
				$message['type'] = $m_type;
				$message['message'] = $m_message;

				$this->displayForm(array($message));

			}
			else {
				$m_type = "danger";
				$m_message = "Un emprunt avec le même nom existe déjà.";
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
		$users = $this->getUsers();

		$compositeView = new CompositeView(
			true,
			'Ajouter un emprunt',
			null,
			"borrowing");

		if ($messages != null) {
			foreach ($messages as $message) {
				if (!empty($message['type']) && !empty($message['message'])) {
					$message = new View("submit_message.html.twig", array("alert_type" => $message['type'] , "alert_message" => $message['message']));
					$compositeView->attachContentView($message);
				}
			}
		}

		$create_borrowing = new View('borrowings/create_borrowing.html.twig', array('keys' => $keys, 'users' => $users, 'previousUrl' => getPreviousUrl()));
		$compositeView->attachContentView($create_borrowing);

		echo $compositeView->render();
	}


	//================================================================================
	// DELETE
	//================================================================================

	/**
	 *
	 */
	public function deleteBorrowingAjax() {

		session_start();

		if (isset($_POST['value'])) {

			if ($this->deleteBorrowing($_POST['value']) == true) {
				$response['borrowings'] = $this->getBorrowings();
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

	/**
	 * @param $id
	 * @return mixed
	 */
	public function getUsers() {

		return $this->_userService->getUsers();
	}

	/**
	 * @param $id
	 * @return mixed
	 */
	public function getKeys() {

		return $this->_keyService->getKeys();
	}

	/**
	 * @param $keyToSave
	 */
	private function saveBorrowing($borrowingToSave) {

		$this->_borrowingService->saveBorrowing($borrowingToSave);
	}


	/**
	 * Used to delete a borrowing from an id.
	 * @param $id
	 */
	private function deleteBorrowing($id) {

		return $this->_borrowingService->deleteBorrowing($id);
	}

	/**
	 * @param $id
	 * @return mixed
	 */
	private function checkUnicity($id) {

		return $this->_borrowingService->checkUnicity($id);
	}
}
