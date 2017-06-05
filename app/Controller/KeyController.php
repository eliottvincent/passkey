<?php

/**
 * Created by PhpStorm.
 * User: Basile Bruhat
 * Date: 12/05/2017
 * Time: 15:21
 */

class KeyController {

	//================================================================================

	/**
	 * KeyController constructor.
	 */
	public function __construct() {
		$this->_keyService = implementationKeyService_Dummy::getInstance();
	}


	//================================================================================
	// LIST
	//================================================================================

	/**
	 * use to list keys
	 */
	public function list() {
		$keys = $this->getKeys();

		if (!empty($keys)) {
			$this->displayList(true);
		}
		else {
			$message['type'] = 'danger';
			$message['message'] = 'Nous n\'avons aucune clé d\'enregistrée.';
			$this->displayList(false, array($message));
		}
	}

	/**
	 * Display list of keys.
	 * @param $state boolean if file datas/datas.xlsx exists
	 * @param null $message array of the message displays
	 */
	public function displayList($state, $messages = null) {
		if ($state) {
			$keys = $this->getKeys();
		} else {
			$keys = null;
		}

		$compositeView = new CompositeView(
			true,
			'Liste des clés',
			'Cette page permet de modifier et/ou supprimer des clés.',
			"key",
			array("sweetAlert" => "https://cdn.jsdelivr.net/sweetalert2/6.6.2/sweetalert2.min.css"),
			array("deleteKeyScript" => "app/View/assets/custom/scripts/deleteKey.js",
				"sweetAlert" => "https://cdn.jsdelivr.net/sweetalert2/6.6.2/sweetalert2.min.js"));

		if ($messages != null) {
			foreach ($messages as $message) {
				if (!empty($message['type']) && !empty($message['message'])) {
					$submit_message = new View("submit_message.html.twig", array("alert_type" => $message['type'] , "alert_message" => $message['message']));
					$compositeView->attachContentView($submit_message);
				}
			}
		}

		$list_keys = new View("keys/list_keys.html.twig", array('keys' => $keys));
		$compositeView->attachContentView($list_keys);

		echo $compositeView->render();
	}


