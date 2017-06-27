<?php
class DoorVO
{
	private $id;
	private $name;
	private $room;

	public function __construct() {
	}

	// GETTER
	public function getId() {
		return $this->id;
	}

	/**
	 * @return mixed
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return mixed
	 */
	public function getRoom()
	{
		return $this->room;
	}




	// SETTER
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @param mixed $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @param mixed $room
	 */
	public function setRoom($room)
	{
		$this->room = $room;
	}



}
