<?php
class LockVO
{
    private $id;
    private $length;
    private $door; // the door ID

    // GETTER
    public function getId() {
        return $this->id;
    }

	public function getLength() {
		return $this->length;
	}

	public function getDoor() {
    	return $this->door;
	}

    // SETTER
	public function setId($id) {
		$this->id = $id;
	}

    public function setLength($length) {
        $this->length = $length;
    }

    public function setDoor($doon) {
    	$this->door = $door;
	}
}
