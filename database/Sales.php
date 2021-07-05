<?php
class Sales{
  private $id;
  private $member_id;
  private $email;
  private $name;
  private $postal1;
  private $postal2;
  private $address;
  private $tel;
  private $created_at;

  public function __construct($id, $member_id, $email, $name, $postal1, $postal2, $address, $tel, $created_at) {
    $this->id = $id;
    $this->member_id = $member_id;
    $this->email = $email;
    $this->name = $name;
    $this->postal1 = $postal1;
    $this->postal2 = $postal2;
    $this->address = $address;
    $this->tel = $tel;
    $this->created_at = $created_at;
  }

  public function getId() {
    return $this->id;
  }
  public function setId($id) {
    $this->id = $id;
  }
  public function getMemberId() {
    return $this->member_id;
  }
  public function setMemberId($member_id) {
    $this->member_id = $member_id;
  }
  public function getEmail() {
    return $this->email;
  }
  public function setEmail($email) {
    $this->email = $email;
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
  public function getCreatedAt() {
    return $this->created_at;
  }
  public function setCreatedAt($created_at) {
    $this->created_at = $created_at;
  }
}
?>