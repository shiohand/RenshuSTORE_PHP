<?php
define('D_ROOT', $_SERVER['DOCUMENT_ROOT'].'/prophp/');
define('S_NAME', 'http://'.$_SERVER['SERVER_NAME'].'/prophp/');
define('URL', htmlspecialchars($_SERVER['PHP_SELF']));
define('URI', htmlspecialchars($_SERVER['REQUEST_URI']));

// sessionStart()           通常のsession_start()
// reqLogin()               未ログインを弾く
// blockLogin()             ログイン済みを弾く

// reqPost()                あるべきリクエストや値が不足している場合を弾く
// reqGet()
// reqSession()
// blockModelEmpty()        あるべきモデルが取得できていない場合を弾く
// blockCartEmpty()         カートが空を弾く shop/order

// inputPost()              inputのサニタイジングとチェック
// inputGet()
// dateCheck()              dateCheck type="date"のフォーマット確認 yyyy-mm-dd
// pageCheck()              pageCheck pの値が不正だったら'1'とする

// commonError()            エラーメッセージとフッターとexit()
// dbError()

/* HTMLの出力 */
// outputNavLis()           単純なリスト(href設定でaタグ)
// outputBirthOptions()     年代のselectを出力 1910から10刻み
// outputRating()           ratingから星を出力
// buildQuerys()            http_build_query + URLと結合
// buildInheritQuerysArr()  現在のリクエストのクエリ文字列をキーバリューの配列に変更 buildQuerysの材料に
// createPager()            pagerの出力

// 通常のsession_start()
function sessionStart() {
  session_start();
  session_regenerate_id(true);
}
// 未ログインを弾く
function reqLogin() {
  session_start();
  session_regenerate_id(true);
  if (BASE === 'admin') {
    if (!isset($_SESSION['staff_login'])) {
      header('Location: '.S_NAME.BASE.'/no_login.php');
      exit();
    }
  } elseif (BASE === 'shop') {
    if (!isset($_SESSION['member_login'])) {
      header('Location: '.S_NAME.BASE.'/no_login.php');
      exit();
    }
  }
}
// ログイン済みを弾く
function blockLogin() {
  session_start();
  if (BASE === 'admin') {
    if (isset($_SESSION['staff_login'])) {
      header('Location: '.S_NAME.BASE.'/already_login.php');
      exit();
    }
  } elseif (BASE === 'shop') {
    if (isset($_SESSION['member_login'])) {
      header('Location: '.S_NAME.BASE.'/already_login.php');
      exit();
    }
  }
}
// あるべきリクエストや値が不足している場合を弾く
function reqPost() {
  if (empty($_POST)) {
    commonError();
  }
}
function reqGet(string ...$keys) {
  foreach($keys as $key) {
    if (!isset($_GET[$key])) {
      commonError();
    }
  }
}
function reqSession(string ...$keys) {
  foreach($keys as $key) {
    if (!isset($_SESSION[$key])) {
      commonError();
    }
  }
}
// あるべきモデルが取得できていない場合を弾く
function blockModelEmpty($model) {
  if(!$model->getId()) {
    commonError();
  }
}
// カートが空を弾く shop/order
function blockCartEmpty() {
  if (!isset($_SESSION['cart'])) {
    print '<p class="confirm-text">カートが空です</p>';
    print '<a href="'.S_NAME.BASE.'/top.php">トップページへ</a>';
    include(D_ROOT.'component/footer_shop.php');
    exit();
  }
}

/* 今回はそのままにしておくが、filter_inputやDB処理ではエスケープをせず、データを利用する場合のみそのときにエスケープするのが最新 */
// inputのサニタイジングとチェック
// 弱点。入力値が0だと$initになるかも。
function inputPost(string $key, string $init = ''): string {
  $ret = (string)filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
  if ($ret) {
    return $ret;
  } else {
    return $init;
  }
}
function inputGet(string $key, string $init = ''): string {
  $ret = (string)filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
  if ($ret) {
    return $ret;
  } else {
    return $init;
  }
}
// dateCheck type="date"のフォーマット確認 yyyy-mm-dd
function dateCheck(string $date): string{
  if (preg_match("/\A[0-9]{4}(-[0-9]{2}){2}\z/", $date) === 0) {
    return '';
  }
  list($y, $m, $d) = explode('-', $date);
  if (!checkdate($m, $d, $y)) {
    return '';
  }
  return $date;
}
// pageCheck pの値が不正だったら'1'とする
// pageCheck(inputGet('p', '1'))で使う
function pageCheck(string $p): string {
  if ((preg_match("/\A[0-9]+\z/", $p) === 0) || ($p < 1)) {
    return '1';
  }
  return $p;
}

// エラーメッセージとフッターとexit()
function commonError() {
  print '<p class="confirm-text">エラーが発生しました</p>';
  print '<ul class="back-nav common-nav"><li><a href="'.S_NAME.BASE.'/top.php">トップページへ戻る</a></li></ul>';
  include(D_ROOT.'component/footer_'.BASE.'.php');
  exit();
}
function dbError() {
  print '<p class="confirm-text">ただいま障害により大変ご迷惑をお掛けしております。</p>';
  print '<ul class="back-nav common-nav"><li><a href="'.S_NAME.BASE.'/top.php">トップページへ戻る</a></li></ul>';
  // print $e->getMessage();
  include(D_ROOT.'component/footer_'.BASE.'.php');
  exit();
}

