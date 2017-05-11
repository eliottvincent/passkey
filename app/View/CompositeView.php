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
	protected $templates = array();

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

	public function attachTemplate(View $template) {
		if (!in_array($template, $this->templates, true)) {
			$this->templates[] = $template;
		}
		return $this;
	}

	public function detachTemplate(View $template) {
		$this->templates = array_filter($this->templates, function ($value) use ($template) {
			return $value !== $template;
		});
		return $this;
	}

	public function twigInstance() {
		$loader = new Twig_Loader_Filesystem('app/View/partials');
		$twig = new Twig_Environment($loader, array('debug' => true));
		$twig->addExtension(new Twig_Extension_Debug());
		return $twig;
	}

	public function getTemplate($twig, $template) {
		if (property_exists($template, 'fields') && !empty($template->getFields())) {
			$temp = $twig->render($template->getTemplate(), $template->getFields());
		}
		else {
			$temp = $twig->render($template->getTemplate());
		}
		return $temp;
	}

	public function oldRenderMethod() {
		$output = "";
		foreach ($this->views as $view) {
			$output .= $view->render();
		}
		return $output;
	}

	public function render() {
		$twig = $this->twigInstance();
		foreach($this->templates as $template) {
			echo $this->getTemplate($twig, $template);
		}
	}
}
