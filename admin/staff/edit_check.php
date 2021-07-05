<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'admin');
  reqLogin();

  require_once(D_ROOT.'database/Staff.php');

  $title = 'スタッフ修正確認';
  include(D_ROOT.'component/header_admin.php');
?>
<?php
  reqSession('staff');
  $staff = unserialize($_SESSION['staff']);
  unset($_SESSION['staff']);

  reqPost();
  $post_name = inputPost('name');
  $post_password = inputPost('password');
  $post_newpassword = inputPost('new_password');
  $post_newpassword2 = inputPost('new_password2');

  // エラーチェック
  $error = array();
  $submit_check = true;

  // スタッフ名
  if ($post_name === '') {
    $error['post_name'] = '<span class="danger">スタッフ名が入力されていません</span>';
    $submit_check = false;
  } elseif (mb_strlen($post_name) > 15) {
    $error['post_name'] = '<br><span class="danger">スタッフ名が長すぎます</span>';
    $submit_check = false;
  }
  // パスワード
  if (md5($post_password) != $staff->getPassword()) {
    $error['post_password'] = '<span class="danger">パスワードが違います</span>';
    $submit_check = false;
  }
  // 新しいパスワード
  if ($post_newpassword != $post_newpassword2) {
    $error['post_newpassword'] = '<span class="danger">パスワードが一致しません</span>';
    $submit_check = false;
  }
?>

<h1>スタッフ修正確認</h1>

<form method="post" action="edit_do.php">
  <table class="staff-table">
    <tr>
      <th>スタッフID</th>
      <td><?php echo $staff->getId() ?></td>
    </tr>
    <tr>
      <th>スタッフ名</th>
      <td>
        <?php echo $post_name ?>
        <?php if(isset($error['post_name'])) echo $error['post_name']; ?>
      </td>
    </tr>
    <tr>
      <th>現在のパスワード</th>
      <td>
        <?php if(!isset($error['post_password'])) echo '**********'; ?>
        <?php if(isset($error['post_password'])) echo $error['post_password']; ?>
      </td>
    </tr>
    <tr>
      <th>新しいパスワード</th>
      <td>
        <?php if(!isset($error['post_newpassword']) && $post_newpassword !== '') echo '**********'; ?>
        <?php if(isset($error['post_newpassword'])) echo $error['post_newpassword']; ?>
      </td>
    </tr>
  </table>

  <p class="confirm-text">修正しますか？</p>

  <div class="btns">
    <button class="btn btn-secondory" type="button" onclick="history.back()">戻る</button>
    <!-- submit可能時 -->
    <?php if($submit_check): ?>
      <input class="btn btn-primary" type="submit" value="確定">
      <?php
        // 新しいパスワードが入力されている場合は変更
        if ($post_newpassword !== '') {
          $post_password = $post_newpassword;
        }
        $post_password = md5($post_password);

        $up_staff = new Staff($staff->getId(), $post_name, $post_password, $staff->getCreatedAt());
        $_SESSION['up_staff'] = serialize($up_staff);
      ?>
    <?php endif ?>
  </div>
</form>

<?php include(D_ROOT.'component/footer_admin.php') ?>