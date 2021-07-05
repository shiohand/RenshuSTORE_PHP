<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'admin');
  reqLogin();

  require_once(D_ROOT.'database/StaffDao.php');

  $title = 'スタッフ削除';
  include(D_ROOT.'component/header_admin.php');
?>
<?php
  reqGet('id');
  $id = inputGet('id');

  try {
    $dao = new StaffDao();
    $staff = $dao->findById($id);
    blockModelEmpty($staff);
    $_SESSION['del_staff'] = serialize($staff);
?>

<h1>スタッフ削除</h1>

<form method="post" action="delete_do.php">
  <table class="staff-table">
    <tr>
      <th>スタッフID</th>
      <td><?php echo $staff->getId() ?></td>
    </tr>
    <tr>
      <th>スタッフ名</th>
      <td>
        <?php echo $staff->getName() ?>
      </td>
    </tr>
  </table>

  <?php if ($staff->getId() == $_SESSION['staff_id']): ?>
    <p class="confirm-text">ログイン中のIDです</p>
    <div class="btns">
      <button class="btn btn-secondory" type="button" onclick="history.back()">戻る</button>
    </div>
    <?php unset($_SESSION['del_staff']) ?>
  <?php else: ?>

    <p class="confirm-text">削除しますか？</p>

    <div class="btns">
      <button class="btn btn-secondory" type="button" onclick="history.back()">戻る</button>
      <input class="btn btn-primary" type="submit" value="削除を解除">
    </div>
  <?php endif ?>
</form>

<?php
  } catch (PDOException $e) {
    dbError();
  }
?>

<?php include(D_ROOT.'component/footer_admin.php') ?>