<?php
class KeyVO {

    public static $keyType = array("simple"=>"Clé","partial"=>"Passe Partiel","total"=>"Passe Total");

    private $id;
    private $type; // Clef ou Passe Partiel ou Passe Total
	private $lock; // Canon

	public function __construct() {
	}

    // GETTER
	public function getId() {
		return $this->id;
	}

	public function getType() {
		return $this->type;
	}

	public function getLock() {
		return $this->lock;
	}

	// SETTER
    public function setId($id) {
        $this->id = $id;
    }

    public function setType($type) {
		$this->type = $type;
    }

    public function setLock($lock) {
		$this->lock = $lock;
	}
}
