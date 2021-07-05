<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'admin');
  reqLogin();

  require_once(D_ROOT.'database/ProductDao.php');
?>
<?php
  reqSession('del_product');
  $del_product = unserialize($_SESSION['del_product']);
  unset($_SESSION['del_product']);

  $del_pict = $del_product->getPict();

  try {
    $dao = new ProductDao();
    $dao->delete($del_product);

    // 画像を削除 noimage.pngの場合を除く
    if ($del_pict !== 'noimage.png' && is_file('./pict/'.$del_pict)) {
      unlink('./pict/'.$del_pict);
    }

    $_SESSION['msg'] = '商品:'.$del_product->getName().' (ID:'.$del_product->getId().') のデータを削除しました';

    header('Location: done.php');
    exit();

  } catch (PDOException $e) {
    dbError();
  }
?>