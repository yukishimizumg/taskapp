<?php

// 設定ファイルを読み込む
require_once __DIR__ . '/config.php';

// データベース接続
function connectDb()
{
    try {
        return new PDO(
            DSN,
            USER,
            PASSWORD,
            [PDO::ATTR_ERRMODE =>
            PDO::ERRMODE_EXCEPTION]
        );
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit;
    }
}

// エスケープ処理
function h($str)
{
    // ENT_QUOTES: シングルクオートとダブルクオートを共に変換する。
    return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}

// 未完了タスクのレコードを取得
function findNotyetTaskByStatus()
{
    // データベースに接続
    $dbh = connectDb();

    // status が notyet のデータを取得
    $sql = "SELECT * FROM tasks WHERE status = 'notyet'";

    // プリペアドステートメントの準備
    $stmt = $dbh->prepare($sql);

    // プリペアドステートメントの実行
    $stmt->execute();

    // 結果の受け取り
    $notyet_tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $notyet_tasks;
}

// 完了タスクのレコードを取得
function findDoneTaskByStatus()
{
    // データベースに接続
    $dbh = connectDb();

    // status が done のデータを取得
    $sql2 = "SELECT * FROM tasks WHERE status = 'done'";

    // プリペアドステートメントの準備
    $stmt = $dbh->prepare($sql2);

    // プリペアドステートメントの実行
    $stmt->execute();

    // 結果の受け取り
    $done_tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $done_tasks;
}

// タスク登録時のバリデーション
function insertValidate($title)
{
    // 初期化
    $errors = []; // エラーチェック用の配列

    if ($title == '') {
        $errors[] = MSG_TITLE_REQUIRED;
    }

    return $errors;
}

// タスク登録
function insertTask($title)
{
    // データベースに接続
    $dbh = connectDb();

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

// タスク完了
function updateStatusToDone($id)
{
    // データベースに接続
    $dbh = connectDb();

    // $id を使用してデータを更新
    $sql = <<<EOM
    UPDATE
        tasks
    SET
        status = 'done'
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
}

// 受け取った id のレコードを取得
function findById($id)
{
    // データベースに接続
    $dbh = connectDb();

    // 初期化
    $task = [];

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

    return $task;
}

// タスク更新時のバリデーション
function updateValidate($title, $task)
{
    // 初期化
    $errors = []; // エラーチェック用の配列

    if ($title == '') {
        $errors[] = MSG_TITLE_REQUIRED;
    }

    if ($title == $task['title']) {
        $errors[] = MSG_TITLE_NO_CHANGE;
    }

    return $errors;
}

// タスク更新
function updateTask($id, $title)
{
    // データベースに接続
    $dbh = connectDb();

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

// エラーメッセージ作成
function createErrMsg($errors)
{
    $err_msg .= "<ul class=\"errors\">\n";

    foreach ((array)$errors as $error) {
        $err_msg .= "<li>" . h($error) . "</li>\n";
    }

    $err_msg .= "</ul>\n";

    return $err_msg;
}

// タスク削除
function deleteTask($id)
{
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
}