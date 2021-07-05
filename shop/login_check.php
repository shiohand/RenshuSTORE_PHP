<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'shop');
  blockLogin();

  require_once(D_ROOT.'database/MemberDao.php');

  try {
    reqPost();
    $post_email = inputPost('email');
    $post_password = inputPost('password');

    $dao = new MemberDao();
    $member = $dao->loginCheck($post_email, md5($post_password));

    if ($member->getId()) {
      $_SESSION['member_login'] = 1;
      $_SESSION['member_id'] = $member->getId();
      $_SESSION['member_name'] = $member->getName();
      header('Location: top.php');
      exit();
    }
  } catch (PDOException $e) {
    dbError();
  }

  $title = 'ログインチェック';
  include(D_ROOT.'component/header_shop.php');
?>

<h1>メールアドレスまたはパスワードが違います</h1>

<ul class="back-nav">
  <li>
    <a href="login.php">ログイン画面へ戻る</a>
  </li>
</ul>

<?php include(D_ROOT.'component/footer_shop.php') ?>