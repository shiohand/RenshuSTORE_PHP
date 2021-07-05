<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'admin');
  reqLogin();

  require_once(D_ROOT.'database/MemberDao.php');
?>
<?php
  reqSession('up_member');
  $up_member = unserialize($_SESSION['up_member']);
  unset($_SESSION['up_member']);

  try {
    $dao = new MemberDao();
    $dao->update($up_member);

    $_SESSION['msg'] = '会員メールアドレス:'.$up_member->getEmail().' (ID:'.$up_member->getId().') のデータを修正しました';

    header('Location: done.php');
    exit();

  } catch (PDOException $e) {
    dbError();
  }
?>