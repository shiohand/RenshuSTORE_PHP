<?php
class SalesProduct{
  private $sales_id;
  private $product_id;
  private $price;
  private $quantity;
  private $created_at;

  public function __construct($sales_id, $product_id, $price, $quantity, $created_at) {
    $this->sales_id = $sales_id;
    $this->product_id = $product_id;
    $this->price = $price;
    $this->quantity = $quantity;
    $this->created_at = $created_at;
  }

  public function getSalesId() {
    return $this->sales_id;
  }
  public function setSalesId($sales_id) {
    $this->sales_id = $sales_id;
  }
  public function getProductId() {
    return $this->product_id;
  }
  public function setProductId($product_id) {
    $this->product_id = $product_id;
  }
  public function getPrice() {
    return $this->price;
  }
  public function setPrice($price) {
    $this->price = $price;
  }
  public function getQuantity() {
    return $this->quantity;
  }
  public function setQuantity($quantity) {
    $this->quantity = $quantity;
  }
  public function getCreatedAt() {
    return $this->created_at;
  }
  public function setCreatedAt($created_at) {
    $this->created_at = $created_at;
  }
}
?>