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
		foreach($this->views as $view) {
			echo $view->render();
		}
	}
}
