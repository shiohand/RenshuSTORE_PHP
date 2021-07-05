<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'shop');
  blockLogin();

  require_once(D_ROOT.'database/MemberDao.php');

  $title = 'ログイン';
  include(D_ROOT.'component/header_shop.php');
?>

<h1>会員ログイン</h1>

<form class="login-form" method="post" action="login_check.php">
  <div class="login-form-item">
    <label for="email">メールアドレス</label>
    <input id="email" type="email" name="email">
  </div>
  <div class="login-form-item">
    <label for="password">パスワード</label>
    <input id="password" type="password" name="password">
  </div>
  <div class="login-form-item">
    <input class="btn btn-primary" type="submit" value="ログイン">
  </div>
</form>

<?php include(D_ROOT.'component/footer_shop.php') ?>