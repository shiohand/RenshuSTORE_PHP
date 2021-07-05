<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'shop');
  sessionStart();

  $title = '未ログイン';
  include(D_ROOT.'component/header_shop.php');
?>

<h1>ログインされていません</h1>

<ul class="back-nav">
  <li>
    <a href="login.php">ログイン</a>
  </li>
  <li>
    <a href="signup.php">会員登録</a>
  </li>
  <li>
    <a href="product.php">商品一覧へ</a>
  </li>
  <li>
    <a href="top.php">トップページへ</a>
  </li>
</ul>

<?php include(D_ROOT.'component/footer_shop.php') ?>