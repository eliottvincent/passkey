<?php

/**
 * Created by PhpStorm.
 * User: chloecorfmat
 * Date: 12/05/2017
 * Time: 17:02
 */
class LockController
{
	public function __construct()
	{
	}

	/**
	 * To create a lock.
	 */
	public function create() {
		if (!isset($_POST['lock_name']) && !isset($_POST['lock_door']) && !isset($_POST['lock_number'])) {
			$doors = DoorController::getDoors();
			if (!empty($doors)) {
				// If we have no values, the form is displayed.
				$this->displayForm(true);
			} else {
				$message['type'] = 'danger';
				$message['message'] = 'Aucune porte n\'a été créée';
				$this->displayForm(false, $message);
			}
		} elseif (empty($_POST['lock_name']) || empty($_POST['lock_door'])) {
			// If we have not all values, error message display and form.
			$m_type = "danger";
			$m_message = "Toutes les valeurs nécessaires n'ont pas été trouvées. Merci de compléter tous les champs.";
			$message['type'] = $m_type;
			$message['message'] = $m_message;
			$this->displayForm(true, $message);
		} else {
			// If we have all values.

			$id = 'l_' . strtolower(str_replace(' ', '_', addslashes($_POST['lock_name'])));

			// Check unicity.
			$exist = false;
			$locks = $this::getLocks();

			if ($locks) {
				foreach ($locks as $lock) {
					if ($lock['lock_id'] == $id) {
						$exist = true;
					}
				}
			}

			if (!$exist) {
				$datas = array(
					'lock_id' => $id,
					'lock_name' => addslashes($_POST['lock_name']),
					'lock_door' => addslashes($_POST['lock_door'])
				);

				$_SESSION['LOCKS'][] = $datas;

				$m_type = "success";
				$m_message = "Le canon a bien été enregistré.";
				$message['type'] = $m_type;
				$message['message'] = $m_message;

				$this->displayForm(true, $message);
			} else {
				$m_type = "danger";
				$m_message = "Un canon avec le même nom existe déjà.";
				$message['type'] = $m_type;
				$message['message'] = $m_message;

				$this->displayForm(true, $message);
			}


		}
	}

	/**
	 * To display the form used to create lock.
	 * @param $state boolean if some doors isset or not
	 * @param null $message array The type and the text of the message
	 */
	public function displayForm($state, $message = null) {
		if ($state) {
			$doors = DoorController::getDoors();
		} else {
			$doors = null;
		}
		$composite = new CompositeView(true, 'Ajouter un canon', null, "lock");

		if ($message != null && !empty($message['type']) && !empty($message['message'])) {
			$message = new View(null, null, "submit_message.html.twig", array("alert_type" => $message['type'] , "alert_message" => $message['message']));
			$composite->attachContentView($message);
		}

		$create_lock = new View(null, null,"locks/create_lock.html.twig", array('doors' => $doors, 'previousUrl' => $_SERVER["HTTP_REFERER"]));
		$composite->attachContentView($create_lock);

		echo $composite->render();
	}

	public function list() {
		if (isset($_POST['delete']) && !empty($_POST['delete'])) {
			$delete = $this->deleteLock(addslashes($_POST['delete']));
			if ($delete) {
				$message['type'] = 'success';
				$message['message'] = 'Le canon a bien été supprimé.';
				$messages[] = $message;

				if(!isset($_SESSION['LOCKS'])) {
					$message['type'] = 'danger';
					$message['message'] = 'Nous n\'avons aucun canon d\'enregistré.';
					$messages[] = $message;
				}
				if (!empty($this::getLocks())) {
					$this->displayList(true, $messages);
				} else {
					$this->displayList(false, $messages);
				}
			} else {
				$message['type'] = 'danger';
				$message['message'] = 'Le canon n\'existe pas.';
				$messages[] = $message;
				if (!empty($this::getLocks())) {
					$this->displayList(true, $messages);
				} else {
					$this->displayList(false, $messages);
				}
			}
		} else {
			$locks = $this::getLocks();
			if (!empty($locks)) {
				if (isset($_GET['update']) && $_GET['update'] == true) {
					$alert['type'] = 'success';
					$alert['message'] = 'Le canon a bien été modifié.';
					$alerts[] = $alert;

					$this->displayList(true, $alerts);
				} else {
					$this->displayList(true);
				}

			} else {
				$alert['type'] = 'danger';
				$alert['message'] = 'Nous n\'avons aucun canon d\'enregistré.';
				$alerts[] = $alert;
				$this->displayList(false, $alerts);
			}
		}
	}

