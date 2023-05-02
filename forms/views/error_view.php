<?php
require_once('header_view.php');
require_once('footer_view.php');

function show_error($title, $url) {
	show_header($title);

	if (isset($url)) {
		echo '<p>イベントの情報: <a href="' . $url . '">'. htmlspecialchars($url) . '</a></p>';
	}
?>
<p>エラーにより入力データを保存できませんでした。</p>
<p>再度試してうまくいかない場合は事務局までお知らせください。</p>

<?php
    show_footer();
}
?>
