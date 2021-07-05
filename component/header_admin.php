<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="<?php echo S_NAME ?>css/admin/myreset.css">
  <link rel="stylesheet" href="<?php echo S_NAME ?>css/admin/common.css">
  <link rel="stylesheet" href="<?php echo S_NAME ?>css/admin/table-form.css">
  <link rel="stylesheet" href="<?php echo S_NAME ?>css/admin/admin.css">
  <title><?php echo $title ?? '' ?> - admin</title>
</head>

<body>
<div class="container">

<header class="header">
  <div class="row-first">

    <h1 class="h1"><a href="<?php echo S_NAME ?>admin/top.php">admin</a></h1>

    <ul class="header-list">
      <?php if (isset($_SESSION['staff_login'])) : ?>
        <li class="staffname">
          ログイン:<?php echo $_SESSION['staff_name'] ?>
        </li>
        <li>
          <a class="btn-login" href="<?php echo S_NAME ?>admin/logout.php">ログアウト</a>
        </li>
      <?php else : ?>
        <li>
          <a class="btn-logout" href="login.php">ログイン</a>
        </li>
      <?php endif ?>
    </ul>
  </div>
  <?php if (isset($_SESSION['staff_login'])) : ?>
    <div class="row-second">
      <nav class="global-nav">
        <ul>
          <li>
            <a href="<?php echo S_NAME ?>admin/staff/top.php">スタッフ管理</a>
          </li>
          <li>
            <a href="<?php echo S_NAME ?>admin/product/top.php">商品管理</a>
          </li>
          <li>
            <a href="<?php echo S_NAME ?>admin/member/top.php">会員管理</a>
          </li>
          <li>
            <a href="<?php echo S_NAME ?>admin/order/top.php">注文管理</a>
          </li>
        </ul>
      </nav>
    </div>
  <?php endif ?>
</header>

<main class="main">