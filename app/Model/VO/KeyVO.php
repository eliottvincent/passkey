<?php
class KeyVO {

    public static $keyType = array(
    	"simple"=>"Clé",
		"partial"=>"Passe Partiel",
		"total"=>"Passe Total"
	);

    private $id;
    private $type; // Clef ou Passe Partiel ou Passe Total
	private $locks; // Canons
	private $name;
	private $supplier; // fournisseur
	private $copies;

	public function __construct() {
	}

    // GETTER
	public function getId() {
		return $this->id;
	}

	public function getType() {
		return $this->type;
	}

	public function getLocks() {
		return $this->locks;
	}

	public function getName() {
		return $this->name;
	}

	public function getCopies() {
		return $this->copies;
	}

	/**
	 * @return mixed
	 */
	public function getSupplier()
	{
		return $this->supplier;
	}

	// SETTER

	public function setId($id) {
        $this->id = $id;
    }

	public function setType($type) {
		$this->type = $type;
    }

	public function setLocks($locks) {
		$this->locks = $locks;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function setCopies($copies) {
		$this->copies = $copies;
	}

	/**
	 * @param mixed $supplier
	 */
	public function setSupplier($supplier)
	{
		$this->supplier = $supplier;
	}

	public function addLock($lock) {
		array_push($this->locks, $lock);
	}

}
