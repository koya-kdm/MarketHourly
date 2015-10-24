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
  = array(MarketManager::FX => array(0 => new Asset(EmojiManager::getDoller(),  'USDJPY=X', '円', 2, MarketManager::FX, false, $retriever::SRC_YAHOO ),
                                     1 => new Asset(EmojiManager::getEuro(),    'EURJPY=X', '円', 2, MarketManager::FX, false, $retriever::SRC_YAHOO ),),
          MarketManager::JP => array(0 => new Asset('日経',                        '^N225', '円', 0, MarketManager::JP,  true, $retriever::SRC_NIKKEI),),
          MarketManager::HK => array(0 => new Asset('香港',                     '13414271', 'pt', 0, MarketManager::HK,  true, $retriever::SRC_GOOGLE),),
          MarketManager::SH => array(0 => new Asset('上海',                      '7521596', 'pt', 0, MarketManager::SH,  true, $retriever::SRC_GOOGLE),),
          MarketManager::UK => array(0 => new Asset(  '英',                     '12590587', 'pt', 0, MarketManager::UK,  true, $retriever::SRC_GOOGLE),),
          MarketManager::GM => array(0 => new Asset(  '独',                     '14199910', 'pt', 0, MarketManager::GM,  true, $retriever::SRC_GOOGLE),),
          MarketManager::US => array(0 => new Asset('ダウ',                       '983582', 'pt', 0, MarketManager::US,  true, $retriever::SRC_GOOGLE),
                                     1 => new Asset('ナス',                     '13756934', 'pt', 0, MarketManager::US,  true, $retriever::SRC_GOOGLE),),
         );

// アセット再定義（コマンドライン引数がある場合）
// * 引数がない場合は全マーケットのアセットをつぶやくが、
// 　引数でマーケット指定がある場合は、そのマーケットのアセットのみをつぶやく。
if ($argc > 1)
{
  $redefined = array();
  
  // つぶやきカスタマイズ
  $tweeter->disableOrder();
  $tweeter->disableClock();
  $tweeter->setHeader('【' . date('G:i') . '】');
  
  // 対象アセット定義の抽出
  for ($i = 0; $i < $argc; $i++)
  {
    if (MarketManager::isValid($argv[$i]))
    {
      $redefined[$argv[$i]] = $assetsByMarket[$argv[$i]];
      
      if ($argv[$i] == MarketManager::JP)
      {
        for ($j = 0; $j < count($redefined[$argv[$i]]); $j++)
        $redefined[$argv[$i]][$j]->setDisplaysChangeByPoint(true);
      }
      
    }
  }
  
  $assetsByMarket = $redefined;
}

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
