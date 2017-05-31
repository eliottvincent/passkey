<?php

/**
 * Created by PhpStorm.
 * User: eliottvincent
 * Date: 30/05/2017
 * Time: 15:28
 */
class UserController
{
	/**
	 * UserController constructor.
	 */
	public function __construct() {
		$this->_userDAO = implementationUserDAO_Dummy::getInstance();
	}

	/**
	 * to create a new user
	 */
	public function create() {

		// if no values are posted -> displaying the form
		if (!isset($_POST['user_name']) && !isset($_POST['key_type']) && !isset($_POST['key_lock'])) {
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
			$exist = false;
			$users = $this::getUsers();

			if ($users) {
				foreach ($users as $user) {
					if ($user->getEnssatPrimaryKey() == $_POST['user_enssatPrimaryKey']) {
						$exist = true;
					}
				}
			}

			if (!$exist) {
				$data = array(
					'user_enssatPrimaryKey' => addslashes($_POST['user_enssatPrimaryKey']),
					'user_ur1identifier' => addslashes($_POST['user_ur1identifier']),
					'user_username' => addslashes($_POST['user_username']),
					'user_name' => addslashes($_POST['user_name']),
					'user_surname' => addslashes($_POST['user_surname']),
					'user_status' => addslashes($_POST['user_status']),
					'user_phone' => addslashes($_POST['user_phone']),
					'user_email' => addslashes($_POST['user_email']),
				);

				$_SESSION['USERS'][] = $data;

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
	 * use to list users
	 */
	public function list($delete = null){
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

	/**
	 * Used to delete a user from an id.
	 * @param $id
	 */
	/**
	 * To get all users.
	 * @return null
	 */
	public function getUsers() {
		return $this->_userDAO->getUsers();
	}

	}
}
