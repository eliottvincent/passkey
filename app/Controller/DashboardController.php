<?php

	class DashboardController
	{
		public function displayDash() {
			$composite = new CompositeView(
				true,
				"Dashboard",
				null,
				"dashboard",
				null,
				array("waypointsScript" => "app/View/assets/global/plugins/counterup/jquery.waypoints.min.js",
					"counterupScript" => "app/View/assets/global/plugins/counterup/jquery.counterup.min.js"));

			$displayDash = new View('dashboard/dashboard.html.twig');
			$composite->attachContentView($displayDash);

			echo $composite->render();
		}
	}
 ?>
