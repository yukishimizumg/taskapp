<?php

// 関数ファイルを読み込む
require_once __DIR__ . '/functions.php';

// index.php から渡された id を受け取る
$id = filter_input(INPUT_GET, 'id');

// タスク削除処理の実行
deleteTask($id);