	/**
	 * to create a new key
	 */
	public function create(){
		if (!isset($_POST['key_name']) && !isset($_POST['key_type']) && !isset($_POST['key_lock'])) {
			$locks = LockController::getLocks();
			if (!empty($locks)) {
				// If we have no values, the form is displayed.
				$this->displayForm(true);
			} else {
				$message['type'] = 'danger';
				$message['message'] = 'Aucun canon n\' a été créé.';
				$messages[] = $message;
				$this->displayForm(false, $messages);
			}
		} elseif (empty($_POST['key_name']) || empty($_POST['key_type']) || empty($_POST['key_lock'])) {
			// If we have not all values, error message display and form.
			$m_type = "danger";
			$m_message = "Toutes les valeurs nécessaires n'ont pas été trouvées. Merci de compléter tous les champs.";
			$message['type'] = $m_type;
			$message['message'] = $m_message;
			$messages[] = $message;
			$this->displayForm(true, $messages);
		} else {
			// If we have all values.
			$id = 'k_' . strtolower(str_replace(' ', '_', addslashes($_POST['key_name'])));

			// Check unicity.
			$exist = false;
			$keys = $this::getKeys();

			if ($keys) {
				foreach ($keys as $key) {
					if ($key['key_id'] == $id) {
						$exist = true;
					}
				}
			}

			if (!$exist) {
				$datas = array(
					'key_id' => $id,
					'key_name' => addslashes($_POST['key_name']),
					'key_type' => addslashes($_POST['key_type']),
					'key_locks' => $_POST['key_lock'],
					'key_number' => addslashes($_POST['key_number'])
				);

				$_SESSION['KEYS'][] = $datas;

				$m_type = "success";
				$m_message = "La clé a bien été enregistrée.";

				$message['type'] = $m_type;
				$message['message'] = $m_message;
				$messages[] = $message;
				$this->displayForm(true, $messages);
			} else {
				$m_type = "danger";
				$m_message = "Une clé avec le même nom existe déjà.";

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

		$composite = new CompositeView(true, 'Ajouter une clé', null, "key");

		if ($messages != null) {
			foreach ($messages as $message) {
				if (!empty($message['type']) && !empty($message['message'])) {
					$message = new View("submit_message.html.twig", array("alert_type" => $message['type'] , "alert_message" => $message['message']));
					$composite->attachContentView($message);
				}
			}
		}

		$create_key = new View('keys/create_key.html.twig', array('locks' => $locks, 'previousUrl' => getPreviousUrl()));
		$composite->attachContentView($create_key);

		echo $composite->render();
	}

	/**
	 * use to list keys
	 */
	public function list($delete = null){
		if (isset($_POST['delete']) && !empty($_POST['delete'])) {
			$delete = $this->deleteKey(addslashes($_POST['delete']));
			if ($delete) {
				$message['type'] = 'success';
				$message['message'] = 'La clé a bien été supprimée';
				$messages[] = $message;

				if(!isset($_SESSION['KEYS'])) {
					$message['type'] = 'danger';
					$message['message'] = 'Nous n\'avons aucune clé d\'enregistrée.';
					$messages[] = $message;
				}
				if (!empty($this::getKeys())) {
					$this->displayList(true, $messages);
				} else {
					$this->displayList(false, $messages);
				}
			} else {
				$message['type'] = 'danger';
				$message['message'] = 'La clé n\'existe pas.';
				$messages[] = $message;
				if (!empty($this::getKeys())) {
					$this->displayList(true, $messages);
				} else {
					$this->displayList(false, $messages);
				}
			}

		} else {
			$keys = $this::getKeys();
			if (!empty($keys)) {
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
	 * Used to delete a key from an id.
	 * @param $id
	 */
	public function deleteKey($id) {
		$keys = $this::getKeys();
		foreach($keys as $key) {
			if ($key['key_id'] == $id) {
				$length = sizeof($_SESSION['KEYS']);
				if ($length > 1) {
					$nb =  array_search($key, $keys);
					unset($_SESSION['KEYS'][$nb]);
				} else {
					unset($_SESSION['KEYS']);
				}
				return true;
			}
		}

		return false;
	}

	/**
	 * Display list of keys.
	 * @param $state boolean if file datas/datas.xlsx exists
	 * @param null $message array of the message displays
	 */
	public function displayList($state, $messages = null) {
		if ($state) {
			$keys = KeyController::getKeys();
		} else {
			$keys = null;
		}
		$composite = new CompositeView(
			true,
			'Liste des clés',
			'Cette page permet de modifier et/ou supprimer des clés.',
			"key",
			array("sweetAlert" => "https://cdn.jsdelivr.net/sweetalert2/6.6.2/sweetalert2.min.css"),
			array("deleteKeyScript" => "app/View/assets/custom/scripts/deleteKey.js",
				"sweetAlert" => "https://cdn.jsdelivr.net/sweetalert2/6.6.2/sweetalert2.min.js"));

		if ($messages != null) {
			foreach ($messages as $message) {
				if (!empty($message['type']) && !empty($message['message'])) {
					$submit_message = new View("submit_message.html.twig", array("alert_type" => $message['type'] , "alert_message" => $message['message']));
					$composite->attachContentView($submit_message);
				}
			}
		}
		$list_keys = new View("keys/list_keys.html.twig", array('keys' => $keys));
		$composite->attachContentView($list_keys);

		echo $composite->render();
	}

	public function update() {
		if (isset($_POST['update']) && !empty($_POST['update'])) {
			$key = $this::getKey(addslashes($_POST['update']));
			$this->displayUpdateForm(true, $key);
		} elseif (isset($_POST['key_hidden_name']) || isset($_POST['key_type']) || isset($_POST['key_lock']) || isset($_POST['key_number'])) {
			$id = 'k_' . strtolower(str_replace(' ', '_', addslashes($_POST['key_hidden_name'])));

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

			redirectToUrl('./?action=listkeys&update=true');
		} else {
			$keys = $this::getKeys();
			if (!empty($keys)) {
				$this->displayList(true);
			} else {
				$alert['type'] = 'danger';
				$alert['message'] = 'Nous n\'avons aucune clé d\'enregistrée.';
				$alerts[] = $alert;
				$this->displayList(false, $alerts);
			}
		}
	}

	public function displayUpdateForm($state, $datas, $messages = null) {
		if ($state) {
			$locks = LockController::getLocks();
		} else {
			$locks = null;
		}

		$composite = new CompositeView(true, 'Mettre à jour une clé', null, "key");

		if ($messages != null) {
			foreach ($messages as $message) {
				if (!empty($message['type']) && !empty($message['message'])) {
					$message = new View("submit_message.html.twig", array("alert_type" => $message['type'] , "alert_message" => $message['message']));
					$composite->attachContentView($message);
				}
			}
		}

		$update_key = new View('keys/update_key.html.twig', array('locks' => $locks, 'key' => $datas, 'previousUrl' => getPreviousUrl()));
		$composite->attachContentView($update_key);

		echo $composite->render();
	}

	/**
	 * To get all keys.
	 * @return null
	 */
	public static function getKeys() {
		if (isset($_SESSION['KEYS'])) {
			$keys = $_SESSION['KEYS'];
			return $keys;
		}

		return null;
	}

	public static function getKey($id) {
		$keys = KeyController::getKeys();

		foreach ( $keys as $key ) {
			if ($key['key_id'] == $id) {
				return $key;
			}
		}

		return false;
	}

	public function deleteKeyAjax() {
		session_start();

		if (isset($_POST['value'])) {

			$first = substr($_POST['value'], 0, 1);

			if ($first == 'k') {
				$key = new KeyController();
				$key->deleteKey($_POST['value']);
				$keys = $key::getKeys();
			}
			$response['keys'] = $keys;
			$response['status'] = 'success';
			$response['message'] = 'This was successful';
		} else {
			$response['status'] = 'error';
			$response['message'] = 'This failed';
		}

		echo json_encode($response);
	}
}
