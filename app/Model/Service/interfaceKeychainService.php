<?php

/**
 * Created by PhpStorm.
 * User: eliottvincent
 * Date: 12/06/2017
 * Time: 08:55
 */
interface interfaceKeychainService
{
	public function getKeychains();

	public function getKeychain($id);

	public function saveKeychain($keychainArray);

	public function deleteKeychain($id);

	public function updateKeychain($keychainArray);

	public function checkUnicity($id);
}
