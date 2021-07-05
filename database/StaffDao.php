<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
require_once(D_ROOT.'database/dbTool.php');
require_once(D_ROOT.'database/Staff.php');

// loginCheck()  ログイン認証

class StaffDao {
  public $dbh;

  // 1件追加
  public function create(Staff $staff): void {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    $sql = "INSERT INTO mst_staff(name,password) VALUES (?,?)";
    $stmt = $this->dbh->prepare($sql);
    $data = array();
    $data[] = $staff->getName();
    $data[] = $staff->getPassword();
    $stmt->execute($data);
  }
  // 1件更新
  public function update(Staff $staff): void {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    $sql = "UPDATE mst_staff SET name=?,password=? WHERE id=?";
    $stmt = $this->dbh->prepare($sql);
    $data = array();
    $data[] = $staff->getName();
    $data[] = $staff->getPassword();
    $data[] = $staff->getId();
    $stmt->execute($data);
  }
  // 1件削除
  public function delete(Staff $staff): void {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    $sql = "DELETE FROM mst_staff WHERE id=?";
    $stmt = $this->dbh->prepare($sql);
    $data = array();
    $data[] = $staff->getId();
    $stmt->execute($data);
  }
  // 1件取得
  public function findById($id): Staff {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    $sql = "SELECT id,name,password,created_at FROM mst_staff WHERE id=?";
    $stmt = $this->dbh->prepare($sql);
    $data = array();
    $data[] = $id;
    $stmt->execute($data);

    return mapStaff($stmt->fetch());
  }
  // 全件取得
  public function findAll(): array {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    $sql = "SELECT id,name,password,created_at FROM mst_staff WHERE 1";
    $stmt = $this->dbh->prepare($sql);
    $stmt->execute();

    return mapStaffs($stmt->fetchAll());
  }
  // ログイン認証
  public function loginCheck($id, $password): Staff {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    $sql = "SELECT id,name,password FROM mst_staff WHERE id=? AND password=?";
    $stmt = $this->dbh->prepare($sql);
    $data = array();
    $data[] = $id;
    $data[] = $password;
    $stmt->execute($data);

    return mapStaff($stmt->fetch());
  }
}

?>