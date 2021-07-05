<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'shop');
  blockLogin();

  require_once(D_ROOT.'database/MemberDao.php');
?>
<?php
  reqSession('new_user');
  $new_user = unserialize($_SESSION['new_user']);
  unset($_SESSION['new_user']);

  try {
    $dao = new MemberDao();
    $dao->create($new_user);

    $_SESSION['msg'] = '登録しました';

    header('Location: done.php');
    exit();

  } catch (PDOException $e) {
    dbError();
  }
?>