<?php


interface interfaceDoorService {

	public function getDoors();

	public function getDoor($id);

	public function saveDoor($doorArray);

	public function deleteDoor($id);

	public function updateDoor($doorArray);

	public function checkUnicity($id);
}
