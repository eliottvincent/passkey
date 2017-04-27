<?php
class KeychainVO
{
    protected $id;
    public function setId($id) {
        $this->id = $id;
    }
    public function getId() {
        return $this->id;
    }

    protected $creationDate;
    public function setCreationDate($date)
    {
      $this->creationDate=$date;
    }
    public function getCreationDate()
    {
      return $this->creationDate;
    }

    protected $destructionDate;
    public function setDestructionDate($date)
    {
      $this->destructionDate=$date;
    }
    public function getDestructionDate()
    {
      return $this->destructionDate;
    }

}
