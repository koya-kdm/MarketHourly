<?php
/*===============================
  MarketHourly メイン
=================================*/

/*---------------------------
  定義
-----------------------------*/

// アプリ格納場所
define('APPLICATION_PHP_PATH', dirname(__FILE__));

// インクルード
require_once APPLICATION_PHP_PATH . '/config.php';  // $twitterAuth is defined here
require_once APPLICATION_PHP_PATH . '/class/Asset.php';
require_once APPLICATION_PHP_PATH . '/class/MarketManager.php';
require_once APPLICATION_PHP_PATH . '/class/EmojiManager.php';
require_once APPLICATION_PHP_PATH . '/class/Retriever.php';
require_once APPLICATION_PHP_PATH . '/class/Tweeter.php';

// タイムゾーン
date_default_timezone_set('Asia/Tokyo');

// インスタンス生成
$retriever = new Retriever(); // 株価取得クラス
$tweeter   = new Tweeter();   // ツイート投稿クラス


// アセット定義
$assetsByMarket
  = array(MarketManager::FX => array(0 => new Asset( 'USD',   'USDJPY=X', '円', 2, MarketManager::FX, false, false, null     ),
                                     1 => new Asset( 'EUR',   'EURJPY=X', '円', 2, MarketManager::FX, false, false, null     ),),
          MarketManager::JP => array(0 => new Asset('日経',      '^N225', '円', 0, MarketManager::JP,  true, false, null     ),),
          MarketManager::HK => array(0 => new Asset('香港',       '^HSI', 'pt', 0, MarketManager::HK,  true, false, null     ),),
          MarketManager::SH => array(0 => new Asset('上海',  '000001.SS', 'pt', 0, MarketManager::SH,  true,  true, '7521596'),),
          MarketManager::UK => array(0 => new Asset(  '英',      '^FTSE', 'pt', 0, MarketManager::UK,  true, false, null     ),),
          MarketManager::GM => array(0 => new Asset(  '独',     '^GDAXI', 'pt', 0, MarketManager::GM,  true, false, null     ),),
          MarketManager::US => array(0 => new Asset('ダウ',       '^DJI', 'pt', 0, MarketManager::US,  true,  true, '983582' ),
                                     1 => new Asset('ナス',      '^IXIC', 'pt', 0, MarketManager::US,  true, false, null     ),),
         );

// アセット再定義
if ($argc > 1)
{
  $redefined = array();
  
  $markets = array(MarketManager::FX,
                   MarketManager::JP,
                   MarketManager::HK,
                   MarketManager::SH,
                   MarketManager::UK,
                   MarketManager::GM,
                   MarketManager::US,
                  );
  
  for ($i = 1; $i <= $argc; $i++)
  {
    if (in_array($argv[$i], $markets))
    {
      array_push($redefined, $assetsByMarket[$argv[$i]]);
    }
  }
  
  $assetsByMarket = $redefined;
}

// アセットタイトルの書換え
$assetsByMarket[MarketManager::FX][0]->setTitle(EmojiManager::getDoller());
$assetsByMarket[MarketManager::FX][1]->setTitle(EmojiManager::getEuro());

/*---------------------------
  処理
-----------------------------*/

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
