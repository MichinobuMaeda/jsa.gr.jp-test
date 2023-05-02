<?php
require_once('header_view.php');
require_once('footer_view.php');

function show_form($title, $base_url, $event, $url, $items) {
    show_header($title);

    if (isset($url)) {
	    echo '<p>イベントの情報: <a href="' . $url . '">'. htmlspecialchars($url) . '</a></p>';
    }
?>
<form method="POST" action="<?php echo $base_url; ?>">
  <input type="hidden" name="event" value="<?php echo $event ?>">
  <input type="hidden" name="action" value="form">

<?php
    foreach ($items as $item) {
  	    $required = isset($item['required']) && $item['required'] ? ' required' : '';
  	    $label = isset($item['label']) ? nl2br(htmlspecialchars($item['label'])) : '';

  	    if (isset($item['type']) && $item['type'] == 'message') {
    	    if (isset($item['label'])) {
      		    echo '<div class="form-item form-item-label">' . $label . '</div>';
    	    }
  	    } else if (isset($item['type']) && isset($item['name'])) {
    	    if ($item['type'] == 'text' ||
      		    $item['type'] == 'password' ||
      		    $item['type'] == 'number' ||
      		    $item['type'] == 'email') {
?>
  <div class="form-item">
    <label>
      <span class="form-item-label">
        <?php echo $label; ?><br>
      </span>
      <input type="<?php echo $item['type']; ?>" name="<?php echo $item['name']; ?>"<?php echo $required ?>>
    </label>
  </div>
<?php
      	    } else if ($item['type'] == 'textarea') {
?>
  <div class="form-item">
    <label>
      <span class="form-item-label">
        <?php echo $label; ?><br>
      </span>
    </label>
    <textarea name="<?php echo $item['name']; ?>" rows="4"<?php echo $required ?>></textarea>
  </div>
<?php
      	    } else if ($item['type'] == 'checkbox') {
?>
  <div class="form-item">
    <label>
      <input type="<?php echo $item['type']; ?>" name="<?php echo $item['name']; ?>" value="Y">
      <?php echo $label ? $label : htmlspecialchars($item['name']); ?>
    </label>
  </div>
<?php
      	    } else if ($item['type'] == 'radio') {
?>
  <div class="form-item">
    <div class="form-item-label">
      <?php echo $label; ?>
    </div>
<?php
                foreach ($item['options'] as $option) {
?>
      <label>
        <input type="<?php echo $item['type']; ?>" name="<?php echo $item['name']; ?>" value="<?php echo $option; ?>"<?php echo $required ?>>
        <?php echo htmlspecialchars($option); ?>
      </label>
<?php
                }
?>
  </div>
<?php
      	    }
        }
    }
?>
  <div class="form-item" style="text-align: center;">
    <button type="submit">保存</button>
  </div>
</form>

<?php
    show_footer();
}
?>
