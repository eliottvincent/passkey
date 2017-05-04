<?php
class UserVO
{

    protected $ur1identifier; //code apogee ou harpege, selon statu
    public function setUr1Identifier($id) {
        $this->ur1identifier = $id;
    }
    public function getUr1Identifier() {
        return $this->ur1identifier;
    }


    protected $enssatPrimaryKey; //32 bits
    public function setEnssatPrimaryKey($id) {
        $this->enssatPrimaryKey = $id;
    }
    public function getEnssatPrimaryKey() {
        return $this->enssatPrimaryKey;
    }

    protected $username;
    public function setUsername($username) {
        $this->username = $username;
    }
    public function getUsername() {
        return $this->username;
    }

    protected $name;
    public function setName($name) {
        $this->name = $name;
    }
    public function getName() {
        return $this->name;
    }

    protected $surname;
    public function setSurname($surname) {
        $this->surname = $surname;
    }
    public function getSurname() {
        return $this->surname;
    }

    protected $phone;
    public function setPhone($phone) {
        $this->phone = $phone;
    }
    public function getPhone() {
        return $this->phone;
    }

    protected $status; //Etudiant, Exterieur, personel
    public function setStatus($status) {
        $this->status = $status;
    }
    public function getStatus() {
        return $this->status;
    }

    protected $email;
    public function setEmail($email) {
        $this->email = $email;
    }
    public function getEmail() {
        return $this->email;
    }

    protected $password;
    public function setPassword($password) {
    	$this->password = $password;
	}
	public function getPassword() {
    	return $this->password;
	}
}
