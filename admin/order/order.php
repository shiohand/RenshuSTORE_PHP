<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'admin');
  reqLogin();

  require_once(D_ROOT.'database/OrderDao.php');
  require_once(D_ROOT.'common/outputOrders.php');

  $title = '注文管理';
  include(D_ROOT.'component/header_admin.php');
?>
<?php
  // フィルター引き継ぎ
  // リセット・クリア処理
  if ((empty($_GET) && empty($_POST)) || (inputPost('submit_clear') === 'クリア')) {
    $_SESSION['order-filter'] = array();
    $post_member_id = '';
    $post_product_id = '';
    $post_at_start = '';
    $post_at_end = '';
  } else {
    // post受け取り->なければsessionから受け取り->なければ空
    $post_member_id = inputPost('member_id');
    $post_product_id = inputPost('product_id');
    $post_at_start = dateCheck(inputPost('at_start'));
    $post_at_end = dateCheck(inputPost('at_end'));
    if (!$post_member_id) {
        $post_member_id = $_SESSION['order-filter']['post_member_id'] ?? '';
    }
    if (!$post_product_id) {
        $post_product_id = $_SESSION['order-filter']['post_product_id'] ?? '';
    }
    if (!$post_at_start) {
      $post_at_start = $_SESSION['order-filter']['post_at_start'] ?? '';
    }
    if (!$post_at_end) {
        $post_at_end = $_SESSION['order-filter']['post_at_end'] ?? '';
    }
  }
  // session更新
  $_SESSION['order-filter']['post_member_id'] = $post_member_id;
  $_SESSION['order-filter']['post_product_id'] = $post_product_id;
  $_SESSION['order-filter']['post_at_start'] = $post_at_start;
  $_SESSION['order-filter']['post_at_end'] = $post_at_end;

  // id
  if ($post_member_id && !preg_match('/\A\d+\z/', $post_member_id)) {
    $post_member_id = 'error';
  };
  // id
  if ($post_product_id && !preg_match('/\A\d+\z/', $post_product_id)) {
    $post_product_id = 'error';
  };

  // BETWEEN
  $get_term = inputGet('term');
  list($termAs, $betweenAnd) = getTerms($get_term, 'dat_sales.created_at');

  // BETWEEN
  $betweenAnd .= makeOptBetween('dat_sales.created_at', $post_at_start, $post_at_end);

  // ORDER BY
  $get_sort = inputGet('sort');
  list($sortAs, $orderBy) = getOrderByForOrder($get_sort);

  // LIMIT pager
  $page = pageCheck(inputGet('p', '1'));
  if ((!preg_match('/\A\d+\z/', $page)) || $page == '0') {
    $page = '1';
  }
  $per_page = 30;
  $offset = ($page - 1) * $per_page;
  try {
    $dao = new OrderDao();
    $count = $dao->getCountForOrder($post_product_id, $post_member_id, $betweenAnd);
  } catch (PDOException $e) {
    dbError();
  }
  $pager = createPager($page, $count, $per_page);

  try {
    list($orders, $_SESSION['order_sql']) = $dao->getForOrder($post_product_id, $post_member_id, $betweenAnd, $orderBy, $offset);
?>

<div class="order-container">
  <nav class="order-nav">
    <ul>
      <?php
        outputNavLis([
          ['注文管理トップ', 'top.php'],
          ['注文データ', ''],
          ['総売上', 'sales.php'], 
          ['商品別売上', 'product_sales.php'], 
          ['指定商品売上', 'one_product_sales.php'], 
        ]);
      ?>
    </ul>
  </nav>

<h1>注文データ一覧</h1>

  <p class="link">
    <a href="<?php echo URL ?>">リセット</a>
  </p>

  <form class="filter" method="post" action="<?php echo URI ?>">
    <p>
      期間指定: <input type="date" name="at_start" value="<?php echo $post_at_start ?>"> から <input type="date" name="at_end" value="<?php echo $post_at_end ?>"> まで
    </p>
    <p>
      <label>会員ID: <input class="short-input" type="text" name="member_id" value="<?php echo $post_member_id ?>"> の注文</label>
      <span class="delimiter"></span>
      <label>商品ID: <input class="short-input" type="text" name="product_id" value="<?php echo $post_product_id ?>"> を含む注文</label>
    </p>
    <div class="btns">
      <input class="btn btn-primary" type="submit" value="絞り込み">
      <input class="btn btn-secondory" type="submit" name="submit_clear" value="クリア">
    </div>
  </form>

  <p class="sort-link">
    <?php echo $termAs['all'] ?>
    <?php echo $termAs['day'] ?>
    <?php echo $termAs['week'] ?>
    <?php echo $termAs['month'] ?>
    <?php echo $termAs['year'] ?>
  </p>

  <div class="pager"><?php echo $pager ?></div>

  <table class="data-table">
    <tr>
      <th>注文ID <?php echo $sortAs['sid'] ?></th>
      <th>会員ID <?php echo $sortAs['mid'] ?></th>
      <th>メールアドレス</th>
      <th>会員名</th>
      <th>金額 <?php echo $sortAs['pri'] ?></th>
      <th>注文日時 <?php echo $sortAs['at'] ?></th>
    </tr>
    <?php foreach ($orders as $order):?>
      <tr>
        <!-- 注文ID -->
        <td class="tar">
          <?php echo $order['sales_id'] ?>
        </td>
        <!-- 会員ID -->
        <td class="tar">
          <?php echo $order['member_id'] ?>
        </td>
        <!-- メールアドレス -->
        <td>
          <?php echo $order['email'] ?>
        </td>
        <!-- 会員名 -->
        <td>
          <?php echo $order['member_name'] ?>
        </td>
        <!-- 金額 -->
        <td class="tar">
          <?php echo number_format($order['final_price']) ?>
        </td>
        <!-- 注文日時 -->
        <td class="tar">
          <?php echo $order['created_at'] ?>
        </td>
      </tr>
    <?php endforeach ?>
  </table>

  <div class="pager"><?php echo $pager ?></div>

  <form method="post" action="download_csv.php">
    <input type="hidden" name="req" value="order">
    <div class="btns">
      <input class="btn btn-secondory" type="submit" value="CSVダウンロード">
    </div>
  </form>
</div>

<?php
  } catch (PDOException $e) {
    dbError();
  }
?>

<?php include(D_ROOT.'component/footer_admin.php') ?>