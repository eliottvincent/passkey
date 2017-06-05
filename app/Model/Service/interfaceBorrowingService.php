<?php


interface interfaceBorrowingService
{
	//on emprunte toujours un trousseau

	public function getBorrowings();

	public function getBorrowing($id);

	public function saveBorrowing($borrowingArray);

	public function deleteBorrowing($id);

	public function updateBorrowing($borrowingArray);

	public function checkUnicity($id);

	//=====
	//=====
	public function returnKeychain($borrowingId, $comment);

	public function lostKeychain($borrowingId, $comment);

}

?>
