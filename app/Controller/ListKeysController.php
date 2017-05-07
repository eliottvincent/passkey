<?php

/**
 * Created by PhpStorm.
 * User: chloecorfmat
 * Date: 07/05/2017
 * Time: 16:15
 */
class ListKeysController
{
	public function __construct()
	{
		if (isset($_GET['delete']) && !empty($_GET['delete'])) {
			$id = explode('delete_k', $_GET['delete'])[1];
			$delete = $this->deleteKey($id);

			if ($delete) {
				$this->displayDeleteKey('success', 'La clé a bien été supprimée');
			} else {
				$this->displayDeleteKey('danger', 'La clé n\'existe pas.');
			}

		} else {
			$this->displayList();
		}
	}

	public function deleteKey($id) {
		$row = $id+2;

		$objReader = new PHPExcel_Reader_Excel2007();
		$objPHPExcel = $objReader->load("datas/datas.xlsx");

		$objPHPExcel->setActiveSheetIndex(2);
		$lastRow = $objPHPExcel->getActiveSheet()->getHighestDataRow();

		$id = $objPHPExcel->getActiveSheet()->getCell('A'.$row);

		if ($id != '') {
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, '');
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, '');
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, '');
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$row, '');
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$row, '');

			$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
			$objWriter->save("datas/datas.xlsx");

			return true;
		} else {
			return false;
		}
	}

	public function displayList() {
		$keys = CreateKeyController::getKeys();
		$composite = new CompositeView();
		$templates[] = array("name" => "head.html.twig", 'variables' => array('title' => 'Liste des clés'));
		$templates[] = array("name" => "header.php");
		$templates[] = array("name" => "body.php");
		$templates[] = array("name" => "keys/list_keys.html.twig", 'variables' => array('keys' => $keys));
		$templates[] = array("name" => "foot.php");
		$templates[] = array("name" => "footer.php");
		$composite->displayView($templates);
	}

	public function displayDeleteKey($type, $message) {
		$keys = CreateKeyController::getKeys();
		$composite = new CompositeView();
		$templates[] = array("name" => "head.html.twig", 'variables' => array('title' => 'Liste des clés'));
		$templates[] = array("name" => "header.php");
		$templates[] = array("name" => "body.php");
		$templates[] = array("name" => "submit_message.html.twig", "variables" => array("alert_type" => $type , "alert_message" => $message));
		$templates[] = array("name" => "keys/list_keys.html.twig", 'variables' => array('keys' => $keys));
		$templates[] = array("name" => "foot.php");
		$templates[] = array("name" => "footer.php");
		$composite->displayView($templates);
	}
}
