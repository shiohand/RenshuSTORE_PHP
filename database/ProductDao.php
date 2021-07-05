<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
require_once(D_ROOT.'database/dbTool.php');
require_once(D_ROOT.'database/Product.php');

// isAlreadyExists()       画像ファイル名重複チェック
// getForShop()             トップ用取得 shop/top
// getForRank()             ランキング用取得 shop/ranking
// findAllWithRating()      商品一覧用取得 shop/product
// getCountAllWithRating()  ページャー用データ数取得
// findRecommend()          一緒に購入されることの多い商品

class ProductDao {
  public $dbh;

  // 1件追加
  public function create(Product $product): void {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    $sql = "INSERT INTO mst_product(name,price,content,pict,release_date) VALUES (?,?,?,?,?)";
    $stmt = $this->dbh->prepare($sql);
    $data = array();
    $data[] = $product->getName();
    $data[] = $product->getPrice();
    $data[] = $product->getContent();
    $data[] = $product->getPict();
    $data[] = $product->getReleaseDate();
    $stmt->execute($data);
  }
  // 1件更新
  public function update(Product $product): void {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    $sql = "UPDATE mst_product SET name=?,price=?,content=?,pict=?,release_date=? WHERE id=?";
    $stmt = $this->dbh->prepare($sql);
    $data = array();
    $data[] = $product->getName();
    $data[] = $product->getPrice();
    $data[] = $product->getContent();
    $data[] = $product->getPict();
    $data[] = $product->getReleaseDate();
    $data[] = $product->getId();
    $stmt->execute($data);
  }
  // 1件削除
  public function delete(Product $product): void {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    $sql = "DELETE FROM mst_product WHERE id=?";
    $stmt = $this->dbh->prepare($sql);
    $data = array();
    $data[] = $product->getId();
    $stmt->execute($data);
  }
  // 1件取得
  public function findById($id): Product {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    $sql = "SELECT id,name,price,content,pict,release_date,created_at FROM mst_product WHERE id=?";
    $stmt = $this->dbh->prepare($sql);
    $data = array();
    $data[] = $id;
    $stmt->execute($data);

    return mapProduct($stmt->fetch());
  }
  // 全件取得
  public function findAll(): array {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    $sql = "SELECT id,name,price,content,pict,release_date,created_at FROM mst_product WHERE 1";
    $stmt = $this->dbh->prepare($sql);
    $stmt->execute();

    return mapProducts($stmt->fetchAll());
  }
  // 画像ファイル名重複チェック
  public function isAlreadyExists(string $file_name): bool {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    $sql = "SELECT pict FROM mst_product WHERE pict=?";
    $stmt = $this->dbh->prepare($sql);
    $data = array();
    $data[] = $file_name;
    $stmt->execute($data);

    if($stmt->fetch()) {
      return true;
    } else {
      return false;
    }
  }
  // shop/top トップ用取得
  public function getForShop(string $opt): array {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    $sql = "SELECT id,name,price,content,pict,release_date,created_at FROM mst_product WHERE 1"
    .$opt
    ." LIMIT 5";
    $stmt = $this->dbh->prepare($sql);
    $stmt->execute();

    return mapProducts($stmt->fetchAll());
  }
  // shop/ranking ランキング用取得
  public function getForRank(string $betweenAnd, $limit): array {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    $sql = "SELECT"
    ." mst_product.id"
    .",mst_product.name"
    .",mst_product.price"
    .",mst_product.pict"
    .",SUM(dat_sales_product.price * quantity) AS total_price"

    ." FROM"
    ." dat_sales_product"
    ." JOIN"
    ." mst_product ON dat_sales_product.product_id = mst_product.id"

    ." WHERE 1=1"
    .$betweenAnd
    ." GROUP BY product_id"
    ." ORDER BY total_price DESC"
    .",product_id ASC"
    ." LIMIT :rank";
    $stmt = $this->dbh->prepare($sql);
    $stmt->bindParam(':rank', $limit, PDO::PARAM_INT);
    $stmt->execute();

    return mapProducts($stmt->fetchAll());
  }
  // shop/product 商品一覧用取得
  public function findAllWithRating(string $orderBy, $offset): array {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    $sql = ""
    ."SELECT"
    ." id AS product_id"
    .",name"
    .",price"
    .",pict"
    .",release_date"
    .",COALESCE(rating.rating_avg, 0) AS rating_result"

    ." FROM mst_product"
    ." LEFT JOIN"
    // SELECT (商品ID, AVG(評価の少数第二位で四捨五入)) FROM dat_review GROUP BY 商品ID
    ." (SELECT product_id,(truncate(AVG(rating)+0.05,1)) AS rating_avg"
    ." FROM dat_review"
    ." GROUP BY product_id) AS rating"
    ." ON mst_product.id = rating.product_id WHERE 1=1"

    // ORDER BY 指定,product_id DESC
    .$orderBy
    .",product_id DESC"
    ." LIMIT {$offset}, 20";

    $stmt = $this->dbh->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll();
  }
  // ページャー用データ数取得
  public function getCountAllWithRating() {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    $sql = ""
    ."SELECT"
    ." COUNT(*)"
    ." FROM"
    ." (SELECT id FROM mst_product WHERE 1=1) AS dummy";
    $stmt = $this->dbh->prepare($sql);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_COLUMN);
  }

  // 一緒に購入されることの多い商品
  public function findRecommend($product_id): array {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    $sql = ""
    ."SELECT"
    ." mst_product.id,"
    ." mst_product.pict,"
    ." mst_product.name,"
    ." mst_product.price,"
    ." SUM(dat_sales_product.quantity)"

    ." FROM dat_sales_product"
    ." JOIN"
    ." mst_product ON dat_sales_product.product_id = mst_product.id"
    ." JOIN"
    ." (SELECT sales_id FROM dat_sales_product WHERE product_id = ?) AS sales_ids"
    ." ON dat_sales_product.sales_id = sales_ids.sales_id"

    ." WHERE"
    ." dat_sales_product.sales_id = sales_ids.sales_id"
    ." AND product_id != ?"
    ." GROUP BY dat_sales_product.product_id"
    ." ORDER BY SUM(dat_sales_product.quantity) DESC"
    ." LIMIT 5";
    $stmt = $this->dbh->prepare($sql);
    $data = array();
    $data[] = $product_id;
    $data[] = $product_id;
    $stmt->execute($data);

    return mapProducts($stmt->fetchAll());
  }
}
?>
