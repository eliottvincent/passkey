<?php

/**
 * Created by PhpStorm.
 * User: eliottvincent
 * Date: 02/05/2017
 * Time: 22:13
 */
class View implements TemplateInterface, ContainerInterface, ViewInterface {


	// a View is linked to a Model and a Controller
	private $model;
	private $controller;

	const DEFAULT_TEMPLATE = "default.php";
	//protected $template = self::DEFAULT_TEMPLATE;
	protected $template;
	protected $fields = array();
	protected $twigInstance;

	/**
	 * View constructor.
	 * @param $controller
	 * @param $model
	 */
	public function __construct($controller = null, $model = null, $template = null, array $fields = array()) {

		if ($controller !== null) {
		$this->controller = $controller;
		}
		if ($model !== null) {
		$this->model = $model;
		}

		if ($template !== null) {
			$this->setTemplate($template);
		}
		if (!empty($fields)) {
			foreach ($fields as $name => $value) {
				$this->$name = $value;
			}
		}

		$this->twigInstance = $this->twigInstance();

	}

	/**
	 * @param $template
	 * @return $this
	 */
	public function setTemplate($template) {
		$this->template = $template;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getTemplate() {
		return $this->template;
	}

	/*
	 *
	 */
	public function getFields() {
		return $this->fields;
	}

	public function __set($name, $value) {
		$this->fields[$name] = $value;
		return $this;
	}

	/**
	 * @param $name
	 * @return mixed
	 */
	public function __get($name) {
		if (!isset($this->fields[$name])) {
			throw new InvalidArgumentException("Unable to get the field '$name'.");
		}
		$field = $this->fields[$name];
		return $field instanceof Closure ? $field($this) : $field;
	}

	/**
	 * @param $name
	 * @return bool
	 */
	public function __isset($name) {
		return isset($this->fields[$name]);
	}

	/**
	 * @param $name
	 * @return $this
	 */
	public function __unset($name) {
		if (!isset($this->fields[$name])) {
			throw new InvalidArgumentException("Unable to unset the field '$name'.");
		}
		unset($this->fields[$name]);
		return $this;
	}

	/**
	 * @return string
	 */
	public function oldRenderMethod() {
		extract($this->fields);
		ob_start();
		include $this->template;
		return ob_get_clean();
	}

	public function twigInstance() {
		$loader = new Twig_Loader_Filesystem('app/View/partials');
		$twig = new Twig_Environment($loader, array('debug' => true));
		$twig->addExtension(new Twig_Extension_Debug());
		return $twig;
	}

	public function render()
	{
		if (property_exists($this, 'fields') && !empty($this->getFields())) {
			$temp = $this->twigInstance->render($this->getTemplate(), $this->getFields());
		}
		else {
			$temp = $this->twigInstance->render($this->getTemplate());
		}
		return $temp;
	}
}
