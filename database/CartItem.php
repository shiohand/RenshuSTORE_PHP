<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
require_once(D_ROOT.'database/Product.php');

class CartItem {
  private Product $product;
  private $quantity;
  private $quantity_error; // カート編集時に個数が正しくなかったら値を入れ、表示させる

  public function __construct(Product $product, $quantity) {
    $this->product = $product;
    $this->quantity = $quantity;
    $this->quantity_error = '';
  }

  public function getProduct(): Product {
    return $this->product;
  }
  public function setProduct($product) {
    $this->product = $product;
  }
  public function getQuantity() {
    return $this->quantity;
  }
  public function setQuantity($quantity) {
    $this->quantity = $quantity;
  }
  public function getQuantityError() {
    return $this->quantity_error;
  }
  public function setQuantityError($quantity_error) {
    $this->quantity_error = $quantity_error;
  }
  // 重複検索
  public static function findById($items, $product_id) {
    foreach ($items as $item) {
      if ($product_id === $item->product->getId()) {
        return $item;
      }
    }
    return false;
  }
}
?>