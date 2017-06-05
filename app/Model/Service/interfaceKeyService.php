<?php

/**
 * Created by PhpStorm.
 * User: eliottvincent
 * Date: 04/06/2017
 * Time: 15:09
 */
interface interfaceKeyService {

	public function getKeys();

	public function getKey($id);

	public function saveKey($keyArray);

	public function deleteKey($id);

	public function updateKey($keyArray);

	public function checkUnicity($id);
}
