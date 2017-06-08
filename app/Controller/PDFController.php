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

	}
	public function test($html){
		$dompdf = new DOMPDF();  //if you use namespaces you may use new \DOMPDF()
		$dompdf->loadHtml($html);
		$dompdf->render();
		$dompdf->stream("sample.pdf", array("Attachment"=>0));
	}
	public function creationPDF(){
		$keyname = $_GET['keyname'];
		$username = $_GET['user'];

		$this->test("<div><p> Bonjour, vous etes ". $username .
			" et la clé que vous avez emprunter est : " . $keyname .
			" ! </p></div>");
	}
}
