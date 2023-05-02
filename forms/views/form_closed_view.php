<?php
require_once('header_view.php');
require_once('footer_view.php');

function show_form_closed($title, $url) {
	show_header($title);

    if (isset($url)) {
	    echo '<p>イベントの情報: <a href="' . $url . '">'. htmlspecialchars($url) . '</a></p>';
    }
?>
<p>受付を終了しました。</p>

<?php
    show_footer();
}
?>
