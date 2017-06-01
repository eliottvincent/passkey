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
			$this->displayForm(true);
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
			$this->displayForm(true, $messages);
		}

		// if we have all values
		else {

			// Check unicity
			$exist = $this->_userService->checkUnicity($_POST['user_enssatPrimaryKey']);

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

				$this->_userService->saveUser($userToSave);

				$m_type = "success";
				$m_message = "L'utilisateur a bien été enregistré.";

				$message['type'] = $m_type;
				$message['message'] = $m_message;
				$messages[] = $message;
				$this->displayForm(true, $messages);
			}
			else {
				$m_type = "danger";
				$m_message = "Un utilisateur avec le même identifiant ENSSAT existe déjà.";

				$message['type'] = $m_type;
				$message['message'] = $m_message;
				$messages[] = $message;
				$this->displayForm(true, $messages);
			}

		}
	}

	/**
	 * Display form used to create key
	 * @param $state boolean if file datas/datas.xlsx exists
	 * @param null $message array of the message displays
	 */
	public function displayForm($state, $messages = null) {
		if ($state) {
			$locks = LockController::getLocks();
		} else {
			$locks = null;
		}

		$composite = new CompositeView(true, 'Ajouter un utilisateur', null, "user");

		if ($messages != null) {
			foreach ($messages as $message) {
				if (!empty($message['type']) && !empty($message['message'])) {
					$message = new View(null, null, "submit_message.html.twig", array("alert_type" => $message['type'] , "alert_message" => $message['message']));
					$composite->attachContentView($message);
				}
			}
		}

		$create_user = new View(null ,null, 'users/create_user.html.twig', array('locks' => $locks, 'previousUrl' => $_SERVER["HTTP_REFERER"]));
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
				$message['message'] = 'La clé n\'existe pas.';
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
					$alert['message'] = 'La clé a bien été modifiée.';
					$alerts[] = $alert;

					$this->displayList(true, $alerts);
				} else {
					$this->displayList(true);
				}

			} else {
				$alert['type'] = 'danger';
				$alert['message'] = 'Nous n\'avons aucune clé d\'enregistrée.';
				$alerts[] = $alert;
				$this->displayList(false, $alerts);
			}
		}
	}


	//================================================================================
	// DELETE
	//================================================================================

	/**
	 * Used to delete a user from an id.
	 * @param $enssatPrimaryKey
	 */
	public function deleteUser($enssatPrimaryKey) {

		return $this->_userService->deleteUser($enssatPrimaryKey);
	}

	/**
	 * Display list of keys.
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
					$submit_message = new View(null, null, "submit_message.html.twig", array("alert_type" => $message['type'] , "alert_message" => $message['message']));
					$composite->attachContentView($submit_message);
				}
			}
		}
		$list_users = new View(null, null,"users/list_users.html.twig", array('users' => $users));
		$composite->attachContentView($list_users);

		echo $composite->render();
	}

	/**
	 *
	 */
	public function deleteUserAjax() {

		session_start();

		if (isset($_POST['value'])) {

			if ($this->deleteUser($_POST['value']) == true) {
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
		if (isset($_POST['update']) && !empty($_POST['update'])) {
			$user = $this::getUser(addslashes($_POST['update']));
			$this->displayUpdateForm(true, $user);
		} elseif (isset($_POST['user_hidden_name']) || isset($_POST['key_type']) || isset($_POST['key_lock']) || isset($_POST['key_number'])) {
			$id = 'u_' . strtolower(str_replace(' ', '_', addslashes($_POST['key_hidden_name'])));

			for ($i = 0; $i < sizeof($_SESSION['KEYS']); $i++) {
				if ($_SESSION['KEYS'][$i]['key_id'] == $id) {
					if (isset($_POST['key_type']) && ($_POST['key_type'] != $_SESSION['KEYS'][$i]['key_type']) && !empty($_POST['key_type'])) {
						$_SESSION['KEYS'][$i]['key_type'] = addslashes($_POST['key_type']);
					}

					if (isset($_POST['key_lock']) && !empty($_POST['key_lock'])) {
						$_SESSION['KEYS'][$i]['key_locks'] = $_POST['key_lock'];
					}

					if (isset($_POST['key_number']) && ($_POST['key_number'] != $_SESSION['KEYS'][$i]['key_number']) && !empty($_POST['key_number'])) {
						$_SESSION['KEYS'][$i]['key_number'] = addslashes($_POST['key_number']);
					}
				}
			}

			// header redirection doesn't work on some environments...
			//header("Location: " . $newUrl);

			// ...thus we use script injection
			$newUrl = './?action=listkeys&update=true';
			echo "<script> window.location.replace('" . $newUrl. "') </script>";

		} else {
			$users = $this::getUsers();
			if (!empty($users)) {
				$this->displayList(true);
			} else {
				$alert['type'] = 'danger';
				$alert['message'] = 'Nous n\'avons aucune clé d\'enregistrée.';
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
	public function displayUpdateForm($state, $datas, $messages = null) {
		if ($state) {
			$locks = LockController::getLocks();
		} else {
			$locks = null;
		}

		$composite = new CompositeView(true, 'Mettre à jour une clé', null, "user");

		if ($messages != null) {
			foreach ($messages as $message) {
				if (!empty($message['type']) && !empty($message['message'])) {
					$message = new View(null, null, "submit_message.html.twig", array("alert_type" => $message['type'] , "alert_message" => $message['message']));
					$composite->attachContentView($message);
				}
			}
		}

		$update_user = new View(null ,null, 'keys/update_user.html.twig', array('locks' => $locks, 'key' => $datas, 'previousUrl' => $_SERVER["HTTP_REFERER"]));
		$composite->attachContentView($update_user);

		echo $composite->render();
	}


	//================================================================================
	// functions to Service
	//================================================================================

	/**
	 * To get all users.
	 * @return null
	 */
	public function getUsers() {
		return $this->_userService->getUsers();
	}

	/**
	 * @param $enssatPrimaryKey
	 * @return mixed
	 */
	public function getUser($enssatPrimaryKey) {

		return $this->_userService->getUserByEnssatPrimaryKey($enssatPrimaryKey);
	}

}
