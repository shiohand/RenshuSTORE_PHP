<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
require_once(D_ROOT.'database/dbTool.php');
require_once(D_ROOT.'database/SalesProduct.php');

// isAlreadyExists()  重複検索
// findBySalesId()    注文指定取得
// findByProductId()  商品指定取得

class SalesProductDao {
  public $dbh;

  // 1件追加
  public function create(SalesProduct $sales_product): void {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    $sql = "INSERT INTO dat_sales_product(sales_id,product_id,price,quantity) VALUES (?,?,?,?)";
    $stmt = $this->dbh->prepare($sql);
    $data = array();
    $data[] = $sales_product->getSalesId();
    $data[] = $sales_product->getProductId();
    $data[] = $sales_product->getPrice();
    $data[] = $sales_product->getQuantity();
    $stmt->execute($data);
  }
  // 1件更新
  public function update(SalesProduct $sales_product): void {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    $sql = "UPDATE dat_sales_product SET price=?,quantity=? WHERE sales_id=? AND product_id=?";
    $stmt = $this->dbh->prepare($sql);
    $data = array();
    $data[] = $sales_product->getPrice();
    $data[] = $sales_product->getQuantity();
    $data[] = $sales_product->getSalesId();
    $data[] = $sales_product->getProductId();
    $stmt->execute($data);
  }
  // 1件削除
  public function delete(SalesProduct $sales_product): void {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    $sql = "DELETE FROM dat_sales_product WHERE sales_id=? AND product_id=?";
    $stmt = $this->dbh->prepare($sql);
    $data = array();
    $data[] = $sales_product->getSalesId();
    $data[] = $sales_product->getProductId();
    $stmt->execute($data);
  }
  // 1件取得
  public function findByIds($sales_id, $product_id): SalesProduct {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    $sql = "SELECT sales_id,product_id,price,quantity,created_at FROM dat_sales_product WHERE sales_id=? AND product_id=?";
    $stmt = $this->dbh->prepare($sql);
    $data = array();
    $data[] = $sales_id;
    $data[] = $product_id;
    $stmt->execute($data);

    return mapSalesProduct($stmt->fetch());
  }
  // 全件取得
  public function findAll(): array {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    $sql = "SELECT sales_id,product_id,price,quantity,created_at FROM dat_sales_product WHERE 1";
    $stmt = $this->dbh->prepare($sql);
    $stmt->execute();

    return mapSalesProducts($stmt->fetchAll());
  }
  // 重複検索
  public function isAlreadyExists($sales_id, $product_id): bool {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    $sql = "SELECT product_id FROM dat_sales_product WHERE sales_id=? AND product_id=?";
    $stmt = $this->dbh->prepare($sql);
    $data = array();
    $data[] = $sales_id;
    $data[] = $product_id;
    $stmt->execute($data);

    if($stmt->fetch()) {
      return true;
    } else {
      return false;
    }
  }

  // 未使用
  // 注文指定取得
  public function findBySalesId($sales_id): array {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    $sql = "SELECT sales_id,product_id,price,quantity,created_at FROM dat_sales_product WHERE sales_id=?";
    $stmt = $this->dbh->prepare($sql);
    $data = array();
    $data[] = $sales_id;
    $stmt->execute($data);

    return mapSalesProducts($stmt->fetch());
  }
  // 未使用
  // 商品指定取得
  public function findByProductId($product_id): array {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    $sql = "SELECT sales_id,product_id,price,quantity,created_at FROM dat_sales_product WHERE product_id=?";
    $stmt = $this->dbh->prepare($sql);
    $data = array();
    $data[] = $product_id;
    $stmt->execute($data);

    return mapSalesProducts($stmt->fetch());
  }
}
?>