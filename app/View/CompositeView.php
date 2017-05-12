<?php

/**
 * Created by PhpStorm.
 * User: eliottvincent
 * Date: 02/05/2017
 * Time: 23:18
 */
class CompositeView implements ViewInterface
{
	protected $views = array();

	public function __construct($default = null) {

		if ($default !== null && $default === true) {
			$head = new View(null, null,"head.html.twig", array('title' => 'Default title'));
			$header = new View(null, null,"header.html.twig", array('session' => $_SESSION));
			$sidebar = new View(null, null,"sidebar.html.twig");
			$content = new View(null, null,"content.html.twig");
			$quicksidebar = new View(null, null,"quicksidebar.html.twig");
			$footer = new View(null, null,"footer.html.twig");
			$quicknav = new View(null, null,"quicknav.html.twig");
			$foot = new View(null, null,"foot.html.twig");

			$this->attachView($head)
				->attachView($header)
				->attachView($sidebar)
				->attachView($content)
				->attachView($quicksidebar)
				->attachView($footer)
				->attachView($quicknav)
				->attachView($foot);
		}
	}

	public function attachView(View $view) {
		if (!in_array($view, $this->views, true)) {
			$this->views[] = $view;
		}
		return $this;
	}

	public function detachView(View $view) {
		$this->views = array_filter($this->views, function ($value) use ($view) {
			return $value !== $view;
		});
		return $this;
	}

	public function oldRenderMethod() {
		$output = "";
		foreach ($this->views as $view) {
			$output .= $view->render();
		}
		return $output;
	}

	public function render() {
		$output = "";
		foreach($this->views as $view) {
			$output .= $view->render();
		}
		return $output;
	}
}
