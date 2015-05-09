<?php

//===============================
// 定義
//===============================

// アプリ格納場所
$applicationPhpPath = dirname(__FILE__);

// 設定の読込み
require_once $applicationPhpPath . '/config.php';

require_once $applicationPhpPath . '/class/Asset.php';
require_once $applicationPhpPath . '/class/MarketManager.php';

// OAuthスクリプトの読込み
require $applicationPhpPath . '/lib/twitteroauth/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

// タイムゾーン
date_default_timezone_set('Asia/Tokyo');

// マーケット管理クラス
$mm = new MarketManager();

// 市場
/*
define('MARKET_FX', 'fx'); // 為替
define('MARKET_JP', 'jp'); // 日本
define('MARKET_HK', 'hk'); // 香港
define('MARKET_SH', 'sh'); // 上海
define('MARKET_EU', 'eu'); // 欧州
define('MARKET_US', 'us'); // 米国
*/

// ツイートする時間

/*


*/
// 休場日
$holidays = array($mm::FX => array(),
                  $mm::JP => array('2015-01-01',  //  元日
                                     '2015-01-02',  //  休場日
                                     '2015-01-12',  //  成人の日
                                     '2015-02-11',  //  建国記念の日
                                     '2015-04-29',  //  昭和の日
                                     '2015-05-04',  //  みどりの日
                                     '2015-05-05',  //  こどもの日
                                     '2015-05-06',  //  振替休日（憲法記念日
                                     '2015-07-20',  //  海の日
                                     '2015-09-21',  //  敬老の日
                                     '2015-09-22',  //  国民の休日
                                     '2015-09-23',  //  秋分の日
                                     '2015-10-12',  //  体育の日
                                     '2015-11-03',  //  文化の日
                                     '2015-11-23',  //  勤労感謝の日
                                     '2015-12-23',  //  天皇誕生日
                                     '2015-12-31',  //  休場日
                                    ),
                  $mm::HK => array('2015-01-01',  //  The first day of January                                    新年
                                     '2015-02-19',  //  Lunar New Year’s Day                                       旧正月
                                     '2015-02-20',  //  The second day of Lunar New Year                            旧正月
                                     '2015-04-03',  //  Good Friday                                                 受難日（聖金曜日）
                                     '2015-04-06',  //  The day following Ching Ming Festival                       清明節振替
                                     '2015-04-07',  //  The day following Easter Monday                             イースター・マンデー振替
                                     '2015-05-01',  //  Labour Day                                                  メーデー
                                     '2015-05-25',  //  The Birthday of the Buddha                                  仏誕節（灌仏会）
                                     '2015-07-01',  //  Hong Kong Special Administrative Region Establishment Day   特別行政区成立記念日
                                     '2015-09-28',  //  The day following the Chinese Mid-Autumn Festival           中秋節振替
                                     '2015-10-01',  //  National Day                                                建国記念日
                                     '2015-10-21',  //  Chung Yeung Festival                                        重陽節
                                     '2015-12-25',  //  Christmas Day                                               クリスマス
                                    ),
                  $mm::SH => array('2015-01-01',  //  新年
                                     '2015-01-02',  //  新年
                                     '2015-02-18',  //  旧正月
                                     '2015-02-19',  //  旧正月
                                     '2015-02-20',  //  旧正月
                                     '2015-02-23',  //  旧正月
                                     '2015-02-24',  //  旧正月
                                     '2015-04-06',  //  清明節
                                     '2015-05-01',  //  メーデー
                                     '2015-06-22',  //  端午節
                                     '2015-10-01',  //  建国記念日
                                     '2015-10-02',  //  建国記念日
                                     '2015-10-05',  //  建国記念日
                                     '2015-10-06',  //  建国記念日
                                     '2015-10-07',  //  建国記念日
                                    ),
                  $mm::EU => array(),
                  $mm::US => array('2015-01-01',  //  New Years Day
                                     '2015-01-19',  //  Martin Luther King, Jr. Day
                                     '2015-02-16',  //  Washington's Birthday
                                     '2015-04-03',  //  Good Friday
                                     '2015-05-25',  //  Memorial Day
                                     '2015-07-03',  //  Independence Day
                                     '2015-09-07',  //  Labor Day
                                     '2015-11-26',  //  Thanksgiving Day
                                     '2015-12-25',  //  Christmas
                                    ),
                  );

