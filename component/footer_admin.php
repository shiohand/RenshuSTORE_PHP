</main>
<footer class="footer">

  <?php if (isset($_SESSION['staff_login'])) : ?>
    <nav class="footer-link">
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
  <?php endif ?>

  <div class="footer-credit"><small>&copy; 2021 Renshu STORE</small></div>
</footer>

</div><!-- .container -->
</body>
</html>