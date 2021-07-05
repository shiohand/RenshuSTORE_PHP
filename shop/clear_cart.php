<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'shop');
  sessionStart();

  $title = 'カートを空にする';
  include(D_ROOT.'component/header_shop.php');
?>
<?php
  unset($_SESSION['cart']);
?>

<h1>カートを空にする</h1>

<p class="confirm-text">カートを空にしました</p>

<ul class="back-nav">
  <li>
    <a href="<?php echo S_NAME ?>shop/product.php">商品一覧へ</a>
  </li>
  <li>
    <a href="<?php echo S_NAME ?>shop/top.php">トップページへ</a>
  </li>
</ul>

<?php include(D_ROOT.'component/footer_shop.php') ?>