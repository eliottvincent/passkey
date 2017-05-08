<?php
class DoorVO
{
	public static $doorsList = array();
	public static $lastId = -1;
	private $id;
	private $name;
	private $building;
	private $floor;

	public function __construct($name, $building, $floor)
	{
		// Generate id for each door
		$this->lastId++;
		$this->id = $this->lastId;

		$this->name = $name;
		$this->building = $building;
		$this->floor = $floor;

		array_push($this->doorsList, $this);
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
