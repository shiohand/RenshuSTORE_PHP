<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'admin');
  reqLogin();

  require_once(D_ROOT.'database/StaffDao.php');

  $title = 'スタッフ参照';
  include(D_ROOT.'component/header_admin.php');
?>
<?php
  reqGet('id');
  $id = inputGet('id');

  try {
    $dao = new StaffDao();
    $staff = $dao->findById($id);
    blockModelEmpty($staff);
    $param = http_build_query(['id' => $staff->getId()]);
?>

<h1>スタッフ参照</h1>

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

<div class="link">
  <a href="edit.php?<?php echo $param ?>">修正</a>
  <a href="delete.php?<?php echo $param ?>">削除</a>
</div>

<div class="btns">
  <button class="btn btn-secondory" type="button" onclick="history.back()">戻る</button>
</div>

<?php
  } catch (PDOException $e) {
    dbError();
  }
?>

<?php include(D_ROOT.'component/footer_admin.php') ?>