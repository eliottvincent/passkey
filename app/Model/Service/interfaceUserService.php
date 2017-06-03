<?php

/**
 * Created by PhpStorm.
 * User: eliottvincent
 * Date: 01/06/2017
 * Time: 13:59
 */

interface interfaceUserService
{
	public function getUsers();

	public function getUser($enssatPrimaryKey);

	public function deleteUser($enssatPrimaryKey);

	public function checkUnicity($enssatPrimaryKey);

	public function saveUser($user);
}