// アセット定義
$assetsByMarket = array($mm::FX => array(0 => new Asset( 'USD',   'USDJPY=X', '円', 2, $mm::FX, false, false, null     ),
                                           1 => new Asset( 'EUR',   'EURJPY=X', '円', 2, $mm::FX, false, false, null     ),),
                        $mm::JP => array(0 => new Asset('日経',      '^N225', '円', 0, $mm::JP,  true, false, null     ),),
                        $mm::HK => array(0 => new Asset('香港',       '^HSI', 'pt', 0, $mm::HK,  true, false, null     ),),
                        $mm::SH => array(0 => new Asset('上海',  '000001.SS', 'pt', 0, $mm::SH,  true,  true, '7521596'),),
                        $mm::EU => array(0 => new Asset(  '英',  '^FTSE',     'pt', 0, $mm::EU,  true, false, null     ),
                                           1 => new Asset(  '独',  '^GDAXI',    'pt', 0, $mm::EU,  true, false, null     ),),
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

// 絵文字辞書
// http://apps.timwhitlock.info/emoji/tables/unicode
$emojiDict = array('currency' => array('dol' => array('unicode' =>  '0024'),
                                       'eur' => array('unicode' =>  '20AC'),
                                       ),
                   'face'  => array('p5' => array('unicode' =>  '3297'), // ≧+5% circled ideograph congratulation
                                    'p4' => array('unicode' => '1F60D'), // ≧+4% smiling face with heart-shaped eyes
                                    'p3' => array('unicode' => '1F606'), // ≧+3% smiling face with open mouth and tightly-closed eyes
                                    'p2' => array('unicode' => '1F601'), // ≧+2% grinning face with smiling eyes
                                    'p1' => array('unicode' => '1F619'), // ≧+1% kissing face with smiling eyes
                                    'p0' => array('unicode' => '1F60C'), // ≧+0% relieved face
                                    'm0' => array('unicode' => '1F61E'), // ＜-0% disappointed face
                                    'm1' => array('unicode' => '1F623'), // ≦-1% persevering face
                                    'm2' => array('unicode' => '1F629'), // ≦-2% weary face
                                    'm3' => array('unicode' => '1F62D'), // ≦-3% loudly crying face
                                    'm4' => array('unicode' => '1F631'), // ≦-4% face screaming in fear
                                    'm5' => array('unicode' => '1F480'), // ≦-5% skull
                                    ),
                   'clock' => array(   0 => array('unicode' => '1F55B'),
                                       1 => array('unicode' => '1F550'),
                                       2 => array('unicode' => '1F551'),
                                       3 => array('unicode' => '1F552'),
                                       4 => array('unicode' => '1F553'),
                                       5 => array('unicode' => '1F554'),
                                       6 => array('unicode' => '1F555'),
                                       7 => array('unicode' => '1F556'),
                                       8 => array('unicode' => '1F557'),
                                       9 => array('unicode' => '1F558'),
                                      10 => array('unicode' => '1F559'),
                                      11 => array('unicode' => '1F55A'),
                                      12 => array('unicode' => '1F55B'),
                                      13 => array('unicode' => '1F550'),
                                      14 => array('unicode' => '1F551'),
                                      15 => array('unicode' => '1F552'),
                                      16 => array('unicode' => '1F553'),
                                      17 => array('unicode' => '1F554'),
                                      18 => array('unicode' => '1F555'),
                                      19 => array('unicode' => '1F556'),
                                      20 => array('unicode' => '1F557'),
                                      21 => array('unicode' => '1F558'),
                                      22 => array('unicode' => '1F559'),
                                      23 => array('unicode' => '1F55A'),),
                   );

// アセットタイトルの書換え
$assetsByMarket[$mm::FX][0]->setTitle(getEmoji('currency', 'dol'));
$assetsByMarket[$mm::FX][1]->setTitle(getEmoji('currency', 'eur'));

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
  global $emojiDict;
  global $order;

  // e.g.) USD=120.21円 EUR=134.64円 日経=19531.63円(△0.06%) 香港=28133pt(▼0.94%) 上海=0pt(N/A) S&P500=2108.29pt(△1.09%) Nasdaq=5005.39pt(△1.29%)
  
  $tweet = '';
  $tweetTail = '';
  $currentHour = (int)date('G');
  
  // 時計アイコン
  $tweet = getEmoji('clock', $currentHour) . ' ';
  
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
  isHoliday
---------------------*/
function isHoliday($asset)
{
  global $holidays;
  
  $today = date('Y-m-d');
  
  return in_array($today, $holidays[$asset->getMarket()], true);
}

/*--------------------
  getEmoji
---------------------*/
function getEmoji($group, $key)
{
  global $emojiDict;
  
  if (false == isset($emojiDict[$group][$key]['char']))
  {
    $target = str_repeat('0', 8 - strlen($emojiDict[$group][$key]['unicode']))
            . $emojiDict[$group][$key]['unicode'];
    
    $bin = pack('H*', $target);
    
    $emojiDict[$group][$key]['char'] = mb_convert_encoding($bin, 'UTF-8', 'UTF-32BE');
  }
  
  return $emojiDict[$group][$key]['char'];
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
