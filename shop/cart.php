<?php
  require_once($_SERVER['DOCUMENT_ROOT'] . '/prophp/common/common.php');
  define('BASE', 'shop');
  sessionStart();

  require_once(D_ROOT . 'database/CartItem.php');

  $title = 'ショッピングカート';
  include(D_ROOT . 'component/header_shop.php');
?>
<?php
  // カート内容取得
  $cart = array();
  if (isset($_SESSION['cart'])) {
    $cart = unserialize($_SESSION['cart']);
  }
  // 数量変更・削除
  if (!empty($_POST)) {
    // 削除後の添字ずれを避けるために末尾から処理
    $len = count($cart);
    for ($i = $len - 1; $i >= 0; $i--) {
      // 数量変更 postされた値
      $q = inputPost('quantity' . $i);
      if ((preg_match("/\A[0-9]+\z/", $q) === 0 || $q < 1)) {
        $cart[$i]->setQuantityError('<br><span class="danger">変更に失敗しました</span>');
      } else {
        // quantityに代入
        $cart[$i]->setQuantity($q);
        $cart[$i]->setQuantityError('');
      }
      // 削除
      if (isset($_POST['delete' . $i])) {
        array_splice($cart, $i, 1);
      }
    }
    $_SESSION['cart'] = serialize($cart);
  }
  // ただの集計用
  $total = ['price' => 0, 'quantity' => 0];
?>

<h1>ショッピングカート</h1>

<?php if (!empty($cart)) : ?>

  <div class="cart">
    <form class="form cart-view" method="post" action="cart.php">
      <input class="btn" type="submit" value="数量変更・チェックした商品の削除を実行">

      <div class="cart-items">
        <?php foreach ($cart as $item) : ?>
          <?php
            $product = $item->getProduct();
            $index = array_search($item, $cart);
            $price = $product->getPrice();
            $quantity = $item->getQuantity();
            $quantity_error = $item->getQuantityError();

            $total['quantity'] += $quantity;
            $total['price'] += $price * $quantity;

            $param = http_build_query(['id' => $product->getId()]);
          ?>
          <div class="cart-item">
            <div class="item-check"><input id="delete" type="checkbox" name="delete<?php echo $index ?>"></div>
            <!-- 商品画像 -->
            <div class="pict-frame"><img src="<?php echo S_NAME ?>admin/product/pict/<?php echo $product->getPict() ?>"></div>
            <div class="data">
              <!-- 商品名 -->
              <div class="product-name"><a href="view.php?<?php echo $param ?>"><?php echo $product->getName() ?></a></div>
              <!-- 価格 -->
              <div class="product-price"><?php echo number_format($price) ?>円</div>
            </div>
            <div class="totals">
              <!-- 数量 -->
              <div class="quantity">数量: <input id="quantity" type="number" name="quantity<?php echo $index ?>" value="<?php echo $quantity ?>" min="1">個
                <?php if ($quantity_error) echo $quantity_error ?></div>
              <!-- 小計 -->
              <div class="total">小計: <?php echo number_format($price * $quantity) ?>円</div>
            </div>
          </div>
        <?php endforeach ?>
        <div class="cart-totals">
          <div class="total-quantity">合計点数: <?php echo $total['quantity'] ?>点</div>
          <div class="total-price">合計金額: <?php echo number_format($total['price']) ?>円</div>
        </div>
      </div>

      <input class="btn" type="submit" value="数量変更・チェックした商品の削除を実行">
    </form>

    <div class="cart-action">
      <div class="btns">
        <a class="btn btn-secondory" href="clear_cart.php">カートを空にする</a>
      </div>
      <div class="btns">
        <a class="btn btn-primary-sub" href="order/order.php">購入手続きへ進む</a>
        <a class="btn btn-primary" href="order/order_member.php">会員かんたん注文へ進む</a>
      </div>
    </div>
  </div>

<?php else : ?>
  <p class="confirm-text">現在カートに商品はありません</p>
<?php endif ?>

<?php include(D_ROOT . 'component/footer_shop.php') ?>