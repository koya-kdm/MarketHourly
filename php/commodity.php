<?php
/*===============================
  MarketHourly - commodity
=================================*/

/*---------------------------
  定義
-----------------------------*/

// アプリ格納場所
define('APPLICATION_PHP_PATH', dirname(__FILE__));

// インクルード
require_once APPLICATION_PHP_PATH . '/config.php';  // $twitterAuth is defined here
require_once APPLICATION_PHP_PATH . '/class/EmojiManager.php';
require_once APPLICATION_PHP_PATH . '/class/Retriever.php';
require_once APPLICATION_PHP_PATH . '/class/Tweeter.php';

// タイムゾーン
date_default_timezone_set('Asia/Tokyo');

// インスタンス生成
$retriever = new Retriever(); // 株価取得クラス
$tweeter   = new Tweeter();   // ツイート投稿クラス


/*---------------------------
  処理
-----------------------------*/

// コモディティの取得
$commodities = $retriever->retrieveCommodities();

// ツイートの作成
$tweet = $tweeter->createTweetOfCommodities($commodities);

// デバッグ
echo $tweet . PHP_EOL;

// ツイートの投稿
$tweeter->postTweet($twitterAuth, $tweet);

?>
