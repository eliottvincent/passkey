<?php

// Declaration de l'interface 'iTemplate'
interface interfaceUserDAO
{
	// Retrieves the corresponding row for the specified user ID.
	//public function getByUserId($userId);

	// Singleton
	public static function getInstance();

	// Retrieves all users currently in the database.
	public function getUsers();

}

?>