/* HTMLの出力 */

// 単純なリスト(href設定でaタグ) $item = ['content', 'href'];
function outputNavLis(array $items) {
  foreach ($items as $item) {
    echo '<li>';
    // hrefがあればaタグ、なければcontentのみ
    if ($item[1]) {
      echo '<a href="'.$item[1].'">'.$item[0].'</a>';
    } else {
      echo $item[0];
    }
    echo '</li>';
  }
}
// 年代のselectを出力 1910から10刻み member登録時
function outputBirthOptions($selected = '1980') {
  $now = round(intval(date('Y')), -1);
  for ($i = 1910; $i <= $now; $i += 10) {
    echo '<option value="'.$i.'"';
    if (strval($i) === $selected) {
      echo ' selected';
    }
    echo '>'.$i.'年代</option>';
  }
}
// ratingから星を出力
function outputRating($rating = '3') {
  $roop = intval($rating);
  for($i = 0; $i < $roop; $i++) {
    echo '★';
  }
  for($i = 0; $i < 5 - $roop; $i++) {
    echo '☆';
  }
}
// valの数だけhttp_build_query + URLと結合
// $inherit 現在のクエリパラメータを維持する場合に指定 buildInheritQuerysArr()を利用できる
// $url     URLを指定 初期値は現在のURL
function buildQuerys(string $key, array $vals, array $inherit = array(), string $url = URL) {
  $urls = array();
  foreach ($vals as $val) {
    $inherit[$key] = $val;
    $urls[$val] = $url.'?'.http_build_query($inherit);
  }
  return $urls;
}
// 現在のリクエストのクエリ文字列をキーバリューの配列に変更 buildQuerysの材料に
// $except 指定したキーを除外 buildQuerys()で追加する予定のキーが重複しないよう
function buildInheritQuerysArr(string $except = ''): array {
  parse_str(($_SERVER['QUERY_STRING']), $querys);
  // 指定したキーがあれば除外
  if ($except) {
    unset($querys[$except]);
  }
  foreach(array_keys($querys) as $key) {
    // プログラムで使用されるキー以外は除外
    if (!in_array($key, ['interv', 'term', 'sort', 'p'])) {
      unset($querys[$key]);
    }
  }
  return $querys;
}

// pagerの出力
// $page     表示するページ
// $count    総データ数
// $per_page 1ページあたりの表示データ数
function createPager($page, $count, $per_page):string {
  $total_page = ceil($count / $per_page);
  if ($total_page == 0) {
    $total_page = 1;
    $page = $total_page;
  }
  if ($total_page < $page) {
    $page = $total_page;
    $is_page_over = true;
  } else {
    $is_page_over = false;
  }
  $prev = max($page - 1, 1);
  $prev2 = max($page - 2, 1);
  $next = min($page + 1, $total_page);
  $next2 = min($page + 2, $total_page);

  // クエリつきURL作成
  $p_array = [1, $prev2, $prev, $next, $next2, $total_page];
  $inherit = buildInheritQuerysArr('p');
  $urls = buildQuerys('p', $p_array, $inherit);

  // 現在のページ位置によって出力変更
  $arr = array();
  // prev側
  switch ($page - 1) {
    case 0:
      break;
    case 1:
      $arr[] = '<a href="'.$urls[$prev].'">'.$prev.'</a>';
      break;
    case 2:
      $arr[] = '<a href="'.$urls[$prev2].'">'.$prev2.'</a>';
      $arr[] = '<a href="'.$urls[$prev].'">'.$prev.'</a>';
      break;
    default:
      $arr[] = '<a href="'.$urls[1].'">1</a> ...';
      $arr[] = '<a href="'.$urls[$prev2].'">'.$prev2.'</a>';
      $arr[] = '<a href="'.$urls[$prev].'">'.$prev.'</a>';
      break;
  }
  // 現在のページ(オーバーしていたら最終ページへのリンク)
  if ($is_page_over) {
    $arr[] = '<a href="'.$urls[$total_page].'">'.$total_page.'</a>';
  } else {
    $arr[] = '<span>'.$page.'</span>';
  }
  // next側
  switch ($total_page - $page) {
    case 0:
      break;
    case 1:
      $arr[] = '<a href="'.$urls[$next].'">'.$next.'</a>';
      break;
    case 2:
      $arr[] = '<a href="'.$urls[$next].'">'.$next.'</a>';
      $arr[] = '<a href="'.$urls[$next2].'">'.$next2.'</a>';
      break;
    default:
      $arr[] = '<a href="'.$urls[$next].'">'.$next.'</a>';
      $arr[] = '<a href="'.$urls[$next2].'">'.$next2.'</a>';
      $arr[] = '... <a href="'.$urls[$total_page].'">'.$total_page.'</a>';
      break;
  }

  // 結合
  $output = '';
  foreach ($arr as $item) {
    $output .= $item."\n";
  }
  return $output;
}
?>