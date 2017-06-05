<?php

/**
 * Created by PhpStorm.
 * User: eliottvincent
 * Date: 05/06/2017
 * Time: 12:24
 */
class BorrowingVO {

	private $id;
	private $borrowDate;
	private $dueDate;
	private $returnDate;
	private $lostDate;
	private $keychain;
	private $user;
	private $status;


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
	public function getBorrowDate()
	{
		return $this->borrowDate;
	}

	/**
	 * @param mixed $borrowDate
	 */
	public function setBorrowDate($borrowDate)
	{
		$this->borrowDate = $borrowDate;
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
	public function getReturnDate()
	{
		return $this->returnDate;
	}

	/**
	 * @param mixed $returnDate
	 */
	public function setReturnDate($returnDate)
	{
		$this->returnDate = $returnDate;
	}

	/**
	 * @return mixed
	 */
	public function getLostDate()
	{
		return $this->lostDate;
	}

	/**
	 * @param mixed $lostDate
	 */
	public function setLostDate($lostDate)
	{
		$this->lostDate = $lostDate;
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

	/**
	 * @return mixed
	 */
	public function getStatus()
	{
		return $this->status;
	}

	/**
	 * @param mixed $status
	 */
	public function setStatus($status)
	{
		$this->status = $status;
	}



}
