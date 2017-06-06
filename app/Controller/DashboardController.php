<?php

	class DashboardController
	{
		public function displayDash() {
			$composite = new CompositeView(true, "Dashboard", null, null);

			$displayDash = new View(null, null, 'dashboard/dashboard.html.twig');
			$composite->attachContentView($displayDash);

			echo $composite->render();
		}
	}
 ?>
