<?php
  require_once($_SERVER['DOCUMENT_ROOT'] . '/prophp/common/common.php');
  define('BASE', 'shop');
  sessionStart();

  require_once(D_ROOT . 'database/ProductDao.php');
  require_once(D_ROOT . 'common/outputOrders.php');

  $title = 'ランキング';
  include(D_ROOT . 'component/header_shop.php');
?>
<?php
  // BETWEEN
  $get_term = inputGet('term');
  list($termAs, $betweenAnd) = getTerms($get_term, 'dat_sales_product.created_at');

  try {
    $dao = new ProductDao();
    $rnk_products = $dao->getForRank($betweenAnd, 10);

    $rnk_count = 1;
?>

<h1>ランキング</h1>

<p class="sort-link">
  <?php echo $termAs['all'] ?>
  <?php echo $termAs['day'] ?>
  <?php echo $termAs['week'] ?>
  <?php echo $termAs['month'] ?>
  <?php echo $termAs['year'] ?>
</p>

<?php if ($rnk_products[0]->getId()) : ?>
  <div class="ranking-list">
    <?php foreach ($rnk_products as $product) : ?>
      <?php $param = http_build_query(['id' => $product->getId()]) ?>
      <div class="ranking-item">
        <a href="view.php?<?php echo $param ?>">
          <!-- ランク -->
          <div class="ranking-num">
            <div class="rank-badge"><span><?php echo $rnk_count++ ?></span></div>
          </div>
          <!-- 商品画像 -->
          <div class="pict-frame">
            <img src="<?php echo S_NAME ?>admin/product/pict/<?php echo $product->getPict() ?>">
          </div>
          <div class="data">
            <!-- 商品名 -->
            <div class="product-name">
              <?php echo $product->getName() ?>
            </div>
            <!-- 価格 -->
            <div class="product-price tar">
              <?php echo number_format($product->getPrice()) ?>円
            </div>
          </div>
        </a>
      </div>
    <?php endforeach ?>
  </div>
<?php else : ?>
  <p class="confirm-text">結果がありません<p>
<?php endif ?>

<?php
  } catch (PDOException $e) {
  dbError();
  }
?>

<?php include(D_ROOT . 'component/footer_shop.php') ?>