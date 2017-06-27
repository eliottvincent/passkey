<?php

/**
 * Created by PhpStorm.
 * User: eliottvincent
 * Date: 07/06/2017
 * Time: 09:42
 */
class TrackingController {

	//================================================================================
	// constructor
	//================================================================================

	/**
	 * TrackingController constructor.
	 */
	function __construct() {

		$this->_actionService = implementationActionService_Dummy::getInstance();
	}

	function createAction($type) {

		$actionToSave = array(
			'user_enssatPrimaryKey' => addslashes($_POST['user_enssatPrimaryKey']),
			'user_ur1identifier' => addslashes($_POST['user_ur1identifier']),
			'user_username' => addslashes($_POST['user_username']),
			'user_name' => addslashes($_POST['user_name']),
			'user_surname' => addslashes($_POST['user_surname']),
			'user_phone' => addslashes($_POST['user_phone']),
			'user_status' => addslashes($_POST['user_status']),
			'user_email' => addslashes($_POST['user_email']),
		);

		$this->saveAction($actionToSave);
	}

	function isFrequent($action) {

		return false;
	}


	//================================================================================
	// calls to Service
	//================================================================================

	/**
	 * @return mixed
	 */
	private function getActions() {

		return $this->_actionService->getActions();
	}

	/**
	 * @param $id
	 * @return mixed
	 */
	private function getAction($id) {

		return $this->_actionService->getAction($id);
	}

	/**
	 * @param $actionToSave
	 */
	private function saveAction($actionToSave) {

		$this->_actionService->saveAction($actionToSave);
	}

	/**
	 * @param $id
	 * @return mixed
	 */
	private function deleteAction($id) {

		return $this->_actionService->deleteAction($id);
	}

	/**
	 * @param $actionToUpdate
	 * @return mixed
	 */
	private function updateAction($actionToUpdate) {

		return $this->_actionService->updateAction($actionToUpdate);
	}

	/**
	 * @param $id
	 * @return mixed
	 */
	private function checkUnicity($id) {

		return $this->_actionService->checkUnicty($id);
	}
}
