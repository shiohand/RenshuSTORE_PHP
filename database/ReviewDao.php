<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
require_once(D_ROOT.'database/dbTool.php');
require_once(D_ROOT.'database/Review.php');

// isAlreadyExists()  重複検索
// findByProductId()  商品指定取得
// findByMemberId()   会員指定取得

class ReviewDao {
  public $dbh;

  // 1件追加
  public function create(Review $review): void {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    $sql = "INSERT INTO dat_review(product_id,member_id,rating,body) VALUES (?,?,?,?)";
    $stmt = $this->dbh->prepare($sql);
    $data = array();
    $data[] = $review->getProductId();
    $data[] = $review->getMemberId();
    $data[] = $review->getRating();
    $data[] = $review->getBody();
    $stmt->execute($data);
  }
  // 1件更新
  public function update(Review $review): void {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    $sql = "UPDATE dat_review SET rating=?,body=? WHERE product_id=? AND member_id=?";
    $stmt = $this->dbh->prepare($sql);
    $data = array();
    $data[] = $review->getRating();
    $data[] = $review->getBody();
    $data[] = $review->getProductId();
    $data[] = $review->getMemberId();
    $stmt->execute($data);
  }
  // 1件削除
  public function delete(Review $review): void {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    $sql = "DELETE FROM dat_review WHERE product_id=? AND member_id=?";
    $stmt = $this->dbh->prepare($sql);
    $data = array();
    $data[] = $review->getProductId();
    $data[] = $review->getMemberId();
    $stmt->execute($data);
  }
  // 1件取得
  public function findByIds($product_id, $member_id): Review {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    $sql = "SELECT product_id,member_id,rating,body,created_at FROM dat_review WHERE product_id=? AND member_id=?";
    $stmt = $this->dbh->prepare($sql);
    $data = array();
    $data[] = $product_id;
    $data[] = $member_id;
    $stmt->execute($data);

    return mapReview($stmt->fetch());
  }
  // 全件取得
  public function findAll(): array {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    $sql = "SELECT product_id,member_id,rating,body,created_at FROM dat_review WHERE 1";
    $stmt = $this->dbh->prepare($sql);
    $stmt->execute();

    return mapReviews($stmt->fetchAll());
  }
  // 重複検索
  public function isAlreadyExists($product_id, $member_id): bool {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    $sql = "SELECT member_id FROM dat_review WHERE product_id=? AND member_id=?";
    $stmt = $this->dbh->prepare($sql);
    $data = array();
    $data[] = $product_id;
    $data[] = $member_id;
    $stmt->execute($data);

    if($stmt->fetch()) {
      return true;
    } else {
      return false;
    }
  }
  // 商品指定取得
  public function findByProductId($product_id): array {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    $sql = "";
    $sql .= "SELECT dat_member.name as member_name,rating,body,dat_review.created_at";
    $sql .= " FROM dat_review JOIN dat_member ON dat_review.member_id = dat_member.id";
    $sql .= " WHERE product_id=?";
    $sql .= " ORDER BY dat_review.created_at DESC";
    $stmt = $this->dbh->prepare($sql);
    $data = array();
    $data[] = $product_id;
    $stmt->execute($data);

    return $stmt->fetchAll();
  }
  // 会員指定取得
  public function findByMemberId($member_id): array {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    $sql = "";
    $sql .= "SELECT mst_product.name as product_name,rating,body,dat_review.created_at";
    $sql .= " FROM dat_review JOIN mst_product ON dat_review.product_id = mst_product.id";
    $sql .= " WHERE member_id=?";
    $sql .= " ORDER BY dat_review.created_at DESC";
    $stmt = $this->dbh->prepare($sql);
    $data = array();
    $data[] = $member_id;
    $stmt->execute($data);

    return $stmt->fetchAll();
  }
}

?>