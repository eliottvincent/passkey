<?php
use Dompdf\Dompdf;


class PDFController
{


// Composer's auto-loading functionality




//generate some PDFs!


	/**
	 * PDFController constructor.
	 */
	public function __construct()
	{
		$this->_keychainService = implementationKeyChainService_Dummy::getInstance();
		$this->_userService = implementationUserService_Dummy::getInstance();
	}
	public function test($html){
		$dompdf = new DOMPDF();  //if you use namespaces you may use new \DOMPDF()
		$dompdf->loadHtml($html);
		$dompdf->render();
		$dompdf->stream("sample.pdf", array("Attachment"=>0));
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

	public function creationPDF(){

		$keyid = $_GET['keyname'];
		$userid = $_GET['user'];

		$key = $this->getBorrowingById($keyid);
		$user = $this->getUser($userid);

		$username = $user->getUsername();

		/*$this->test("<div><h1>L'application PassKey vous remercie de votre emprunt</h1>
			<p> <L'utilisateur ". $userid .
			" a emprunté la clé : " . $keyid.
			" ! </p></div>");*/
		$this->test("<div><h1>L'application PassKey vous remercie de votre emprunt</h1>
			<p> <L'utilisateur ". $userid .
			" a emprunté la clé : " . $keyid.
			" ! </p></div>");

	}

	private function getUser($enssatPrimaryKey) {

		return $this->_userService->getUser($enssatPrimaryKey);
	}
}
