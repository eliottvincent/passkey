<?php
class DoorVO
{
	public static $doorsList = array();
	private $id;
	private $name;
	private $building;
	private $floor;

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
	public function getBuilding()
	{
		return $this->building;
	}

	/**
	 * @return mixed
	 */
	public function getFloor()
	{
		return $this->floor;
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
	 * @param mixed $building
	 */
	public function setBuilding($building)
	{
		$this->building = $building;
	}

	/**
	 * @param mixed $floor
	 */
	public function setFloor($floor)
	{
		$this->floor = $floor;
	}
}
