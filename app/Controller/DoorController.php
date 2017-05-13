<?php

/**
 * Created by PhpStorm.
 * User: Basile Bruhat
 * Date: 12/05/2017
 * Time: 17:01
 */
class DoorController
{
	public function __construct()
	{
	}

	public function create(){
		if (!isset($_POST['door_name']) && !isset($_POST['door_building']) && !isset($_POST['door_floor'])) {
			// If we have no values, the form is displayed.
			$this->displayForm();
		} elseif (empty($_POST['door_name']) || empty($_POST['door_building']) || empty($_POST['door_floor'])){
			// If we have not all values, error message display and form.
			$m_type = "danger";
			$m_message = "Toutes les valeurs nécessaires n'ont pas été trouvées. Merci de compléter tous les champs.";
			$message['type'] = $m_type;
			$message['message'] = $m_message;
			$this->displayForm( $message);
		} else {
			// If we have all values, the form is displayed.
			$id = strtolower(str_replace(' ', '_', addslashes($_POST['door_name'])));
			$datas = array(
				'door_id' => 'd_' . $id,
				'door_name' => addslashes($_POST['door_name']),
				'door_building' => addslashes($_POST['door_building']),
				'door_floor' => addslashes($_POST['door_floor'])
			);

			$_SESSION['DOORS'][] = $datas;

			$m_type = "success";
			$m_message = "La porte a bien été créée.";
			$message['type'] = $m_type;
			$message['message'] = $m_message;
			$this->displayForm($message);
		}
	}

	public function displayForm($message = null) {
		$composite = new CompositeView(true, 'Ajouter une porte');

		if ($message != null && !empty($message['type']) && !empty($message['message'])) {
			$message = new View(null, null, "submit_message.html.twig", array("alert_type" => $message['type'] , "alert_message" => $message['message']));
			$composite->attachContentView($message);
		}

		$create_door = new View(null,null, 'doors/create_door.html.twig');
		$composite->attachContentView($create_door);

		echo $composite->render();
	}

	/**
	 * Used to get all doors created.
	 * @return null
	 */
	public static function getDoors() {
		if (isset($_SESSION['DOORS'])) {
			$doors = $_SESSION['DOORS'];
			return $doors;
		}

		return null;
	}
}
