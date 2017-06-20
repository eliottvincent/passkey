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

		$compositeView = new CompositeView(
			true,
			'Liste des emprunts',
			'Cette page permet de modifier et/ou supprimer des emprunts.',
			"borrowing",
			array("sweetAlert" => "https://cdn.jsdelivr.net/sweetalert2/6.6.2/sweetalert2.min.css"),
			array("deleteKeyScript" => "app/View/assets/custom/scripts/deleteBorrowing.js",
			 	"extendBorrowingScript" => "app/View/assets/custom/scripts/extendBorrowing.js",
				"sweetAlert" => "https://cdn.jsdelivr.net/sweetalert2/6.6.2/sweetalert2.min.js",
				"borrowingsScript" => "app/View/assets/custom/scripts/list_borrowings.js"));

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
				$m_message = "L'emprunt a bien été créée." .$link;
				//$link = "<a href=\"./?action=pdftest\" >";
				$message['type'] = $m_type;
				$message['message'] = $m_message;
				$message['link']="true";
				$message['link_href']="./?action=testpdf&keyname=".$borrowingToSave['borrowing_keychain']."&user=".$borrowingToSave['borrowing_user'];
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
			"borrowing");

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

   session_start();

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
			"borrowing",
			array("bootstrap-datetimepicker" => "app/View/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"),
			array("form-datetime-picker" => "app/View/assets/custom/scripts/update-forms-datetime-picker.js",
				"bootstrap-datetimepicker" => "app/View/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js")
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

	public function detailed($id) {
		$borrow = $this->getBorrowing($id);
		$number = explode("b_", $id)[1]; // [0] is empty

		// Get the name of user.
		$u = $borrow->getUser();
		$users = $this->getUsers();
		$currentUser = null;
		foreach($users as $user) {
			$uid = $user->getUr1identifier();
			if ($uid == $u) {
				$currentUser = $user;
			}
		}

		if (isset($currentUser) && !empty($currentUser)) {
			$currentUser = $currentUser->getSurname() . " " . $currentUser->getName();
		}

		// Format dates.
		$dBorrow = date('d/m/Y', strtotime($borrow->getBorrowDate()));
		$dDue = date('d/m/Y', strtotime($borrow->getBorrowDate()));

		// State.
		switch($borrow->getStatus()) {
			case "borrowed":
				$status = "en cours";
				break;
			case "late":
				$status = "en retard";
				break;
			case "returned":
				$status = "rendu";
				break;
			case "lost":
				$status = "perdu";
				break;
			default:
				$status = "n'existe pas";
				break;
		}


		$composite = new CompositeView(
			true,
			"Détail de l'emprunt",
			null,
			"borrowing"
		);

		$detailed_borrowing = new View('borrowings/detailed_borrowing.html.twig',
			array(
				'borrow' => $borrow,
				'number' => $number,
				'user' => $currentUser,
				'borrowDate' => $dBorrow,
				'dueDate' => $dDue,
				'status' => $status
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
