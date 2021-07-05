<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'admin');
  reqLogin();

  require_once(D_ROOT.'database/ProductDao.php');

  $title = '商品削除';
  include(D_ROOT.'component/header_admin.php');
?>
<?php
  reqGet('id');
  $id = inputGet('id');

  try {
    $dao = new ProductDao();
    $product = $dao->findById($id);
    blockModelEmpty($product);
    $_SESSION['del_product'] = serialize($product);
?>

<h1>商品削除</h1>

<form method="post" action="delete_do.php">
  <table class="product-table">
    <tr>
      <th>商品ID</th>
      <td><?php echo $product->getId() ?></td>
    </tr>
    <tr>
      <th>商品名</th>
      <td><?php echo $product->getName() ?></td>
    </tr>
    <tr>
      <th>価格</th>
      <td><?php echo number_format($product->getPrice()) ?>円</td>
    </tr>
    <tr>
      <th>発売日</th>
      <td><?php echo $product->getReleaseDate() ?></td>
    </tr>
    <tr>
      <th>紹介文</th>
      <td><?php echo nl2br(str_replace('&#10;', '<br>', $product->getContent())) ?></td>
    </tr>
    <tr>
      <th>画像</th>
      <td>
        <div class="pict-frame">
          <img src="<?php echo S_NAME ?>admin/product/pict/<?php echo $product->getPict() ?>">
        </div>
      </td>
    </tr>
  </table>

  <p class="confirm-text">削除しますか？</p>

  <div class="btns">
    <button class="btn btn-secondory" type="button" onclick="history.back()">戻る</button>
    <input class="btn btn-primary" type="submit" value="登録を解除">
  </div>
</form>

<?php
  } catch (PDOException $e) {
    dbError();
  }
?>

<?php include(D_ROOT.'component/footer_admin.php') ?>