</main>
<footer class="footer">
  <div class="footer-link">
    <a href="<?php echo S_NAME ?>shop/product.php">商品一覧へ</a>
    <a href="<?php echo S_NAME ?>shop/top.php">トップページへ</a>
  </div>
  <div class="footer-credit">
    <div class="footer-logo">
      <a href="<?php echo S_NAME ?>shop/top.php"><img src="<?php echo S_NAME ?>shop/imgs/logo.png" alt="Renshu STORE"></a>
    </div>
    <div class="footer-copyright"><small>&copy; 2021 Renshu STORE</small></div>
  </div>
</footer>

</div><!-- .container -->
<script src="<?php echo S_NAME ?>script/common.js"></script>
<?php if( URL == '/prophp/shop/order/order.php'): ?>
  <script src="<?php echo S_NAME ?>script/withSignupEvent.js"></script>
<?php endif ?>
</body>
</html>