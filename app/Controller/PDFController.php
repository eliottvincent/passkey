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
		/*$this->_keychainService = implementationKeyChainService_Dummy::getInstance();
		$this->_userService = implementationUserService_Dummy::getInstance();*/
	}
	public function test($html){
		$dompdf = new DOMPDF();  //if you use namespaces you may use new \DOMPDF()
		$dompdf->loadHtml($html);
		$dompdf->render();
		$dompdf->stream("sample.pdf", array("Attachment"=>0));
	}

	private function getUser($enssatPrimaryKey) {

		return $this->_userService->getUser($enssatPrimaryKey);
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

		/*$keyname = $this->getBorrowingById($keyid);
		$username = $this->getUser($userid);*/


		/*$this->test("<div><h1>L'application PassKey vous remercie de votre emprunt</h1>
			<p> <L'utilisateur ". $userid .
			" a emprunté la clé : " . $keyid.
			" ! </p></div>");*/
		$this->test("<div><h1>L'application PassKey vous remercie de votre emprunt</h1>
			<p> <L'utilisateur ". $userid .
			" a emprunté la clé : " . $keyid.
			" ! </p></div>");

		$this->test("<div><body> <h1>L'emprunt demandé a bien été réalisé</h1> <table> <tr > <td > L'utilisateur [User] a emprunté la clé : [clé] </td></tr><tr> <td > Pour confirmer l'emprunt, merci de signer ce reçu </td></tr><tr> <td> La scolarité : </td><td style=\"width:300px; height:100px;\"> [User] : </td></tr></table> </body> </html><style>tr /* Toutes les cellules des tableaux... */table{border-collapse: collapse;}table /* Mettre une bordure sur les td ET les th */{border: 1px solid black;}</style></div>");


	}

}
