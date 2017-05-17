<?php

require_once 'app/Model/Service/interfaceBorrowService.php';


class implementationBorrowService_Dummy implements interfaceBorrowService
{

	public static $borrowingStatus = array(
		"DoesNotExists"=>"n\'existe pas",
		"Borrowed"=>"en cours",
		"Late"=>"en retard",
		"Returned"=>"rendu",
		"Lost"=>"perdu",
	);


	/**
	 * @var Singleton
	 * @access private
	 * @static
	 */
	private static $_instance = null;

	private $_userDAO;
	private $_keychainDAO;



	/**
	 * Constructeur de la classe
	 *
	 * @param void
	 * @return void
	 */
	private function __construct()
	{
		$this->_userDAO = implementationUserDAO_Dummy::getInstance();
		$this->_keychainDAO = implementationKeychainDAO_Dummy::getInstance();

		if(!isset($_SESSION['BORROWINGS']))
		{
			foreach($this->_userDAO->getUsers() as $user)
			{
				$randKeychain = $this->_keychainDAO->getRandomKeychain();
				$_SESSION['BORROWINGS'][]=[
					'borrowingId'=>count($_SESSION['BORROWINGS'])+1,
					'userEnssatPrimaryKey'=>$user->getEnssatPrimaryKey(),
					'keychainId'=>$randKeychain->getId(),
					'borrowDate'=>$randKeychain->getCreationDate()->modify('+1 day')->format("d-m-Y"),
					'dueDate'=>$randKeychain->getCreationDate()->modify('+20 day')->format("d-m-Y"),
					'returnDate'=>null,
					'lostDate'=>null,
					'comment'=>""
				];
			}
		}
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
			self::$_instance = new implementationBorrowService_Dummy();
		}

		return self::$_instance;
	}



	//on emprunte toujours un trousseau
	public function borrowKeychain($userId,$keychainId)
	{
		$tDate = new DateTime;
		$tDate->setTimestamp(time());

		$_SESSION['BORROWINGS'][]=[
			'borrowingId'=>count($_SESSION['BORROWINGS'])+1,
			'userEnssatPrimaryKey'=>$userId,
			'keychainId'=>$keychainId,
			'borrowDate'=>$tDate->format("d-m-Y"),
			'dueDate'=>$tDate->modify('+20 day')->format("d-m-Y"),
			'returnDate'=>null,
			'lostDate'=>null,
			'comment'=>""
		];
	}

	public function getBorrowingById($borrowingId)
	{
		$borrowing=null;
		if(count($this->_borrowings)+1 > $borrowingId)
		{
			$borrowing = $this->_borrowings[$borrowingId-1];

		}
		return $borrowing;
	}

	public function setBorrowingStatus($borrowingId,$status)
	{

		$oStatus = $this->getBorrowingStatus($borrowingId);
		$tDate = new DateTime;
		$tDate->setTimestamp(time());
		if(strcmp($oStatus,"DoesNotExists")!==0)
		{
			switch($status)
			{
				case "Returned":
					$this->_borrowings[$borrowingId-1]['returnDate'] = $tDate;
					break;
				case "Lost":
					$this->_borrowings[$borrowingId-1]['lostDate'] = $tDate;
					break;
				default :
					throw new RuntimeException('borrowing does not exists.');
			}

		}
	}

	public function getBorrowingStatus($borrowingId)
	{
		$status = "DoesNotExists";
		if(!is_null($borrowing=$this->getBorrowingById($borrowingId)))
		{
			if(!is_null($borrowing['returnDate']))
			{
				$status = "Returned";
			}
			else
			{
				if(!is_null($borrowing['lostDate']))
				{
					$status = "Lost";
				}
				else
				{
					$status = "Borrowed";
				}
			}
		}
		return $status;
	}

	private function _cancelBorrowing($borrowingId,$type,$comment)
	{
		$status = $this->getBorrowingStatus($borrowingId);
		echo "status of borrowingId ".$borrowingId." : ".$status."\n";
		if(strcmp($status,"DoesNotExists")!==0  && strcmp($status,"Returned")!==0 )
		{
			echo "\tprocessing\n";
			switch($type)
			{
				case "return" :
					$this->setBorrowingStatus($borrowingId,"Returned");
					break;
				case "lost" :
					$this->setBorrowingStatus($borrowingId,"Lost");
					break;
				default :
					throw new RuntimeException('borrowing does not exists.');
			}
			$this->_borrowings[$borrowingId-1]['comment'] .= $comment;
		}
	}

	public function returnKeychain($borrowingId,$comment)
	{
		$this->_cancelBorrowing($borrowingId,"return",$comment);
	}

	public function lostKeychain($borrowingId,$comment)
	{
		$this->_cancelBorrowing($borrowingId,"lost",$comment);
	}

	public function getBorrowings()
	{
		return $_SESSION['BORROWINGS'];
	}
}

?>
