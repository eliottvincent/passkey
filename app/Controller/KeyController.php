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

		if ($message != null){
			$message = new View(null, null, "submit_message.html.twig", array("alert_type" => $message['type'] , "alert_message" => $message['message']));
			$composite->attachContentView($message);
		}

		$create_key = new View(null ,null, 'keys/create_key.html.twig', array('locks' => $locks));
		$composite->attachContentView($create_key);

		echo $composite->render();
	}

	// TODO
	public static function getKeys() {
		$keys = null;
		return $keys;
	}
}
