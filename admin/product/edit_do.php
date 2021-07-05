<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'admin');
  reqLogin();

  require_once(D_ROOT.'database/ProductDao.php');
?>
<?php
  reqSession('up_product', 'before_pict');
  $up_product = unserialize($_SESSION['up_product']);
  unset($_SESSION['up_product']);
  $before_pict = $_SESSION['before_pict'];
  unset($_SESSION['before_pict']);

  $new_pict = $up_product->getPict();

  try {
    $dao = new ProductDao();
    $dao->update($up_product);

    // 画像の変更ある場合
    if ($before_pict !== $new_pict) {
      // 画像保存を確定 変更前の画像を削除 noimage.pngの場合を除く
      if ($new_pict !== 'noimage.png' && is_file('./pict/tmp/'.$new_pict)) {
        rename('./pict/tmp/'.$new_pict, './pict/'.$new_pict);
      }
      if ($before_pict !== 'noimage.png' && is_file('./pict/'.$before_pict)) {
        unlink('./pict/'.$before_pict);
      }
    }

    $_SESSION['msg'] = '商品:'.$up_product->getName().' (ID:'.$up_product->getId().') を修正しました';

    header('Location: done.php');
    exit();

  } catch (PDOException $e) {
    dbError();
  }
?>