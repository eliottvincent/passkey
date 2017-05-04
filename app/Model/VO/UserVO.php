<?php

/**
 * Created by PhpStorm.
 * User: chloecorfmat
 * Date: 04/05/2017
 * Time: 14:25
 */
class UserVO
{
	private $identifier; // code apogee ou harpege
	private $enssatPrimaryKey; // 32 bits
	private $username;
	private $name;
	private $surname;
	private $phone;
	private $status; // etudiant, exterieur, personnel
	private $email;

	public function setIdentifier($identifier) {
		$this->identifier = $identifier;
	}
	public function getIdentifier() {
		return $this->identifier;
	}

	public function setEnssatPrimaryKey($id) {
		$this->enssatPrimaryKey = $id;
	}
	public function getEnssatPrimaryKey() {
		return $this->enssatPrimaryKey;
	}

	public function setUsername($username) {
		$this->username = $username;
	}
	public function getUsername() {
		return $this->username;
	}

	public function setName($name) {
		$this->name = $name;
	}
	public function getName() {
		return $this->name;
	}

	public function setSurname($surname) {
		$this->surname = $surname;
	}
	public function getSurname() {
		return $this->surname;
	}

	public function setPhone($phone) {
		$this->phone = $phone;
	}
	public function getPhone() {
		return $this->phone;
	}

	public function setStatus($status) {
		$this->status = $status;
	}
	public function getStatus() {
		return $this->status;
	}

	public function setEmail($email) {
		$this->email = $email;
	}
	public function getEmail() {
		return $this->email;
	}
}
