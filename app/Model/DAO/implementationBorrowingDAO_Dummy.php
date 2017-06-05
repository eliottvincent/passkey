<?php

/**
 * Created by PhpStorm.
 * User: eliottvincent
 * Date: 05/06/2017
 * Time: 12:17
 */
class implementationBorrowingDAO_Dummy implements interfaceBorrowingDAO {

	private $_borrowings = array();

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
		if (file_exists(dirname(__FILE__).'/borrowings.xml')) {
			$borrowings = simplexml_load_file(dirname(__FILE__).'/borrowings.xml');
			foreach($borrowings->children() as $xmlBorrowing) {
				$borrowing = new BorrowingVO();

				$borrowing->setId((string) $xmlBorrowing->id);
				$borrowing->setDueDate((string) $xmlBorrowing->dueDate);
				$borrowing->setKeychain((string) $xmlBorrowing->keychain);
				$borrowing->setUser((string) $xmlBorrowing->user);

				array_push($this->_borrowings, $borrowing);
			}
		} else {
			exit('Echec lors de l\'ouverture du fichier borrowings.xml.');
		}

	}


	public static function getInstance() {
		if(is_null(self::$_instance)) {
			self::$_instance = new implementationBorrowingDAO_Dummy();
		}

		return self::$_instance;
	}

	public function getBorrowings() {

		return $this->_borrowings;
	}
}
