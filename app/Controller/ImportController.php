<?php

/**
 * Created by PhpStorm.
 * User: chloecorfmat
 * Date: 20/06/2017
 * Time: 21:29
 */
class ImportController
{

	public function __construct()
	{
		$this->_keyService = implementationKeyService_Dummy::getInstance();
		$this->_doorService = implementationDoorService_Dummy::getInstance();
		$this->_lockService = implementationLockService_Dummy::getInstance();
		$this->_roomService = implementationRoomService_Dummy::getInstance();
		$this->_userService = implementationUserService_Dummy::getInstance();
		$this->_keychainService = implementationKeychainService_Dummy::getInstance();
		$this->_borrowingService = implementationBorrowingService_Dummy::getInstance();

		if (!empty($_FILES) && !empty($_POST['type'])) {
			if ($_FILES["file"]["type"] == "text/csv") {
				if (!file_exists("datas")) {
					mkdir("datas", 0777, true);
				}

				$name = "datas/" . $_POST["type"] . ".csv";
				move_uploaded_file($_FILES['file']['tmp_name'], $name);

				switch ($_POST["type"]) {
					case "keys":
						$this->importKeys();
						break;
					case "doors";
						$this->importDoors();
						break;
					case "locks":
						$this->importLocks();
						break;
					case "rooms":
						$this->importRooms();
						break;
					case "users":
						$this->importUsers();
						break;
					case "keychains":
						$this->importKeychains();
						break;
					case "borrowings":
						$this->importBorrowings();
						break;
					default:
						break;
				}

				unlink($name);


				$message["type"] = "info";
				$message["message"] = "Le fichier a bien été importé.";
				$this->displayForm(array($message));

			} else {
				$message["type"] = "danger";
				$message["message"] = "Le fichier doit être de type CSV.";
				$this->displayForm(array($message));
			}



		} else if ( !empty($_POST['type']) && empty($_FILES["file"]["name"])) {
			$message["type"] = "danger";
			$message["message"] = "Tous les champs doivent être remplis.";

			$this->displayForm(array($message));
		} else {
			$this->displayForm();
		}
	}

	//================================================================================
	// IMPORTS
	//================================================================================

	private function importBorrowings() {
		$row = 1; // compteur de ligne
		$fic = fopen("datas/borrowings.csv", "a+");
		while($data = fgetcsv($fic,1024,';'))
		{
			$row++;
			$datas = array();

			$datas['borrowing_id'] = $data[0];
			$datas['borrowing_user'] = $data[1];
			$datas['borrowing_keychain'] = $data[2];


			$this->_borrowingService->saveBorrowing($datas);
		}
		fclose($fic);
	}

	private function importKeychains() {
		$row = 1; // compteur de ligne
		$fic = fopen("datas/keychains.csv", "a+");
		while($data = fgetcsv($fic,1024,';'))
		{
			$row++;
			$datas = array();
			$num = count($data);//nombre de champ dans la ligne en question

			$datas['keychain_id'] = $data[0];
			$datas['keychain_name'] = $data[1];
			$datas['keychain_keys'] = array();

			for( $c = 2; $c < $num; $c++ ) {
				array_push($datas['keychain_keys'], $data[$c]);
			}

			$this->_keychainService->saveKeychain($datas);
		}
		fclose($fic);
	}

	private function importUsers() {
		$row = 1; // compteur de ligne
		$fic = fopen("datas/users.csv", "a+");
		while($data = fgetcsv($fic,1024,';'))
		{
			$row++;
			$datas = array();

			$datas['user_enssatPrimaryKey'] = $data[0];
			$datas['user_ur1identifier'] = $data[1];
			$datas['user_username'] = $data[2];
			$datas['user_name'] = $data[3];
			$datas['user_surname'] = $data[4];
			$datas['user_phone'] = $data[5];
			$datas['user_status'] = $data[6];
			$datas['user_email'] = $data[7];

			$this->_userService->saveUser($datas);
		}
		fclose($fic);
	}

	private function importLocks() {
		$row = 1; // compteur de ligne
		$fic = fopen("datas/locks.csv", "a+");
		while($data = fgetcsv($fic,1024,';'))
		{
			$row++;
			$datas = array();

			$datas['lock_id'] = $data[0];
			$datas['lock_name'] = $data[1];
			$datas['lock_door'] = $data[2];
			$datas['lock_length'] = $data[3];

			$this->_lockService->saveLock($datas);
		}
		fclose($fic);
	}

	private function importDoors() {
		$row = 1; // compteur de ligne
		$fic = fopen("datas/doors.csv", "a+");
		while($data = fgetcsv($fic,1024,';'))
		{
			$row++;
			$datas = array();

			$datas['door_id'] = $data[0];
			$datas['door_name'] = $data[1];
			$datas['door_room'] = $data[2];

			$this->_doorService->saveDoor($datas);
		}
		fclose($fic);
	}

	private function importKeys() {
		$row = 1; // compteur de ligne
		$fic = fopen("datas/keys.csv", "a+");
		while($data = fgetcsv($fic,1024,';'))
		{
			$row++;
			$datas = array();
			$num = count($data);//nombre de champ dans la ligne en question

			$datas['key_id'] = $data[0];
			$datas['key_name'] = $data[1];
			$datas['key_supplier'] = $data[2];
			$datas['key_type'] = $data[3];
			$datas['key_copies'] = $data[4];
			$datas['key_locks'] = array();

			for( $c = 5; $c < $num; $c++ ) {
				array_push($datas['key_locks'], $data[$c]);
			}

			$this->_keyService->saveKey($datas);
		}
		fclose($fic);
	}

	private function importRooms() {
		$row = 1; // compteur de ligne
		$fic = fopen("datas/rooms.csv", "a+");
		while($data = fgetcsv($fic,1024,';'))
		{
			$row++;
			$datas = array();
			$num = count($data);//nombre de champ dans la ligne en question

			$datas['room_id'] = $data[0];
			$datas['room_name'] = $data[1];
			$datas['room_building'] = $data[2];
			$datas['room_floor'] = $data[3];
			$datas['room_doors'] = array();

			for( $c = 4; $c < $num; $c++ ) {
				array_push($datas['room_doors'], $data[$c]);
			}

			$this->_roomService->saveRoom($datas);
		}
		fclose($fic);
	}

	//================================================================================
	// DISPLAY
	//================================================================================

	public function displayForm($messages = null) {
		$compositeView = new CompositeView(
			true,
			'Importer un fichier CSV',
			null,
			"key",
			null,
			null);

		if ($messages != null) {
			foreach ($messages as $message) {
				if (!empty($message['type']) && !empty($message['message'])) {
					$message = new View("submit_message.html.twig", array("alert_type" => $message['type'] , "alert_message" => $message['message']));
					$compositeView->attachContentView($message);
				}
			}
		}

		$import_file = new View('import.html.twig', array('previousUrl' => getPreviousUrl()));
		$compositeView->attachContentView($import_file);

		echo $compositeView->render();
	}
}
