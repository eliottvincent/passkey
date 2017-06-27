<?php
class RoomVO
{
    private $id;
    private $name;
    private $building;
    private $floor;
    private $doors;

	/**
	 * RoomVO constructor.
	 * @param $id
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
	public function getBuilding()
	{
		return $this->building;
	}

	/**
	 * @param mixed $building
	 */
	public function setBuilding($building)
	{
		$this->building = $building;
	}

	/**
	 * @return mixed
	 */
	public function getFloor()
	{
		return $this->floor;
	}

	/**
	 * @param mixed $floor
	 */
	public function setFloor($floor)
	{
		$this->floor = $floor;
	}

	/**
	 * @return mixed
	 */
	public function getDoors() {
		return $this->doors;
	}

	/**
	 * @param mixed $doors
	 */
	public function setDoors($doors)
	{
		$this->doors = $doors;
	}

	/**
	 * @param $door
	 */
	public function addDoor($door) {
		array_push($this->doors, $door);

	}


}
