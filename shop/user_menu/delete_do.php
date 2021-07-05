<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'shop');
  reqLogin();

  require_once(D_ROOT.'database/MemberDao.php');
?>
<?php
  reqSession('del_user');
  $del_user = unserialize($_SESSION['del_user']);
  unset($_SESSION['del_user']);

  try {
    $dao = new MemberDao();
    $dao->delete($del_user);

    // logout処理
    $_SESSION = array(); // 空の配列を代入
    if (isset($_COOKIE[session_name()]) == true) {
      setcookie(session_name(), '', time()-42000, '/');
    }
    session_destroy();

    $_SESSION['msg'] = '登録解除しました';

    header('Location: ../login.php');
    exit();

  } catch (PDOException $e) {
    dbError();
  }
?>