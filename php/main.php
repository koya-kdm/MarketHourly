<?php

//===============================
// 定義
//===============================

// アプリ格納場所
$applicationPhpPath = dirname(__FILE__);

// 設定の読込み
require_once $applicationPhpPath . '/config.php';

require_once $applicationPhpPath . '/class/Asset.php';

// OAuthスクリプトの読込み
require $applicationPhpPath . '/lib/twitteroauth/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

// タイムゾーン
date_default_timezone_set('Asia/Tokyo');

// 市場
define('MARKET_FX', 'fx'); // 為替
define('MARKET_JP', 'jp'); // 日本
define('MARKET_HK', 'hk'); // 香港
define('MARKET_SH', 'sh'); // 上海
define('MARKET_EU', 'eu'); // 欧州
define('MARKET_US', 'us'); // 米国

// ツイートする時間
/*
取引時間
FX      :24h
Tokyo   : 9:00〜15:00
Shanghai:10:30〜16:00
HongKong:10:30〜16:00
EU      :16:00〜 0:30 (summer time)
         17:00〜 1:30
NewYork :22:30〜 5:00 (summer time)
         23:30〜 6:00
*/
$tweetHours = array(MARKET_FX => array(0, 1, 2, 3 ,4 ,5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23,),
                    MARKET_JP => array(                           9, 10, 11, 12, 13, 14, 15, 16,                            ),
                    MARKET_HK => array(                                  11, 12, 13, 14, 15, 16, 17,                        ),
                    MARKET_SH => array(                                  11, 12, 13, 14, 15, 16, 17,                        ),
                    MARKET_EU => array(0, 1,                                                 16, 17, 18, 19, 20, 21, 22, 23,),
                    MARKET_US => array(0, 1, 2, 3 ,4 ,5, 6, 7,                                                           23,),
                   );

