<?php

/**
 * Created by PhpStorm.
 * User: eliottvincent
 * Date: 05/06/2017
 * Time: 12:24
 */
class BorrowingVO {

	private $id;
	private $dueDate;
	private $keychain;
	private $user;

	/**
	 * BorrowingVO constructor.
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
	public function getDueDate()
	{
		return $this->dueDate;
	}

	/**
	 * @param mixed $dueDate
	 */
	public function setDueDate($dueDate)
	{
		$this->dueDate = $dueDate;
	}

	/**
	 * @return mixed
	 */
	public function getKeychain()
	{
		return $this->keychain;
	}

	/**
	 * @param mixed $keychain
	 */
	public function setKeychain($keychain)
	{
		$this->keychain = $keychain;
	}

	/**
	 * @return mixed
	 */
	public function getUser()
	{
		return $this->user;
	}

	/**
	 * @param mixed $user
	 */
	public function setUser($user)
	{
		$this->user = $user;
	}




}
