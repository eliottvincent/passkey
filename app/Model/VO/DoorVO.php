<?php
class DoorVO
{
	public static $doorsList = array();
	public static $lastId = -1;
	private $id;

	public function __construct()
	{
		// Generate id for each door
		$this->lastId++;
		$this->id = $this->lastId;
		array_push($this->doorsList, $this);
	}

	// GETTER
	public function getId() {
		return $this->id;
	}

	// SETTER
	public function setId($id) {
		$this->id = $id;
	}

}
