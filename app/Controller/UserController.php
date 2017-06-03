<?php

/**
 * Created by PhpStorm.
 * User: eliottvincent
 * Date: 30/05/2017
 * Time: 15:28
 */
class UserController
{

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

		// if we have all values
		else {

			// Check unicity
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
	 * @param $state boolean if file datas/datas.xlsx exists
	 * @param null $message array of the message displays
	 */
	public function displayForm($messages = null) {
		$composite = new CompositeView(true, 'Ajouter un utilisateur', null, "user");

		if ($messages != null) {
			foreach ($messages as $message) {
				if (!empty($message['type']) && !empty($message['message'])) {
					$message = new View("submit_message.html.twig", array("alert_type" => $message['type'] , "alert_message" => $message['message']));
					$composite->attachContentView($message);
				}
			}
		}

		$create_user = new View('users/create_user.html.twig', array('previousUrl' => getPreviousUrl()));
		$composite->attachContentView($create_user);

		echo $composite->render();
	}

	/**
	 * use to list users
	 */
	public function list($delete = null) {
		if (isset($_POST['delete']) && !empty($_POST['delete'])) {
			$delete = $this->deleteUser(addslashes($_POST['delete']));
			if ($delete) {
				$message['type'] = 'success';
				$message['message'] = 'L\'utilisateur a bien été supprimé';
				$messages[] = $message;

				if(!isset($_SESSION['USERS'])) {
					$message['type'] = 'danger';
					$message['message'] = 'Nous n\'avons aucun utilisateur d\'enregistré.';
					$messages[] = $message;
				}
				if (!empty($this::getUsers())) {
					$this->displayList(true, $messages);
				} else {
					$this->displayList(false, $messages);
				}
			} else {
				$message['type'] = 'danger';
				$message['message'] = 'L\'utilisateur n\'existe pas.';
				$messages[] = $message;
				if (!empty($this::getUsers())) {
					$this->displayList(true, $messages);
				} else {
					$this->displayList(false, $messages);
				}
			}

		} else {
			$users = $this::getUsers();
			if (!empty($users)) {
				if (isset($_GET['update']) && $_GET['update'] == true) {
					$alert['type'] = 'success';
					$alert['message'] = "L'utilisateur a bien été modifié.";
					$alerts[] = $alert;

					$this->displayList(true, $alerts);
				} else {
					$this->displayList(true);
				}

			} else {
				$alert['type'] = 'danger';
				$alert['message'] = 'Nous n\'avons aucun utilisateur d\'enregistré.';
				$alerts[] = $alert;
				$this->displayList(false, $alerts);
			}
		}
	}


	//================================================================================
	// DELETE
	//================================================================================

	/**
	 * Display list of users.
	 * @param $state boolean if file datas/datas.xlsx exists
	 * @param null $message array of the message displays
	 */
	public function displayList($state, $messages = null) {
		if ($state) {
			$users = UserController::getUsers();
		} else {
			$users = null;
		}
		$composite = new CompositeView(true, 'Liste des utilisateurs', 'Cette page permet de modifier et/ou supprimer des utilisateurs.', "user");

		if ($messages != null) {
			foreach ($messages as $message) {
				if (!empty($message['type']) && !empty($message['message'])) {
					$submit_message = new View("submit_message.html.twig", array("alert_type" => $message['type'] , "alert_message" => $message['message']));
					$composite->attachContentView($submit_message);
				}
			}
		}
		$list_users = new View("users/list_users.html.twig", array('users' => $users));
		$composite->attachContentView($list_users);

		echo $composite->render();
	}

	/**
	 *
	 */
	public function deleteUserAjax() {

		session_start();

		if (isset($_POST['value'])) {
		// if (isset($_GET['value'])) {

			if ($this->deleteUser($_POST['value']) == true) {
			// if ($this->deleteUser($_GET['value']) == true) {
				$response['users'] = $this->getUsers();
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
			$user = $this::getUser($_POST['update']);
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

			$this->updateUser($userToUpdate);

			redirectToUrl('./?action=listUsers&update=true');
		}

		else {
			$users = $this::getUsers();
			if (!empty($users)) {
				$this->displayList(true);
			}
			else {
				$alert['type'] = 'danger';
				$alert['message'] = 'Nous n\'avons aucun utilisateur d\'enregistré.';
				$alerts[] = $alert;
				$this->displayList(false, $alerts);
			}
		}
	}

	/**
	 * @param $state
	 * @param $datas
	 * @param null $messages
	 */
	public function displayUpdateForm($user, $messages = null) {

		$composite = new CompositeView(true, "Mettre à jour un utilisateur", null, "user");

		if ($messages != null) {

			foreach ($messages as $message) {
				if (!empty($message['type']) && !empty($message["message"])) {
					$message = new View("submit_message.html.twig", array("alert_type" => $message["type"] , "alert_message" => $message["message"]));
					$composite->attachContentView($message);
				}
			}
		}

		$update_user = new View("users/update_user.html.twig", array("user" => $user, "previousUrl" => getPreviousUrl()));
		$composite->attachContentView($update_user);

		echo $composite->render();
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
	 * @param $enssatPrimaryKey
	 * @return mixed
	 */
	private function checkUnicity($enssatPrimaryKey) {

		return $this->_userService->checkUnicity($enssatPrimaryKey);
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

	private function updateUser($userToUpdate) {

		$this->_userService->updateUser($userToUpdate);
	}



}
