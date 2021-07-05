<?php
  require_once($_SERVER['DOCUMENT_ROOT'] . '/prophp/common/common.php');
  define('BASE', 'shop');
  sessionStart();

  require_once(D_ROOT . 'database/ProductDao.php');
  require_once(D_ROOT . 'database/ReviewDao.php');

  $title = '商品詳細';
  include(D_ROOT . 'component/header_shop.php');
?>
<?php
  if (isset($_SESSION['member_login'])) {
    $member_id = $_SESSION['member_id'];
  }

  reqGet('id');
  $product_id = inputGet('id');

  try {
    $p_dao = new ProductDao();
    $product = $p_dao->findById($product_id);
    blockModelEmpty($product);
    $r_dao = new ReviewDao();
    $reviews = $r_dao->findByProductId($product->getId());
?>

<h1>商品詳細</h1>

<div class="link">
  <a class="btn btn-round" href="product.php">商品一覧へ</a>
</div>

<form class="product-view" method="post" action="cartin.php">
  <div class="pict-frame">
    <img src="<?php echo S_NAME ?>admin/product/pict/<?php echo $product->getPict() ?>">
  </div>
  <div class="datas">
    <div class="product-name">
      <p class="field-name">商品名</p>
      <?php echo $product->getName() ?>
    </div>
    <div class="product-price">
      <p class="field-name">価格</p>
      <?php echo number_format($product->getPrice()) ?>円
    </div>
    <div class="action">
      <?php if (strtotime($product->getReleaseDate()) <= time()) : ?>
        <div class="quantity">
          数量: <input type="number" name="quantity" value="1" min="1"> 個
        </div>
        <div class="product-cartin">
          <input type="hidden" name="target" value="<?php echo $product_id ?>">
          <input class="btn btn-primary" type="submit" value="カートに入れる">
        </div>
      <?php else : ?>
        <?php echo date('Y年m月d日', strtotime($product->getReleaseDate())) ?> 発売予定
      <?php endif ?>
    </div>
  </div>
</form>

<div class="product-info">
  <h2><div class="h2">商品説明</div></h2>
  <div class="product-info-body"><?php echo nl2br(str_replace('&#10;', '<br>', $product->getContent())) ?></div>
</div>

<div class="review-container">
  <div class="review-input">
    <h2><div class="h2">レビューを書く</div></h2>
    <div class="review-form">
      <?php if (isset($member_id)) : ?>
        <?php if (!$r_dao->isAlreadyExists($product_id, $member_id)) : ?>
          <form method="post" action="user_menu/review_check.php">
            <span class="announce">※1000文字以内でご入力ください</span>
            <textarea name="review_post"></textarea>
            <div class="rating-input">
              評価(★1～5): <br class="sp-br">
              <label for="rat1"><input id="rat1" type="radio" name="rating" value="1">1</label>
              <label for="rat2"><input id="rat2" type="radio" name="rating" value="2">2</label>
              <label for="rat3"><input id="rat3" type="radio" name="rating" value="3" checked>3</label>
              <label for="rat4"><input id="rat4" type="radio" name="rating" value="4">4</label>
              <label for="rat5"><input id="rat5" type="radio" name="rating" value="5">5</label>
            </div>
            <div class="review-post">
              <input type="hidden" name="target" value="<?php echo $product_id ?>">
              <input class="btn btn-primary" type="submit" value="確認">
            </div>
          </form>
        <?php else : ?>
          <p class="confirm-text">
            レビュー済みです
          </p>
        <?php endif ?>
      <?php else : ?>
        <p class="confirm-text">
          ログインが必要です
        </p>
        <ul class="back-nav">
          <li><a href="login.php">ログイン</a></li>
          <li><a href="user_menu/signup.php">会員登録する</a></li>
        </ul>
      <?php endif ?>
    </div>
  </div>

  <div class="reviews">
    <h2><div class="h2">レビュー一覧</div></h2>
    <div class="review-list">
      <?php if (!empty($reviews)) : ?>
        <?php foreach ($reviews as $review) : ?>
          <div class="review-item">
            <div class="review-data">
              <p>会員名: <?php echo $review['member_name'] ?></p>
              <p>評価: <?php outputRating($review['rating']) ?></p>
              <p>投稿日: <?php echo date('Y/m/d H:i:s', strtotime($review['created_at'])) ?></p>
            </div>
            <div class="review-body">
              <?php echo nl2br(str_replace('&#10;', '<br>', $review['body'])) ?>
            </div>
          </div>
        <?php endforeach ?>
      <?php else : ?>
        <p class="confirm-text">まだレビューがありません</p>
      <?php endif ?>
    </div>
  </div>
</div>

<?php
  } catch (PDOException $e) {
    dbError();
  }
?>

<?php include(D_ROOT . 'component/footer_shop.php') ?>