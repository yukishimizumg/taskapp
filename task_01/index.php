<?php

// 関数ファイルを読み込む
require_once __DIR__ . '/functions.php';

/* タスク照会
---------------------------------------------*/
// 未完了タスクの取得
$notyet_tasks = findNotyetTaskByStatus();

// 完了タスクの取得
$done_tasks = findDoneTaskByStatus();

/* タスク登録
---------------------------------------------*/
// 初期化
$title = '';

// リクエストメソッドの判定
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // フォームに入力されたデータを受け取る
    $title = filter_input(INPUT_POST, 'title');
    
    // バリデーション
    $errors = insertValidate($title);
    
    // エラーチェック
    if (empty($errors)) {
        // タスク登録処理の実行
        insertTask($title);
    }
}
?>
<!DOCTYPE html>
<html lang="ja">

<?php include __DIR__ . "/_head.php"; ?>

<body>
    <div class="wrapper">
        <div class="new-task">
            <h1>My Tasks</h1>
            <!-- エラーが発生した場合、エラーメッセージを出力 -->
            <?php if ($errors) echo (createErrMsg($errors)); ?>

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