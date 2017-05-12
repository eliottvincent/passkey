<?php

/**
 * Created by PhpStorm.
 * User: Basile Bruhat
 * Date: 12/05/2017
 * Time: 15:21
 */
class KeyController
{
	public function __construct()
	{

	}

	/**
	 * to create a new key
	 */
	public function create(){
		if (!isset($_POST['key_name']) && !isset($_POST['key_type']) && !isset($_POST['key_lock'])) {
			// TODO : Check if locks exists or not
			if (true) {
				// If we have no values, the form is displayed.
				$this->displayForm(true);
			} else {
				$this->displayForm(false);
			}
		} elseif (empty($_POST['key_name']) || empty($_POST['key_type']) || empty($_POST['key_lock'])) {
			// If we have not all values, error message display and form.
			$m_type = "danger";
			$m_message = "Toutes les valeurs nécessaires n'ont pas été trouvées. Merci de compléter tous les champs.";
			$message['type'] = $m_type;
			$message['message'] = $m_message;
			$this->displayForm(true, $message);
		} else {
			$m_type = "success";
			$m_message = "La clé a bien été enregistrée.";

			$message['type'] = $m_type;
			$message['message'] = $m_message;
			$this->displayForm(true, $message);
		}
	}

	/**
	 * Display form used to create key
	 * @param $state boolean if file datas/datas.xlsx exists
	 * @param null $message array of the message displays
	 */
	public function displayForm($state, $message = null) {
		if ($state) {
			$locks = CreateLockController::getLocks();
		} else {
			$locks = null;
		}

		$composite = new CompositeView(true, 'Ajouter une clé');

		if ($message != null && !empty($message['type']) && !empty($message['message'])) {
			$message = new View(null, null, "submit_message.html.twig", array("alert_type" => $message['type'] , "alert_message" => $message['message']));
			$composite->attachContentView($message);
		}

		$create_key = new View(null ,null, 'keys/create_key.html.twig', array('locks' => $locks));
		$composite->attachContentView($create_key);

		echo $composite->render();
	}

	/**
	 * use to list keys
	 */
	public function list(){
		if (isset($_GET['delete']) && !empty($_GET['delete'])) {
			$id = explode('delete_k', $_GET['delete'])[1];
			$delete = $this->deleteKey($id);

			if ($delete) {
				$this->displayDeleteKey('success', 'La clé a bien été supprimée');
			} else {
				$this->displayDeleteKey('danger', 'La clé n\'existe pas.');
			}

		} else {
			// TODO : Check if locks exists or not
			if (true) {
				$this->displayList(true);
			} else {
				$alert['type'] = 'danger';
				$alert['message'] = 'Aucune clé n\'a été créée.';
				$this->displayList(false, $alert);
			}
		}
	}

	// TODO
	public function deleteKey($id) {

	}

	/**
	 * Display list of keys.
	 * @param $state boolean if file datas/datas.xlsx exists
	 * @param null $message array of the message displays
	 */
	public function displayList($state, $message = null) {
		if ($state) {
			$keys = KeyController::getKeys();
		} else {
			$keys = null;
		}
		$composite = new CompositeView(true, 'Liste des clés');

		if ($message != null && !empty($message['type']) && !empty($message['message'])) {
			$submit_message = new View(null, null, "submit_message.html.twig", array("alert_type" => $message['type'] , "alert_message" => $message['message']));
			$composite->attachContentView($submit_message);
		}
		$list_keys = new View(null, null,"keys/list_keys.html.twig", array('keys' => $keys));
		$composite->attachContentView($list_keys);

		echo $composite->render();
	}

	// TODO
	public static function getKeys() {
		$keys = null;
		return $keys;
	}
}