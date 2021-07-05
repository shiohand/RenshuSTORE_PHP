<?php
class Member{
  private $id;
  private $email;
  private $password;
  private $name;
  private $postal1;
  private $postal2;
  private $address;
  private $tel;
  private $gender;
  private $birth;
  private $created_at;

  public function __construct($id, $email, $password, $name, $postal1, $postal2, $address, $tel, $gender, $birth, $created_at) {
    $this->id = $id;
    $this->email = $email;
    $this->password = $password;
    $this->name = $name;
    $this->postal1 = $postal1;
    $this->postal2 = $postal2;
    $this->address = $address;
    $this->tel = $tel;
    $this->gender = $gender;
    $this->birth = $birth;
    $this->created_at = $created_at;
  }

  public function getId() {
    return $this->id;
  }
  public function setId($id) {
    $this->id = $id;
  }
  public function getEmail() {
    return $this->email;
  }
  public function setEmail($email) {
    $this->email = $email;
  }
  public function getPassword() {
    return $this->password;
  }
  public function setPassword($password) {
    $this->password = $password;
  }
  public function getName() {
    return $this->name;
  }
  public function setName($name) {
    $this->name = $name;
  }
  public function getPostal1() {
    return $this->postal1;
  }
  public function setPostal1($postal1) {
    $this->postal1 = $postal1;
  }
  public function getPostal2() {
    return $this->postal2;
  }
  public function setPostal2($postal2) {
    $this->postal2 = $postal2;
  }
  public function getAddress() {
    return $this->address;
  }
  public function setAddress($address) {
    $this->address = $address;
  }
  public function getTel() {
    return $this->tel;
  }
  public function setTel($tel) {
    $this->tel = $tel;
  }
  public function getGender() {
    return $this->gender;
  }
  public function setGender($gender) {
    $this->gender = $gender;
  }
  public function getBirth() {
    return $this->birth;
  }
  public function setBirth($birth) {
    $this->birth = $birth;
  }
  public function getCreatedAt() {
    return $this->created_at;
  }
  public function setCreatedAt($created_at) {
    $this->created_at = $created_at;
  }
}
?>