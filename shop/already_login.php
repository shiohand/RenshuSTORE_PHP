<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'shop');
  reqLogin();

  $title = 'ログイン済';
  include(D_ROOT.'component/header_shop.php');
?>

<h1>ログイン済です</h1>

<ul class="back-nav">
  <li>
    <a href="logout.php">ログアウト</a>
  </li>
  <li>
    <a href="product.php">商品一覧へ</a>
  </li>
  <li>
    <a href="top.php">トップページへ</a>
  </li>
</ul>

<?php include(D_ROOT.'component/footer_shop.php') ?>