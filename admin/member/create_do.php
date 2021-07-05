<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'admin');
  reqLogin();

  require_once(D_ROOT.'database/MemberDao.php');
?>
<?php
  reqSession('new_member');
  $new_member = unserialize($_SESSION['new_member']);
  unset($_SESSION['new_member']);

  try {
    $dao = new MemberDao();
    $dao->create($new_member);

    $_SESSION['msg'] = '会員メールアドレス:'.$new_member->getEmail().' を追加しました';

    header('Location: done.php');
    exit();

  } catch (PDOException $e) {
    dbError();
  }
?>