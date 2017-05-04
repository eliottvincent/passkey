<?php
class KeyVO {

    public static $keyType = array("Simple"=>"Clé","Partiel"=>"Passe Partiel","Total"=>"Passe Total");
	public static $keysList = array();

    private $id;
    private $type; // Clef ou Passe Partiel ou Passe Total
	private $lock; // Canon

	public function __construct($type, $lock) {
		$this->type = $type;
		$this->lock = $lock;

		array_push($this->keysList, $this);
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
      if(array_key_exists($type,$this->keyType)){
        $this->type = $type;
      }
      else
      {
		  throw new RuntimeException('Le type de clef <strong>' . $type . '</strong> n\'existe pas !');
      }
    }

    public function setLock($lock) {
		$this->lock = $lock;
	}
}
