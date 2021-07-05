<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'admin');
  reqLogin();

  $title = '会員追加';
  include(D_ROOT.'component/header_admin.php');
?>

<h1>会員追加</h1>

<form method="post" action="create_check.php">
  <table class="member-table">
    <tr>
      <th><label for="email">メールアドレス</label></th>
      <td><input id="email" type="email" name="email" maxlength="50"><span class="announce"><br>※最大50文字</span></td>
    </tr>
    <tr>
      <th><label for="password">パスワード</label></th>
      <td><input id="password" type="password" name="password"></td>
    </tr>
    <tr>
      <th><label for="password2">パスワード再入力</label></th>
      <td><input id="password2" type="password" name="password2"></td>
    </tr>
    <tr>
      <th><label for="name">お名前</label></th>
      <td><input id="name" type="text" name="name" maxlength="15"><span class="announce"><br>※最大15文字</span></td>
    </tr>
    <tr>
      <th><label for="postal1">郵便番号</label></th>
      <td><input id="postal1" type="text" name="postal1" maxlength="3">-<input id="postal2" type="text" name="postal2" maxlength="4"></td>
    </tr>
    <tr>
      <th><label for="address">住所</label></th>
      <td><input id="address" type="text" name="address" maxlength="50"><span class="announce"><br>※最大50文字</span></td>
    </tr>
    <tr>
      <th><label for="tel">電話番号</label></th>
      <td><input id="tel" type="text" name="tel" maxlength="15"><span class="announce"><br>※半角数字・ハイフン区切り</span></td>
    </tr>
    <tr>
      <th><label for="male">性別</label></th>
      <td>
        <div class="radio-wrap clearfix">
          <label class="radio-label"><input type="radio" name="gender" value="1" checked>男性</label>
          <label class="radio-label"><input type="radio" name="gender" value="2">女性</label>
        </div>
      </td>
    </tr>
    <tr>
      <th>年代</th>
      <td>
        <div class="select-wrap">
          <select name="birth">
            <?php outputBirthOptions() ?>
          </select>
        </div>
      </td>
    </tr>
  </table>

  <div class="btns">
    <button class="btn btn-secondory" type="button" onclick="history.back()">戻る</button>
    <input class="btn btn-primary" type="submit" value="確認">
  </div>
</form>

<?php include(D_ROOT.'component/footer_admin.php') ?>