<?php
class KeyVO {

    public static $keyType = array("Simple"=>"Clé","Partiel"=>"Passe Partiel","Total"=>"Passe Total");
	public static $keysList = array(); // TODO : Implement the list of keys

    private $id;
    private $type; //Clef ou Passe Partiel ou Passe Total
	private $barrel; // canon

	public __construct($type, $barrel) {
		$this->type = $type;
		$this->barrel = $barrel;
	}

    // GETTER
	public function getId() {
		return $this->id;
	}

	public function getType() {
		return $this->type;
	}

	public function getBarrel() {
		return $this->barrel;
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

    public function setBarrel($barrel) {
		$this->barrel = $barrel;
	}
}
