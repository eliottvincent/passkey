<?php
/**
 * Created by PhpStorm.
 * User: eliottvincent
 * Date: 31/05/2017
 * Time: 14:45
 */

function getPreviousUrl() {

	if (!empty($_SERVER['HTTP_REFERER'])) {
		return $_SERVER['HTTP_REFERER'];
	} else {
		return "/";
	}
}
