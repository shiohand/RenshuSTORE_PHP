<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'shop');
  reqLogin();

  require_once(D_ROOT.'database/ProductDao.php');
  require_once(D_ROOT.'database/ReviewDao.php');

  $title = 'レビュー投稿確認';
  include(D_ROOT.'component/header_shop.php');
?>
<?php
  try {
    $p_dao = new ProductDao();
    $r_dao = new ReviewDao();

    $member_id = $_SESSION['member_id'];

    reqPost();
    $post_product_id = inputPost('target');
    $post_rating = inputPost('rating');
    $post_body = inputPost('review_post');

    $product = $p_dao->findById($post_product_id);
    blockModelEmpty($product);
    $product_name = $product->getName();
    $param = http_build_query(['id' => $product->getId()]);

    // 商品の存在チェック
    // レビューの存在チェック
    if ($post_product_id !== $product->getId() && $r_dao->isAlreadyExists($post_product_id, $member_id)) {
      commonError();
    }

    // エラーチェック
    $error = array();
    $submit_check = true;

    // 本文
    if ($post_body === '') {
      $error['post_body'] = '<span class="danger">レビューが入力されていません</span>';
      $submit_check = false;
    } elseif (mb_strlen($post_body) > 1000) {
      $error['post_body'] = '<span class="danger">本文が長すぎます</span>';
      $submit_check = false;
    }
?>

<h1>レビュー投稿確認</h1>

<form class="form" method="post" action="review_do.php">
  <div class="review-check">
    <p class="confirm-text">
      <a href="<?php echo S_NAME ?>shop/view.php?<?php echo $param ?>"><?php echo $product_name ?></a>のレビューを投稿
    </p>
    <div class="review">
      <div class="data">評価: <?php outputRating($post_rating) ?></div>
      <div class="data">レビュー本文:
        <div class="body">
          <?php echo nl2br(str_replace('&#10;', '<br>', $post_body)) ?>
        </div>
      </div>
      <?php if(isset($error['post_body'])) echo $error['post_body']; ?>
    </div>
  </div>

  <p class="confirm-text">この内容でレビューを投稿します。よろしいですか？</p>

  <div class="btns">
    <button class="btn btn-secondory" type="button" onclick="history.back()">戻る</button>
    <!-- submit可能時 -->
    <?php if($submit_check): ?>
      <input class="btn btn-primary" type="submit" value="投稿">
      <?php
        $new_review = new Review($post_product_id, $member_id, $post_rating, $post_body, null);
        $_SESSION['new_review'] = serialize($new_review);
        // 表示用のproductのデータ
        $_SESSION['product_data'] = serialize(array('param' => $param, 'name' => $product_name));
      ?>
    <?php endif ?>
  </div>
</form>

<?php
  } catch (PDOException $e) {
    dbError();
  }
?>

<?php include(D_ROOT.'component/footer_shop.php') ?>