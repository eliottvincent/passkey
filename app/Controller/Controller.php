<?php

/**
 * Created by PhpStorm.
 * User: eliottvincent
 * Date: 02/05/2017
 * Time: 22:13
 */
class Controller
{
	// a Controller is linked to a Model
	private $model;

	public function __construct($model){
		$this->model = $model;
	}

	// dummy function
	public function clicked() {

		// changing the data of the Model
		$this->model->string = 'Updated Data, thanks to MVC and PHP!';
    }
}
