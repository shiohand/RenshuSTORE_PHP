<?php
// getTerms()                      [累計 24時間 週間 月間 年間] リンクとSQLの代入文を作る
// getIntervs()                    [日別 月別 年別] リンクとSQLの代入文を作る

/* [△▽] リンクとSQLの代入文を作る */
// getOrderByForSalesAndOPSales()  admin.order.sales, admin.order.one_product_sales用
// getOrderByForOrder()            admin.order.order用
// getOrderByForPSales()           admin.order.product_sales用
// getOrderByForProduct()          shop.product用

// クエリパラメータ付きのリンクを作ると同時に現在のリクエストパラメータを反映したSQLの代入文を作る
// 引数
// $get_key 現在のパラメータ
// $column  SQL用 絞り込みやソートの対象となるカラム
// return array 出力用stringのarray

// 例)getTerms
// array $term_array        $_GET['term']に想定される値
// buildInheritQuerysArr()  現在のリクエストのクエリを保持
// buildQuerys()            値ごとのURL作成
// array $termAs            値ごとのHTML要素作成
// if (!in_array($get_term, $term_array))  想定される値以外が入っていたらデフォルト値に
// $termAs[$get_term]...                   $termAsのうち、現在のパラメータのURLはただの文字列に
// makeOptBetweenByTerm()                  SQLの代入文作成

/* [累計 24時間 週間 月間 年間] リンクとSQLの代入文を作る */
function getTerms(string $get_term, string $column): array {
  $term_array = ['all', 'day', 'week', 'month', 'year'];

  $inherit = buildInheritQuerysArr('term');
  $urls = buildQuerys('term', $term_array, $inherit);

  $termAs = [
    'all' => '<a href="'.$urls['all'].'">累計</a>',
    'day' => '<a href="'.$urls['day'].'">24時間</a>',
    'week' => '<a href="'.$urls['week'].'">週間</a>',
    'month' => '<a href="'.$urls['month'].'">月間</a>',
    'year' => '<a href="'.$urls['year'].'">年間</a>'
  ];
  if (!in_array($get_term, $term_array)) {
    $get_term = 'all';
  }
  $termAs[$get_term] = ['all' => '累計', 'day' => '24時間', 'week' => '週間', 'month' => '月間', 'year' => '年間'][$get_term];
  $betweenAnd = makeOptBetweenByTerm($column, $get_term);
  return [$termAs, $betweenAnd];
}

/* [日別 月別 年別] リンクとSQLの代入文を作る */
function getIntervs(string $get_interv): array {
  $interv_array = ['day', 'month', 'year'];

  $inherit = buildInheritQuerysArr('interv');
  $urls = buildQuerys('interv', $interv_array, $inherit);

  $intervAs = [
    'day' => '<a href="'.$urls['day'].'">日別</a>',
    'month' => '<a href="'.$urls['month'].'">月別</a>',
    'year' => '<a href="'.$urls['year'].'">年別</a>'
  ];
  if (!in_array($get_interv, $interv_array)) {
    $get_interv = 'day';
  }
  $intervAs[$get_interv] = ['day' => '日別', 'month' => '月別', 'year' => '年別'][$get_interv];
  $interv = makeOptIntervStr($get_interv);
  return [$intervAs, $interv];
}

/* [△▽] リンクとSQLの代入文を作る */

// admin.order.sales, admin.order.one_product_sales用
function getOrderByForSalesAndOPSales(string $get_sort) {
  $sort_array = ['at', 'rat', 'pri', 'rpri'];

  $inherit = buildInheritQuerysArr('sort');
  $urls = buildQuerys('sort', $sort_array, $inherit);

  $sortAs = [
    'at' => '<a href="'.$urls['at'].'">△</a>'.'<a href="'.$urls['rat'].'">▽</a>',
    'pri' => '<a href="'.$urls['pri'].'">△</a>'.'<a href="'.$urls['rpri'].'">▽</a>',
  ];
  if (!in_array($get_sort, $sort_array)) {
    $get_sort = 'rat';
  }
  $column = '';
  $desc = false;
  switch ($get_sort) {
    case 'pri':
      $column = 'total_price';
      break;
    case 'rpri':
      $column = 'total_price';
      $desc = true;
      break;
    case 'at':
      $column = 'interv';
      break;
    case 'rat':
      $column = 'interv';
      $desc = true;
      break;
    default:
      $column = 'interv';
      $desc = true;
      break;
  }
  if ($desc) {
    $sortAs[substr($get_sort, 1)] = '<a href="'.$urls[substr($get_sort, 1)].'">△</a>'.'▼';
  } else {
    $sortAs[$get_sort] = '▲'.'<a href="'.$urls['r'.$get_sort].'">▽</a>';
  }
  $orderBy = makeOptOrderBy($column, $desc);
  return [$sortAs, $orderBy];
}

