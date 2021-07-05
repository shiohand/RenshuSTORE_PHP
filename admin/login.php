<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'admin');
  blockLogin();

  $title = 'ログイン';
  include(D_ROOT.'component/header_admin.php');
?>
<div class="login"></div>

<h1>スタッフログイン</h1>

<form class="form login-form" method="post" action="login_check.php">
  <table>
    <tr>
      <th><label for="id">スタッフID</label></th>
      <td><input id="id" type="text" name="id"></td>
    </tr>
    <tr>
      <th><label for="password">パスワード</label></th>
      <td><input id="password" type="password" name="password"></td>
    </tr>
  </table>
  <div class="btns">
    <input class="btn btn-primary" type="submit" value="ログイン">
  </div>
</form>

<?php include(D_ROOT.'component/footer_admin.php') ?>