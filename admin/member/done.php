<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'admin');
  reqLogin();

  $title = '完了';
  include(D_ROOT.'component/header_admin.php');
?>
<?php
  reqSession('msg');
  $msg = $_SESSION['msg'];
  unset($_SESSION['msg']);
?>

<h1>完了</h1>

<p class="confirm-text"><?php echo $msg ?></p>

<ul class="common-nav">
  <li>
    <a href="top.php">会員一覧へ</a>
  </li>
</ul>

<?php include(D_ROOT.'component/footer_admin.php') ?>