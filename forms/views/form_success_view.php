<?php
require_once('header_view.php');
require_once('footer_view.php');

function show_form_success($title, $url) {
	show_header($title);

	if (isset($url)) {
		echo '<p>イベントの情報: <a href="' . $url . '">'. htmlspecialchars($url) . '</a></p>';
	}
?>
<p>登録を受け付けました。</p>

<?php
    show_footer();
}
?>
