// このスクリプトのために jQuery が必要。

// 768ドット未満ならスマホ用の設定を適用する。
//
// この処理のために以下の設定が必要
// HTML
//    <!-- media 判定用 -->
//    <div id="viewType" style="display:none"></div>
// CSS
// /* media 判定用 */
// @media (min-width: 768px)
//     #viewType { width: 1px; }
// }

var mobileMedia = null;

// return モードの変更の有無
function getWindowResized() {
  var modeNew =
    ! navigator.userAgent.match(/msie\s+[5-8]/i) &&
    ($("#viewType").width() != 1);

  if (mobileMedia === modeNew) {
    return false;
  } else {
    mobileMedia = modeNew;
    return true;
  }
}

// ウィンドウサイズに応じた設定
function onWindowReize () {

  // ウィンドウの幅からスマホ用設定の適用の有無を判断する。
  // モードの変更が無ければ何もしない。
  if (!getWindowResized()) { return; }

  // ウィンドウサイズに応じた設定：トップページ
  onWindowReizeTop();
}

// 初期設定
$( document ).ready( function () {

  // ウィンドウサイズに応じた設定
  onWindowReize();

  // ウィンドウのサイズ変更時に、ウィンドウサイズに応じた設定をやり直す。
  window.onresize = function () {
    onWindowReize();
  }

  // 折りたたみ見出し ( class="foldtitle" ) クリック時の処理。
  $('.fold-title').on('click', function (event) {
    var body = $(this).siblings('.fold-body');
    if ($(body).hasClass('hidden-phone')) {
      $(body).removeClass('hidden-phone');
    } else {
      $(body).addClass('hidden-phone');
    }
  });

  // 初期設定：トップページ
  onDocumentReadyTop();
});
