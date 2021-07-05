<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'admin');
  reqLogin();

  require_once(D_ROOT.'database/StaffDao.php');
?>
<?php
  reqSession('new_staff');
  $new_staff = unserialize($_SESSION['new_staff']);
  unset($_SESSION['new_staff']);

  try {
    $dao = new StaffDao();
    $dao->create($new_staff);

    $_SESSION['msg'] = 'スタッフ名:'.$new_staff->getName().' を追加しました';

    header('Location: done.php');
    exit();

  } catch (PDOException $e) {
    dbError();
  }
?>