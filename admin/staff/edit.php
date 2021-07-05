<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'admin');
  reqLogin();

  require_once(D_ROOT.'database/StaffDao.php');

  $title = 'スタッフ修正';
  include(D_ROOT.'component/header_admin.php');
?>
<?php
  reqGet('id');
  $id = inputGet('id');

  try {
    $dao = new StaffDao();
    $staff = $dao->findById($id);
    blockModelEmpty($staff);
    $_SESSION['staff'] = serialize($staff);
?>

<h1>スタッフ修正</h1>

<form method="post" action="edit_check.php">
  <table class="staff-table">
    <tr>
      <th>スタッフID</th>
      <td><?php echo $id ?></td>
    </tr>
    <tr>
      <th><label for="name">スタッフ名</label></th>
      <td><input id="name" type="text" name="name" maxlength="15" value="<?php echo $staff->getName() ?>"><span class="announce"><br>※最大15文字</span></td>
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