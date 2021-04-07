<?php

// 関数ファイルを読み込む
require_once __DIR__ . '/functions.php';

// index.php から渡された id を受け取る
$id = filter_input(INPUT_GET, 'id');

// データベースに接続
$dbh = connectDb();

/* 受け取った id のレコードを取得
---------------------------------------------------*/
// $id を使用してデータを取得
$sql = 'SELECT * FROM tasks WHERE id = :id';

// プリペアドステートメントの準備
$stmt = $dbh->prepare($sql);

// パラメータのバインド
$stmt->bindParam(':id', $id, PDO::PARAM_INT);

// プリペアドステートメントの実行
$stmt->execute();

// 結果の取得
$task = $stmt->fetch(PDO::FETCH_ASSOC);

/* タスク更新処理
---------------------------------------------------*/
// 初期化
$errors = []; // エラーチェック用の配列
$title = '';

// リクエストメソッドの判定
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // フォームに入力されたデータを受け取る
    $title = filter_input(INPUT_POST, 'title');

    // バリデーション
    if ($title == '') {
        $errors[] = 'タスク名を入力してください';
    }

    if ($title == $task['title']) {
        $errors[] = 'タスク名が変更されていません';
    }

    // エラーチェック
    if (empty($errors)) {

        // エラーが 1 つもなければ $id を使用してデータを更新
        $sql = <<<EOM
        UPDATE
            tasks
        SET
            title = :title
        WHERE
            id = :id
        EOM;

        // プリペアドステートメントの準備
        $stmt = $dbh->prepare($sql);

        // パラメータのバインド
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // プリペアドステートメントの実行
        $stmt->execute();
        
        // index.php にリダイレクト
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- CSS -->
    <link rel="stylesheet" href="https://unpkg.com/ress@3.0.0/dist/ress.min.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>My Tasks - 更新</title>
</head>
<body>
    <div class="wrapper">
        <h2>タスク更新</h2>
        <!-- エラーが発生した場合、エラーメッセージを出力 -->
        <?php if ($errors): ?>
            <ul class="errors">
                <?php foreach ($errors as $error): ?>
                    <li><?= h($error) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <form action="" method="post">
            <input type="text" name="title" value="<?= h($task['title']) ?>">
            <input type="submit" value="更新" class="btn submit-btn">
        </form>
        <a href="index.php" class="btn return-btn">戻る</a>
    </div>
</body>
</html>