<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');

// getDb()  DB接続

/* マッピング */
// mapStaff()        mapStaffs()
// mapProduct()      mapProducts()
// mapMember()       mapMembers()
// mapReview()       mapReviews()
// mapSales()        mapSaless()
// mapSalesProduct() mapSalesProducts()

/* SQL生成 */
// makeOptBetween()        BETWEEN 作成 makeOptBetween(フィールド, 期間1, 期間2)
// makeOptBetweenByTerm()  24時間, 月間, ...からBETWEENを作成
// makeOptOrderBy()        ORDER BY 作成
// makeOptIntervStr()      interv sales.php one_product_sales.php

// DB接続
function getDb() {
  $dsn = 'mysql:dbname=prophp;host=localhost;charset=utf8';
  $user = 'root';
  $password = '';
  $dbh = new PDO($dsn, $user, $password, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
  ]);

  return $dbh;
}

/* マッピング */
// Staff
function mapStaff($row): Staff {
  require_once(D_ROOT.'database/Staff.php');
  if ($row) {
    $staff = new Staff(
      $row['id'] ?? '',
      $row['name'] ?? '',
      $row['password'] ?? '',
      $row['created_at'] ?? ''
    );
  } else {
    $staff = new Staff('','','','');
  }
  return $staff;
}
function mapStaffs(array $rows): array {
  require_once(D_ROOT.'database/Staff.php');
  $staffs = array();
  if ($rows) {
    foreach ($rows as $row) {
      $staff = new Staff(
        $row['id'] ?? '',
        $row['name'] ?? '',
        $row['password'] ?? '',
        $row['created_at'] ?? ''
      );
      $staffs[] = $staff;
    }
  } else {
    $staff = new Staff('','','','');
    $staffs[] = $staff;
  }
  return $staffs;
}
// Product
function mapProduct($row): Product {
  require_once(D_ROOT.'database/Product.php');
  if ($row) {
    $product = new Product(
      $row['id'] ?? '',
      $row['name'] ?? '',
      $row['price'] ?? '',
      $row['content'] ?? '',
      $row['pict'] ?? '',
      $row['release_date'] ?? '',
      $row['created_at'] ?? ''
    );
  } else {
    $product = new Product('','','','','','','');
  }
  return $product;
}
function mapProducts(array $rows): array {
  require_once(D_ROOT.'database/Product.php');
  $products = array();
  if ($rows) {
    foreach ($rows as $row) {
      $product = new Product(
        $row['id'] ?? '',
        $row['name'] ?? '',
        $row['price'] ?? '',
        $row['content'] ?? '',
        $row['pict'] ?? '',
        $row['release_date'] ?? '',
        $row['created_at'] ?? ''
      );
      $products[] = $product;
    }
  } else {
    $product = new Product('','','','','','','');
    $products[] = $product;
  }
  return $products;
}
// Member
function mapMember($row): Member {
  require_once(D_ROOT.'database/Member.php');
  if ($row) {
    $member = new Member(
      $row['id'] ?? '',
      $row['email'] ?? '',
      $row['password'] ?? '',
      $row['name'] ?? '',
      $row['postal1'] ?? '',
      $row['postal2'] ?? '',
      $row['address'] ?? '',
      $row['tel'] ?? '',
      $row['gender'] ?? '',
      $row['birth'] ?? '',
      $row['created_at'] ?? ''
    );
  } else {
    $member = new Member('','','','','','','','','','','');
  }
  return $member;
}
function mapMembers(array $rows): array {
  require_once(D_ROOT.'database/Member.php');
  $members = array();
  if ($rows) {
    foreach ($rows as $row) {
      $member = new Member(
        $row['id'] ?? '',
        $row['email'] ?? '',
        $row['password'] ?? '',
        $row['name'] ?? '',
        $row['postal1'] ?? '',
        $row['postal2'] ?? '',
        $row['address'] ?? '',
        $row['tel'] ?? '',
        $row['gender'] ?? '',
        $row['birth'] ?? '',
        $row['created_at'] ?? ''
      );
      $members[] = $member;
    }
  } else {
    $member = new Member('','','','','','','','','','','');
    $members[] = $member;
  }
  return $members;
}
// Review
function mapReview($row): Review {
  require_once(D_ROOT.'database/Review.php');
  if ($row) {
    $review = new Review(
      $row['product_id'] ?? '',
      $row['member_id'] ?? '',
      $row['rating'] ?? '',
      $row['body'] ?? '',
      $row['created_at'] ?? ''
    );
  } else {
    $review = new Review('','','','','');
  }
  return $review;
}
function mapReviews(array $rows): array {
  require_once(D_ROOT.'database/Review.php');
  $reviews = array();
  if ($rows) {
    foreach ($rows as $row) {
      $review = new Review(
        $row['product_id'] ?? '',
        $row['member_id'] ?? '',
        $row['rating'] ?? '',
        $row['body'] ?? '',
        $row['created_at'] ?? ''
      );
      $reviews[] = $review;
    }
  } else {
    $review = new Review('','','','','');
    $reviews[] = $review;
  }
  return $reviews;
}
// Sales
function mapSales($row): Sales {
  require_once(D_ROOT.'database/Sales.php');
  if ($row) {
    $sales = new Sales(
      $row['id'] ?? '',
      $row['member_id'] ?? '',
      $row['email'] ?? '',
      $row['name'] ?? '',
      $row['postal1'] ?? '',
      $row['postal2'] ?? '',
      $row['address'] ?? '',
      $row['tel'] ?? '',
      $row['created_at'] ?? ''
    );
  } else {
    $sales = new Sales('','','','','','','','','');
  }
  return $sales;
}
function mapSaless(array $rows): array {
  require_once(D_ROOT.'database/Sales.php');
  $saless = array();
  if ($rows) {
    foreach ($rows as $row) {
      $sales = new Sales(
        $row['id'] ?? '',
        $row['member_id'] ?? '',
        $row['email'] ?? '',
        $row['name'] ?? '',
        $row['postal1'] ?? '',
        $row['postal2'] ?? '',
        $row['address'] ?? '',
        $row['tel'] ?? '',
        $row['created_at'] ?? ''
      );
      $saless[] = $sales;
    }
  } else {
    $sales = new Sales('','','','','','','','','');
    $saless[] = $sales;
  }
  return $saless;
}
// SalesProduct
function mapSalesProduct($row): SalesProduct {
  require_once(D_ROOT.'database/SalesProduct.php');
  if ($row) {
    $review = new SalesProduct(
      $row['product_id'] ?? '',
      $row['member_id'] ?? '',
      $row['rating'] ?? '',
      $row['body'] ?? '',
      $row['created_at'] ?? ''
    );
  } else {
    $review = new SalesProduct('','','','','');
  }
  return $review;
}
function mapSalesProducts(array $rows): array {
  require_once(D_ROOT.'database/SalesProduct.php');
  $reviews = array();
  if ($rows) {
    foreach ($rows as $row) {
      $review = new SalesProduct(
        $row['product_id'] ?? '',
        $row['member_id'] ?? '',
        $row['rating'] ?? '',
        $row['body'] ?? '',
        $row['created_at'] ?? ''
      );
      $reviews[] = $review;
    }
  } else {
    $review = new SalesProduct('','','','','');
    $reviews[] = $review;
  }
  return $reviews;
}

