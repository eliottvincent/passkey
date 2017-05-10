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

	public function attachView(ViewInterface $view) {
		if (!in_array($view, $this->views, true)) {
			$this->views[] = $view;
		}
		return $this;
	}

	public function detachView(ViewInterface $view) {
		$this->views = array_filter($this->views, function ($value) use ($view) {
			return $value !== $view;
		});
		return $this;
	}

	public function render() {
		$output = "";
		foreach ($this->views as $view) {
			$output .= $view->render();
		}
		return $output;
	}

	/**
	 * @param $templates An array of templates and variables
	 */
	public function displayView($templates) {
		$twig = $this->twigInstance();
		$this->displayTemplate($twig, $templates);
	}

	public function twigInstance() {
		$loader = new Twig_Loader_Filesystem('app/View/partials');
		$twig = new Twig_Environment($loader, array('debug' => true));
		$twig->addExtension(new Twig_Extension_Debug());
		return $twig;
	}

	public function getTemplate($twig, $template) {
		if (isset($template['variables']) && !empty($template['variables'])) {
			$temp = $twig->render($template['name'], $template['variables']);
		} else {
			$temp = $twig->render($template['name']);
		}

		return $temp;
	}

	public function displayTemplate($twig, $templates) {
		foreach($templates as $template) {
			$template = $this->getTemplate($twig, $template);
			echo $template;
		}
	}
}
