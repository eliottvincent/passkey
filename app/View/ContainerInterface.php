<?php
/**
 * Created by PhpStorm.
 * User: eliottvincent
 * Date: 02/05/2017
 * Time: 23:17
 */

interface ContainerInterface
{
	public function __set($field, $value);
	public function __get($field);
	public function __isset($field);
	public function __unset($field);
}
