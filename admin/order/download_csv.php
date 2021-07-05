<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'admin');
  reqLogin();

  require_once(D_ROOT.'database/dbTool.php');

  $title = 'CSVダウンロード';
  include(D_ROOT.'component/header_admin.php');
?>
<?php
  define('FILE_NAME', './file/records.csv');
  $fields_arr = [
    'order' => ['注文ID', '会員ID', 'メールアドレス', '会員名', '金額', '注文日時'],
    'sales' => ['期間', '小計'],
    'product_sales' => ['商品ID', '商品名', '数量', '計', '平均単価'],
    'one_product_sales' => ['期間', '小計'],
  ];
  $from = [
    'order' => '注文データ',
    'sales' => '総売上',
    'product_sales' => '商品別売上',
    'one_product_sales' => '指定商品売上'
  ];

  reqPost();
  // どこからのリクエストかを受け取り、見出しリスト設定
  $req = inputPost('req');
  $fields = $fields_arr[$req];

  reqSession($req.'_sql');
  $sql = $_SESSION[$req.'_sql'];

  if (file_exists(FILE_NAME)) {
    unlink(FILE_NAME);
  }

  try {
    $dbh = getDb();
    $stmt = $dbh->prepare($sql);
    $stmt->execute();

  $fh = fopen(FILE_NAME, 'wb');
  fputcsv($fh, $fields);
  while ($row = $stmt->fetch()) {
    fputcsv($fh, $row);
  }
?>

<div class="order-container">
  <nav class="order-nav">
    <ul>
      <?php
        outputNavLis([
          ['注文管理トップ', 'top.php'],
          ['注文データ', 'order.php'],
          ['総売上', 'sales.php'], 
          ['商品別売上', 'product_sales.php'], 
          ['指定商品売上', 'one_product_sales.php'], 
        ]);
      ?>
    </ul>
  </nav>

  <h1>CSVダウンロード</h1>

  <p class="confirm-text"><?php echo $from[$req] ?>テーブルをダウンロードします</p>

  <div  class="btns">
    <button class="btn btn-primary" type="button" onclick="location.href='<?php echo FILE_NAME ?>'">ダウンロード</button>
  </div>
</div>

<?php
  } catch (PDOException $e) {
    dbError();
  }
?>

<?php include(D_ROOT.'component/footer_admin.php') ?>