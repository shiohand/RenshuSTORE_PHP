<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'shop');
  reqLogin();

  require_once(D_ROOT.'database/ReviewDao.php');
?>
<?php
  if (isset($_SESSION['new_review'], $_SESSION['product_data'])) {
    $new_review = unserialize($_SESSION['new_review']);
    $product_data = unserialize($_SESSION['product_data']);
  } else {
    commonError();
  }
  unset($_SESSION['new_review']);
  unset($_SESSION['product_data']);

  $product_param = $product_data['param'];
  $product_name = $product_data['name'];

  try {
    $dao = new ReviewDao();
    $dao->create($new_review);

    $_SESSION['msg'] = '<a href="'.S_NAME.'shop/view.php?'.$product_param.'">'.$product_name.'</a>のレビューを投稿しました';

    header('Location: done.php');
    exit();

  } catch (PDOException $e) {
    dbError();
  }
?>