<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'admin');
  reqLogin();

  require_once(D_ROOT.'database/ProductDao.php');

  $title = '商品修正';
  include(D_ROOT.'component/header_admin.php');
?>
<?php
  reqGet('id');
  $id = inputGet('id');

  try {
    $dao = new ProductDao();
    $product = $dao->findById($id);
    blockModelEmpty($product);
    $_SESSION['product'] = serialize($product);
?>

<h1>商品修正</h1>

<form method="post" action="edit_check.php" enctype="multipart/form-data">
  <table class="product-table">
    <tr>
      <th>商品ID</th>
      <td><?php echo $product->getId() ?></td>
    </tr>
    <tr>
      <th><label for="name">商品名</label></th>
      <td><input id="name" type="text" name="name" maxlength="30" value="<?php echo $product->getName() ?>"><span class="announce"><br>※最大30文字</span></td>
    </tr>
    <tr>
      <th><label for="price">価格</label></th>
      <td><input id="price" type="text" name="price" value="<?php echo $product->getPrice() ?>"></td>
    </tr>
    <tr>
      <th><label for="release_date">発売日</label></th>
      <td><input id="release_date" type="text" name="release_date" value="<?php echo $product->getReleaseDate() ?>"></td>
    </tr>
    <tr>
      <th><label for="content">紹介文</label></th>
      <td><textarea id="content" name="content" maxlength="1000" cols="30" rows="10"><?php echo $product->getContent() ?></textarea><span class="announce"><br>※最大1000文字</span></td>
    </tr>
    <tr>
      <th>画像</th>
      <td>
        <div class="pict-frame">
          <img src="<?php echo S_NAME ?>admin/product/pict/<?php echo $product->getPict() ?>">
        </div>
        <div class="file-input">
          変更する:
          <input type="hidden" name="MAX_FILE_SIZE" value="1048576">
          <input type="file" name="pict" accept="image/*">
        </div>
      </td>
    </tr>
  </table>

  <div class="btns">
    <button class="btn btn-secondory" type="button" onclick="history.back()">戻る</button>
    <input class="btn btn-primary" type="submit" value="確認">
  </div>
</form>

<?php
  } catch (PDOException $e) {
    dbError();
  }
?>

<?php include(D_ROOT.'component/footer_admin.php') ?>