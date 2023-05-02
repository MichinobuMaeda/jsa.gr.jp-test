<?php
require_once('header_view.php');
require_once('footer_view.php');

function show_login($title) {
	show_header($title);
?>
<h2>ログイン</h2>
<p style="color: red;"><?php echo (isset($_GET['message']) && $_GET['message'] == 'failed') ? 'ログインできませんでした。' : ''; ?></p>
<form method="POST" action="<?php echo $base_url; ?>">
  <input type="hidden" name="action" value="login">
  <div class="form-item">
    <label>
      <span class="form-item-label">
        メールアドレス
      </span>
      <br>
      <input type="email" name="email" required style="max-width: 480px;">
    </label>
  </div>
  <div class="form-item">
    <label>
      <span class="form-item-label">
        パスワード
      </span>
      <br>
      <input type="password" name="password" required style="max-width: 480px;">
    </label>
  </div>
  <div class="form-item">
    <button type="submit">ログイン</button>
  </div>
</form>

<?php
    show_footer();
}
?>
