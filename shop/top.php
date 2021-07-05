<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'shop');
  sessionStart();

  require_once(D_ROOT.'database/ProductDao.php');

  $title = 'トップ';
  include(D_ROOT.'component/header_shop.php');
?>
<?php
  $now = date('Y-m-d H:i:s');
?>

<h1>shopトップ</h1>

<div class="top-links tac">
  <div class="product tal"><a href="product.php">商品一覧へ</a></div>
  <div class="ranking tal"><a href="ranking.php">ランキングへ</a></div>
</div>

<!-- ランキング -->
<?php
  try {
    $dao = new ProductDao();

    $betweenAnd = makeOptBetweenByTerm('dat_sales_product.created_at', 'month');
    $rnk_products = $dao->getForRank($betweenAnd, 5);

    $rnk_count = 1;
?>

<h2><div class="h2 h2-top">ランキング(月間売上額)</div></h2>

<div class="link link-top"><a class="btn btn-round" href="ranking.php">ランキングの続きへ</a></div>

<div class="ranking-top scroll"><!-- 横スクロール -->
  <?php if ($rnk_products[0]->getId()) : ?>
    <div class="top-list">
      <?php foreach ($rnk_products as $product) : ?>
        <?php $param = http_build_query(['id' => $product->getId()]) ?>
        <div class="top-list-item">
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
</div>

<div class="link link-top"><a class="btn btn-round" href="ranking.php">ランキングの続きへ</a></div>

<?php
  } catch (PDOException $e) {
    dbError();
  }
?>

<!-- 新着商品 -->
<?php
  try {
    $dao = new ProductDao();

    $opt = '';
    $opt .= " AND release_date <= '{$now}'";
    $opt .= makeOptOrderBy('release_date', true);
    $products = $dao->getForShop($opt);
?>

<h2><div class="h2 h2-top">新着商品</div></h2>

<div class="link link-top"><a class="btn btn-round" href="product.php">商品一覧へ</a></div>

<div class="product-top scroll"><!-- 横スクロール -->
  <?php if ($products[0]->getId()) : ?>
    <div class="top-list">
      <?php foreach ($products as $product) : ?>
        <?php $param = http_build_query(['id' => $product->getId()]) ?>
        <div class="top-list-item">
          <a href="view.php?<?php echo $param ?>">
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
</div>

<div class="link link-top"><a class="btn btn-round" href="product.php">商品一覧へ</a></div>

<?php
  } catch (PDOException $e) {
    dbError();
  }
?>

<!-- 発売予定 -->
<?php
  try {
    $dao = new ProductDao();
  
    $opt = '';
    $opt .= " AND '{$now}' < release_date";
    $opt .= makeOptOrderBy('release_date', false);
    $products = $dao->getForShop($opt);
?>

<h2><div class="h2 h2-top">発売予定</div></h2>

<div class="product-top scroll"><!-- 横スクロール -->
  <?php if ($products[0]->getId()) : ?>
    <div class="top-list">
      <?php foreach ($products as $product) : ?>
        <?php $param = http_build_query(['id' => $product->getId()]) ?>
        <div class="top-list-item">
          <a href="view.php?<?php echo $param ?>">
            <!-- 商品画像 -->
            <div class="pict-frame">
              <img src="<?php echo S_NAME ?>admin/product/pict/<?php echo $product->getPict() ?>">
            </div>
            <div class="data">
              <!-- 商品名 -->
              <div class="product-name">
                <?php echo $product->getName() ?>
              </div>
              <!-- 発売日 -->
              <div class="product-release tar">
                <?php echo date('Y年m月d日', strtotime($product->getReleaseDate())) ?>
              </div>
            </div>
          </a>
        </div>
      <?php endforeach ?>
    </div>
  <?php else : ?>
    <p class="confirm-text">結果がありません<p>
  <?php endif ?>
</div>

<?php
  } catch (PDOException $e) {
    dbError();
  }
?>

<?php include(D_ROOT.'component/footer_shop.php') ?>