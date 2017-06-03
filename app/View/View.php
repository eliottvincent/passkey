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
	public function __construct($template = null, array $fields = array()) {

		if ($template !== null) {
			$this->setTemplate($template);
		}

		// allow us to access fields by doing
		// myView->myField

		// also means that if we want to access to an object that's not a field ($template, $controller, $model) ...
		// we need to use the getter function specific to the object (getTemplate(), etc.)
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

	public function twigInstance() {
		$loader = new Twig_Loader_Filesystem('app/View/partials');
		$twig = new Twig_Environment($loader, array('debug' => true));
		$twig->addExtension(new Twig_Extension_Debug());
		return $twig;
	}

	public function render()
	{
		// if the view has some fields
		if (property_exists($this, 'fields') && !empty($this->getFields())) {

			// then we render the template with our twigInstance, without forgetting to pass the fields
			$temp = $this->twigInstance->render($this->getTemplate(), $this->getFields());
		}
		else {

			// we render the template with our twigInstance, without fields
			$temp = $this->twigInstance->render($this->getTemplate());
		}
		return $temp;
	}
}
