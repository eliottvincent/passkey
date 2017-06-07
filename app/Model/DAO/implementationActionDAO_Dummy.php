<?php

/**
 * Created by PhpStorm.
 * User: eliottvincent
 * Date: 07/06/2017
 * Time: 10:01
 */
class implementationActionDAO_Dummy {

	private $_actions = array();

	/**
	 * @var Singleton
	 * @access private
	 * @static
	 */
	private static $_instance = null;


	/**
	 * Constructeur de la classe
	 *
	 * @param void
	 * @return void
	 */
	private function __construct() {

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
			self::$_instance = new implementationActionDAO_Dummy();
		}

		return self::$_instance;
	}

	public function getActions()
	{
		return $this->_actions;
	}

}
