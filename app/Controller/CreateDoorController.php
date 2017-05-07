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
		} elseif (empty($_POST['door_name']) || empty($_POST['door_building']) || empty($_POST['door_floor'])){
			// If we have not all values, error message display and form.
			$type = "danger";
			$message = "Toutes les valeurs nécessaires n'ont pas été trouvées. Merci de compléter tous les champs.";
			$this->doorMessage($type, $message);
		} else {
			// If we have all values, the form is displayed.
			if (!file_exists('datas/datas.xlsx')) {
				$this->createDoorFile();
			}

			$datas = array(
				'door_name' => addslashes($_POST['door_name']),
				'door_building' => addslashes($_POST['door_building']),
				'door_floor' => addslashes($_POST['door_floor'])
			);
			$this->writeInFile($datas);

			$type = "success";
			$message = "La porte a bien été créée.";
			$this->doorMessage($type, $message);

		}
	}

	public function displayForm() {
		$composite = new CompositeView();
		$templates[] = array("name" => "head.html.twig", 'variables' => array('title' => 'Ajouter une porte'));
		$templates[] = array("name" => "header.php");
		$templates[] = array("name" => "body.php");
		$templates[] = array("name" => "doors/create_door.html.twig");
		$templates[] = array("name" => "foot.html.twig");
		$templates[] = array("name" => "footer.php");
		$composite->displayView($templates);
	}

	public function doorMessage($type, $message) {
		$composite = new CompositeView();
		$templates[] = array("name" => "head.html.twig", 'variables' => array('title' => 'Ajouter une porte'));
		$templates[] = array("name" => "header.php");
		$templates[] = array("name" => "body.php");
		$templates[] = array("name" => "submit_message.html.twig", "variables" => array("alert_type" => $type , "alert_message" => $message));
		$templates[] = array("name" => "doors/create_door.html.twig");
		$templates[] = array("name" => "foot.html.twig");
		$templates[] = array("name" => "footer.php");
		$composite->displayView($templates);
	}

	public function writeInFile($datas) {
		$objReader = new PHPExcel_Reader_Excel2007();
		$objPHPExcel = $objReader->load("datas/datas.xlsx");

		$objPHPExcel->setActiveSheetIndex(0);
		$lastRow = $objPHPExcel->getActiveSheet()->getHighestDataRow();
		$id = $lastRow-1;
		$row = $lastRow + 1;

		$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $id);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $datas['door_name']);
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $datas['door_building']);
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$row, $datas['door_floor']);

		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		$objWriter->save("datas/datas.xlsx");
	}

	public function createDoorFile() {
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Door id');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Door name');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Door building');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Door floor');
		$objPHPExcel->getActiveSheet()->setTitle('Doors');

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

	public static function getDoors() {
		$doors = array();
		// Read Excel file.
		$objReader = new PHPExcel_Reader_Excel2007();
		$objPHPExcel = $objReader->load("datas/datas.xlsx");
		$objPHPExcel->setActiveSheetIndex(0);
		$sheet = $objPHPExcel->getActiveSheet();
		$lastRow = $sheet->getHighestDataRow();

		for ($i = 2; $i <= $lastRow; $i++) {
			$door_id = $sheet->getCell('A'.$i)->getValue();
			$door_name = $sheet->getCell('B'.$i)->getValue();

			$doors[] = array('door_id' => $door_id, 'door_name' => $door_name);

		}
		return $doors;
	}
}
