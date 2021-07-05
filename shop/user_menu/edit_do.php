<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'shop');
  reqLogin();

  require_once(D_ROOT.'database/MemberDao.php');
?>
<?php
  reqSession('up_user');
  $up_user = unserialize($_SESSION['up_user']);
  unset($_SESSION['up_user']);

  try {
    $dao = new MemberDao();
    $dao->update($up_user);

    $_SESSION['msg'] = '登録情報を修正しました';

    header('Location: done.php');
    exit();

  } catch (PDOException $e) {
    dbError();
  }
?>