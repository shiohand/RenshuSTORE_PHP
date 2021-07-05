<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'admin');
  reqLogin();

  require_once(D_ROOT.'database/MemberDao.php');
?>
<?php
  reqSession('del_member');
  $del_member = unserialize($_SESSION['del_member']);
  unset($_SESSION['del_member']);

  try {
    $dao = new MemberDao();
    $dao->delete($del_member);

    $_SESSION['msg'] = '会員メールアドレス:'.$del_member->getEmail().' (ID:'.$del_member->getId().') のデータを削除しました';

    header('Location: done.php');
    exit();

  } catch (PDOException $e) {
    dbError();
  }
?>