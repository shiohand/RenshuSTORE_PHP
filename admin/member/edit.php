<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'admin');
  reqLogin();

  require_once(D_ROOT.'database/MemberDao.php');

  $title = '会員修正';
  include(D_ROOT.'component/header_admin.php');
?>
<?php
  reqGet('id');
  $id = inputGet('id');

  try {
    $dao = new MemberDao();
    $member = $dao->findById($id);
    blockModelEmpty($member);
    $_SESSION['member'] = serialize($member);
?>

<h1>会員修正</h1>

<form method="post" action="edit_check.php">
  <table class="member-table">
    <tr>
      <th>会員ID</th>
      <td><?php echo $id ?></td>
    </tr>
    <tr>
      <th><label for="email">メールアドレス</label></th>
      <td><input id="email" type="email" name="email" maxlength="50" value="<?php echo $member->getEmail() ?>"><span class="announce"><br>※最大50文字</span></td>
    </tr>
    <tr>
      <th><label for="password">現在のパスワード</label></th>
      <td><input id="password" type="password" name="password"></td>
    </tr>
    <tr>
      <th><label for="new_password">新しいパスワード</label></th>
      <td><input id="new_password" type="password" name="new_password"></td>
    </tr>
    <tr>
      <th><label for="new_password2">新しいパスワード再入力</label></th>
      <td><input id="new_password2" type="password" name="new_password2"></td>
    </tr>
    <tr>
      <th><label for="name">名前</label></th>
      <td><input id="name" type="text" name="name" maxlength="15" value="<?php echo $member->getName() ?>"><span class="announce"><br>※最大15文字</span></td>
    </tr>
    <tr>
      <th><label for="postal1">郵便番号</label></th>
      <td>
        <input id="postal1" type="text" name="postal1" maxlength="3" value="<?php echo $member->getPostal1() ?>">-<input id="postal2" type="text" name="postal2" maxlength="4" value="<?php echo $member->getPostal2() ?>">
      </td>
    </tr>
    <tr>
      <th><label for="address">住所</label></th>
      <td><input id="address" type="text" name="address" maxlength="50" value="<?php echo $member->getAddress() ?>"><span class="announce"><br>※最大50文字</span></td>
    </tr>
    <tr>
      <th><label for="tel">電話番号</label></th>
      <td><input id="tel" type="text" name="tel" maxlength="15" value="<?php echo $member->getTel() ?>"><span class="announce"><br>※半角数字・ハイフン区切り</span></td>
    </tr>
    <tr>
      <th><label for="male">性別</label></th>
      <td>
        <div class="radio-wrap clearfix">
          <label class="radio-label"><input type="radio" name="gender" value="1"<?php if ($member->getGender() === '1') echo ' checked' ?>>男性</label>
          <label class="radio-label"><input type="radio" name="gender" value="2"<?php if ($member->getGender() === '2') echo ' checked' ?>>女性</label>
        </div>
      </td>
    </tr>
    <tr>
      <th>年代</th>
      <td>
        <div class="select-wrap">
          <select name="birth">
            <?php outputBirthOptions($member->getBirth()) ?>
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

<?php
  } catch (PDOException $e) {
    dbError();
  }
?>

<?php include(D_ROOT.'component/footer_admin.php') ?>