	public function displayList($state, $messages = null) {
		if ($state) {
			$locks = LockController::getLocks();
		} else {
			$locks = null;
		}
		$composite = new CompositeView(true, 'Liste des canons', 'Cette page permet de modifier et/ou supprimer des canons.', "lock");

		if ($messages != null) {
			foreach ($messages as $message) {
				if (!empty($message['type']) && !empty($message['message'])) {
					$submit_message = new View(null, null, "submit_message.html.twig", array("alert_type" => $message['type'] , "alert_message" => $message['message']));
					$composite->attachContentView($submit_message);
				}
			}
		}
		$list_locks = new View(null, null,"locks/list_locks.html.twig", array('locks' => $locks));
		$composite->attachContentView($list_locks);

		echo $composite->render();
	}

	public function deleteLock($id) {
		$locks = $this::getLocks();
		foreach($locks as $lock) {
			if ($lock['lock_id'] == $id) {
				$length = sizeof($_SESSION['LOCKS']);
				if ($length > 1) {
					$nb =  array_search($lock, $locks);
					unset($_SESSION['LOCKS'][$nb]);
				} else {
					unset($_SESSION['LOCKS']);
				}
				return true;
			}
		}

		return false;
	}

	public function update() {
		if (isset($_POST['update']) && !empty($_POST['update'])) {
			$lock = $this::getLock(addslashes($_POST['update']));
			$this->displayUpdateForm(true, $lock);
		} elseif (isset($_POST['lock_hidden_name']) || isset($_POST['lock_door'])) {
			$id = 'l_' . strtolower(str_replace(' ', '_', addslashes($_POST['lock_hidden_name'])));

			for ($i = 0; $i < sizeof($_SESSION['LOCKS']); $i++) {
				if ($_SESSION['LOCKS'][$i]['lock_id'] == $id) {
					if (isset($_POST['lock_door']) && ($_POST['lock_door'] != $_SESSION['LOCKS'][$i]['lock_door']) && !empty($_POST['lock_door'])) {
						$_SESSION['LOCKS'][$i]['lock_door'] = addslashes($_POST['lock_door']);
					}
				}
			}

			// header redirection doesn't work on some environments...
			//header("Location: " . $newUrl);

			// ...thus we use script injection
			$newUrl = './?action=listlocks&update=true';
			echo "<script> window.location.replace('" . $newUrl. "') </script>";

		} else {
				$locks = $this::getLocks();
				if (!empty($locks)) {
					$this->displayList(true);
				} else {
					$alert['type'] = 'danger';
					$alert['message'] = 'Nous n\'avons aucun canon d\'enregistré.';
					$alerts[] = $alert;
					$this->displayList(false, $alerts);
				}
			}
	}

	public function displayUpdateForm($state, $datas, $messages = null) {
		if ($state) {
			$doors = DoorController::getDoors();
		} else {
			$locks = null;
		}

		$composite = new CompositeView(true, 'Mettre à jour un canon', null, "lock");

		if ($messages != null) {
			foreach ($messages as $message) {
				if (!empty($message['type']) && !empty($message['message'])) {
					$message = new View(null, null, "submit_message.html.twig", array("alert_type" => $message['type'] , "alert_message" => $message['message']));
					$composite->attachContentView($message);
				}
			}
		}

		$update_lock = new View(null ,null, 'locks/update_lock.html.twig', array('doors' => $doors, 'lock' => $datas, 'previousUrl' => $_SERVER["HTTP_REFERER"]));
		$composite->attachContentView($update_lock);

		echo $composite->render();
	}

	/**
	 * Used to get all locks.
	 * @return null
	 */
	public static function getLocks() {
		if (isset($_SESSION['LOCKS'])) {
			$locks = $_SESSION['LOCKS'];
			return $locks;
		}

		return null;
	}

	public static function getLock($id) {
		$locks = LockController::getLocks();

		foreach ( $locks as $lock ) {
			if ($lock['lock_id'] == $id) {
				return $lock;
			}
		}

		return false;
	}

}
