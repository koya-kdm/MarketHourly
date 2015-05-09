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

// OAuthスクリプトの読込み
require $applicationPhpPath . '/lib/twitteroauth/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

// タイムゾーン
date_default_timezone_set('Asia/Tokyo');

// マーケット管理クラス
$mm = new MarketManager();

// 絵文字管理クラス
$em = new EmojiManager();


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

// Yahoo Finace ベースURL
define('YAHOO_BASE_URL', 'http://finance.yahoo.com/d/quotes.csv');

// Google Finace ベースURL
define('GOOGLE_BASE_URL', 'https://www.google.com/finance');

// Yahoo Finance パラメータ
/*
s  = Symbol
n  = Name
l1 = Last Trade (Price Only)
d1 = Last Trade Date
t1 = Last Trade Time
c  = Change and Percent Change
v  = Volume
c6 = Change (Realtime)
k2 = Change Percent (Realtime)
p2 = Change in Percent
*/
$yahooParams  = array('s', 'l1', 'p2');



// アセットタイトルの書換え
$assetsByMarket[$mm::FX][0]->setTitle($em->getEmojiOfCurrency('dol'));
$assetsByMarket[$mm::FX][1]->setTitle($em->getEmojiOfCurrency('eur'));

//===============================
// メイン
//===============================
// URLの作成
$url = createUrl($yahooParams, $assetsByMarket);

// 株価の取得
retrieveStockPrice($url, $assetsByMarket);

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
  createUrl
---------------------*/
function createUrl($yahooParams, $assetsByMarket)
{
  // e.g.) http://finance.yahoo.com/d/quotes.csv?s=INDU+^IXIC+USDJPY=X+^N225&f=snl1c1p2d1t1
  
  $tickerString = '';
  
  foreach ($assetsByMarket as $market => $assets)
  {
    foreach ($assets as $key => $asset)
    {
      $tickerString = $tickerString . $asset->getTicker() . '+';
    }
  }
  
  $url= YAHOO_BASE_URL . '?s=' . $tickerString . '&f=' . implode('', $yahooParams);

  return $url;
}

/*--------------------
  retrieveStockPrice
---------------------*/
function retrieveStockPrice($url, &$assetsByMarket)
{
  $handle = fopen($url, 'r');
  
  foreach ($assetsByMarket as $market => $assets)
  {
    foreach ($assets as $key => $asset)
    {
      $data = fgetcsv($handle, 1000, ',');
      
      if (true == $asset->getRetrievesFromGoogle())
      {
        $asset->retrieveStockPriceFromGoogle();
      }
      else
      {
        $asset->setPrice ($data[1]);
        $asset->setChange($data[2]);
      }
    }
  }
  
  fclose($handle);

  return;
}

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
      $tweet = $tweet . $asset->getTweetPiece() . ' ';
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
