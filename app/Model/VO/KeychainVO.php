<?php

class KeychainVO
{
	private $id;
	private $name;
	private $keys;
	private $creationDate;
	private $destructionDate;

	/**
	 * KeychainVO constructor.
	 */
	public function __construct() {

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
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param mixed $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @return mixed
	 */
	public function getKeys()
	{
		return $this->keys;
	}

	/**
	 * @param mixed $keys
	 */
	public function setKeys($keys)
	{
		$this->keys = $keys;
	}

	/**
	 * @return mixed
	 */
	public function getCreationDate()
	{
		return $this->creationDate;
	}

	/**
	 * @param mixed $creationDate
	 */
	public function setCreationDate($creationDate)
	{
		$this->creationDate = $creationDate;
	}

	/**
	 * @return mixed
	 */
	public function getDestructionDate()
	{
		return $this->destructionDate;
	}

	/**
	 * @param mixed $destructionDate
	 */
	public function setDestructionDate($destructionDate)
	{
		$this->destructionDate = $destructionDate;
	}

	public function addKey($key) {

		array_push($this->keys, $key);
	}

}
