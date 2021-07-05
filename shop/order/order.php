<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'shop');
  sessionStart();

  require_once(D_ROOT.'database/CartItem.php');

  $title = '注文フォーム';
  include(D_ROOT.'component/header_shop.php');

  blockCartEmpty();
?>

<h1>注文フォーム</h1>

<h2><div class="h2">注文者情報</div></h2>

<form class="form common-table" method="post" action="order_check.php">
  <p class="with-signup-text">注文と同時に会員登録されますと、<br>次回から入力を省略できます。</p>

  <div class="with-signup radio-wrap clearfix">
    <label class="radio-label"><input type="radio" name="with_signup" value="guest" checked>今回だけの注文</label>
    <label class="radio-label"><input type="radio" name="with_signup" value="signup">注文と同時に会員登録</label>
  </div>

  <table>
    <tr>
      <th><label for="email">メールアドレス</label></th>
      <td><input id="email" type="email" name="email" maxlength="50"><span class="announce"><br>※最大50文字</span></td>
    </tr>
    <tr>
      <th><label for="name">お名前</label></th>
      <td><input id="name" type="text" name="name" maxlength="15"><span class="announce"><br>※最大15文字</span></td>
    </tr>
    <tr>
      <th><label for="postal1">郵便番号</label></th>
      <td><input id="postal1" type="text" name="postal1" maxlength="3">-<input id="postal2" type="text" name="postal2" maxlength="4"></td>
    </tr>
    <tr>
      <th><label for="address">住所</label></th>
      <td><input id="address" type="text" name="address" maxlength="50"><span class="announce"><br>※最大50文字</span></td>
    </tr>
    <tr>
      <th><label for="tel">電話番号</label></th>
      <td><input id="tel" type="text" name="tel" maxlength="15"><span class="announce"><br>※半角数字・ハイフン区切り</span></td>
    </tr>
    <tr>
      <td class="with-signup-alert" colspan="2">
        以下、会員登録する場合のみ入力
      </td>
    </tr>
    <tr>
      <th><label for="password">パスワード</label></th>
      <td><input class="disabled-toggle" id="password" type="password" name="password"></td>
    </tr>
    <tr>
      <th><label for="password2">パスワード再入力</label></th>
      <td><input class="disabled-toggle" id="password2" type="password" name="password2"></td>
    </tr>
    <tr>
      <th><label for="male">性別</label></th>
      <td>
        <div class="radio-wrap clearfix">
          <label class="radio-label"><input class="disabled-toggle" type="radio" name="gender" value="1" checked>男性</label>
          <label class="radio-label"><input class="disabled-toggle" type="radio" name="gender" value="2">女性</label>
        </div>
      </td>
    </tr>
    <tr>
      <th>年代</th>
      <td>
        <div class="select-wrap">
          <select class="disabled-toggle" name="birth">
            <?php outputBirthOptions() ?>
          </select>
        </div>
      </td>
    </tr>
  </table>

  <div class="btns">
    <button class="btn btn-secondory" type="button" onclick="location.href='<?php echo S_NAME ?>shop/cart.php'">カートへ戻る</button>
    <input class="btn btn-primary" type="submit" value="確認">
  </div>
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

<?php include(D_ROOT.'component/footer_shop.php') ?>