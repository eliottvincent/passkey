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
		$this->_keychainService = implementationKeychainService_Dummy::getInstance();
		$this->_userService = implementationUserService_Dummy::getInstance();
		$this->_borrowingService = implementationBorrowingService_Dummy::getInstance();
	}
	public function renderPDF($html){
		$dompdf = new DOMPDF();  //if you use namespaces you may use new \DOMPDF()
		$dompdf->loadHtml($html);
		$dompdf->render();
		$dompdf->stream("sample.pdf", array("Attachment"=>0));
	}




	public function generatePDF() {

		$keychainid = $_GET['keyname'];
		$userid = $_GET['user'];
		$borid = $_GET['borid'];

		$keychain = $this->getKeychain($keychainid);
		$user = $this->getUser($userid);

		$username = $user->getUsername();
		$keychainname = $keychain->getName();

		$keyinborrowing = $this->getKeysInBorrow($borid);
		$roominborrowing = $this->getOpenedRooms($borid);
		//$keyinborrowing = $this->_borrowingService->getKeysInBorrow($borid);

		$strg = "<html>
				<head>
					<meta charset=\"utf-8\" />
					<title>Rendu_PDF</title>
				</head>
				
				<body>
					<h1>L'emprunt demandé a bien été réalisé</h1>
					<table>
						<tr >
							<td >
								L'utilisateur " . $username . " a emprunté le trousseau : ".$keychainname . "
							</td>
						</tr>
						<tr>
							<td>
								le trousseau ".$keychainname . " est composé des clés : </td></tr>";

						foreach ($keyinborrowing as $key){
							$strg = $strg."<tr><td>". $key->getName() . "</td></tr>";
						}

						$strg = $strg."<tr>
							<td >
								Il permet d'ouvrir les salles :
							</td>
						</tr>";

						foreach ($roominborrowing as $room) {
							$strg = $strg."<tr><td>". $room->getName() . "</td></tr>";
						}

						$strg = $strg."
						<tr>
							<td >
								Pour confirmer l'emprunt, merci de signer ce reçu
							</td>
						</tr>
							<tr>
								<td>
									La scolarité : 
								</td>
								<td style=\"width:300px; height:100px;\">
									" . $username . "  : 
								</td>
						</tr>	
					</table>
					</body>
					
				</html>
				<style>
				tr /* Toutes les cellules des tableaux... */
				table
				{
					border-collapse: collapse;
				}
				table /* Mettre une bordure sur les td ET les th */
				{
					border: 1px solid black;
				}
				</style>";


		/*$this->test("<div><h1>L'application " . $username . " PassKey ".$keychainname . "vous remercie de votre emprunt</h1>
        <p> <L'utilisateur ". $username .
        " a emprunté la clé : " . $keychainname.
        " ! </p></div>");*/

		$this->renderPDF($strg);
	}



	//================================================================================
	// calls to Service
	//================================================================================

	/**
	 * @param $enssatPrimaryKey
	 * @return mixed
	 */
	private function getUser($enssatPrimaryKey) {

		return $this->_userService->getUser($enssatPrimaryKey);
	}

	/**
	 * @param $id
	 * @return mixed
	 */
	public function getKeychain($id) {

		return $this->_keychainService->getKeychain($id);
	}

	/**
	 * @param $id
	 * @return array
	 */
	public function getKeysInBorrow($id) {

		return $this->_borrowingService->getKeysInBorrow($id);
	}

	/**
	 * @param $id
	 * @return array
	 */
	public function getOpenedRooms($id) {

		return $this->_borrowingService->getOpenedRooms($id);
	}
}
