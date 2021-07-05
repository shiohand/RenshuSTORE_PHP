<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'shop');
  reqLogin();

  require_once(D_ROOT.'database/MemberDao.php');

  $title = '会員情報';
  include(D_ROOT.'component/header_shop.php');
?>
<?php
  $member_id = $_SESSION['member_id'];

  try {
    $dao = new MemberDao();
    $member = $dao->findById($member_id);
    blockModelEmpty($member);
?>

<h1>会員情報</h1>

<nav class="user-links">
  <ul>
    <li>
      <a href="<?php echo S_NAME ?>shop/user_menu/ordered.php">注文履歴</a>
    </li>
    <li>
      <a href="<?php echo S_NAME ?>shop/user_menu/reviewed.php">レビュー履歴</a>
    </li>
  </ul>
</nav>

<div class="common-table">
  <table>
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

  <div class="btns">
    <a href="edit.php">修正</a>
    <a href="delete.php">削除</a>
  </div>
</div>

<?php
  } catch (PDOException $e) {
    dbError();
  }
?>

<?php include(D_ROOT.'component/footer_shop.php') ?>