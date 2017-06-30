<?php

/**
 * Created by PhpStorm.
 * User: eliottvincent
 * Date: 07/06/2017
 * Time: 09:44
 */
interface interfaceActionService {

	public function getActions();

	public function getAction($id);

	public function saveAction($actionArray);

	public function deleteAction($id);

	public function updateAction($actionArray);

	public function checkUnicity($id);

}
