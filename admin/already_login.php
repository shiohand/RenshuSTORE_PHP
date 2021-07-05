<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'admin');
  reqLogin();

  $title = 'ログイン済';
  include(D_ROOT.'component/header_admin.php');
?>

<h1>ログイン済です</h1>

<ul class="common-nav">
  <li>
    <a href="staff/top.php">スタッフ管理</a>
  </li>
  <li>
    <a href="product/top.php">商品管理</a>
  </li>
  <li>
    <a href="member/top.php">会員管理</a>
  </li>
  <li>
    <a href="order/top.php">注文管理</a>
  </li>
</ul>

<?php include(D_ROOT.'component/footer_admin.php') ?>