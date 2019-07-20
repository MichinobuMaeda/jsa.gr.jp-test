// 指定した年月日の 7日後まで New を表示する。
function writeNew(y, m, d) {
  var modDate = new Date(y, m-1, d);
  var nowDate = new Date();
  var newDays = 7;
  if ((nowDate-modDate)/(1000*60*60*24) < 7) {
    document.write('<span class="label label-warning">New</span>');
  }
}
