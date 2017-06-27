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
		$this->_userService = implementationUserService_Dummy::getInstance();
		$this->_keychainService = implementationKeychainService_Dummy::getInstance();
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
			$this->displayList();
		}
		else {
			$message['type'] = 'danger';
			$message['message'] = 'Nous n\'avons aucun emprunt d\'enregistré.';
			$this->displayList(array($message));
		}
	}

	/**
	 * Display list of borrowings.
	 * @param null $messages
	 * @internal param null $message array of the message displays
	 */
	public function displayList($messages = null) {

		$borrowings = $this->getBorrowings();
		$users = $this->getUsers();

		$compositeView = new CompositeView(
			true,
			'Liste des emprunts',
			'Cette page permet de modifier et/ou supprimer des emprunts.',
			"borrowings",
			array("sweetAlert" => "https://cdn.jsdelivr.net/sweetalert2/6.6.2/sweetalert2.min.css"),
			array("borrowingButtons" => "app/View/assets/custom/scripts/borrowingButtons.js",
				"sweetAlert" => "https://cdn.jsdelivr.net/sweetalert2/6.6.2/sweetalert2.min.js",
				"tableFilterScript" => "app/View/assets/custom/scripts/table-filter.js"));

		if ($messages != null) {
			foreach ($messages as $message) {
				if (!empty($message['type']) && !empty($message['message'])) {
					$data = array("alert_type" => $message['type'],
						"alert_message" => $message['message']);
					if (isset($message['link']) &&
						isset($message['link_href']) &&
						isset($message['link_text'])) {
						$data['alert_link'] = $message['link'];
						$data['alert_link_href'] = $message['link_href'];
						$data['alert_link_text'] = $message['link_text'];
					}
					$message = new View("submit_message.html.twig", $data);
					$compositeView->attachContentView($message);
				}
			}
		}

		$list_borrowings = new View("borrowings/list_borrowings.html.twig", array('borrowings' => $borrowings, 'users' => $users));
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
			$message['link']="false";
			$message['link_href']="";
			$message['link_text']="";

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
				$link = "<a href=\"./?action=pdftest\" class=\"alert-link\"> test </a>";
				$m_message = "L'emprunt a bien été créé.";
				//$link = "<a href=\"./?action=pdftest\" >";
				$m_message = "L'emprunt a bien été créé.";
				$message['type'] = $m_type;
				$message['message'] = $m_message;
				$message['link']="true";
				$message['link_href']="./?action=generatePDF&keyname=".$borrowingToSave['borrowing_keychain']."&user=".$borrowingToSave['borrowing_user']."&borid=".$borrowingToSave['borrowing_id'];
				$message['link_text']="Vous pouvez récupérer le PDF de l'emprunt en cliquant ici";

				$this->displayForm(array($message));

			}
			else {
				$m_type = "danger";
				$m_message = "Un emprunt avec le même nom existe déjà.";
				$message['type'] = $m_type;
				$message['message'] = $m_message;
				$message['link']="false";
				$message['link_href']="";
				$message['link_text']="";

				$this->displayForm(array($message));
			}
		}
	}

	/**
	 * Display form used to create a borrowing
	 * @param null $message array of the message displays
	 */
	public function displayForm($messages = null) {

		$keychains = $this->getKeychains();
		$users = $this->getUsers();

		$compositeView = new CompositeView(
			true,
			'Ajouter un emprunt',
			null,
			"borrowings");

		if ($messages != null) {
			foreach ($messages as $message) {
				if (!empty($message['type']) && !empty($message['message'])) {
					$data = array("alert_type" => $message['type'],
						"alert_message" => $message['message']);
					if (isset($message['link']) &&
						isset($message['link_href']) &&
						isset($message['link_text'])) {
						$data['alert_link'] = $message['link'];
						$data['alert_link_href'] = $message['link_href'];
						$data['alert_link_text'] = $message['link_text'];
					}
					$message = new View("submit_message.html.twig", $data);
					$compositeView->attachContentView($message);
				}
			}
		}

		$create_borrowing = new View('borrowings/create_borrowing.html.twig', array('keychains' => $keychains, 'users' => $users, 'previousUrl' => getPreviousUrl()));
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

			if ($this->deleteBorrowing(urldecode($_POST['value'])) == true) {
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
	// EXTEND
	//================================================================================

	/**
	 *
	 */
	public function extendBorrowingAjax() {

		if (isset($_POST['value']) && isset($_POST['number'])) {

			if ($this->extendBorrowing(urldecode($_POST['value']), $_POST['number']) == true) {
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
			$borrowing = $this->getBorrowing($_POST['update']);
			$this->displayUpdateForm($borrowing);
		}

		// if all values were posted (= form submission)
		elseif (isset($_POST['borrowing_id']) &&
			isset($_POST['borrowing_user']) &&
			isset($_POST['borrowing_keychain']) &&
			isset($_POST['borrowing_borrowdate']) &&
			isset($_POST['borrowing_duedate']) &&
			isset($_POST['borrowing_returndate']) &&
			isset($_POST['borrowing_lostdate']) &&
			isset($_POST['borrowing_status'])) {

			$borrowingToUpdate = array(
				'borrowing_id' => $_POST['borrowing_id'],
				'borrowing_user' => addslashes($_POST['borrowing_user']),
				'borrowing_keychain' => addslashes($_POST['borrowing_keychain']),
				'borrowing_borrowdate' => addslashes($_POST['borrowing_borrowdate']),
				'borrowing_duedate' => addslashes($_POST['borrowing_duedate']),
				'borrowing_returndate' => addslashes($_POST['borrowing_returndate']),
				'borrowing_lostdate' => addslashes($_POST['borrowing_lostdate']),
				'borrowing_status' => addslashes($_POST['borrowing_status'])
			);

			if ($this->updateBorrowing($borrowingToUpdate) == false) {
				$message['type'] = 'danger';
				$message['message'] = 'Erreur lors de la modification de l\'emprunt.';
				$this->displayList(array($message));
			}
			else {
				$message['type'] = 'success';
				$message['message'] = 'L\'emprunt a bien été modifié.';
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
	public function displayUpdateForm($borrowing, $messages = null) {

		$keychains = $this->getKeychains();
		$users = $this->getUsers();
		$statuses = $this->getStatuses();

		$compositeView = new CompositeView(
			true,
			'Mettre à jour un emprunt',
			null,
			"borrowings",
			array(
				"bootstrap-datepicker" => "app/View/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css",
				"select2minCss" => "app/View/assets/custom/scripts/select2/css/select2.min.css",
				"select2bootstrap" => "app/View/assets/custom/scripts/select2/css/select2-bootstrap.min.css"
			),
			array("form-datetime-picker" => "app/View/assets/custom/scripts/update-forms-datetime-picker.js",
				"bootstrap-datepicker" => "app/View/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js",
				"chooseKey" => "app/View/assets/custom/scripts/chooseKey.js",
				"select2min" => "app/View/assets/custom/scripts/select2/js/select2.full.min.js",
				"customselect2" => "app/View/assets/custom/scripts/components-select2.js"
			)
		);

		if ($messages != null) {
			foreach ($messages as $message) {
				if (!empty($message['type']) && !empty($message['message'])) {
					$data = array("alert_type" => $message['type'],
						"alert_message" => $message['message']);
					if (isset($message['link']) &&
						isset($message['link_href']) &&
						isset($message['link_text'])) {
						$data['alert_link'] = $message['link'];
						$data['alert_link_href'] = $message['link_href'];
						$data['alert_link_text'] = $message['link_text'];
					}
					$message = new View("submit_message.html.twig", $data);
					$compositeView->attachContentView($message);
				}
			}
		}

		$update_borrowing = new View('borrowings/update_borrowing.html.twig', array('borrowing' => $borrowing, 'keychains' => $keychains, 'users' => $users, 'statuses' => $statuses, 'previousUrl' => getPreviousUrl()));
		$compositeView->attachContentView($update_borrowing);

		echo $compositeView->render();
	}

	//================================================================================
	// DETAILED
	//================================================================================

	public function detailed() {

		$id = $_GET['id'];
		$borrow = $this->getBorrowing($id);

		// Get the name of user.
		$userId = $borrow->getUser();

		$currentUser = $this->getUser($userId);

		if (isset($currentUser) && !empty($currentUser)) {
			$currentUser = $currentUser->getSurname() . " " . $currentUser->getName();
		}

		// Format dates.
		$dBorrow = date('Y-m-d', strtotime($borrow->getBorrowDate()));
		$dDue = date('Y-m-d', strtotime($borrow->getBorrowDate()));

		// State.
		switch($borrow->getStatus()) {
			case "en cours":
				$status = "en cours";
				break;
			case "en retard":
				$status = "en retard";
				break;
			case "rendu":
				$status = "rendu";
				break;
			case "perdu":
				$status = "perdu";
				break;
			default:
				$status = "n'existe pas";
				break;
		}

		// Rooms.
		$rooms = $this->_borrowingService->getOpenedRooms($id);

		// Keys.
		$keys = $this->_borrowingService->getKeysInBorrow($id);

		$composite = new CompositeView(
			true,
			"Détail de l'emprunt",
			null,
			"borrowings"
		);

		$detailed_borrowing = new View('borrowings/detailed_borrowing.html.twig',
			array(
				'borrow' => $borrow,
				'user' => $currentUser,
				'borrowDate' => $dBorrow,
				'dueDate' => $dDue,
				'status' => $status,
				'rooms' => $rooms,
				'keys' => $keys
			)
		);
		$composite->attachContentView($detailed_borrowing);

		echo $composite->render();

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
	 * @return mixed
	 * @internal param $id
	 */
	public function getUsers() {

		return $this->_userService->getUsers();
	}

	/**
	 * @param $id
	 * @return mixed
	 */
	public function getUser($id) {

		return $this->_userService->getUser($id);
	}

	/**
	 * @param $id
	 * @return mixed
	 */
	public function getKeychains() {

		return $this->_keychainService->getKeychains();
	}

	/**
	 * @param $borrowingToSave
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
	 * Used to extend a borrowing from an id with number day(s).
	 * @param $id, $number
	 */
	private function extendBorrowing($id, $number) {

		return $this->_borrowingService->extendBorrowing($id, $number);
	}

	/**
	 * @param $borrowingToUpdate
	 */
	private function updateBorrowing($borrowingToUpdate) {

		return $this->_borrowingService->updateBorrowing($borrowingToUpdate);
	}

	/**
	 * @param $id
	 * @return mixed
	 */
	private function checkUnicity($id) {

		return $this->_borrowingService->checkUnicity($id);
	}

	/**
	 *
	 */
	private function getStatuses() {

		return $this->_borrowingService->getStatuses();
	}
}
