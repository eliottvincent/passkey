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
			// TODO : Check if doors exists or not
			if (true) {
				// If we have no values, the form is displayed.
				$this->displayForm(true);
			} else {
				$this->displayForm(false);
			}
		} elseif (empty($_POST['lock_name']) || empty($_POST['lock_door'])) {
			// If we have not all values, error message display and form.
			$m_type = "danger";
			$m_message = "Toutes les valeurs nécessaires n'ont pas été trouvées. Merci de compléter tous les champs.";
			$message['type'] = $m_type;
			$message['message'] = $m_message;
			$this->displayForm(true, $message);
		} else {
			// If the sheet in datas.xlsx is not created.
			$datas = array(
				'lock_name' => addslashes($_POST['lock_name']),
				'lock_door' => addslashes($_POST['lock_door'])
			);

			$m_type = "success";
			$m_message = "Le canon a bien été enregistré.";
			$message['type'] = $m_type;
			$message['message'] = $m_message;

			$this->displayForm(true, $message);
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
		$composite = new CompositeView(true, 'Ajouter un canon');

		if ($message != null && !empty($message['type']) && !empty($message['message'])) {
			$message = new View(null, null, "submit_message.html.twig", array("alert_type" => $message['type'] , "alert_message" => $message['message']));
			$composite->attachContentView($message);
		}

		$create_lock = new View(null, null,"locks/create_lock.html.twig", array('doors' => $doors));
		$composite->attachContentView($create_lock);

		echo $composite->render();
	}

	// TODO
	public static function getLocks() {
	}

}
