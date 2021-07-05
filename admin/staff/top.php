<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'admin');
  reqLogin();

  require_once(D_ROOT.'database/StaffDao.php');

  $title = 'スタッフ一覧';
  include(D_ROOT.'component/header_admin.php');
?>
<?php
  try {
    $dao = new StaffDao();
    $staffs = $dao->findAll();
?>

<h1>スタッフ一覧</h1>

<div class="link">
  <a href="create.php">スタッフ追加</a>
</div>

<table class="data-table">
  <tr>
    <th>スタッフID</th>
    <th>スタッフ名</th>
    <th></th>
  </tr>
  <?php foreach($staffs as $staff): ?>
    <?php $param = http_build_query(['id' => $staff->getId()]) ?>
    <tr>
      <td class="tar"><?php echo $staff->getId() ?></td>
      <td><a href="view.php?<?php echo $param ?>"><?php echo $staff->getName() ?></a></td>
      <td>
        <a href="edit.php?<?php echo $param ?>">修正</a>
        <a href="delete.php?<?php echo $param ?>">削除</a>
      </td>
    </tr>
  <?php endforeach ?>
</table>

<?php
  } catch (PDOException $e) {
    dbError();
  }
?>

<?php include(D_ROOT.'component/footer_admin.php') ?>