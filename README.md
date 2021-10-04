# ECサイトPHP版 RenshuSTORE

## 概要

管理者は管理画面で商品やスタッフ情報を編集でき、利用者はストアページで商品をカートに入れて購入できるECサイト。

## 使用技術

- PHP 7
- MySQL
- HTML5 / CSS3

## 機能

### ストアページ
- 会員機能・ログイン機能
  - 会員登録・情報編集・退会
  - 注文履歴表示ページ
  - レビュー履歴表示ページ
- 商品表示
  - ランキング
  - 商品一覧
  - 商品個別
- レビュー機能
- カート機能(ゲスト用, 登録ユーザー用)
  - 商品の追加ページ, おすすめ商品
  - カートの内容の変更・クリア
- 注文機能
  - 注文情報入力フォーム
  - 会員用・住所入力省略機能

### 管理ページ
- 商品・スタッフ・会員それぞれの情報表示・編集機能
  - 商品画像データの保存、呼び出し
- 注文データの一覧表示、絞り込み、抽出表示
- 表示中データのCSV出力

### その他パーツ
- ペジネーション
- 並べ替え・絞り込み
