<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
require_once(D_ROOT.'database/dbTool.php');
require_once(D_ROOT.'database/Sales.php');

class SalesDao {
  public $dbh;

  // 1件追加
  public function create(Sales $sales): void {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    $sql = "INSERT INTO dat_sales(member_id,email,password,name,postal1,postal2,address,tel) VALUES (?,?,?,?,?,?,?)";
    $stmt = $this->dbh->prepare($sql);
    $data = array();
    $data[] = $sales->getMemberId();
    $data[] = $sales->getEmail();
    $data[] = $sales->getName();
    $data[] = $sales->getPostal1();
    $data[] = $sales->getPostal2();
    $data[] = $sales->getAddress();
    $data[] = $sales->getTel();
    $stmt->execute($data);
  }
  // 1件更新
  public function update(Sales $sales): void {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    $sql = "UPDATE dat_sales SET member_id=?,email=?,password=?,name=?,postal1=?,postal2=?,address=?,tel=? WHERE id=?";
    $stmt = $this->dbh->prepare($sql);
    $data = array();
    $data[] = $sales->getMemberId();
    $data[] = $sales->getEmail();
    $data[] = $sales->getName();
    $data[] = $sales->getPostal1();
    $data[] = $sales->getPostal2();
    $data[] = $sales->getAddress();
    $data[] = $sales->getTel();
    $data[] = $sales->getId();
    $stmt->execute($data);
  }
  // 1件削除
  public function delete(Sales $sales): void {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    $sql = "DELETE FROM dat_sales WHERE id=?";
    $stmt = $this->dbh->prepare($sql);
    $data = array();
    $data[] = $sales->getId();
    $stmt->execute($data);
  }
  // 1件取得
  public function findById($id): Sales {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    $sql = "SELECT id,member_id,email,password,name,postal1,postal2,address,tel,created_at FROM dat_sales WHERE id=?";
    $stmt = $this->dbh->prepare($sql);
    $data = array();
    $data[] = $id;
    $stmt->execute($data);

    return mapSales($stmt->fetch());
  }
  // 全件取得
  public function findAll(): array {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    $sql = "SELECT id,member_id,email,password,name,postal1,postal2,address,tel,created_at FROM dat_sales WHERE 1";
    $stmt = $this->dbh->prepare($sql);
    $stmt->execute();

    return mapSaless($stmt->fetchAll());
  }
}
?>