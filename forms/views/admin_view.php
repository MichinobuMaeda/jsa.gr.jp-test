<?php
require_once('header_view.php');
require_once('footer_view.php');
require_once(dirname(__FILE__) . '/../services/utils.php');

function show_admin($title, $base_url) {
	show_header($title);
	
	echo '<h2>申し込み一覧</h2>';

    $sys = false;

    foreach (getPrivs($_SESSION['email']) as $priv) {
        if ($priv == 'sys') {
            	$sys = true;
        } else {
            echo '<p><a href="' . $base_url . '?event=' . $priv . '&action=list">' . $priv . '</a></p>';
        }
    }
?>
<h2>パスワード変更</h2>
<form method="POST" action="<?php echo $base_url; ?>">
  <input type="hidden" name="action" value="change_password">
  <div class="form-item">
    <label>
      <span class="form-item-label">現在のパスワード</span><br>
      <input type="password" name="curr_password" required>
    </label>
  </div>
  <div class="form-item">
    <label>
      <span class="form-item-label">新しいパスワード</span><br>
      <input type="password" name="password" minlength="8" required>
    </label>
  </div>
  <div>パスワードは8文字以上で、推測されにくいものにしてください。</div>
  <button type="submit">変更</button>
</form>
<?php
    if ($sys) {
?>
<h2>アカウント一覧</h2>
<p>「アカウント一覧」と「アカウント追加」は権限 'sys' で表示されます。権限に設定できるのは 'sys' とイベントIDです。複数の権限を半角のカンマで区切って設定できます。</p>
<?php
        $dbh = connectDB();
  	    $stmt = $dbh->prepare(<<<SQL
SELECT email, priv FROM accounts ORDER BY email
SQL);
  	    $stmt->execute();

  	    foreach ($stmt->fetchAll() as $account) {
?>
<hr>
<form method="POST" action="<?php echo $base_url; ?>">
  <input type="hidden" name="action" value="update_account">
  <input type="hidden" name="email" value="<?php echo $account['email']; ?>">
  <div class="form-item">
    <label>
      <span class="form-item-label">E-mail</span><br>
      <?php echo $account['email']; ?>
    </label>
  </div>
  <div class="form-item">
    <label>
      <span class="form-item-label">権限</span><br>
      <input type="text" name="priv" value="<?php echo $account['priv']; ?>">
    </label>
  </div>
  <div><button type="submit" style="margin: 0;">更新</button></div>
</form>
<form method="POST" action="<?php echo $base_url; ?>">
  <input type="hidden" name="action" value="reset_password">
  <input type="hidden" name="email" value="<?php echo $account['email']; ?>">
  <button type="submit" style="width: 12em;">パスワード再発行</button>
</form>
<?php
	    }
?>
<h2>アカウント追加</h2>
<form method="POST" action="<?php echo $base_url; ?>">
  <input type="hidden" name="action" value="create_account">
  <div class="form-item">
    <label>
      <span class="form-item-label">E-mail</span><br>
      <input type="email" name="email" required>
    </label>
  </div>
  <div class="form-item">
    <label>
      <span class="form-item-label">権限</span><br>
      <input type="text" name="priv" required>
    </label>
  </div>
  <div><button type="submit" style="margin-top: 0;">追加</button></div>
</form>
<?php
    }
?>
<hr>
<form method="POST" action="<?php echo $base_url; ?>">
  <input type="hidden" name="action" value="logout">
  <button type="submit">ログアウト</button>
</form>

<?php
    show_footer();
}
?>
