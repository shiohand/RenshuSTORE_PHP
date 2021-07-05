<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'shop');
  reqLogin();

  require_once(D_ROOT.'database/MemberDao.php');
  require_once(D_ROOT.'database/CartItem.php');

  $title = 'かんたん注文確認';
  include(D_ROOT.'component/header_shop.php');

  blockCartEmpty();
?>
<?php
  $member_id = $_SESSION['member_id'];

  try {
    $dao = new MemberDao();
    $orderer = $dao->findById($member_id);
    blockModelEmpty($orderer);
?>

<h1>かんたん注文確認</h1>

<h2><div class="h2">注文者情報</div></h2>

<form class="form common-table" method="post" action="order_do.php">
  <input type="hidden" name="with_signup" value="already" >
  <table>
    <tr>
      <th>メールアドレス</th>
      <td><?php echo $orderer->getEmail() ?></td>
    </tr>
    <tr>
      <th>お名前</th>
      <td><?php echo $orderer->getName() ?></td>
    </tr>
    <tr>
      <th>郵便番号</th>
      <td><?php echo $orderer->getPostal1() ?>-<?php echo $orderer->getPostal2() ?></td>
    </tr>
    <tr>
      <th>住所</th>
      <td><?php echo $orderer->getAddress() ?></td>
    </tr>
    <tr>
      <th>電話番号</th>
      <td><?php echo $orderer->getTel() ?></td>
    </tr>
  </table>

  <p class="confirm-text">こちらの内容でお間違いありませんか？</p>

  <div class="btns">
    <button class="btn btn-secondory" type="button" onclick="location.href='<?php echo S_NAME ?>shop/cart.php'">カートへ戻る</button>
    <input class="btn btn-primary" type="submit" value="注文を確定する">
  </div>
  <?php
    $_SESSION['orderer'] = serialize($orderer);
  ?>
</form>

<?php
  $cart = unserialize($_SESSION['cart']);
  $total = ['price' => 0, 'quantity' => 0];
?>

<h2><div class="h2">カートの中の商品</div></h2>

<div class="cart-items-s">
  <div class="items">
    <?php foreach ($cart as $item): ?>
      <?php
        $product = $item->getProduct();
        $index = array_search($item, $cart);
        $price = $product->getPrice();
        $quantity = $item->getQuantity();

        $total['quantity'] += $quantity;
        $total['price'] += $price * $quantity;
      ?>
      <div class="product-datas-list">
        <!-- 連番 -->
        <div class="item-index">
          <?php echo $index + 1 ?>
        </div>
        <div class="pict-frame">
          <img src="<?php echo S_NAME ?>admin/product/pict/<?php echo $product->getPict() ?>">
        </div>
        <div class="datas">
          <!-- 商品名 -->
          <div class="data"><?php echo $product->getName() ?></div>
          <!-- 価格 -->
          <div class="data"><?php echo number_format($price) ?>円</div>
          <!-- 数量 -->
          <div class="data">数量: <?php echo $quantity ?>個</div>
          <!-- 小計 -->
          <div class="data">小計: <?php echo number_format($price * $quantity) ?>円</div>
        </div>
      </div>
    <?php endforeach ?>
  </div>
  <div class="totals">
    <div class="total-quantity">
      合計点数: <?php echo $total['quantity'] ?>点
    </div>
    <div class="total-price">
      合計金額: <?php echo number_format($total['price']) ?>円
    </div>
  </div>
</div>

<div class="btns">
  <button class="btn btn-secondory" type="button" onclick="location.href='<?php echo S_NAME ?>shop/cart.php'">カートへ戻る</button>
</div>

<?php
  } catch (PDOException $e) {
    dbError();
  }
?>

<?php include(D_ROOT.'component/footer_shop.php') ?>