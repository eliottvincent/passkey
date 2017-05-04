<?php

/**
 * Created by PhpStorm.
 * User: chloecorfmat
 * Date: 04/05/2017
 * Time: 15:53
 */
class AlertVO
{
	public static $alertTypes = array("Simple"=>"Clé");

	private $type;
	private $message;

	public function __construct($type, $message)
	{
		$this->type = $type;
		$this->message = $message;
	}

	// GETTER
	/**
	 * @return mixed
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @return mixed
	 */
	public function getMessage()
	{
		return $this->message;
	}

	// SETTER

	/**
	 * @param mixed $type
	 */
	public function setType($type)
	{
		$this->type = $type;
	}

	/**
	 * @param mixed $message
	 */
	public function setMessage($message)
	{
		$this->message = $message;
	}

}
