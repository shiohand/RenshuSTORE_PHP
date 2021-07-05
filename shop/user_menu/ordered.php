<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'shop');
  reqLogin();

  require_once(D_ROOT.'database/OrderDao.php');
  require_once(D_ROOT.'common/outputOrders.php');

  $title = '注文履歴';
  include(D_ROOT.'component/header_shop.php');
?>
<?php
  $member_id = $_SESSION['member_id'];

  // BETWEEN
  $get_term = inputGet('term');
  list($termAs, $betweenAnd) = getTerms($get_term, 'dat_sales.created_at');

  // LIMIT pager
  $page = pageCheck(inputGet('p', '1'));
  $per_page = 5;
  $offset = ($page - 1) * $per_page;
  try {
    $dao = new OrderDao();
    // countはsales_id単位
    $count = $dao->getCountForOrdered($member_id, $betweenAnd);
  } catch (PDOException $e) {
    dbError();
  }
  $pager = createPager($page, $count, $per_page);

  try {
    $dao = new OrderDao();
    $orders = $dao->getForOrdered($member_id, $betweenAnd, $offset);

    $last_sales_id = 0;
?>

<h1>注文履歴</h1>

<p class="sort-link">
  <?php echo $termAs['all'] ?>
  <?php echo $termAs['day'] ?>
  <?php echo $termAs['week'] ?>
  <?php echo $termAs['month'] ?>
  <?php echo $termAs['year'] ?>
</p>

<div class="pager"><?php echo $pager ?></div>

<?php if (!empty($orders)): ?>

  <div class="ordered-view">

    <?php foreach ($orders as $order): ?>

      <!-- 注文IDが変わったときだけordered-title -->
      <?php if ($last_sales_id !== $order['sales_id']):?>
        <!-- 初回以外は前回のordered-productsを閉じる -->
        <?php if ($last_sales_id !== 0):?>
          </div>
        <?php endif?>

        <div class="ordered-products">
          <div class="ordered-title">
            <div class="ordered-date">
              注文日時: <?php echo date('Y/m/d H:i:s', strtotime($order['created_at'])) ?>
            </div>
            <div class="ordered-totalprice">
              合計金額: <?php echo number_format($order['final_price']) ?>円
            </div>
          </div>
          <?php $last_sales_id = $order['sales_id'] ?>
      <?php endif?>

      <!-- URL生成 -->
      <?php $param = http_build_query(['id' => $order['product_id']]) ?>
      <div class="product-datas-list">
        <!-- 商品画像 -->
        <div class="pict-frame">
          <img src="<?php echo S_NAME ?>admin/product/pict/<?php echo $order['pict'] ?>">
        </div>
        <div class="datas">
          <!-- 商品名 -->
          <div class="data"><a href="<?php echo S_NAME ?>shop/view.php?<?php echo $param ?>"><?php echo $order['product_name'] ?></a></div>
          <!-- 価格 -->
          <div class="data"><?php echo number_format($order['sales_price']) ?>円</div>
          <!-- 数量 -->
          <div class="data">数量: <?php echo $order['quantity'] ?>個</div>
          <!-- 小計 -->
          <div class="data">小計: <?php echo number_format($order['total_price']) ?>円</div>
        </div>
      </div>
    <?php endforeach?>
    </div><!-- 最後のordered-productsを閉じる -->
  </div>

<?php else: ?>
  <p class="confirm-text">該当なし</p>
<?php endif ?>

<div class="pager"><?php echo $pager ?></div>

<?php
  } catch (PDOException $e) {
    dbError();
  }
?>

<?php include(D_ROOT.'component/footer_shop.php') ?>