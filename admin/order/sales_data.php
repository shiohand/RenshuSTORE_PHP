<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'admin');
  reqLogin();

  require_once(D_ROOT.'database/OrderDao.php');

  $title = '注文管理';
  include(D_ROOT.'component/header_admin.php');
?>
<?php
  $post_sales_id = inputPost('sales_id');
  if ($post_sales_id && !preg_match('/\A\d+\z/', $post_sales_id)) {
    $post_sales_id = 'error';
  };

  try {
    $dao = new OrderDao();
    $orders = $dao->getForSalesData($post_sales_id);
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

  <h1>注文詳細</h1>

  <form class="filter" method="post" action="sales_data.php">
    <p>
      <label for="sales_id">注文ID: <input id="sales_id" class="short-input" type="text" name="sales_id" value="<?php echo $post_sales_id ?>"></label>
      <input class="btn btn-primary" type="submit" value="注文詳細表示">
    </p>
  </form>

  <?php if (!empty($orders)): ?>
    <p>注文ID: <?php echo $orders[0]['sales_id'] ?></p>
    <p>会員ID: <?php echo $orders[0]['member_id'] ?></p>
    <p>注文日時: <?php echo date('Y/m/d H:i:s', strtotime($orders[0]['created_at'])) ?></p>

    <table class="data-table">
      <tr>
        <th>商品ID</th>
        <th>商品名</th>
        <th>価格(円)</th>
        <th>数量(個)</th>
        <th>小計(円)</th>
      </tr>
      <?php foreach ($orders as $order):?>
        <tr>
          <!-- 商品ID -->
          <td class="tar">
            <?php echo $order['product_id'] ?>
          </td>
          <!-- 商品名 -->
          <td>
            <?php echo $order['product_name'] ?>
          </td>
          <!-- 価格 -->
          <td class="tar">
            <?php echo number_format($order['sales_price']) ?>
          </td>
          <!-- 数量 -->
          <td class="tar">
            <?php echo $order['quantity'] ?>
          </td>
          <!-- 小計 -->
          <td class="tar">
            <?php echo number_format($order['total_price']) ?>
          </td>
        </tr>
      <?php endforeach ?>
    </table>

    <p class="sales-data-total">合計金額: <?php echo number_format($order['final_price']) ?>円</p>

  <?php else: ?>
    <p class="confirm-text">データがありません</p>
  <?php endif ?>
</div>

<?php
  } catch (PDOException $e) {
    dbError();
  }
?>

<?php include(D_ROOT.'component/footer_admin.php') ?>