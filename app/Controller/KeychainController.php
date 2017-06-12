<?php


class KeychainController
{

	//================================================================================
	// constructor
	//================================================================================


	/**
	 * KeychainController constructor.
	 */
	public function __construct() {

		$this->_keychainService = implementationKeychainService_Dummy::getInstance();

	}


	//================================================================================
	// LIST
	//================================================================================


	/**
	 * used to list all rooms
	 */
	public function list() {

		$keychains = $this->getKeychains();

		if (!empty($keychains)) {
			$this->displayList();
		}
		else {
			$message['type'] = 'danger';
			$message['message'] = 'Nous n\'avons aucun trousseau d\'enregistré.';
			$this->displayList(array($message));
		}
	}


	/**
	 * @param null $messages
	 * @internal param $state
	 */
	public function displayList($messages = null) {

		$keychains = $this->getKeychains();

		$compositeView = new CompositeView(
			true,
			'Liste des trousseaux',
			null,
			"keychain",
			array("sweetAlert" => "https://cdn.jsdelivr.net/sweetalert2/6.6.2/sweetalert2.min.css"),
			array("deleteKeychainScript" => "app/View/assets/custom/scripts/deleteKeychain.js",
				"sweetAlert" => "https://cdn.jsdelivr.net/sweetalert2/6.6.2/sweetalert2.min.js"));

		if ($messages != null) {
			foreach ($messages as $message) {
				if (!empty($message['type']) && !empty($message['message'])) {
					$submit_message = new View("submit_message.html.twig", array("alert_type" => $message['type'] , "alert_message" => $message['message']));
					$compositeView->attachContentView($submit_message);
				}
			}
		}

		$list_keychains = new View("keychains/list_keychains.html.twig", array('keychains' => $keychains));
		$compositeView->attachContentView($list_keychains);

		echo $compositeView->render();
	}

	//================================================================================
	// calls to Service
	//================================================================================

	/**
	 * To get all keychains
	 * @return null
	 */
	public function getKeychains() {

		return $this->_keychainService->getKeychains();
	}
}
