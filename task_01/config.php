<?php

// 接続に必要な情報を定数として定義
define('DSN', 'mysql:host=db;dbname=task_app;charset=utf8');
define('USER', 'testuser');
define('PASSWORD', '9999');

// エラーメッセージを定数として定義
define('MSG_TITLE_REQUIRED', 'タスク名を入力してください');
define('MSG_TITLE_NO_CHANGE', 'タスク名が変更されていません');