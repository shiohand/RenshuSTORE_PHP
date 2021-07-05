<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'admin');
  reqLogin();

  require_once(D_ROOT.'database/MemberDao.php');

  $title = '会員一覧';
  include(D_ROOT.'component/header_admin.php');
?>
<?php
  try {
    $dao = new MemberDao();
    $members = $dao->findAll();
?>

<h1>会員一覧</h1>

<div class="link">
  <a href="create.php">会員追加</a>
</div>

<table class="data-table">
  <tr>
    <th>会員ID</th>
    <th>メールアドレス</th>
    <th>お名前</th>
    <th>性別</th>
    <th>年代</th>
    <th></th>
  </tr>
  <?php foreach($members as $member): ?>
    <?php $param = http_build_query(['id' => $member->getId()]) ?>
    <tr>
      <td class="tar"><?php echo $member->getId() ?></td>
      <td><a href="view.php?<?php echo $param ?>"><?php echo $member->getEmail() ?></a></td>
      <td><?php echo $member->getName() ?></td>
      <td>
        <?php if ($member->getGender() === '1'): ?>
          男性
        <?php elseif ($member->getGender() === '2'): ?>
          女性
        <?php endif ?>
      </td>
      <td class="tar"><?php echo $member->getBirth() ?></td>
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