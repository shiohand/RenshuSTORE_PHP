<?php
class Staff{
  private $id;
  private $name;
  private $password;
  private $created_at;

  public function __construct($id, $name, $password, $created_at) {
    $this->id = $id;
    $this->name = $name;
    $this->password = $password;
    $this->created_at = $created_at;
  }

  public function getId() {
    return $this->id;
  }
  public function setId($id) {
    $this->id = $id;
  }
  public function getName() {
    return $this->name;
  }
  public function setName($name) {
    $this->name = $name;
  }
  public function getPassword() {
    return $this->password;
  }
  public function setPassword($password) {
    $this->password = $password;
  }
  public function getCreatedAt() {
    return $this->created_at;
  }
  public function setCreatedAt($created_at) {
    $this->created_at = $created_at;
  }
}
?>