<?php

// 関数ファイルを読み込む
require_once __DIR__ . '/functions.php';

// index.php から渡された id を受け取る
$id = filter_input(INPUT_GET, 'id');

// 受け取った id のレコードを取得
$task = findById($id);

/* タスク更新処理
---------------------------------------------*/
// 初期化
$title = '';

// リクエストメソッドの判定
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // フォームに入力されたデータを受け取る
    $title = filter_input(INPUT_POST, 'title');

    // バリデーション
    $errors = updateValidate($title, $task);
    
    // エラーチェック
    if (empty($errors)) {
        // タスク更新処理の実行
        updateTask($id, $title);
    }
}
?>
<!DOCTYPE html>
<html lang="ja">

<?php include __DIR__ . "/_head.php"; ?>

<body>
    <div class="wrapper">
        <h2>タスク更新</h2>
        <!-- エラーが発生した場合、エラーメッセージを出力 -->
        <?php if ($errors) echo (createErrMsg($errors)); ?>

        <form action="" method="post">
            <input type="text" name="title" value="<?= h($task['title']) ?>">
            <input type="submit" value="更新" class="btn submit-btn">
        </form>
        <a href="index.php" class="btn return-btn">戻る</a>
    </div>
</body>
</html>