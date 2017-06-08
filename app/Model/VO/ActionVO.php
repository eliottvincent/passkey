<?php

/**
 * Created by PhpStorm.
 * User: eliottvincent
 * Date: 07/06/2017
 * Time: 09:53
 */
class ActionVO
{
	private static $actionTypes = array(
		"borrowing_creation",
		"borrowing_update",
		"borrowing_deletion",

		"door_creation",
		"door_update",
		"door_deletion",

		"keychain_creation",
		"keychain_update",
		"keychain_deletion",

		"key_creation",
		"key_update",
		"key_deletion",

		"lock_creation",
		"lock_update",
		"lock_deletion",

		"user_creation",
		"user_update",
		"user_deletion",
	);

	private $id; // 32 bits

	private $type;

	private $date;

	/**
	 * @return array
	 */
	public static function getActionTypes(): array
	{
		return self::$actionTypes;
	}

	/**
	 * @param array $actionTypes
	 */
	public static function setActionTypes(array $actionTypes)
	{
		self::$actionTypes = $actionTypes;
	}

	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param mixed $id
	 */
	public function setId($id)
	{
		$this->id = $id;
	}

	/**
	 * @return mixed
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @param mixed $type
	 */
	public function setType($type)
	{
		$this->type = $type;
	}

	/**
	 * @return mixed
	 */
	public function getDate()
	{
		return $this->date;
	}

	/**
	 * @param mixed $date
	 */
	public function setDate($date)
	{
		$this->date = $date;
	}



}
