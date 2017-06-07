<?php

/**
 * Created by PhpStorm.
 * User: eliottvincent
 * Date: 07/06/2017
 * Time: 09:44
 */
class implementationActionService_Dummy implements interfaceActionService {

	//================================================================================
	// properties
	//================================================================================


	/**
	 * @var Singleton
	 * @access private
	 * @static
	 */
	private static $_instance = null;

	private $_actionDAO;
	private $_actions = array();
	private $_sessionActions = null;
	private $_xmlTrackings = null;

	/**
	 * implementationActionService_Dummy constructor.
	 */
	function __construct() {

		$this->_actionDAO = implementationActionDAO_Dummy::getInstance();

		// getting data we need
		// $this->_xmlTrackings = $this->$_actionDAO->getActions();

		if (isset($_SESSION["ACTIONS"])) {
			$this->_sessionActions = $_SESSION["ACTIONS"];
		}

		// if we got users in session
		if ($this->_sessionActions !== null) {

			$this->_actions = $this->_sessionActions;
		}
		// else that means there are no actions in session (first use)
		else {
			$_SESSION["ACTIONS"] = array();
			$this->_actions = array();
			$this->_sessionActions = array();
		}
	}

	/**
	 * Méthode qui crée l'unique instance de la classe
	 * si elle n'existe pas encore puis la retourne.
	 *
	 * @param void
	 * @return Singleton
	 */
	public static function getInstance() {

		if(is_null(self::$_instance)) {
			self::$_instance = new implementationActionService_Dummy();
		}

		return self::$_instance;
	}


	//================================================================================
	// Getters
	//================================================================================

	public function getActions() {

		return $this->_actions;
	}

	public function getAction($id) {

		foreach ($this->_actions as $action) {

			if ($action->getId() == (int) $id) {
				return $action;
			}
		}
	}


	//================================================================================
	// CREATE
	//================================================================================

	public function saveAction($actionArray) {

		$tDate = new DateTime;
		$tDate->setTimestamp(time());

		$actionToSave = new ActionVO();
		$actionToSave->setId((int) $actionArray['tracking_id']);	// TODO : generate id here ?
		$actionToSave->setType((string) $actionArray['tracking_type']);
		$actionToSave->setDate((string) $tDate->format('Y-m-d H:i:s'));

		array_push($_SESSION["ACTIONS"], $actionToSave);
		array_push($this->_actions, $actionToSave);
		array_push($this->_sessionActions, $actionToSave);
	}


	//================================================================================
	// DELETE
	//================================================================================

	public function deleteAction($id) {

		$this->updateServiceVariables();

		foreach ($this->_actions as $key=>$action) {

			if ($action->getId() == (int) $id) {

				unset($_SESSION['ACTIONS'][$key]);
				unset($this->_actions[$key]);
				unset($this->_sessionActions[$key]);

				return true;
			}
		}

		return false;
	}


	//================================================================================
	// UPDATE
	//================================================================================


	public function updateAction($actiongArray) {

		$actionToUpdate = new ActionVO();
		$actionToUpdate->setId((int) $actiongArray['action_id']);	// TODO : generate id here ?
		$actionToUpdate->setType((string) $actiongArray['action_type']);
		$actionToUpdate->setDate((string) $actiongArray['action_date']);

		foreach ($this->_actions as $key=>$action) {

			if ($action->getId() == $actionToUpdate->getId()) {

				$_SESSION["ACTIONS"][$key] = $actionToUpdate;
				$this->_actions[$key] = $actionToUpdate;
				$this->_sessionActions[$key] = $actionToUpdate;

				return true;
			}
		}

		return false;
	}


	//================================================================================
	// OTHER
	//================================================================================

	public function checkUnicity($id) {

		if ($this->_actions) {

			foreach ($this->_actions as $action) {

				if ($action->getId() == $id) {

					return true;
				}
			}
		}

		return false;
	}

	private function updateServiceVariables() {

		if (isset($_SESSION["ACTIONS"])) {
			$this->_sessionTrackings = $_SESSION["ACTIONS"];
			$this->_trackings = $_SESSION["ACTIONS"];
		}

	}
}
