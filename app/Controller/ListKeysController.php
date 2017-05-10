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
			$exist = false;
			if (file_exists('datas/datas.xlsx')) {
				$objReader = new PHPExcel_Reader_Excel2007();
				$objPHPExcel = $objReader->load("datas/datas.xlsx");
				$sheets = $objPHPExcel->getAllSheets();

				foreach ($sheets as $sheet) {
					if ($sheet->getTitle() == 'Keys') {
						if ($sheet->getHighestDataRow() != 1) {
							$exist = true;
						}
					}
				}
			}

			if ($exist) {
				$this->displayList(true);
			} else {
				$alert['type'] = 'danger';
				$alert['message'] = 'Aucune clé n\'a été créée.';
				$this->displayList(false, $alert);
			}
		}
	}

	public function deleteKey($id) {
		$row = $id+1;

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

	public function displayList($state, $alert = null) {
		if ($state) {
			$keys = CreateKeyController::getKeys();
		} else {
			$keys = null;
		}

		$composite = new CompositeView();
		$templates[] = array("name" => "head.html.twig", 'variables' => array('title' => 'Liste des clés'));
		$templates[] = array("name" => "header.html.twig");
		$templates[] = array("name" => "sidebar.html.twig");
		$templates[] = array("name" => "content.html.twig");

		if (isset($alert) && !empty($alert['type']) && !empty($alert['message'])) {
			$templates[] = array("name" => "submit_message.html.twig", "variables" => array("alert_type" => $alert['type'] , "alert_message" => $alert['message']));
		}

		$templates[] = array("name" => "keys/list_keys.html.twig", 'variables' => array('keys' => $keys));
		$templates[] = array("name" => "quicksidebar.html.twig");
		$templates[] = array("name" => "content_end.html.twig");
		$templates[] = array("name" => "foot.html.twig");
		$templates[] = array("name" => "footer.html.twig");
		$composite->displayView($templates);
	}

	public function displayDeleteKey($type, $message) {
		$keys = CreateKeyController::getKeys();
		$composite = new CompositeView();
		$templates[] = array("name" => "head.html.twig", 'variables' => array('title' => 'Liste des clés'));
		$templates[] = array("name" => "header.html.twig");
		$templates[] = array("name" => "sidebar.html.twig");
		$templates[] = array("name" => "content.html.twig");
		$templates[] = array("name" => "submit_message.html.twig", "variables" => array("alert_type" => $type , "alert_message" => $message));
		$templates[] = array("name" => "keys/list_keys.html.twig", 'variables' => array('keys' => $keys));
		$templates[] = array("name" => "quicksidebar.html.twig");
		$templates[] = array("name" => "content_end.html.twig");
		$templates[] = array("name" => "foot.html.twig");
		$templates[] = array("name" => "footer.html.twig");
		$composite->displayView($templates);
	}
}
