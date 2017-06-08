<?php

/**
 * Created by PhpStorm.
 * User: eliottvincent
 * Date: 07/06/2017
 * Time: 09:58
 */
interface interfaceActionDAO
{

	public static function getInstance();

	// Retrieves all users currently in the database.
	public function getActions();
}
