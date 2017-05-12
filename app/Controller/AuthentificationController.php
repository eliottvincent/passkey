<?php

/**
 * Created by PhpStorm.
 * User: eliottvincent
 * Date: 04/05/2017
 * Time: 14:40
 */
class AuthentificationController
{
	function check() {
		session_start();
		if (!isset($_SESSION["USERNAME"]))
		{
			$url = $_SERVER["REQUEST_URI"];
			header("Location: ?action=showLoginPageTest&url=".$url);
		}
	}

	function login() {

		if (isset($_REQUEST['username']) && !empty($_REQUEST['username']))
			$username = $_REQUEST['username'];
		else
			echo 'oups?! no username provided';
		// TODO : handle case here

		if (isset($_REQUEST['password']) && !empty($_REQUEST['password']))
			$password = $_REQUEST['password'];
		else
			echo 'oups?! no username provided';
		// TODO : handle case here

		$userDAO = implementationUserDAO_Dummy::getInstance();
		$users = $userDAO->getUsers();

		foreach ($users as $key => $user) {
			if ($user->getUsername() === $username) {
				$userFound = $user;
			}
		}

		if (isset($userFound) && $userFound != null) {

			if ($userFound->getPassword() === $password) {

				session_start();
				$_SESSION['USERNAME']= $username;
				//header("Location: /");
				$url = $_SERVER["HTTP_ORIGIN"] . "/";
				echo "<script> window.location.replace('" . $url . "') </script>";
				//echo $url;
				//header("Location " . $url);
			}
			else {
				// TODO : handle wrong password here
			}
		}
		else {
			// TODO : handle wrong username here
		}
	}

	function logout() {
		// do not remove the echo, otherwise the redirection doesn't work

		session_start();
		$url = $_REQUEST["url"];
		session_destroy();
		//header("Location: $url");
		echo "<script> window.location.replace('" . $url . "') </script>";
	}
}
