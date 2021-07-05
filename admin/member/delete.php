<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'admin');
  reqLogin();

  require_once(D_ROOT.'database/MemberDao.php');

  $title = '会員削除';
  include(D_ROOT.'component/header_admin.php');
?>
<?php
  reqGet('id');
  $id = inputGet('id');

  try {
    $dao = new MemberDao();
    $member = $dao->findById($id);
    blockModelEmpty($member);
    $_SESSION['del_member'] = serialize($member);
?>

<h1>会員削除</h1>

<form method="post" action="delete_do.php">
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

  <p class="confirm-text">削除しますか？</p>

  <div class="btns">
    <button class="btn btn-secondory" type="button" onclick="history.back()">戻る</button>
    <input class="btn btn-primary" type="submit" value="登録を解除">
  </div>
</form>

<?php
  } catch (PDOException $e) {
    dbError();
  }
?>

<?php include(D_ROOT.'component/footer_admin.php') ?>