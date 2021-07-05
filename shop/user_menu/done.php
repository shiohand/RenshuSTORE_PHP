<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'shop');
  sessionStart();

  $title = '完了';
  include(D_ROOT.'component/header_shop.php');
?>
<?php
  reqSession('msg');
  $msg = $_SESSION['msg'];
  unset($_SESSION['msg']);
?>

<h1>完了</h1>

<p class="confirm-text"><?php echo $msg ?></p>

<ul class="back-nav">
  <li>
    <a href="<?php echo S_NAME ?>shop/product.php">商品一覧へ</a>
  </li>
  <li>
    <a href="<?php echo S_NAME ?>shop/top.php">トップページへ</a>
  </li>
</ul>

<?php include(D_ROOT.'component/footer_shop.php') ?>