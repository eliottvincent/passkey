<?php


interface interfaceBorrowService
{
	//on emprunte toujours un trousseau
	public function borrowKeychain($user,$keychain);

	public function returnKeychain($borrowingId,$comment);

	public function lostKeychain($borrowingId,$comment);

	public function getBorrowings();
}

?>
