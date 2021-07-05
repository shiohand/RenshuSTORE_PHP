<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'admin');
  reqLogin();

  require_once(D_ROOT.'database/ProductDao.php');

  $title = '商品修正確認';
  include(D_ROOT.'component/header_admin.php');
?>
<?php
  reqSession('product');
  $product = unserialize($_SESSION['product']);
  unset($_SESSION['product']);

  reqPost();
  $post_name = inputPost('name');
  $post_price = inputPost('price');
  $post_release_date = inputPost('release_date');
  $post_content = inputPost('content');
  $post_pict = $_FILES['pict'];
  // 画像変更判定用
  $before_pict = $product->getPict();

  // エラーチェック
  $error = array();
  $submit_check = true;

  // 商品名
  if ($post_name === '') {
    $error['post_name'] = '<span class="danger">商品名が入力されていません</span>';
    $submit_check = false;
  } elseif (mb_strlen($post_name) > 30) {
    $error['post_name'] = '<br><span class="danger">商品名が長すぎます</span>';
    $submit_check = false;
  }
  // 価格
  if ($post_price === '') {
    $error['post_price'] = '<span class="danger">価格が入力されていません</span>';
    $submit_check = false;
  } elseif (preg_match('/\A[0-9]+\z/', $post_price) === 0) {
    $error['post_price'] = '<br><span class="danger">価格を正しく入力してください</span>';
    $submit_check = false;
  }
  // 発売日
  if ($post_release_date === '') {
    $error['post_release_date'] = '<span class="danger">発売日が入力されていません</span>';
    $submit_check = false;
  } elseif (preg_match('/\A[0-9]{4}(-[0-9]{2}){2}\z/', $post_release_date) === 0) {
    $error['post_release_date'] = '<br><span class="danger">発売日が入力されていません</span>';
    $submit_check = false;
  } else {
    list($y, $m, $d) = explode('-', $post_release_date);
    if (!checkdate($m, $d, $y)) {
      $error['post_release_date'] = '<br><span class="danger">発売日を正しく入力してください</span>';
      $submit_check = false;
    }
  }
  // 紹介文
  if ($post_content === '') {
    $error['post_content'] = '<span class="danger">紹介文が入力されていません</span>';
    $submit_check = false;
  } elseif (mb_strlen(html_entity_decode($post_content)) > 1000) {
    $error['post_content'] = '<br><span class="danger">紹介文は1000文字以内で入力してください</span>';
    $submit_check = false;
  }
  // 画像 なければ現在のファイル名
  $pict_name = $before_pict;
  if ($post_pict['error'] === 2 || $post_pict['size'] > 1048576) {
    $error['post_pict'] = '<span class="danger">画像が大き過ぎます（上限:1MB）</span>';
    $submit_check = false;
  } elseif ($post_pict['size'] > 0) {
    // 画像ファイル名決定
    try {
      $dao = new ProductDao();
      // 設定しようとしたファイル名が既存の場合再設定
      do {
        // ランダム2桁+元ファイル名(30字)
        $pict_name = rand(10, 99).mb_substr($post_pict['name'], -28);
      } while ($dao->isAlreadyExists($pict_name));
      // !$submit_check なら長期保存しないためのファイル名に
      if (!$submit_check) {
        // 拡張子だけ取り出し
        $type = '.'.substr($post_pict['name'], strrpos($post_pict['name'], '.') + 1);
        // tmp0~20.拡張子
        $pict_name = 'tmp'.rand(0, 20).$type;
      }
    } catch (PDOException $e) {
      dbError();
    }
    // /pict/tmp/ファイル名 で一時保存
    move_uploaded_file($post_pict['tmp_name'],'./pict/tmp/'.$pict_name);
  }
?>

<h1>商品修正確認</h1>

<form method="post" action="edit_do.php">
  <table class="product-table">
    <tr>
      <th>商品ID</th>
      <td>
        <?php echo $product->getId() ?>
      </td>
    </tr>
    <tr>
      <th>商品名</th>
      <td>
        <?php echo $post_name ?>
        <?php if(isset($error['post_name'])) echo $error['post_name']; ?>
      </td>
    </tr>
    <tr>
      <th>価格</th>
      <td>
        <?php echo number_format($post_price) ?>円
        <?php if(isset($error['post_price'])) echo $error['post_price']; ?>
      </td>
    </tr>
    <tr>
      <th>発売日</th>
      <td>
        <?php echo $post_release_date ?>
        <?php if(isset($error['post_release_date'])) echo $error['post_release_date']; ?>
      </td>
    </tr>
    <tr>
      <th>紹介文</th>
      <td>
        <?php echo nl2br(str_replace('&#10;', '<br>', $post_content)) ?>
        <?php if(isset($error['post_content'])) echo $error['post_content']; ?>
      </td>
    </tr>
    <tr>
      <th>画像</th>
      <td>
        <?php if(isset($error['post_pict'])): ?>
          <?php echo $error['post_pict']; ?>
        <?php elseif ($pict_name === $before_pict): ?>
          <div class="pict-frame">
            <img src="<?php echo S_NAME ?>admin/product/pict/<?php echo $pict_name ?>">
          </div>
        <?php else: ?>
          <div class="pict-frame">
            <img src="<?php echo S_NAME ?>admin/product/pict/tmp/<?php echo $pict_name ?>">
          </div>
        <?php endif ?>
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
        $up_product = new Product($product->getId(), $post_name, $post_price, $post_content, $pict_name, $post_release_date, $product->getCreatedAt());
        $_SESSION['up_product'] = serialize($up_product);

        // 処理のために修正前のファイル名を送る
        $_SESSION['before_pict'] = $before_pict;
      ?>
    <?php endif ?>
  </div>
</form>

<?php include(D_ROOT.'component/footer_admin.php') ?>