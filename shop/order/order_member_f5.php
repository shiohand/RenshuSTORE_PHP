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
  $member_id = rand(1, 14);

  try {
    $dao = new MemberDao();
    $orderer = $dao->findById($member_id);
    blockModelEmpty($orderer);
?>

<h1>かんたん注文確認</h1>

<h2>注文者情報</h2>

<form method="post" action="order_do.php">
  <input type="hidden" name="with_signup" value="already" >
  <table>
    <tr>
    <tr>
      <td>メールアドレス</td>
      <td><?php echo $orderer->getEmail() ?></td>
    </tr>
    <tr>
      <td>お名前</td>
      <td><?php echo $orderer->getName() ?></td>
    </tr>
    <tr>
      <td>郵便番号</td>
      <td><?php echo $orderer->getPostal1() ?>-<?php echo $orderer->getPostal2() ?></td>
    </tr>
    <tr>
      <td>住所</td>
      <td><?php echo $orderer->getAddress() ?></td>
    </tr>
    <tr>
      <td>電話番号</td>
      <td><?php echo $orderer->getTel() ?></td>
    </tr>
  </table>

  <p>確認メッセージ</p>

  <button type="button" onclick="history.back()">戻る</button>
  <input type="submit" value="注文を確定する">
  <?php
    $_SESSION['orderer'] = serialize($orderer);
  ?>
</form>

<?php
  $cart = unserialize($_SESSION['cart']);
  $total = ['price' => 0, 'quantity' => 0];
?>

<h2>カートの中の商品</h2>

<table>
  <?php foreach ($cart as $item): ?>
    <?php
      $product = $item->getProduct();
      $index = array_search($item, $cart);
      $price = $product->getPrice();
      $quantity = $item->getQuantity();

      $total['quantity'] += $quantity;
      $total['price'] += $price * $quantity;
    ?>
    <tr>
      <!-- index -->
      <td>
        <?php echo $index + 1 ?>
      </td>
      <!-- 商品名 -->
      <td>
        <?php echo $product->getName() ?>
      </td>
      <!-- 価格 -->
      <td>
        <?php echo number_format($price) ?>円
      </td>
      <!-- 数量 -->
      <td>
        数量: <?php echo $quantity ?>個
      </td>
      <!-- 小計 -->
      <td>
        小計: <?php echo number_format($price * $quantity) ?>円
      </td>
    </tr>
  <?php endforeach ?>
</table>
<table>
  <tr>
    <td>
      合計点数: <?php echo $total['quantity'] ?>点
    </td>
    <td>
      合計金額: <?php echo number_format($total['price']) ?>円
    </td>
  </tr>
</table>

<?php
  } catch (PDOException $e) {
    dbError();
  }
?>

<?php include(D_ROOT.'component/footer_shop.php') ?>