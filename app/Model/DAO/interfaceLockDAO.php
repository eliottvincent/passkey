<?php

interface interfaceLockDAO
{

	// Singleton
	public static function getInstance();

	public function getLocks();

	public function getRandomLock();

}

?>

