<?php

/**
 * Created by PhpStorm.
 * User: eliottvincent
 * Date: 30/05/2017
 * Time: 15:28
 */
class UserController {

	//================================================================================
	// constructor
	//================================================================================

	/**
	 * UserController constructor.
	 */
	public function __construct() {
		$this->_userService = implementationUserService_Dummy::getInstance();
	}


	//================================================================================
	// LIST
	//================================================================================

	/**
	 * used to list users
	 */
	public function list() {

		$users = $this->getUsers();

		if (!empty($users)) {

			$this->displayList();
		}
		else {
			$message['type'] = 'danger';
			$message['message'] = 'Nous n\'avons aucun utilisateur d\'enregistré.';
			$this->displayList(array($message));
		}
	}

	/**
	 * Display list of users.
	 * @param null $messages
	 * @internal param bool $state if file datas/datas.xlsx exists
	 * @internal param null $message array of the message displays
	 */
	public function displayList($messages = null) {

		$users = $this->getUsers();

		$compositeView = new CompositeView(
			true,
			'Liste des utilisateurs',
			'Cette page permet de modifier et/ou supprimer des utilisateurs.',
			"users",
			array("sweetAlert" => "https://cdn.jsdelivr.net/sweetalert2/6.6.2/sweetalert2.min.css"),
			array("deleteUserScript" => "app/View/assets/custom/scripts/deleteUser.js",
				"sweetAlert" => "https://cdn.jsdelivr.net/sweetalert2/6.6.2/sweetalert2.min.js",
				"borrowingsScript" => "app/View/assets/custom/scripts/list_borrowings.js"
			));

		if ($messages != null) {
			foreach ($messages as $message) {
				if (!empty($message['type']) && !empty($message['message'])) {
					$submit_message = new View("submit_message.html.twig", array("alert_type" => $message['type'] , "alert_message" => $message['message']));
					$compositeView->attachContentView($submit_message);
				}
			}
		}

		$list_users = new View("users/list_users.html.twig", array('users' => $users));
		$compositeView->attachContentView($list_users);

		echo $compositeView->render();
	}


	//================================================================================
	// CREATE
	//================================================================================

	/**
	 * to create a new user
	 */
	public function create() {

		// if no values are posted -> displaying the form
		if (!isset($_POST['user_enssatPrimaryKey']) &&
			!isset($_POST['user_ur1identifier']) &&
			!isset($_POST['user_username']) &&
			!isset($_POST['user_name']) &&
			!isset($_POST['user_surname']) &&
			!isset($_POST['user_status']) &&
			!isset($_POST['user_phone']) &&
			!isset($_POST['user_email'])) {

			$this->displayForm();
		}

		// if some (but not all) values are posted -> error message
		elseif (empty($_POST['user_enssatPrimaryKey']) ||
			empty($_POST['user_ur1identifier']) ||
			empty($_POST['user_username']) ||
			empty($_POST['user_name']) ||
			empty($_POST['user_surname']) ||
			empty($_POST['user_status']) ||
			empty($_POST['user_phone']) ||
			empty($_POST['user_email'])) {

			$m_type = "danger";
			$m_message = "Toutes les valeurs nécessaires n'ont pas été trouvées. Merci de compléter tous les champs.";
			$message['type'] = $m_type;
			$message['message'] = $m_message;
			$messages[] = $message;
			$this->displayForm($messages);
		}

		// if we have all values, we can create the user
		else {

			// unicity check
			$exist = $this->checkUnicity($_POST['user_enssatPrimaryKey']);

			if (!$exist) {
				$userToSave = array(
					'user_enssatPrimaryKey' => addslashes($_POST['user_enssatPrimaryKey']),
					'user_ur1identifier' => addslashes($_POST['user_ur1identifier']),
					'user_username' => addslashes($_POST['user_username']),
					'user_name' => addslashes($_POST['user_name']),
					'user_surname' => addslashes($_POST['user_surname']),
					'user_phone' => addslashes($_POST['user_phone']),
					'user_status' => addslashes($_POST['user_status']),
					'user_email' => addslashes($_POST['user_email']),
				);

				$this->saveUser($userToSave);

				$m_type = "success";
				$m_message = "L'utilisateur a bien été enregistré.";

				$message['type'] = $m_type;
				$message['message'] = $m_message;
				$messages[] = $message;

				$this->displayForm($messages);
			}
			else {
				$m_type = "danger";
				$m_message = "Un utilisateur avec le même identifiant ENSSAT existe déjà.";

				$message['type'] = $m_type;
				$message['message'] = $m_message;
				$messages[] = $message;

				$this->displayForm($messages);
			}

		}
	}

