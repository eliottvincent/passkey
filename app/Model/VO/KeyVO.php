<?php
class KeyVO
{

    public static $keyType = array("Simple"=>"Clé","Partiel"=>"Passe Partiel","Total"=>"PasseTotal");

    protected $id;
    protected $type; //Clef ou Passe Partiel ou Passe Total

    public __construct($type)
    {
      $this->type=$type;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
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

    public function getType() {
        return $this->type;
    }

}
