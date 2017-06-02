<?php

interface interfaceDoorDAO
{

	// Singleton
	public static function getInstance();

	public function getDoors();

	public function getRandomDoor();

}

?>
