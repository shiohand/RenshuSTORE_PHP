<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'shop');
  reqLogin();

  require_once(D_ROOT.'database/MemberDao.php');

  $title = '登録解除';
  include(D_ROOT.'component/header_shop.php');
?>
<?php
  $id = $_SESSION['member_id'];

  try {
    $dao = new MemberDao();
    $user = $dao->findById($id);
    blockModelEmpty($user);
    $_SESSION['del_user'] = serialize($user);
?>

<h1>登録解除</h1>

<form class="form common-table" method="post" action="delete_do.php">
  <table>
    <tr>
      <th>メールアドレス</th>
      <td><?php echo $user->getEmail() ?></td>
    </tr>
    <tr>
      <th>お名前</th>
      <td><?php echo $user->getName() ?></td>
    </tr>
    <tr>
      <th>郵便番号</th>
      <td><?php echo $user->getPostal1() ?>-<?php echo $user->getPostal2() ?></td>
    </tr>
    <tr>
      <th>住所</th>
      <td><?php echo $user->getAddress() ?></td>
    </tr>
    <tr>
      <th>電話番号</th>
      <td><?php echo $user->getTel() ?></td>
    </tr>
    <tr>
      <th>性別</th>
      <td>
        <?php if ($user->getGender() === '1'): ?>
          男性
        <?php elseif ($user->getGender() === '2'): ?>
          女性
        <?php endif ?>
      </td>
    </tr>
    <tr>
      <th>年代</th>
      <td><?php echo $user->getBirth() ?>年代</td>
    </tr>
  </table>

  <p class="confirm-text">登録を解除してデータを削除します。よろしいですか？</p>

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

<?php include(D_ROOT.'component/footer_shop.php') ?>