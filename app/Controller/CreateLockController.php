<?php

/**
 * Created by PhpStorm.
 * User: chloecorfmat
 * Date: 07/05/2017
 * Time: 12:26
 */
class CreateLockController
{
	public function __construct()
	{
		if (!isset($_POST['lock_name']) && !isset($_POST['lock_door']) && !isset($_POST['lock_number'])) {
			if (file_exists('datas/datas.xlsx')) {
				// If we have no values, the form is displayed.
				$this->displayForm(true);
			} else {
				$this->displayForm(false);
			}
		} elseif (empty($_POST['lock_name']) || empty($_POST['lock_door'])) {
			// If we have not all values, error message display and form.
			$type = "danger";
			$message = "Toutes les valeurs nécessaires n'ont pas été trouvées. Merci de compléter tous les champs.";
			$this->lockMessage($type, $message);
		} else {
			// If the sheet in datas.xlsx is not created.
			$exist = false;
			$objReader = new PHPExcel_Reader_Excel2007();
			$objPHPExcel = $objReader->load("datas/datas.xlsx");
			$sheets = $objPHPExcel->getAllSheets();

			foreach ($sheets as $sheet) {
				if ($sheet->getTitle() == 'Locks') {
					$exist = true;
				}
			}

			if ( !$exist) {
				$this->createLockFile();
			}


			/**foreach ($_POST['lock_door'] as $door) {
				$datas = array(
				'lock_name' => addslashes($_POST['lock_name']),
				'lock_door' => addslashes($door)
				);

				$this->writeInFile($datas);
			}**/

			$datas = array(
				'lock_name' => addslashes($_POST['lock_name']),
				'lock_door' => addslashes($_POST['lock_door'])
			);

			$this->writeInFile($datas);

			$type = "success";
			$message = "Le canon a bien été enregistré.";
			$this->lockMessage($type, $message);
		}

	}

	public function writeInFile($datas) {
		$objReader = new PHPExcel_Reader_Excel2007();
		$objPHPExcel = $objReader->load("datas/datas.xlsx");

		$objPHPExcel->setActiveSheetIndex(1);
		$lastRow = $objPHPExcel->getActiveSheet()->getHighestDataRow();
		$id = $lastRow-1;
		$row = $lastRow + 1;

		$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $id);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $datas['lock_name']);
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $datas['lock_door']);

		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		$objWriter->save("datas/datas.xlsx");
	}

	public function displayForm($state) {
		if ($state) {
			$doors = CreateDoorController::getDoors();
		} else {
			$doors = null;
		}
		$composite = new CompositeView(true, 'Ajouter un canon');
		$create_lock = new View(null, null,"locks/create_lock.html.twig", array('doors' => $doors));
		$composite->attachContentView($create_lock);

		echo $composite->render();
	}

	public function lockMessage($type, $message) {
		$doors = CreateDoorController::getDoors();
		$composite = new CompositeView(true, 'Ajouter un canon');

		$submit_message = new View(null, null, 'submit_message.html.twig', array('alert_type' => $type , 'alert_message' => $message));
		$create_lock = new View(null, null, 'locks/create_lock.html.twig', array('doors' => $doors));

		$composite->attachContentView($submit_message);
		$composite->attachContentView($create_lock);

		echo $composite->render();
	}

	public function createLockFile() {
		// We only modify the file datas.xlsx because we have to create doors before locks.
		$objReader = new PHPExcel_Reader_Excel2007();
		$objPHPExcel = $objReader->load("datas/datas.xlsx");

		// Create a new worksheet called "My Data"
		$myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, 'Locks');

		// Attach the "My Data" worksheet as the first worksheet in the PHPExcel object
		$objPHPExcel->addSheet($myWorkSheet);
		$objPHPExcel->setActiveSheetIndex(1);

		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Lock id');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Lock name');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Lock door');
		$objPHPExcel->getActiveSheet()->setTitle('Locks');

		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		$objWriter->save("datas/datas.xlsx");
	}

	public static function getLocks() {
		$doors = array();
		// Read Excel file.
		$objReader = new PHPExcel_Reader_Excel2007();
		$objPHPExcel = $objReader->load("datas/datas.xlsx");
		$objPHPExcel->setActiveSheetIndex(1);
		$sheet = $objPHPExcel->getActiveSheet();
		$lastRow = $sheet->getHighestDataRow();

		for ($i = 2; $i <= $lastRow; $i++) {
			$door_id = $sheet->getCell('A'.$i)->getValue();
			$door_name = $sheet->getCell('B'.$i)->getValue();

			$doors[] = array('lock_id' => $door_id, 'lock_name' => $door_name);

		}
		return $doors;
	}
}
