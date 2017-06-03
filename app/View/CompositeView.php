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

	public function __construct($default = null, $title = 'Default title', $subtitle = null, $activePage = null) {

		if ($default !== null && $default === true) {
			$head = new View("head.html.twig", array('title' => $title));
			$header = new View("header.html.twig", array('session' => $_SESSION));
			$sidebar = new View("sidebar.html.twig", array('activePage' => $activePage));
			$content_start = new View("content_start.html.twig", array('title' => $title, 'subtitle' => $subtitle));
			$quicksidebar = new View("quicksidebar.html.twig");
			$content_end = new View("content_end.html.twig");
			$footer = new View("footer.html.twig");
			$quicknav = new View("quicknav.html.twig");
			$foot = new View("foot.html.twig");

			$this->attachView($head)
				->attachView($header)
				->attachView($sidebar)
				->attachView($content_start)
				->attachView($quicksidebar)
				->attachView($content_end)
				->attachView($footer)
				->attachView($quicknav)
				->attachView($foot);
		}
	}

	/**
	 * @param View $view
	 * @return $this
	 *
	 * adds a View at the end of views[]
	 */
	public function attachView(View $view) {

		// sometimes the view is null
		if ($view !== null) {

			if (!in_array($view, $this->views, true)) {
				$this->views[] = $view;
			}
		}
		return $this;
	}

	/**
	 * @param View $view
	 * @return $this
	 *
	 *
	 */
	public function detachView(View $view) {
		$this->views = array_filter($this->views, function ($value) use ($view) {
			return $value !== $view;
		});
		return $this;
	}

	/**
	 * @param View $view
	 *
	 * adds a View in views[], between content_start and content_end
	 */
	public function attachContentView(View $contentView) {

		if ($contentView !== null) {

			// first we need to search the position of the quicksidebar View...
			// ...because we want to insert the content View just before it
			$quicksidebarPosition = 0;
			foreach ($this->views as $pos => $currentView) {
				if ($currentView->getTemplate() === 'quicksidebar.html.twig') {
					$quicksidebarPosition = $pos;
				}
			}

			// then we separate $this->views[] in quicksidebarPosition
			// and we insert the content View
			// we merge the three arrays
			$this->views = array_merge(
				array_slice( $this->views, 0, $quicksidebarPosition, true ),
				array($contentView),
				array_slice( $this->views, $quicksidebarPosition, null, true )
			);
		}
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
