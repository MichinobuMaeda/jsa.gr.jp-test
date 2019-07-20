// このスクリプトのために jQuery が必要。

var selectedPoster = null;

// リストの先頭から n 項目だけを表示する。
function foldItems(target, n) {
  var count = 0;
  $(target).each(function () {
    ++ count;
    if (n < count) {
      $(this).hide();
    }
  });
}  

// 初期設定：トップページ
function onDocumentReadyTop() {

  // マウスがポスターのアイコン上に入ったときの処理
  $('.poster-item').on('mouseover', function (event) {
    if (selectedPoster != this) {
      selectedPoster = this;
      $('#poster-full').attr('src', $(this).children('img').attr('src'));
      $('#poster-full').attr('alt', $(this).children('img').attr('alt'));
      $('#poster-full').css('top',  $(this).position().top - 200);
      $('#poster-full').css('left', $('#poster-area').position().left -
                                    $('#poster-full').width() - 30);
      $('#poster-full').show();
    }
  });

  // マウスがポスターのアイコンから出たときの処理
  $('.poster-item').on('mouseout', function (event) {
    if (selectedPoster == this) {
      selectedPoster = null;
        $('#poster-full').hide();
    }
  });

  // Short Term 「続きを見る」/「折りたたむ」ボタンクリック時
  $('#show-all-short-term').on('click', function () {
    // 「続きを見る」ボタンの場合
    if ($(this).text() == "▼ 続きを見る") {
      // 全ての項目を表示する。
      $('#short-term-list > li').each(function () {
        $(this).show();
      });
      $(this).text("▲ 折りたたむ");
    // 折りたたむ」ボタンの場合
    } else {
      // 先頭から n 項目だけを表示する。 ( スマホ : 10, PC : 20 )
      foldItems('#short-term-list > li', mobileMedia ? 10 : 20);
      $(this).text("▼ 続きを見る");
    }
  });

}

// ウィンドウサイズに応じた設定：トップページ
function onWindowReizeTop() {

  // Short Term の先頭から n 項目だけを表示する。 ( スマホ : 10, PC : 20 )
  foldItems('#short-term-list > li', mobileMedia ? 10 : 20);
}
