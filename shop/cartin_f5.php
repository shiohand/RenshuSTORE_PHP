<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'shop');
  sessionStart();

  require_once(D_ROOT.'database/ProductDao.php');
  require_once(D_ROOT.'database/CartItem.php');

  $title = 'カートに入れる';
  include(D_ROOT.'component/header_shop.php');
?>
<?php
  try {
    $dao = new ProductDao();

    $post_product_id = strval(rand(1, 3) > 1 ? rand(1, 7) : rand(9, 50));
    $post_quantity = rand(1, 3);

    $product = $dao->findById($post_product_id);
    blockModelEmpty($product);
    $param = http_build_query(['id' => $post_product_id]);

    // 商品の存在チェック、未発売チェック
    if (($post_product_id !== $product->getId()) || (time() < strtotime($product->getReleaseDate()))) {
      print '商品が見つかりません<br>';
      commonError();
    }
    // 数量確認
    if ((preg_match("/\A[0-9]+\z/", $post_quantity) === 0 || $post_quantity < 1)) {
      print '数量が不正です<br>';
      print '<a href="'.S_NAME.'shop/product.php">商品一覧へ戻る</a>';
      print '<br>';
      print '<a href="'.S_NAME.'shop/view.php?'.$param.'">商品ページへ戻る</a>';
      commonError();
    }

    $cart = array();
    if (isset($_SESSION['cart'])) {
      $cart = unserialize($_SESSION['cart']);
      // カート内のチェック
      if (CartItem::findById($cart, $post_product_id)) {
        print 'その商品はすでにカートに入っています。<br>';
        print '<a href="'.S_NAME.'shop/product.php">商品一覧へ戻る</a>';
        print '<br>';
        print '<a href="'.S_NAME.'shop/view.php?'.$param.'">商品ページへ戻る</a>';
        commonError();
      }
    }
    // CartItem作成、追加
    $cart[] = new CartItem($product, $post_quantity);
    $_SESSION['cart'] = serialize($cart);
?>

<h1>カートに追加しました</h1>

<p>
  <a href="<?php echo S_NAME ?>shop/view.php?<?php echo $param ?>">
    <?php echo $product->getName() ?>
  </a>をカートに追加しました。
  <br>
  数量: <?php echo $post_quantity ?>
</p>
<div class="pict-frame">
  <img src="<?php echo S_NAME ?>admin/product/pict/<?php echo $product->getPict() ?>">
</div>

<a href="cart.php">カートを見る</a>
<br>
<a href="order/order_check_f5.php">購入手続きf5へ進む</a>
<a href="order/order_member_f5.php">会員かんたん注文f5へ進む</a>

<?php
  } catch (PDOException $e) {
    dbError();
  }
?>

<?php include(D_ROOT.'component/footer_shop.php') ?>