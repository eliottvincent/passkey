<?php

/**
 * Created by PhpStorm.
 * User: eliottvincent
 * Date: 08/06/2017
 * Time: 15:27
 */
interface interfaceRoomService
{

	public function getRooms();

	public function getRoom($id);

	public function saveRoom($roomArray);

	public function deleteRoom($id);

	public function updateRoom($roomArray);

	public function checkUnicity($id);

}
