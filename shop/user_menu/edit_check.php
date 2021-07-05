<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'shop');
  reqLogin();

  require_once(D_ROOT.'database/MemberDao.php');

  $title = '会員修正確認';
  include(D_ROOT.'component/header_shop.php');
?>
<?php
  reqSession('user');
  $user = unserialize($_SESSION['user']);
  unset($_SESSION['user']);

  reqPost();
  $post_email = inputPost('email');
  $post_password = inputPost('password');
  $post_newpassword = inputPost('new_password');
  $post_newpassword2 = inputPost('new_password2');
  $post_name = inputPost('name');
  $post_postal1 = inputPost('postal1');
  $post_postal2 = inputPost('postal2');
  $post_address = inputPost('address');
  $post_tel = inputPost('tel');
  $post_gender = inputPost('gender');
  $post_birth = inputPost('birth');

  // エラーチェック
  $error = array();
  $submit_check = true;

  // メールアドレス
  if ($post_email === '') {
    $error['post_email'] = '<span class="danger">メールアドレスが入力されていません</span>';
    $submit_check = false;
  } elseif (mb_strlen($post_email) > 50) {
    $error['post_email'] = '<span class="danger">メールアドレスが長すぎます</span>';
    $submit_check = false;
  } elseif (preg_match('/\A[\w\-\.]+\@[\w\-\.]+\.([a-z]+)\z/', $post_email) === 0) {
    $error['post_email'] = '<br><span class="danger">メールアドレスを正確に入力してください</span>';
    $submit_check = false;
  } else {
    try {
      $dao = new MemberDao();
      if ($post_email !== $user->getEmail() && $dao->isAlreadyExists($post_email)) {
        $error['post_email'] = '<br><span class="danger">このメールアドレスは既に登録されています</span>';
        $submit_check = false;
      }
    } catch (PDOException $e) {
      dbError();
    }
  }
  // パスワード
  if (md5($post_password) != $user->getPassword()) {
    $error['post_password'] = '<span class="danger">パスワードが違います</span>';
    $submit_check = false;
  }
  // 新しいパスワード
  if ($post_newpassword != $post_newpassword2) {
    $error['post_newpassword'] = '<span class="danger">パスワードが一致しません</span>';
    $submit_check = false;
  }
  // お名前
  if ($post_name === '') {
    $error['post_name'] = '<span class="danger">お名前が入力されていません</span>';
    $submit_check = false;
  } elseif (mb_strlen($post_name) > 15) {
    $error['post_name'] = '<br><span class="danger">お名前が長すぎます</span>';
    $submit_check = false;
  }
  // 郵便番号
  if ($post_postal1 === '') {
    $error['post_postal'] = '<span class="danger">郵便番号が入力されていません</span>';
    $submit_check = false;
  } elseif (preg_match('/\A[0-9]{3}\z/', $post_postal1) === 0 || preg_match('/\A[0-9]{4}\z/', $post_postal2) === 0) {
    $error['post_postal'] = '<br><span class="danger">郵便番号を正しく入力してください</span>';
    $submit_check = false;
  }
  // 住所
  if ($post_address === '') {
    $error['post_address'] = '<span class="danger">住所が入力されていません</span>';
    $submit_check = false;
  } elseif (mb_strlen($post_address) > 50) {
    $error['post_address'] = '<br><span class="danger">住所が長すぎます</span>';
    $submit_check = false;
  }
  // 電話番号
  if ($post_tel === '') {
    $error['post_tel'] = '<span class="danger">電話番号が入力されていません</span>';
    $submit_check = false;
  } elseif (preg_match('/\A0[0-9]{1,4}-[0-9]{1,6}(-[0-9]{0,5})?\z/', $post_tel) === 0) {
    $error['post_tel'] = '<br><span class="danger">電話番号をハイフン区切りで正しく入力してください</span>';
    $submit_check = false;
  } elseif (strlen(str_replace('-', '', $post_tel)) !== 10 &&
  strlen(str_replace('-', '', $post_tel)) !== 11) {
    $error['post_tel'] = '<br><span class="danger">電話番号を正しく入力してください</span>';
    $submit_check = false;
  }
  // 性別
  if ($post_gender !== '1' && $post_gender !== '2') {
    commonError();
  }
  // 年代
  if ($post_birth % 10 !== 0 || $post_birth < 1910 || round(intval(date('Y')), -1) < $post_birth) {
    commonError();
  }
?>

<h1>会員修正確認</h1>

<form class="form common-table" method="post" action="edit_do.php">
  <table>
    <tr>
      <th>メールアドレス</th>
      <td>
        <?php echo $post_email ?>
        <?php if (isset($error['post_email'])) echo $error['post_email'] ?>
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
    <tr>
      <th>お名前</th>
      <td>
        <?php echo $post_name ?>
        <?php if (isset($error['post_name'])) echo $error['post_name'] ?>
      </td>
    </tr>
    <tr>
      <th>郵便番号</th>
      <td>
        <?php echo $post_postal1 ?><?php if ($post_postal1 !== '' && $post_postal2 !== '') echo '-' ?><?php echo $post_postal2 ?>
        <?php if (isset($error['post_postal'])) echo $error['post_postal'] ?>
      </td>
    </tr>
    <tr>
      <th>住所</th>
      <td>
        <?php echo $post_address ?>
        <?php if (isset($error['post_address'])) echo $error['post_address'] ?>
      </td>
    </tr>
    <tr>
      <th>電話番号</th>
      <td>
        <?php echo $post_tel ?>
        <?php if (isset($error['post_tel'])) echo $error['post_tel'] ?>
      </td>
    </tr>
    <tr>
      <th>性別</th>
      <td>
        <?php if ($post_gender === '1'): ?>
          男性
        <?php elseif ($post_gender === '2'): ?>
          女性
        <?php endif ?>
      </td>
    </tr>
    <tr>
      <th>年代</th>
      <td>
        <?php echo $post_birth ?>年代
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

        $up_user = new Member($user->getId(), $post_email, $post_password, $post_name, $post_postal1, $post_postal2, $post_address, $post_tel, $post_gender, $post_birth, $user->getCreatedAt());
        $_SESSION['up_user'] = serialize($up_user);
      ?>
    <?php endif ?>
  </div>
</form>

<?php include(D_ROOT.'component/footer_shop.php') ?>