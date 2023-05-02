<?php
function show_header($title) {
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?php echo htmlspecialchars($title); ?></title>
  <style>
div, p, input, button {
  font-size: 18px;
}
.content {
  max-width: 960px;
}
input[type=text],
input[type=password],
input[type=number],
input[type=email],
textarea {
  width: 98%;
  max-width: 640px;
}
input[type=text],
input[type=password],
input[type=number],
input[type=email] {
  height: 2em;
}
button {
  width: 8em;
  height: 2em;
  margin: 1em 0 1em 0;
}
.form-item {
  margin: 1em 0 1em 0;
}
.form-item-label {
  color: darkred;
}
table {
  border-collapse: collapse;
}
th, td {
  padding: 0.25em;
  border: solid 1px gray;
}
th {
  background: ivory;
}
@media only screen and (max-width: 639px) {
  .fixed-head-table {
    overflow: auto;
    width: 640px;
    height: 640px;
  }
}
@media only screen and (min-width: 640px) {
  .fixed-head-table {
    overflow: auto;
    width: 632px;
    height: 640px;
  }
}
@media only screen and (min-width: 1024px) {
  .fixed-head-table {
    overflow: auto;
    width: 1016px;
    height: 640px;
  }
}
@media only screen and (min-width: 1280px) {
  .fixed-head-table {
    overflow: auto;
    width: 1272px;
    height: 640px;
  }
}
.fixed-head-table thead th {
  position: sticky;
  top: 0;
  z-index: 2;
}
.fixed-head-table thead th:nth-child(1),
.fixed-head-table tbody td:nth-child(1) {
  position: -webkit-sticky;
  position: sticky;
  left: 0px;
  width: 128px;
  min-width: 128px;
  max-width: 128px;
}
.fixed-head-table tbody td:nth-child(1) {
  background-color: white;
  z-index: 1;
}
.fixed-head-table thead th:nth-child(1) {
  background-color: bisque;
  z-index: 3;
}
@media only screen and (min-width: 1024px) {
  .fixed-head-table thead th:nth-child(2),
  .fixed-head-table tbody td:nth-child(2) {
    position: -webkit-sticky;
    position: sticky;
    left: 128px;
    width: 192px;
    min-width: 192px;
    max-width: 192px;
    line-break: anywhere;
  }
  .fixed-head-table tbody td:nth-child(2) {
    background-color: white;
    z-index: 1;
  }
  .fixed-head-table thead th:nth-child(2) {
    background-color: bisque;
    z-index: 3;
  }
}
tr.deleted,
.fixed-head-table tbody tr.deleted td:nth-child(1),
.fixed-head-table tbody tr.deleted td:nth-child(2) {
  background-color: lightgray;
}
  </style>
</head>
<body>
<h1><?php echo isset($title) ? htmlspecialchars($title) : '管理者メニュー'; ?></h1>
<div class="content">

<?php
}
?>