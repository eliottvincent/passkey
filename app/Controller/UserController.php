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