// 休場日
$holidays = array(MARKET_FX => array(),
                  MARKET_JP => array('2015-01-01',  //  元日
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
                  MARKET_HK => array('2015-01-01',  //  The first day of January                                    新年
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
                  MARKET_SH => array('2015-01-01',  //  新年
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
                  MARKET_EU => array(),
                  MARKET_US => array('2015-01-01',  //  New Years Day
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
/*
$assets
  = array(
    0 => array('title' => '米',   'ticker' => 'USDJPY=X',  'unit' => '円', 'market' => MARKET_FX, 'displays_change' => false, 'decimals' => 2, 'price' => '', 'change' => ''),
    1 => array('title' => '欧',   'ticker' => 'EURJPY=X',  'unit' => '円', 'market' => MARKET_FX, 'displays_change' => false, 'decimals' => 2, 'price' => '', 'change' => ''),
    2 => array('title' => '日経', 'ticker' => '^N225',     'unit' => '円', 'market' => MARKET_JP, 'displays_change' => true,  'decimals' => 0, 'price' => '', 'change' => ''),
    3 => array('title' => '香港', 'ticker' => '^HSI',      'unit' => 'pt', 'market' => MARKET_HK, 'displays_change' => true,  'decimals' => 0, 'price' => '', 'change' => ''),
    4 => array('title' => '上海', 'ticker' => '000001.SS', 'unit' => 'pt', 'market' => MARKET_SH, 'displays_change' => true,  'decimals' => 0, 'price' => '', 'change' => ''),
    5 => array('title' => '英',   'ticker' => '^FTSE',     'unit' => 'pt', 'market' => MARKET_EU, 'displays_change' => true,  'decimals' => 0, 'price' => '', 'change' => ''),
    6 => array('title' => '独',   'ticker' => '^GDAXI',    'unit' => 'pt', 'market' => MARKET_EU, 'displays_change' => true,  'decimals' => 0, 'price' => '', 'change' => ''),
    7 => array('title' => 'ダウ', 'ticker' => '^DJI',      'unit' => 'pt', 'market' => MARKET_US, 'displays_change' => true,  'decimals' => 0, 'price' => '', 'change' => ''),
    8 => array('title' => 'ナス', 'ticker' => '^IXIC',     'unit' => 'pt', 'market' => MARKET_US, 'displays_change' => true,  'decimals' => 0, 'price' => '', 'change' => ''),
    );
*/
/*
$assetsByMarket
  = array(
    MARKET_FX => array(0 => array('title' => '米',   'ticker' => 'USDJPY=X',  'unit' => '円', 'market' => MARKET_FX, 'displays_change' => false, 'decimals' => 2, 'price' => '', 'change' => ''),
                       1 => array('title' => '欧',   'ticker' => 'EURJPY=X',  'unit' => '円', 'market' => MARKET_FX, 'displays_change' => false, 'decimals' => 2, 'price' => '', 'change' => ''),
                      ),
    MARKET_JP => array(0 => array('title' => '日経', 'ticker' => '^N225',     'unit' => '円', 'market' => MARKET_JP, 'displays_change' => true,  'decimals' => 0, 'price' => '', 'change' => ''),
                      ),
    MARKET_HK => array(0 => array('title' => '香港', 'ticker' => '^HSI',      'unit' => 'pt', 'market' => MARKET_HK, 'displays_change' => true,  'decimals' => 0, 'price' => '', 'change' => ''),
                      ),
    MARKET_SH => array(0 => array('title' => '上海', 'ticker' => '000001.SS', 'unit' => 'pt', 'market' => MARKET_SH, 'displays_change' => true,  'decimals' => 0, 'price' => '', 'change' => ''),
                      ),
    MARKET_EU => array(0 => array('title' => '英',   'ticker' => '^FTSE',     'unit' => 'pt', 'market' => MARKET_EU, 'displays_change' => true,  'decimals' => 0, 'price' => '', 'change' => ''),
                       1 => array('title' => '独',   'ticker' => '^GDAXI',    'unit' => 'pt', 'market' => MARKET_EU, 'displays_change' => true,  'decimals' => 0, 'price' => '', 'change' => ''),
                      ),
    MARKET_US => array(0 => array('title' => 'ダウ', 'ticker' => '^DJI',      'unit' => 'pt', 'market' => MARKET_US, 'displays_change' => true,  'decimals' => 0, 'price' => '', 'change' => ''),
                       1 => array('title' => 'ナス', 'ticker' => '^IXIC',     'unit' => 'pt', 'market' => MARKET_US, 'displays_change' => true,  'decimals' => 0, 'price' => '', 'change' => ''),
                      ),
    );
*/
$assetsByMarket = array(MARKET_FX => array(0 => new Asset( 'USD',   'USDJPY=X', '円', 2, MARKET_FX, false, false, null    ),
                                           1 => new Asset( 'EUR',   'EURJPY=X', '円', 2, MARKET_FX, false, false, null    ),),
                        MARKET_JP => array(0 => new Asset('日経',      '^N225', '円', 0, MARKET_JP,  true, false, null    ),),
                        MARKET_HK => array(0 => new Asset('香港',       '^HSI', 'pt', 0, MARKET_HK,  true, false, null    ),),
                        MARKET_SH => array(0 => new Asset('上海',  '000001.SS', 'pt', 0, MARKET_SH,  true, true, '7521596'),),
                        MARKET_EU => array(0 => new Asset(  '英',  '^FTSE',     'pt', 0, MARKET_EU, false, null           ),
                                           1 => new Asset(  '独',  '^GDAXI',    'pt', 0, MARKET_EU, false, false, null    ),),
                        MARKET_US => array(0 => new Asset('ダウ',       '^DJI', 'pt', 0, MARKET_US,  true, true, '983582' ),
                                           1 => new Asset('ナス',      '^IXIC', 'pt', 0, MARKET_US,  true, false, null    ),),
                       );


//6 => array('title' => 'S&P500', 'ticker' => '^GSPC',     'unit' => 'pt', 'market' => MARKET_US, 'displays_change' => true, 'decimals' => 0, 'price' => '', 'change' => ''),
  
// アセット追加定義（Yahoo非対応アセットはGoogleから取得）
//$assetsByMarket[MARKET_SH][0] = array_merge($assetsByMarket[MARKET_SH][0], array('retrieves_from_gogole' => true, 'g_code' => '7521596')); //上海
//$assetsByMarket[MARKET_US][0] = array_merge($assetsByMarket[MARKET_US][0], array('retrieves_from_gogole' => true, 'g_code' => '983582' )); //Dow

// 各時間における表示順
$order = array( 0 => array(MARKET_FX, MARKET_US, MARKET_EU, MARKET_JP, MARKET_SH, MARKET_HK),
                1 => array(MARKET_FX, MARKET_US, MARKET_EU, MARKET_JP, MARKET_SH, MARKET_HK),
                2 => array(MARKET_FX, MARKET_US, MARKET_EU, MARKET_JP, MARKET_SH, MARKET_HK),
                3 => array(MARKET_FX, MARKET_US, MARKET_EU, MARKET_JP, MARKET_SH, MARKET_HK),
                4 => array(MARKET_FX, MARKET_US, MARKET_EU, MARKET_JP, MARKET_SH, MARKET_HK),
                5 => array(MARKET_FX, MARKET_US, MARKET_EU, MARKET_JP, MARKET_SH, MARKET_HK),
                6 => array(MARKET_FX, MARKET_US, MARKET_EU, MARKET_JP, MARKET_SH, MARKET_HK),
                7 => array(MARKET_FX, MARKET_US, MARKET_EU, MARKET_JP, MARKET_SH, MARKET_HK),
                8 => array(MARKET_FX, MARKET_US, MARKET_EU, MARKET_JP, MARKET_SH, MARKET_HK),
                9 => array(MARKET_FX, MARKET_JP, MARKET_US, MARKET_EU, MARKET_SH, MARKET_HK),
               10 => array(MARKET_FX, MARKET_JP, MARKET_US, MARKET_EU, MARKET_SH, MARKET_HK),
               11 => array(MARKET_FX, MARKET_JP, MARKET_SH, MARKET_HK, MARKET_US, MARKET_EU),
               12 => array(MARKET_FX, MARKET_JP, MARKET_SH, MARKET_HK, MARKET_US, MARKET_EU),
               13 => array(MARKET_FX, MARKET_JP, MARKET_SH, MARKET_HK, MARKET_US, MARKET_EU),
               14 => array(MARKET_FX, MARKET_JP, MARKET_SH, MARKET_HK, MARKET_US, MARKET_EU),
               15 => array(MARKET_FX, MARKET_JP, MARKET_SH, MARKET_HK, MARKET_US, MARKET_EU),
               16 => array(MARKET_FX, MARKET_JP, MARKET_SH, MARKET_HK, MARKET_EU, MARKET_US),
               17 => array(MARKET_FX, MARKET_EU, MARKET_SH, MARKET_HK, MARKET_JP, MARKET_US),
               18 => array(MARKET_FX, MARKET_EU, MARKET_SH, MARKET_HK, MARKET_JP, MARKET_US),
               19 => array(MARKET_FX, MARKET_EU, MARKET_SH, MARKET_HK, MARKET_JP, MARKET_US),
               20 => array(MARKET_FX, MARKET_EU, MARKET_SH, MARKET_HK, MARKET_JP, MARKET_US),
               21 => array(MARKET_FX, MARKET_EU, MARKET_SH, MARKET_HK, MARKET_JP, MARKET_US),
               22 => array(MARKET_FX, MARKET_EU, MARKET_SH, MARKET_HK, MARKET_JP, MARKET_US),
               23 => array(MARKET_FX, MARKET_US, MARKET_EU, MARKET_JP, MARKET_SH, MARKET_HK),
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
                   'face'  => array('pppppp' => array('unicode' =>  '3297'), // ≧+5% circled ideograph congratulation
                                    'ppppp'  => array('unicode' => '1F60D'), // ≧+4% smiling face with heart-shaped eyes
                                    'pppp'   => array('unicode' => '1F606'), // ≧+3% smiling face with open mouth and tightly-closed eyes
                                    'ppp'    => array('unicode' => '1F601'), // ≧+2% grinning face with smiling eyes
                                    'pp'     => array('unicode' => '1F619'), // ≧+1% kissing face with smiling eyes
                                    'p'      => array('unicode' => '1F60C'), // ≧+0% relieved face
                                    'm'      => array('unicode' => '1F61E'), // ＜-0% disappointed face
                                    'mm'     => array('unicode' => '1F623'), // ≦-1% persevering face
                                    'mmm'    => array('unicode' => '1F629'), // ≦-2% weary face
                                    'mmmm'   => array('unicode' => '1F62D'), // ≦-3% loudly crying face
                                    'mmmmm'  => array('unicode' => '1F631'), // ≦-4% face screaming in fear
                                    'mmmmmm' => array('unicode' => '1F480'), // ≦-5% skull
                                    ),
                   'clock' => array(       0 => array('unicode' => '1F55B'),
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
$assetsByMarket[MARKET_FX][0]->setTitle(getEmoji('currency', 'dol'));
$assetsByMarket[MARKET_FX][1]->setTitle(getEmoji('currency', 'eur'));

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
        retrieveStockPriceFromGoogle($assetsByMarket[$market][$key]);
      }
      else
      {
        $assetsByMarket[$market][$key]['price' ] = $data[1];
        $assetsByMarket[$market][$key]['change'] = $data[2];
      }
    }
  }
  
  fclose($handle);

  return;
}

/*--------------------
  retrieveStockPriceFromGoogle
---------------------*/
function retrieveStockPriceFromGoogle(&$asset)
{
  $html = file_get_contents(GOOGLE_BASE_URL . '?q=' . $asset->getGoogleCode());
  
  if (preg_match('/<span id="ref_' . $asset->getGoogleCode() . '_l">([\d,.]*)<\/span>/is', $html, $matches))
  {
    $asset->setPrice(str_replace(',', '', $matches[1]));
  }
  
  if (preg_match('/<span class=".*" id="ref_' . $asset->getGoogleCode() . '_cp">\(([\d.-]*%)\)<\/span>/is', $html, $matches))
  {
    $asset->setChange('+' . $matches[1]);
    $asset->setChange(str_replace('+-', '-', $asset->getChange()));
  }
  
  return;
}

/*--------------------
  createTweet
---------------------*/
function createTweet($assetsByMarket)
{
  global $tweetHours;
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
      // 時間外アセットはツイートの後方に
      if (false == in_array($currentHour, $market))
      {
        $tweetTail =  $tweetTail . createTweetOfOneAsset($asset) . ' ';
        continue;
      }
      
      $tweet = $tweet . createTweetOfOneAsset($asset) . ' ';
    }
  }
    
  return $tweet . $tweetTail;
}

/*--------------------
  createTweetOfOneAsset
---------------------*/
function createTweetOfOneAsset($asset)
{
  global $emojiDict;
  
  $tweetOfOneAsset = $asset->getTitle()
                   . ''
                   . number_format($asset->getPrice(), $asset->getDecimals());
  
  if (isHoliday($asset))
  {
    $tweetOfOneAsset = $tweetOfOneAsset . ' (休)';
  }
  else
  {
    if ($asset->getDisplaysChange())
    {
      //顔アイコン
      $changeIcon = '';
      $change = (float) str_replace('%', '', $asset->getChange());
      
      if     ($change >=  5) { $key = 'pppppp'; }
      elseif ($change >=  4) { $key =  'ppppp'; }
      elseif ($change >=  3) { $key =   'pppp'; }
      elseif ($change >=  2) { $key =    'ppp'; }
      elseif ($change >=  1) { $key =     'pp'; }
      elseif ($change >=  0) { $key =      'p'; }
      elseif ($change <= -5) { $key = 'mmmmmm'; }
      elseif ($change <= -4) { $key =  'mmmmm'; }
      elseif ($change <= -3) { $key =   'mmmm'; }
      elseif ($change <= -2) { $key =    'mmm'; }
      elseif ($change <= -1) { $key =     'mm'; }
      elseif ($change <   0) { $key =      'm'; }
      
      $changeIcon = getEmoji('face', $key);
      
      $tweetOfOneAsset = $tweetOfOneAsset
                       . ' (' 
                       . str_replace(array('+', '-'), array('△', '▼'), $asset->getChange()) 
                       . $changeIcon 
                       . ')';
    }
  }
  
  return $tweetOfOneAsset;
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
