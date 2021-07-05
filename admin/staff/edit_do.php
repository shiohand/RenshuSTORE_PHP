<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'admin');
  reqLogin();

  require_once(D_ROOT.'database/StaffDao.php');
?>
<?php
  reqSession('up_staff');
  $up_staff = unserialize($_SESSION['up_staff']);
  unset($_SESSION['up_staff']);

  try {
    $dao = new StaffDao();
    $dao->update($up_staff);

    $_SESSION['msg'] = 'スタッフ名:'.$up_staff->getName().' (ID:'.$up_staff->getId().') のデータを修正しました';

    header('Location: done.php');
    exit();

  } catch (PDOException $e) {
    dbError();
  }
?>