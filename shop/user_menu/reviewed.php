<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'shop');
  reqLogin();

  require_once(D_ROOT.'database/ReviewDao.php');

  $title = 'レビュー履歴';
  include(D_ROOT.'component/header_shop.php');
?>
<?php
  $member_id = $_SESSION['member_id'];

  try {
    $dao = new ReviewDao();
    $reviews = $dao->findByMemberId($member_id);
?>

<h1>レビュー履歴</h1>

<?php if (!empty($reviews)): ?>
  <div class="reviews">
    <?php foreach ($reviews as $review): ?>
      <div class="review">
        <div class="data">商品名: <?php echo $review['product_name'] ?></div>
        <div class="data">評価: <?php outputRating($review['rating']) ?></div>
        <div class="data">本文: <div class="body"><?php echo nl2br(str_replace('&#10;', '<br>', $review['body'])) ?></div></div>
        <div class="data">投稿日: <?php echo date('Y/m/d H:i:s', strtotime($review['created_at'])) ?></div>
      </div>
    <?php endforeach ?>
  </div>
<?php else: ?>
  <p class="confirm-text">まだレビューがありません</p>
<?php endif ?>

<?php
  } catch (PDOException $e) {
    dbError();
  }
?>

<?php include(D_ROOT.'component/footer_shop.php') ?>