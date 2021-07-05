<?php
class Product{
  private $id;
  private $name;
  private $price;
  private $content;
  private $pict;
  private $release_date;
  private $created_at;

  public function __construct($id, $name, $price, $content, $pict, $release_date, $created_at) {
    $this->id = $id;
    $this->name = $name;
    $this->price = $price;
    $this->content = $content;
    $this->pict = $pict;
    $this->release_date = $release_date;
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
  public function getPrice() {
    return $this->price;
  }
  public function setPrice($price) {
    $this->price = $price;
  }
  public function getContent() {
    return $this->content;
  }
  public function setContent($content) {
    $this->content = $content;
  }
  public function getPict() {
    return $this->pict;
  }
  public function setPict($pict) {
    $this->pict = $pict;
  }
  public function getReleaseDate() {
    return $this->release_date;
  }
  public function setReleaseDate($release_date) {
    $this->release_date = $release_date;
  }
  public function getCreatedAt() {
    return $this->created_at;
  }
  public function setCreatedAt($created_at) {
    $this->created_at = $created_at;
  }
}
?>
