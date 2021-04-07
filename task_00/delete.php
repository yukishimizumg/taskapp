<?php

// 関数ファイルを読み込む
require_once __DIR__ . '/functions.php';

// index.php から渡された id を受け取る
$id = filter_input(INPUT_GET, 'id');

// データベースに接続
$dbh = connectDb();

// $id を使用してデータを削除
$sql = <<<EOM
DELETE FROM
    tasks
WHERE
    id = :id
EOM;

// プリペアドステートメントの準備
$stmt = $dbh->prepare($sql);

// パラメータのバインド
$stmt->bindParam(':id', $id, PDO::PARAM_INT);

// プリペアドステートメントの実行
$stmt->execute();

// index.php にリダイレクト
header('Location: index.php');
exit;