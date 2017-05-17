<?php
/**
 * Created by PhpStorm.
 * User: chloecorfmat
 * Date: 16/05/2017
 * Time: 09:08
 */

require_once 'app/Controller/KeyController.php';

session_start();

if (isset($_POST['value'])) {

	$first = substr($_POST['value'], 0, 1);

	if ($first == 'k') {
		$key = new KeyController();
		$key->deleteKey($_POST['value']);
		$keys = KeyController::getKeys();
	}
	$response['keys'] = $keys;
	$response['status'] = 'success';
	$response['message'] = 'This was successful';
} else {
	$response['status'] = 'error';
	$response['message'] = 'This failed';
}

echo json_encode($response);
