<?php

// 関数ファイルを読み込む
require_once __DIR__ . '/functions.php';

// データベースに接続
$dbh = connectDb();

/* 未完了タスクのレコードを取得
---------------------------------------------------*/
// status が notyet のデータを取得
$sql = "SELECT * FROM tasks WHERE status = 'notyet'";

// プリペアドステートメントの準備
$stmt = $dbh->prepare($sql);

// プリペアドステートメントの実行
$stmt->execute();

// 結果の受け取り
$notyet_tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* 完了タスクのレコードを取得
---------------------------------------------------*/
// status が done のデータを取得
$sql2 = "SELECT * FROM tasks WHERE status = 'done'";

// プリペアドステートメントの準備
$stmt = $dbh->prepare($sql2);

// プリペアドステートメントの実行
$stmt->execute();

// 結果の受け取り
$done_tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* タスク登録処理
---------------------------------------------------*/
// 初期化
$title = '';
$errors = []; // エラーチェック用の配列

// リクエストメソッドの判定
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // フォームに入力されたデータを受け取る
    $title = filter_input(INPUT_POST, 'title');
    
    // バリデーション
    if ($title == '') {
        $errors[] = 'タスク名を入力してください';
    }
    
    // エラーチェック
    if (empty($errors)) {
        // エラーが 1 つもなければレコードを追加
        $sql = <<<EOM
        INSERT INTO
            tasks
            (title)
        VALUES
            (:title)
        EOM;
        
        // プリペアドステートメントの準備
        $stmt = $dbh->prepare($sql);
        
        // パラメータのバインド
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        
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
    <title>My Tasks</title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="https://unpkg.com/ress@3.0.0/dist/ress.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="wrapper">
        <div class="new-task">
            <h1>My Tasks</h1>
            <!-- エラーが発生した場合、エラーメッセージを出力 -->
            <?php if ($errors): ?>
                <ul class="errors">
                    <?php foreach ($errors as $error): ?>
                        <li><?= h($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <form action="" method="post">
                <input type="text" name="title" placeholder="タスクを入力してください">
                <input type="submit" value="登録" class="btn submit-btn">
            </form>
        </div>
        <div class="notyet-task">
            <h2>未完了タスク</h2>
            <ul>
                <?php foreach ($notyet_tasks as $task): ?>
                    <li>
                        <!-- done.php へのパスを追記 -->
                        <a href="done.php?id=<?= h($task['id']) ?>" class="btn done-btn">完了</a>
                        <!-- edit.php へのパスを追記 -->
                        <a href="edit.php?id=<?= h($task['id']) ?>" class="btn edit-btn">編集</a>
                        <!-- delete.php へのパスを追記 -->
                        <a href="delete.php?id=<?= h($task['id']) ?>" class="btn delete-btn">削除</a>
                        <?= h($task['title']) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <hr>
        <div class="done-task">
            <h2>完了タスク</h2>
            <ul>
                <?php foreach ($done_tasks as $task): ?>
                    <li>
                        <?= h($task['title']) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</body>
</html>