<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'admin');
  blockLogin();

  require_once(D_ROOT.'database/StaffDao.php');

  // ログインチェック
  try {
    reqPost();
    $post_id = inputPost('id');
    $post_password = inputPost('password');

    $dao = new StaffDao();
    $staff = $dao->loginCheck($post_id, md5($post_password));

    if ($staff->getId()) {
      $_SESSION['staff_login'] = 1;
      $_SESSION['staff_id'] = $staff->getId();
      $_SESSION['staff_name'] = $staff->getName();
      header('Location: top.php');
      exit();
    }
  } catch (PDOException $e) {
    dbError();
  }

  $title = 'ログインチェック';
  include(D_ROOT.'component/header_admin.php');
?>

<h1>スタッフIDまたはパスワードが違います</h1>

<ul class="common-nav">
  <li>
    <a href="login.php">ログイン画面へ戻る</a>
  </li>
</ul>

<?php include(D_ROOT.'component/footer_admin.php') ?>