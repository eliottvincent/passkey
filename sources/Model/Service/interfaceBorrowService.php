<?php


interface interfaceBorrowService
{
  //on emprunte toujours un trousseau
    public function borrowKeychain(int $user,int $keychain,DateTime $dueDate);

    public function returnKeychain($borrowingId,$comment);

    public function lostKeychain($borrowingId,$comment);

    public function getBorrowings();
}



?>