	/**
	 * Display form used to create a user
	 * @param null $message array of the message displays
	 */
	public function displayForm($messages = null) {
		$compositeView = new CompositeView(
			true,
			'Ajouter un utilisateur',
			null,
			"users",
			null,
			array("jQueryInputMask" => "app/View/assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js",
				"customMasks" => "app/View/assets/custom/scripts/form-input-mask.js"));

		if ($messages != null) {
			foreach ($messages as $message) {
				if (!empty($message['type']) && !empty($message['message'])) {
					$message = new View("submit_message.html.twig", array("alert_type" => $message['type'] , "alert_message" => $message['message']));
					$compositeView->attachContentView($message);
				}
			}
		}

		$create_user = new View('users/create_user.html.twig', array('previousUrl' => getPreviousUrl()));
		$compositeView->attachContentView($create_user);

		echo $compositeView->render();
	}


	//================================================================================
	// DELETE
	//================================================================================

	/**
	 *
	 */
	public function deleteUserAjax() {

		session_start();

		if (isset($_POST['value'])) {

			if ($this->deleteUser(urldecode($_POST['value'])) == true) {
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

		//
		if (isset($_POST['update']) && !empty($_POST['update'])) {
			$user = $this->getUser($_POST['update']);
			$this->displayUpdateForm($user);
		}

		// if all values were posted (= form submission)
		elseif (isset($_POST['user_enssatPrimaryKey']) &&
			isset($_POST['user_ur1identifier']) &&
			isset($_POST['user_username']) &&
			isset($_POST['user_name']) &&
			isset($_POST['user_surname']) &&
			isset($_POST['user_status']) &&
			isset($_POST['user_phone']) &&
			isset($_POST['user_email'])) {

			$userToUpdate = array(
				'user_enssatPrimaryKey' => addslashes($_POST['user_enssatPrimaryKey']),
				'user_ur1identifier' => addslashes($_POST['user_ur1identifier']),
				'user_username' => addslashes($_POST['user_username']),
				'user_name' => addslashes($_POST['user_name']),
				'user_surname' => addslashes($_POST['user_surname']),
				'user_phone' => addslashes($_POST['user_phone']),
				'user_status' => addslashes($_POST['user_status']),
				'user_email' => addslashes($_POST['user_email']),
			);

			if ($this->updateUser($userToUpdate) == false) {
				$message['type'] = 'danger';
				$message['message'] = 'Erreur lors de la modification de l\'utilisateur.';
				$this->displayList(array($message));
			}
			else {
				$message['type'] = 'success';
				$message['message'] = 'L\'utilisateur a bien été modifié.';
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
	public function displayUpdateForm($user, $messages = null) {

		$compositeView = new CompositeView(true, "Mettre à jour un utilisateur", null, "users");

		if ($messages != null) {

			foreach ($messages as $message) {
				if (!empty($message['type']) && !empty($message["message"])) {
					$message = new View("submit_message.html.twig", array("alert_type" => $message["type"] , "alert_message" => $message["message"]));
					$compositeView->attachContentView($message);
				}
			}
		}

		$update_user = new View("users/update_user.html.twig", array("users" => $user, "previousUrl" => getPreviousUrl()));
		$compositeView->attachContentView($update_user);

		echo $compositeView->render();
	}


	//================================================================================
	// calls to Service
	//================================================================================

	/**
	 * To get all users.
	 * @return null
	 */
	private function getUsers() {

		return $this->_userService->getUsers();
	}

	/**
	 * @param $enssatPrimaryKey
	 * @return mixed
	 */
	private function getUser($enssatPrimaryKey) {

		return $this->_userService->getUser($enssatPrimaryKey);
	}

	/**
	 * @param $userToSave
	 */
	private function saveUser($userToSave) {

		$this->_userService->saveUser($userToSave);
	}

	/**
	 * Used to delete a user from an id.
	 * @param $enssatPrimaryKey
	 */
	private function deleteUser($enssatPrimaryKey) {

		return $this->_userService->deleteUser($enssatPrimaryKey);
	}

	/**
	 * @param $userToUpdate
	 * @return mixed
	 */
	private function updateUser($userToUpdate) {

		return $this->_userService->updateUser($userToUpdate);
	}

	/**
	 * @param $enssatPrimaryKey
	 * @return mixed
	 */
	private function checkUnicity($enssatPrimaryKey) {

		return $this->_userService->checkUnicity($enssatPrimaryKey);
	}


}