/* SQL生成 */
// BETWEEN 作成 makeOptBetween(フィールド, 期間1, 期間2)
function makeOptBetween($name, $start, $end): string {
  $end = date_create($end)->modify('1 day')->format('Y-m-d');
  if ($start && $end) {
    return " AND '{$start}' <= {$name}"
    ." AND {$name} < '{$end}'";
  } elseif ($start) {
    return " AND '{$start}' <= {$name}";
  } elseif ($end) {
    return " AND {$name} < '{$end}'";
  } else {
    return '';
  }
}
// 24時間, 月間, ...からBETWEENを作成
function makeOptBetweenByTerm($name, $val): string {
  $term = '';
  switch ($val) {
    case 'day':
      $term = 'day';
      break;
    case 'week':
      $term = 'weeks';
      break;
    case 'month':
      $term = 'month';
      break;
    case 'year':
      $term = 'year';
      break;
    // 他は期間指定なし
    case 'all':
      return '';
      break;
    default:
      return '';
      break;
  }
  $start = date('Y-m-d H:i:s', strtotime('- 1'.$term));
  $end = date('Y-m-d H:i:s');
  return makeOptBetween($name, $start, $end);
}
// ORDER BY 作成
function makeOptOrderBy($name, $desc = false): string {
  $ret = " ORDER BY {$name}";
  if ($desc) {
    $ret .= " DESC";
  }
  return $ret;
}
// interv sales.php one_product_sales.php
function makeOptIntervStr($val): string {
  switch ($val) {
    case 'month':
      return '%Y-%m';
      break;
    case 'year':
      return '%Y';
      break;
    // 他は日別
    case 'day':
      return '%Y-%m-%d';
      break;
    default:
      return '%Y-%m-%d';
      break;
  }
}
?>