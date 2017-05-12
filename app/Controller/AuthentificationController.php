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
		if (!isset($_SESSION["USERNAME"])) {
			$url = $_SERVER["REQUEST_URI"];
			header("Location: ?action=showLoginPageTest&url=".$url);
		}
	}

	function login() {

		// if there is a username
		if (isset($_REQUEST['username']) && !empty($_REQUEST['username'])) {
			$username = $_REQUEST['username'];

			$userDAO = implementationUserDAO_Dummy::getInstance();
			$users = $userDAO->getUsers();

			foreach ($users as $key => $user) {
				if ($user->getUsername() === $username) {
					$userFound = $user;
				}
			}

			if (isset($userFound) && $userFound != null) {

				if (isset($_REQUEST['password']) && !empty($_REQUEST['password'])) {
					$password = $_REQUEST['password'];

					if ($userFound->getPassword() === $password) {

						session_start();
						$_SESSION['USERNAME']= $username;

						$url = $_SERVER["HTTP_REFERER"];
						$newUrl = substr($url, 0, strpos($url, "?"));

						// header redirection doesn't work on some environments...
						// header("Location: " . $newUrl);

						// ...thus we use script injection
						echo "<script> window.location.replace('" . $newUrl. "') </script>";
					}

					else {
						$this->resendLoginPage('danger', 'Mot de passe invalide.');
					}
				}

				else {
					// redirecting to login with bad password alert
					$this->resendLoginPage('danger', 'Mot de passe invalide.');
				}

			}
			else {
				$this->resendLoginPage('danger', 'Login invalide.');
			}
		}

		else {
			// redirecting to login page with bad username alert
			$this->resendLoginPage('danger', 'Login invalide.');
		}


	}

	function resendLoginPage($type, $message) {
		$compositeView = new CompositeView();

		$headView 	= new View(null, null, "head.html.twig", array('title' => "Login"));
		$bodyView 	= new View(null, null, "login_body.html.twig");
		$submit_message = new View(null, null, "submit_message.html.twig", array('alert_type' => $type , 'alert_message' => $message));
		$footView 	= new View(null, null, "foot.html.twig");

		$compositeView->attachView($headView)
			->attachView($submit_message)
			->attachView($bodyView)
			->attachView($footView);

		echo $compositeView->render();
	}
	function logout() {
		// do not remove the echo, otherwise the redirection doesn't work

		session_start();
		session_destroy();

		$url = $_SERVER["HTTP_REFERER"];
		$newUrl = substr($url, 0, strpos($url, "?"));

		echo "<script> window.location.replace('" . $newUrl. "') </script>";
	}
}
