<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'admin');
  reqLogin();

  require_once(D_ROOT.'database/StaffDao.php');
?>
<?php
  reqSession('del_staff');
  $del_staff = unserialize($_SESSION['del_staff']);
  unset($_SESSION['del_staff']);

  try {
    $dao = new StaffDao();
    $dao->delete($del_staff);

    $_SESSION['msg'] = 'スタッフ名:'.$del_staff->getName().' (ID:'.$del_staff->getId().') のデータを削除しました';

    header('Location: done.php');
    exit();

  } catch (PDOException $e) {
    dbError();
  }
?>