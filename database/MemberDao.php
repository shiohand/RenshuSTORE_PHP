<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
require_once(D_ROOT.'database/dbTool.php');
require_once(D_ROOT.'database/Member.php');

// loginCheck()        ログイン認証
// isAlreadyExists()  メールアドレス重複検索

class MemberDao {
  public $dbh;

  // 1件追加
  public function create(Member $member): void {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    $sql = "INSERT INTO dat_member(email,password,name,postal1,postal2,address,tel,gender,birth) VALUES (?,?,?,?,?,?,?,?,?)";
    $stmt = $this->dbh->prepare($sql);
    $data = array();
    $data[] = $member->getEmail();
    $data[] = $member->getPassword();
    $data[] = $member->getName();
    $data[] = $member->getPostal1();
    $data[] = $member->getPostal2();
    $data[] = $member->getAddress();
    $data[] = $member->getTel();
    $data[] = $member->getGender();
    $data[] = $member->getBirth();
    $stmt->execute($data);
  }
  // 1件更新
  public function update(Member $member): void {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    $sql = "UPDATE dat_member SET email=?,password=?,name=?,postal1=?,postal2=?,address=?,tel=?,gender=?,birth=? WHERE id=?";
    $stmt = $this->dbh->prepare($sql);
    $data = array();
    $data[] = $member->getEmail();
    $data[] = $member->getPassword();
    $data[] = $member->getName();
    $data[] = $member->getPostal1();
    $data[] = $member->getPostal2();
    $data[] = $member->getAddress();
    $data[] = $member->getTel();
    $data[] = $member->getGender();
    $data[] = $member->getBirth();
    $data[] = $member->getId();
    $stmt->execute($data);
  }
  // 1件削除
  public function delete(Member $member): void {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    $sql = "DELETE FROM dat_member WHERE id=?";
    $stmt = $this->dbh->prepare($sql);
    $data = array();
    $data[] = $member->getId();
    $stmt->execute($data);
  }
  // 1件取得
  public function findById($id): Member {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    $sql = "SELECT id,email,password,name,postal1,postal2,address,tel,gender,birth,created_at FROM dat_member WHERE id=?";
    $stmt = $this->dbh->prepare($sql);
    $data = array();
    $data[] = $id;
    $stmt->execute($data);

    return mapMember($stmt->fetch());
  }
  // 全件取得
  public function findAll(): array {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    $sql = "SELECT id,email,password,name,postal1,postal2,address,tel,gender,birth,created_at FROM dat_member WHERE 1";
    $stmt = $this->dbh->prepare($sql);
    $stmt->execute();

    return mapMembers($stmt->fetchAll());
  }
  // ログイン認証
  public function loginCheck(string $email, $password): Member {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    $sql = "SELECT id,email,password,name FROM dat_member WHERE email=? AND password=?";
    $stmt = $this->dbh->prepare($sql);
    $data = array();
    $data[] = $email;
    $data[] = $password;
    $stmt->execute($data);

    return mapMember($stmt->fetch());
  }
  // メールアドレス重複検索
  public function isAlreadyExists(string $email): bool {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    $sql = "SELECT email FROM dat_member WHERE email=?";
    $stmt = $this->dbh->prepare($sql);
    $data = array();
    $data[] = $email;
    $stmt->execute($data);

    if($stmt->fetch()) {
      return true;
    } else {
      return false;
    }
  }
}
?>