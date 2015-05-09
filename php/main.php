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

// OAuthスクリプトの読込み
require $applicationPhpPath . '/lib/twitteroauth/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

// タイムゾーン
date_default_timezone_set('Asia/Tokyo');

// マーケット管理クラス
$mm = new MarketManager();

// 絵文字管理クラス
$em = new EmojiManager();

// 絵文字管理クラス
$retriever = new /Retriever();

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

// 各時間における表示順
$order = array( 0 => array($mm::FX, $mm::US, $mm::EU, $mm::JP, $mm::SH, $mm::HK),
                1 => array($mm::FX, $mm::US, $mm::EU, $mm::JP, $mm::SH, $mm::HK),
                2 => array($mm::FX, $mm::US, $mm::EU, $mm::JP, $mm::SH, $mm::HK),
                3 => array($mm::FX, $mm::US, $mm::EU, $mm::JP, $mm::SH, $mm::HK),
                4 => array($mm::FX, $mm::US, $mm::EU, $mm::JP, $mm::SH, $mm::HK),
                5 => array($mm::FX, $mm::US, $mm::EU, $mm::JP, $mm::SH, $mm::HK),
                6 => array($mm::FX, $mm::US, $mm::EU, $mm::JP, $mm::SH, $mm::HK),
                7 => array($mm::FX, $mm::US, $mm::EU, $mm::JP, $mm::SH, $mm::HK),
                8 => array($mm::FX, $mm::US, $mm::EU, $mm::JP, $mm::SH, $mm::HK),
                9 => array($mm::FX, $mm::JP, $mm::US, $mm::EU, $mm::SH, $mm::HK),
               10 => array($mm::FX, $mm::JP, $mm::US, $mm::EU, $mm::SH, $mm::HK),
               11 => array($mm::FX, $mm::JP, $mm::SH, $mm::HK, $mm::US, $mm::EU),
               12 => array($mm::FX, $mm::JP, $mm::SH, $mm::HK, $mm::US, $mm::EU),
               13 => array($mm::FX, $mm::JP, $mm::SH, $mm::HK, $mm::US, $mm::EU),
               14 => array($mm::FX, $mm::JP, $mm::SH, $mm::HK, $mm::US, $mm::EU),
               15 => array($mm::FX, $mm::JP, $mm::SH, $mm::HK, $mm::US, $mm::EU),
               16 => array($mm::FX, $mm::JP, $mm::SH, $mm::HK, $mm::EU, $mm::US),
               17 => array($mm::FX, $mm::EU, $mm::SH, $mm::HK, $mm::JP, $mm::US),
               18 => array($mm::FX, $mm::EU, $mm::SH, $mm::HK, $mm::JP, $mm::US),
               19 => array($mm::FX, $mm::EU, $mm::SH, $mm::HK, $mm::JP, $mm::US),
               20 => array($mm::FX, $mm::EU, $mm::SH, $mm::HK, $mm::JP, $mm::US),
               21 => array($mm::FX, $mm::EU, $mm::SH, $mm::HK, $mm::JP, $mm::US),
               22 => array($mm::FX, $mm::EU, $mm::SH, $mm::HK, $mm::JP, $mm::US),
               23 => array($mm::FX, $mm::US, $mm::EU, $mm::JP, $mm::SH, $mm::HK),
               );






// アセットタイトルの書換え
$assetsByMarket[$mm::FX][0]->setTitle($em->getEmojiOfCurrency('dol'));
$assetsByMarket[$mm::FX][1]->setTitle($em->getEmojiOfCurrency('eur'));

//===============================
// メイン
//===============================
// URLの作成
$url = $retriever->createUrl($yahooParams, $assetsByMarket);

// 株価の取得
$retriever->retrieveStockPrice($url, $assetsByMarket);

// ツイートの作成
$tweet = createTweet($assetsByMarket);

// Debug
//print_r($assetsByMarket);
echo $tweet . PHP_EOL;

// ツイートの投稿
//postTweet($twitterAuth, $tweet);

//===============================
// 関数
//===============================

/*--------------------
  createTweet
---------------------*/
function createTweet($assetsByMarket)
{
  global $order;
  global $mm;
  global $em;
  
  // e.g.) USD=120.21円 EUR=134.64円 日経=19531.63円(△0.06%) 香港=28133pt(▼0.94%) 上海=0pt(N/A) S&P500=2108.29pt(△1.09%) Nasdaq=5005.39pt(△1.29%)
  
  $tweet = '';
  $tweetTail = '';
  $currentHour = (int)date('G');
  
  // 時計アイコン
  $tweet = $em->getEmojiOfClock($currentHour) . ' ';
  
  foreach ($order[$currentHour] as $market)
  {
    foreach ($assetsByMarket[$market] as $key => $asset)
    {
      $tweet = $tweet . $asset->getTweetPiece($mm, $em) . ' ';
    }
  }
    
  return $tweet . $tweetTail;
}

/*--------------------
  postTweet
---------------------*/
function postTweet($twitterAuth, $tweet)
{
  $connection = new TwitterOAuth($twitterAuth['consumer_key'       ],
                                 $twitterAuth['consumer_secret'    ],
                                 $twitterAuth['access_token'       ],
                                 $twitterAuth['access_token_secret']);
  
  $res = $connection->post('statuses/update', array('status' => mb_substr($tweet, 0, 140, 'UTF-8')));
  
  // var_dump($res);
  
  return;
}

?>
