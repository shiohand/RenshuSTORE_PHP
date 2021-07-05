<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'shop');
  sessionStart();

  $title = '注文完了';
  include(D_ROOT.'component/header_shop.php');
?>
<?php
  reqSession('msg');
  list($msg, $body) = $_SESSION['msg'];
  unset($_SESSION['msg']);
?>

<h1>注文完了</h1>

<p class="confirm-text"><?php echo nl2br($msg) ?></p>

<div class="mail-preview" style="display:none">
  <!-- テスト用 -->
  <h2><div class="h2">確認 顧客側 注文完了メール</div></h2>
  <p><?php echo nl2br($body) ?></p>
  <!-- endテスト用 -->
</div>

<ul class="back-nav">
  <li>
    <a href="<?php echo S_NAME ?>shop/product.php">商品一覧へ</a>
  </li>
  <li>
    <a href="<?php echo S_NAME ?>shop/top.php">トップページへ</a>
  </li>
</ul>

<?php include(D_ROOT.'component/footer_shop.php') ?>