// admin.order.order用
function getOrderByForOrder(string $get_sort) {
  $sort_array = ['sid', 'rsid', 'mid', 'rmid', 'pri', 'rpri', 'at', 'rat'];

  $inherit = buildInheritQuerysArr('sort');
  $urls = buildQuerys('sort', $sort_array, $inherit);

  $sortAs = [
    'sid' => '<a href="'.$urls['sid'].'">△</a>'.'<a href="'.$urls['rsid'].'">▽</a>',
    'mid' => '<a href="'.$urls['mid'].'">△</a>'.'<a href="'.$urls['rmid'].'">▽</a>',
    'pri' => '<a href="'.$urls['pri'].'">△</a>'.'<a href="'.$urls['rpri'].'">▽</a>',
    'at' => '<a href="'.$urls['at'].'">△</a>'.'<a href="'.$urls['rat'].'">▽</a>',
  ];
  if (!in_array($get_sort, $sort_array)) {
    $get_sort = 'rsid';
  }
  $column = '';
  $desc = false;
  switch ($get_sort) {
    case 'sid':
      $column = 'dat_sales.id';
      break;
    case 'rsid':
      $column = 'dat_sales.id';
      $desc = true;
      break;
    case 'mid':
      $column = 'member_id';
      break;
    case 'rmid':
      $column = 'member_id';
      $desc = true;
      break;
    case 'pri':
      $column = 'final_price';
      break;
    case 'rpri':
      $column = 'final_price';
      $desc = true;
      break;
    case 'at':
      $column = 'dat_sales.created_at';
      break;
    case 'rat':
      $column = 'dat_sales.created_at';
      $desc = true;
      break;
    default:
      $column = 'dat_sales.id';
      $desc = true;
      break;
  }
  if ($desc) {
    $sortAs[substr($get_sort, 1)] = '<a href="'.$urls[substr($get_sort, 1)].'">△</a>'.'▼';
  } else {
    $sortAs[$get_sort] = '▲'.'<a href="'.$urls['r'.$get_sort].'">▽</a>';
  }
  $orderBy = makeOptOrderBy($column, $desc);
  return [$sortAs, $orderBy];
}

// admin.order.product_sales用
function getOrderByForPSales(string $get_sort) {
  $sort_array = ['pid', 'rpid', 'q', 'rq', 'pri', 'rpri', 'uni', 'runi'];

  $inherit = buildInheritQuerysArr('sort');
  $urls = buildQuerys('sort', $sort_array, $inherit);

  $sortAs = [
    'pid' => '<a href="'.$urls['pid'].'">△</a>'.'<a href="'.$urls['rpid'].'">▽</a>',
    'q' => '<a href="'.$urls['q'].'">△</a>'.'<a href="'.$urls['rq'].'">▽</a>',
    'pri' => '<a href="'.$urls['pri'].'">△</a>'.'<a href="'.$urls['rpri'].'">▽</a>',
    'uni' => '<a href="'.$urls['uni'].'">△</a>'.'<a href="'.$urls['runi'].'">▽</a>',
  ];
  if (!in_array($get_sort, $sort_array)) {
    $get_sort = 'rpri';
  }
  $column = '';
  $desc = false;
  switch ($get_sort) {
    case 'pid':
      $column = 'product_id';
      break;
    case 'rpid':
      $column = 'product_id';
      $desc = true;
      break;
    case 'q':
      $column = 'quantity';
      break;
    case 'rq':
      $column = 'quantity';
      $desc = true;
      break;
    case 'pri':
      $column = 'total_price';
      break;
    case 'rpri':
      $column = 'total_price';
      $desc = true;
      break;
    case 'uni':
      $column = 'avg_unit_price';
      break;
    case 'runi':
      $column = 'avg_unit_price';
      $desc = true;
      break;
    default:
      $column = 'total_price';
      $desc = true;
      break;
  }
  if ($desc) {
    $sortAs[substr($get_sort, 1)] = '<a href="'.$urls[substr($get_sort, 1)].'">△</a>'.'▼';
  } else {
    $sortAs[$get_sort] = '▲'.'<a href="'.$urls['r'.$get_sort].'">▽</a>';
  }
  $orderBy = makeOptOrderBy($column, $desc);
  return [$sortAs, $orderBy];
}

// shop.product用
function getOrderByForProduct(string $get_sort) {
  $sort_array = ['rat', 'pri', 'rpri', 'rev'];

  $inherit = buildInheritQuerysArr('sort');
  $urls = buildQuerys('sort', $sort_array, $inherit);

  $sortAs = [
    'rat' => '<a href="'.$urls['rat'].'">発売日の近い順</a>',
    'pri' => '<a href="'.$urls['pri'].'">価格の安い順</a>',
    'rpri' => '<a href="'.$urls['rpri'].'">価格の高い順</a>',
    'rev' => '<a href="'.$urls['rev'].'">評価の高い順</a>'
  ];
  if (!in_array($get_sort, $sort_array)) {
    $get_sort = 'rat';
  }
  $column = '';
  $desc = false;
  switch ($get_sort) {
    case 'pri':
      $column = 'price';
      break;
    case 'rpri':
      $column = 'price';
      $desc = true;
      break;
    case 'rev':
      $column = 'rating_avg';
      $desc = true;
      break;
    case 'rat':
      $column = 'release_date';
      $desc = true;
      break;
    default:
      $column = 'release_date';
      $desc = true;
      break;
  }
  $sortAs[$get_sort] = ['rat' => '発売日の近い順','pri' => '価格の安い順', 'rpri' => '価格の高い順', 'rev' => '評価の高い順'][$get_sort];
  $orderBy = makeOptOrderBy($column, $desc);
  return [$sortAs, $orderBy];
}
?>