<?php

//===============================
// 定義
//===============================

// アプリ格納場所
$applicationPhpPath = dirname(__FILE__);

// インクルード
require_once $applicationPhpPath . '/config.php';
require_once $applicationPhpPath . '/class/Asset.php';
require_once $applicationPhpPath . '/class/MarketManager.php';
require_once $applicationPhpPath . '/class/EmojiManager.php';
require_once $applicationPhpPath . '/class/Retriever.php';
require_once $applicationPhpPath . '/class/Tweeter.php';

// OAuthスクリプトの読込み
require $applicationPhpPath . '/lib/twitteroauth/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

// タイムゾーン
date_default_timezone_set('Asia/Tokyo');

// インスタンス生成
$mm = new MarketManager();    // マーケット管理クラス
$em = new EmojiManager();     // 絵文字管理クラス
$retriever = new Retriever(); // 絵文字管理クラス
$tweeter   = new Tweeter($mm, $em);

// アセット定義
$assetsByMarket = array($mm::FX => array(0 => new Asset( 'USD',   'USDJPY=X', '円', 2, $mm::FX, false, false, null     ),
                                         1 => new Asset( 'EUR',   'EURJPY=X', '円', 2, $mm::FX, false, false, null     ),),
                        $mm::JP => array(0 => new Asset('日経',      '^N225', '円', 0, $mm::JP,  true, false, null     ),),
                        $mm::HK => array(0 => new Asset('香港',       '^HSI', 'pt', 0, $mm::HK,  true, false, null     ),),
                        $mm::SH => array(0 => new Asset('上海',  '000001.SS', 'pt', 0, $mm::SH,  true,  true, '7521596'),),
                        $mm::EU => array(0 => new Asset(  '英',      '^FTSE', 'pt', 0, $mm::EU,  true, false, null     ),
                                         1 => new Asset(  '独',     '^GDAXI', 'pt', 0, $mm::EU,  true, false, null     ),),
                        $mm::US => array(0 => new Asset('ダウ',       '^DJI', 'pt', 0, $mm::US,  true,  true, '983582' ),
                                         1 => new Asset('ナス',      '^IXIC', 'pt', 0, $mm::US,  true, false, null     ),),
                       );

// アセットタイトルの書換え
$assetsByMarket[$mm::FX][0]->setTitle($em->getEmojiOfCurrency('dol'));
$assetsByMarket[$mm::FX][1]->setTitle($em->getEmojiOfCurrency('eur'));

//===============================
// メイン
//===============================

// 株価の取得
$retriever->retrieveStockPrice($assetsByMarket);

// ツイートの作成
$tweet = $tweeter->createTweet($assetsByMarket);

// デバッグ
//print_r($assetsByMarket);
echo $tweet . PHP_EOL;

// ツイートの投稿
$tweeter->postTweet($twitterAuth, $tweet);

?>
