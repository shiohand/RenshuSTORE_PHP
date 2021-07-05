<?php
class Review{
  private $product_id;
  private $member_id;
  private $rating;
  private $body;
  private $created_at;

  public function __construct($product_id, $member_id, $rating, $body, $created_at) {
    $this->product_id = $product_id;
    $this->member_id = $member_id;
    $this->rating = $rating;
    $this->body = $body;
    $this->created_at = $created_at;
  }

  public function getProductId() {
    return $this->product_id;
  }
  public function setProductId($product_id) {
    $this->product_id = $product_id;
  }
  public function getMemberId() {
    return $this->member_id;
  }
  public function setMemberId($member_id) {
    $this->member_id = $member_id;
  }
  public function getRating() {
    return $this->rating;
  }
  public function setRating($rating) {
    $this->rating = $rating;
  }
  public function getBody() {
    return $this->body;
  }
  public function setBody($body) {
    $this->body = $body;
  }
  public function getCreatedAt() {
    return $this->created_at;
  }
  public function setCreatedAt($created_at) {
    $this->created_at = $created_at;
  }
}
?>