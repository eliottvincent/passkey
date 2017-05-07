<?php

/**
 * Created by PhpStorm.
 * User: chloecorfmat
 * Date: 04/05/2017
 * Time: 18:05
 */
class CreateDoorController extends RouterController
{
	public function __construct()
	{
		if (!isset($_POST['door_name']) && !isset($_POST['door_building']) && !isset($_POST['door_floor'])) {
			// If we have no values, the form is displayed.
			$this->displayForm();
		} elseif (empty($_POST['door_name']) || empty($_POST['door_building'] || empty($_POST['door_floor']))){
			// If we have not all values, error message display and form.
			$this->errorForm();
		} else {
			// If we have all values, the form is displayed.
			$this->writeInFile();
		}
	}

	public function displayForm() {
		$composite = new CompositeView();
		$templates[] = array("name" => "head.php");
		$templates[] = array("name" => "header.php");
		$templates[] = array("name" => "body.php");
		$templates[] = array("name" => "create_door.html.twig");
		$templates[] = array("name" => "foot.php");
		$templates[] = array("name" => "footer.php");
		$composite->displayView($templates);
	}

	public function errorForm() {
		$composite = new CompositeView();
		$templates[] = array("name" => "head.php");
		$templates[] = array("name" => "header.php");
		$templates[] = array("name" => "body.php");
		$templates[] = array("name" => "submit_door.html.twig", "variables" => array("alert" => "Aucune valeur n'a été rentrée. Merci de compléter tous les champs."));
		$templates[] = array("name" => "create_door.html.twig");
		$templates[] = array("name" => "foot.php");
		$templates[] = array("name" => "footer.php");
		$composite->displayView($templates);
	}

	public function writeInFile() {
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Hello');
		$objPHPExcel->getActiveSheet()->setTitle('Portes');

		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		$fileName = "datas.xlsx";
		$folder = "datas";

		//Create the Result Directory if Directory is not created already
		if (!file_exists($folder)) {
			mkdir($folder);
		}

		$fullpath = $folder . '/' . $fileName;
		$objWriter->save($fullpath);
	}
}
