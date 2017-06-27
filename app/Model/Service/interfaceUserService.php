<?php

/**
 * Created by PhpStorm.
 * User: eliottvincent
 * Date: 01/06/2017
 * Time: 13:59
 */

interface interfaceUserService {

	public function getUsers();

	public function getUser($enssatPrimaryKey);

	public function saveUser($userArray);

	public function deleteUser($enssatPrimaryKey);

	public function updateUser($userArray);

	public function checkUnicity($enssatPrimaryKey);
}
