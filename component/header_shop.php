<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="<?php echo S_NAME ?>css/shop/myreset.css">
  <link rel="stylesheet" href="<?php echo S_NAME ?>css/shop/common.css">
  <link rel="stylesheet" href="<?php echo S_NAME ?>css/shop/table-form.css">
  <link rel="stylesheet" href="<?php echo S_NAME ?>css/shop/shop.css">
<title><?php echo $title ?? '' ?> - shop</title>
</head>

<body>
<div class="container">

<header class="header">
  <div class="header-logo">
    <h1><a href="<?php echo S_NAME ?>shop/top.php"><img src="<?php echo S_NAME ?>shop/imgs/logo.png" alt="Renshu STORE"></a></h1>
  </div>

  <?php if(isset($_SESSION['member_login'])): ?>
    <p class="header-username">
      <a href="<?php echo S_NAME ?>shop/user_menu/view.php">
        <span class="username"><?php echo $_SESSION['member_name'] ?></span>様
      </a>
    </p>
    <nav class="header-nav">
      <ul>
        <li>
          <a href="<?php echo S_NAME ?>shop/user_menu/ordered.php">注文履歴</a>
        </li>
        <li>
          <a href="<?php echo S_NAME ?>shop/cart.php">カート</a>
        </li>
        <li>
          <a href="<?php echo S_NAME ?>shop/logout.php">ログアウト</a>
        </li>
        <li class="hamburger">
          <div class="hambuger-btn">
            <span></span>
            <span></span>
            <span></span>
          </div>
          <div class="hambuger-menu">
            <ul>
              <li>
                <a href="<?php echo S_NAME ?>shop/user_menu/view.php">マイページ</a>
              </li>
              <li>
                <a href="<?php echo S_NAME ?>shop/user_menu/ordered.php">注文履歴</a>
              </li>
              <li>
                <a href="<?php echo S_NAME ?>shop/cart.php">カート</a>
              </li>
              <li>
              <a href="<?php echo S_NAME ?>shop/logout.php">ログアウト</a>
              </li>
            </ul>
          </div>
        </li>
      </ul>
    </nav>
  <?php else: ?>
    <p class="header-username">
      ゲスト様
    </p>
    <nav class="header-nav">
      <ul>
        <li>
          <a href="<?php echo S_NAME ?>shop/user_menu/signup.php">会員登録</a>
        </li>
        <li>
          <a href="<?php echo S_NAME ?>shop/cart.php">カート</a>
        </li>
        <li>
          <a href="<?php echo S_NAME ?>shop/login.php">ログイン</a>
        </li>
        <li class="hamburger">
          <div class="hambuger-btn">
            <span></span>
            <span></span>
            <span></span>
          </div>
          <div class="hambuger-menu">
            <ul>
              <li>
                <a href="<?php echo S_NAME ?>shop/user_menu/signup.php">会員登録</a>
              </li>
              <li>
                <a href="<?php echo S_NAME ?>shop/cart.php">カート</a>
              </li>
              <li>
                <a href="<?php echo S_NAME ?>shop/login.php">ログイン</a>
              </li>
            </ul>
          </div>
        </li>
      </ul>
    </nav>
  <?php endif ?>
</header>

<main class="main">