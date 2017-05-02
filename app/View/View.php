<?php

/**
 * Created by PhpStorm.
 * User: eliottvincent
 * Date: 02/05/2017
 * Time: 22:13
 */
class View
{
	// a View is linked to a Model and a Controller
	private $model;
	private $controller;

	public function __construct($controller, $model) {
		$this->controller = $controller;
		$this->model = $model;
	}


	// basic function
	public function output() {
		$html = '<a href="?action=clicked" >Test</a>';
		$html .= '<p>' . $this->model->string . '</p>';
		return $html;

	}

	// write more rendering functions

}
