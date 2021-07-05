<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'admin');
  reqLogin();

  require_once(D_ROOT.'database/Staff.php');

  $title = 'スタッフ追加確認';
  include(D_ROOT.'component/header_admin.php');
?>
<?php
  reqPost();
  $post_name = inputPost('name');
  $post_password = inputPost('password');
  $post_password2 = inputPost('password2');

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
  if ($post_password === '') {
    $error['post_password'] = '<span class="danger">パスワードが入力されていません</span>';
    $submit_check = false;
  } elseif ($post_password != $post_password2) {
    $error['post_password'] = '<span class="danger">パスワードが一致しません</span>';
    $submit_check = false;
  }
?>

<h1>スタッフ追加確認</h1>

<form method="post" action="create_do.php">
  <table class="staff-table">
    <tr>
      <th>スタッフ名</th>
      <td>
        <?php echo $post_name ?>
        <?php if(isset($error['post_name'])) echo $error['post_name']; ?>
      </td>
    </tr>
    <tr>
      <th>パスワード</th>
      <td>
        <?php if(!isset($error['post_password'])) echo '**********'; ?>
        <?php if(isset($error['post_password'])) echo $error['post_password']; ?>
      </td>
    </tr>
  </table>

  <p class="confirm-text">追加しますか？</p>

  <div class="btns">
    <button class="btn btn-secondory" type="button" onclick="history.back()">戻る</button>
    <!-- submit可能時 -->
    <?php if($submit_check): ?>
      <input class="btn btn-primary" type="submit" value="追加">
      <?php
        $post_password = md5($post_password);

        $new_staff = new Staff(null, $post_name, $post_password, null);
        $_SESSION['new_staff'] = serialize($new_staff);
      ?>
    <?php endif ?>
  </div>
</form>

<?php include(D_ROOT.'component/footer_admin.php') ?>