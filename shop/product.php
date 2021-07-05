<?php
  require_once($_SERVER['DOCUMENT_ROOT'] . '/prophp/common/common.php');
  define('BASE', 'shop');
  sessionStart();

  require_once(D_ROOT . 'database/ProductDao.php');
  require_once(D_ROOT . 'common/outputOrders.php');

  $title = '商品一覧';
  include(D_ROOT . 'component/header_shop.php');
?>
<?php
  // ORDER BY
  $get_sort = inputGet('sort');
  list($sortAs, $orderBy) = getOrderByForProduct($get_sort);

  // LIMIT pager
  $page = pageCheck(inputGet('p', '1'));
  $per_page = 20;
  $offset = ($page - 1) * $per_page;
  try {
    $dao = new ProductDao();
    $count = $dao->getCountAllWithRating();
  } catch (PDOException $e) {
    dbError();
  }
  $pager = createPager($page, $count, $per_page);

  try {
    $products = $dao->findAllWithRating($orderBy, $offset);
?>

<h1>商品一覧</h1>

<p class="sort-link">
  <?php echo $sortAs['rat'] ?>
  <?php echo $sortAs['pri'] ?>
  <?php echo $sortAs['rpri'] ?>
  <?php echo $sortAs['rev'] ?>
</p>

<ul class="product-list">
  <?php foreach ($products as $product) : ?>
    <?php $param = http_build_query(['id' => $product['product_id']]) ?>
    <li class="product-item">
      <a href="view.php?<?php echo $param ?>">
        <!-- 商品画像 -->
        <div class="pict-frame">
          <img src="<?php echo S_NAME ?>admin/product/pict/<?php echo $product['pict'] ?>">
        </div>
        <div class="data">
          <!-- 商品名 -->
          <div class="product-name">
            <?php echo $product['name'] ?>
          </div>
          <!-- 価格 -->
          <div class="product-price tar">
            <?php echo number_format($product['price']) ?>円
          </div>
        </div>
      </a>
    </li>
  <?php endforeach ?>
</ul>

<div class="pager tac"><?php echo $pager ?></div>

<?php
  } catch (PDOException $e) {
    dbError();
  }
?>

<?php include(D_ROOT . 'component/footer_shop.php') ?>