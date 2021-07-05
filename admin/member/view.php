<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'admin');
  reqLogin();

  require_once(D_ROOT.'database/MemberDao.php');

  $title = '会員参照';
  include(D_ROOT.'component/header_admin.php');
?>
<?php
  reqGet('id');
  $id = inputGet('id');

  try {
    $dao = new MemberDao();
    $member = $dao->findById($id);
    blockModelEmpty($member);
    $param = http_build_query(['id' => $member->getId()]);
?>

<h1>会員参照</h1>

<table class="member-table">
  <tr>
    <th>会員ID</th>
    <td><?php echo $member->getId() ?></td>
  </tr>
  <tr>
    <th>メールアドレス</th>
    <td><?php echo $member->getEmail() ?></td>
  </tr>
  <tr>
    <th>お名前</th>
    <td><?php echo $member->getName() ?></td>
  </tr>
  <tr>
    <th>郵便番号</th>
    <td><?php echo $member->getPostal1() ?>-<?php echo $member->getPostal2() ?></td>
  </tr>
  <tr>
    <th>住所</th>
    <td><?php echo $member->getAddress() ?></td>
  </tr>
  <tr>
    <th>電話番号</th>
    <td><?php echo $member->getTel() ?></td>
  </tr>
  <tr>
    <th>性別</th>
    <td>
      <?php if ($member->getGender() === '1'): ?>
        男性
      <?php elseif ($member->getGender() === '2'): ?>
        女性
      <?php endif ?>
    </td>
  </tr>
  <tr>
    <th>年代</th>
    <td><?php echo $member->getBirth() ?>年代</td>
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