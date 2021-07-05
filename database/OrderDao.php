<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
require_once(D_ROOT.'database/dbTool.php');

// orderResistation()            注文登録 shop/order
// getForOrdered()               会員ID指定取得 会員注文履歴 user_menu/ordered
// getCountForOrdered()          ページャー用データ数取得
// getForOrder()                 全件取得＋各合計金額 注文データ一覧 order/order
// getCountForOrder()            ページャー用データ数取得
// getForSales()                 全件取得＋各合計金額 総売上推移 order/sales
// getCountForSales()            ページャー用データ数取得
// getForOneProductSales()       全件取得＋各合計金額 総売上推移 order/one_product_sales
// getCountForOneProductSales()  ページャー用データ数取得
// getForProductSales()          全件取得＋各合計金額 商品別売上比較 order/product_sales
// getCountForProductSales()     ページャー用データ数取得
// getForSalesData()             注文ID指定取得 注文伝票 order/sales_data

class OrderDao {
  public $dbh;

  // shop/order 注文登録 呼び出し側で全部requireしてある前提
  function orderResistation(Member $orderer, array $cart, bool $signup): void {
    require_once(D_ROOT.'database/Member.php');
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }

    $sql = "LOCK TABLES dat_sales WRITE, dat_sales_product WRITE, dat_member WRITE";
    $stmt = $this->dbh->prepare($sql);
    $stmt->execute();

    // 会員登録する場合のみ登録、idを'guest'から新しいIDに
    if ($signup) {
      $sql = "INSERT INTO dat_member(email,password,name,postal1,postal2,address,tel,gender,birth) VALUES (?,?,?,?,?,?,?,?,?)";
      $stmt = $this->dbh->prepare($sql);
      $data = array();
      $data[] = $orderer->getEmail();
      $data[] = $orderer->getPassword();
      $data[] = $orderer->getName();
      $data[] = $orderer->getPostal1();
      $data[] = $orderer->getPostal2();
      $data[] = $orderer->getAddress();
      $data[] = $orderer->getTel();
      $data[] = $orderer->getGender();
      $data[] = $orderer->getBirth();
      $stmt->execute($data);

      // 最後に発生したA_I(今回はdat_member.id)
      $sql = 'SELECT LAST_INSERT_ID()';
      $stmt = $this->dbh->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetch();
      $orderer->setId($result['LAST_INSERT_ID()']);
    }

    // dat_salesにINSERT
    $sql = "INSERT INTO dat_sales (member_id,email,name,postal1,postal2,address,tel) VALUES (?,?,?,?,?,?,?)";
    $stmt = $this->dbh->prepare($sql);
    $data = array();
    $data[] = $orderer->getId();
    $data[] = $orderer->getEmail();
    $data[] = $orderer->getName();
    $data[] = $orderer->getPostal1();
    $data[] = $orderer->getPostal2();
    $data[] = $orderer->getAddress();
    $data[] = $orderer->getTel();
    $stmt->execute($data);

    // 最後に発生したA_I(今回はdat_sales.id)
    $sql = "SELECT LAST_INSERT_ID()";
    $stmt = $this->dbh->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch();
    $new_sales_id = $result['LAST_INSERT_ID()'];

    // dat_sales_productにINSERT
    $sql = "INSERT INTO dat_sales_product(sales_id,product_id,price,quantity) VALUES (?,?,?,?)";
    $stmt = $this->dbh->prepare($sql);

    foreach ($cart as $item) {
      $data = array();
      $data[] = $new_sales_id;
      $data[] = $item->getProduct()->getId();
      $data[] = $item->getProduct()->getPrice();
      $data[] = $item->getQuantity();
      $stmt->execute($data);
    }

