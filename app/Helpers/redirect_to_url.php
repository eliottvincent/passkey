<?php
/**
 * Created by PhpStorm.
 * User: eliottvincent
 * Date: 03/06/2017
 * Time: 15:46
 */

function redirectToUrl($url) {

	// header redirection doesn't work on some environments...
	//header("Location: " . $newUrl);

	// ...thus we use script injection
	echo "<script> window.location.replace('" . $url . "') </script>";


}