    // データベースのロック解除
    $sql = "UNLOCK TABLES";
    $stmt = $this->dbh->prepare($sql);
    $stmt->execute();
  }

  // user_menu/ordered 会員ID指定取得 会員注文履歴
  public function getForOrdered($member_id, string $betweenAnd, $offset): array {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }

    /* WHERE IN()用に該当のsales_idを取得 */
    // SELECT
    // id
    // FROM
    // dat_sales

    $sql = ""
    ."SELECT"
    ." id"
    ." FROM"
    ." dat_sales"
    ." WHERE member_id = '{$member_id}'"
    .$betweenAnd
    ." ORDER BY created_at DESC"
    ." LIMIT {$offset}, 5";
    $stmt = $this->dbh->prepare($sql);
    $stmt->execute();
    $sales_ids_array = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $sales_ids = implode(',', $sales_ids_array);

    // SELECT
    //   sales_id, product_id, product_name, pict,
    //   sales_price, quantity, total_price, final_price, created_at
    // FROM
    //   dat_sales
    //   dat_sales_product
    //   mst_product
    // 副問合せ
    //   SELECT SUM(price * quantity) AS final_price

    $sql = ""
    ."SELECT"
    ." dat_sales.id AS sales_id"
    .",dat_sales_product.product_id"
    .",mst_product.name AS product_name"
    .",mst_product.pict"
    .",dat_sales_product.price AS sales_price"
    .",quantity"
    .",(dat_sales_product.price * quantity) AS total_price"
    .",final_price"
    .",dat_sales.created_at"

    ." FROM"
    ." dat_sales"
    ." JOIN"
    ." dat_sales_product ON dat_sales.id = dat_sales_product.sales_id"
    ." JOIN"
    ." mst_product ON dat_sales_product.product_id = mst_product.id"
    ." JOIN"
    ." (SELECT sales_id, SUM(price * quantity) AS final_price"
    ." FROM dat_sales_product GROUP BY sales_id) AS final_prices"
    ." ON dat_sales.id = final_prices.sales_id"

    ." WHERE 1=1";
    if (!empty($sales_ids)) {
      $sql .= " AND dat_sales_product.sales_id IN ($sales_ids)";
    } else {
      $sql .= " AND 0";
    }
    $sql .= " ORDER BY dat_sales_product.created_at DESC";
    $stmt = $this->dbh->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll();
  }
  // ページャー用データ数取得
  public function getCountForOrdered($member_id, string $betweenAnd) {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    $sql = ""
    ."SELECT"
    ." COUNT(*)"
    ." FROM"
    ." ("
    ."SELECT"
    ." id"
    ." FROM"
    ." dat_sales"
    ." WHERE member_id='{$member_id}'"
    .$betweenAnd
    .") AS dummy";
    $stmt = $this->dbh->prepare($sql);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_COLUMN);
  }

  // order/order 全件取得＋各合計金額 注文データ一覧
  public function getForOrder($product_id, $member_id, string $betweenAnd, string $orderBy, $offset): array {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    // SELECT
    //   sales_id, member_id, email, member_name, final_price, created_at
    // FROM
    //   dat_sales
    //   dat_sales_product

    if ($member_id === 'error') {
      $member_id = '';
    }
    if ($product_id === 'error') {
      $product_id = '';
    }

    $sql = ""
    ."SELECT"
    ." dat_sales.id AS sales_id"
    .",member_id"
    .",email"
    .",name AS member_name"
    .",SUM(price * quantity) AS final_price"
    .",dat_sales.created_at"

    ." FROM"
    ." dat_sales"
    ." LEFT JOIN"
    ." dat_sales_product ON dat_sales.id = dat_sales_product.sales_id";
    if ($product_id) {
      $sql .= ""
      ." JOIN"
      ." (SELECT sales_id AS target_id FROM dat_sales_product WHERE product_id = '{$product_id}') AS target_ids_by_product"
      ." ON dat_sales_product.sales_id = target_ids_by_product.target_id";
    }
    if ($member_id) {
      $sql .= ""
      ." JOIN"
      ." (SELECT id AS target_id FROM dat_sales WHERE member_id = '{$member_id}') AS target_ids_by_member"
      ." ON dat_sales_product.sales_id = target_ids_by_member.target_id";
    }

    $sql .= ""
    ." WHERE 1=1"
    .$betweenAnd
    ." GROUP BY sales_id"
    .$orderBy
    .",sales_id"
    ." LIMIT {$offset}, 30";
    $stmt = $this->dbh->prepare($sql);
    $stmt->execute();

    return [$stmt->fetchAll(), $sql];
  }
  // ページャー用データ数取得
  public function getCountForOrder($product_id, $member_id, string $betweenAnd) {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    if ($member_id === 'error') {
      $member_id = '';
    }
    if ($product_id === 'error') {
      $product_id = '';
    }

    $sql = ""
    ."SELECT"
    ." COUNT(*)"
    ." FROM"
    ." ("
    ."SELECT"
    ." id"
    ." FROM"
    ." dat_sales"
    ." LEFT JOIN"
    ." dat_sales_product ON dat_sales.id = dat_sales_product.sales_id";
    if ($product_id) {
      $sql .= ""
      ." JOIN"
      ." (SELECT sales_id AS target_id FROM dat_sales_product WHERE product_id = '{$product_id}') AS target_ids_by_product"
      ." ON dat_sales_product.sales_id = target_ids_by_product.target_id";
    }
    if ($member_id) {
      $sql .= ""
      ." JOIN"
      ." (SELECT id AS target_id FROM dat_sales WHERE member_id = '{$member_id}') AS target_ids_by_member"
      ." ON dat_sales_product.sales_id = target_ids_by_member.target_id";
    }
    $sql .= ""
    ." WHERE 1=1"
    .$betweenAnd
    ." GROUP BY id"
    .") AS dummy";
    $stmt = $this->dbh->prepare($sql);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_COLUMN);
  }
  // order/sales 全件取得＋各合計金額 総売上推移
  public function getForSales(string $interv, string $betweenAnd, string $orderBy, $offset): array {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    // SELECT
    //   interv(引数から), total_price
    // FROM
    //   dat_sales_product
    // 副問合せ
    //   SELECT SUM(price * quantity) AS final_price

    $sql = ""
    ."SELECT"
    ." DATE_FORMAT(dat_sales_product.created_at, '{$interv}') AS interv"
    .",SUM(dat_sales_product.price * quantity) AS total_price"

    ." FROM"
    ." dat_sales_product"

    ." WHERE 1=1"
    .$betweenAnd
    ." GROUP BY interv"
    .$orderBy
    .",interv ASC"
    ." LIMIT {$offset}, 30";
    $stmt = $this->dbh->prepare($sql);
    $stmt->execute();

    return [$stmt->fetchAll(), $sql];
  }
  // ページャー用データ数取得
  public function getCountForSales(string $interv, string $betweenAnd) {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    $sql = ""
    ."SELECT"
    ." COUNT(*)"
    ." FROM"
    ." ("
    ."SELECT"
    ." DATE_FORMAT(dat_sales_product.created_at, '{$interv}') AS interv"
    ." FROM"
    ." dat_sales_product"
    ." WHERE 1=1"
    .$betweenAnd
    ." GROUP BY interv"
    .") AS dummy";
    $stmt = $this->dbh->prepare($sql);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_COLUMN);
  }

  // order/one_product_sales 全件取得＋各合計金額 総売上推移
  public function getForOneProductSales($product_id, string $interv, string $betweenAnd, string $orderBy, $offset): array {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    // SELECT
    //   interv(引数から), total_price
    // FROM
    //   dat_sales_product
    //   mst_product

    if ($product_id === 'error') {
      $product_id = '';
    }
    $sql = ""
    ."SELECT"
    ." DATE_FORMAT(dat_sales_product.created_at, '{$interv}') AS interv"
    .",SUM(dat_sales_product.price * quantity) AS total_price"

    ." FROM"
    ." dat_sales_product"
    ." JOIN"
    ." mst_product ON dat_sales_product.product_id = mst_product.id"

    ." WHERE 1=1"
    ." AND product_id = '{$product_id}'"
    .$betweenAnd
    ." GROUP BY interv"
    .$orderBy
    .",interv ASC"
    ." LIMIT {$offset}, 30";
    $stmt = $this->dbh->prepare($sql);
    $stmt->execute();

    return [$stmt->fetchAll(), $sql];
  }
  // ページャー用データ数取得
  public function getCountForOneProductSales($product_id, string $interv, string $betweenAnd) {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    if ($product_id === 'error') {
      $product_id = '';
    }
    $sql = ""
    ."SELECT"
    ." COUNT(*)"
    ." FROM"
    ." ("
    ."SELECT"
    ." DATE_FORMAT(dat_sales_product.created_at, '{$interv}') AS interv"
    ." FROM"
    ." dat_sales_product"
    ." WHERE 1=1"
    ." AND product_id = '{$product_id}'"
    .$betweenAnd
    ." GROUP BY interv"
    .") AS dummy";
    $stmt = $this->dbh->prepare($sql);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_COLUMN);
  }

  // order/product_sales 全件取得＋各合計金額 商品別売上比較
  public function getForProductSales($product_ids, string $betweenAnd, string $orderBy, $offset): array {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    // SELECT
    //   product_id, product_name, quantity, total_price, avg_unit_price,
    // 参考 (AVG(rating)
    // FROM
    //   dat_sales_product
    //   mst_product

    if ($product_ids === 'error') {
      $product_ids = '';
    }
    $sql = ""
    ."SELECT"
    ." product_id"
    .",name AS product_name"
    .",SUM(quantity) AS quantity"
    .",SUM(dat_sales_product.price * quantity) AS total_price"
    .",truncate(((SUM(dat_sales_product.price * quantity) / SUM(quantity)) + 0.5), 0) AS avg_unit_price"

    ." FROM"
    ." dat_sales_product"
    ." JOIN"
    ." mst_product ON dat_sales_product.product_id = mst_product.id"

    ." WHERE 1=1";
    if (!empty($product_ids)) {
      $sql .= " AND product_id IN ({$product_ids})";
    }
    $sql .= ""
    .$betweenAnd
    ." GROUP BY product_id"
    .$orderBy
    .",product_id ASC"
    ." LIMIT {$offset}, 30";
    $stmt = $this->dbh->prepare($sql);
    $stmt->execute();

    return [$stmt->fetchAll(), $sql];
  }
  // ページャー用データ数取得
  public function getCountForProductSales($product_ids, string $betweenAnd) {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    $sql = ""
    ."SELECT"
    ." COUNT(*)"
    ." FROM"
    ." ("
    ."SELECT"
    ." product_id"
    ." FROM"
    ." dat_sales_product"
    ." JOIN"
    ." mst_product ON dat_sales_product.product_id = mst_product.id"
    ." WHERE 1=1";
    if (!empty($product_ids)) {
      $sql .= " AND product_id IN ({$product_ids})";
    }
    $sql .= ""
    .$betweenAnd
    ." GROUP BY product_id"
    .") AS dummy";
    $stmt = $this->dbh->prepare($sql);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_COLUMN);
  }

  // order/sales_data 注文ID指定取得 注文伝票
  public function getForSalesData($sales_id): array {
    if (is_null($this->dbh)) {
      $this->dbh = getDb();
    }
    // SELECT
    //   sales_id, member_id, product_id, product_name,
    //   sales_price, quantity, total_price, final_price, created_at
    // FROM
    //   dat_sales
    //   dat_sales_product
    //   mst_product
    // 副問合せ
    //   SELECT SUM(price * quantity) AS final_price

    if ($sales_id === 'error') {
      $sales_id = '';
    }

    $sql = ""
    ."SELECT"
    ." dat_sales.id AS sales_id"
    .",dat_sales.member_id"
    .",dat_sales_product.product_id"
    .",mst_product.name AS product_name"
    .",dat_sales_product.price AS sales_price"
    .",quantity"
    .",(dat_sales_product.price * quantity) AS total_price"
    .",final_price"
    .",dat_sales.created_at"

    ." FROM"
    ." dat_sales"
    ." JOIN"
    ." dat_sales_product ON dat_sales.id = dat_sales_product.sales_id"
    ." JOIN"
    ." mst_product ON dat_sales_product.product_id = mst_product.id"
    ." JOIN"
    ." (SELECT sales_id, SUM(price * quantity) AS final_price"
    ." FROM dat_sales_product GROUP BY sales_id) AS final_prices"
    ." ON dat_sales.id = final_prices.sales_id"

    ." WHERE dat_sales.id=?"
    ." ORDER BY product_id ASC";
    $stmt = $this->dbh->prepare($sql);
    $data = array();
    $data[] = $sales_id;
    $stmt->execute($data);

    return $stmt->fetchAll();
  }
}
?>