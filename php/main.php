//<?php

//===============================
// 定義
//===============================

// アプリ格納場所
$applicationPath = dirname(__FILE__);

// 設定の読込み
require_once $applicationPath . '/php/config.php';

// OAuthスクリプトの読込み
require $applicationPath . '/php/lib/twitteroauth/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

// タイムゾーン
date_default_timezone_set('Asia/Tokyo');

// 市場
define('MARKET_FX', 'fx');
define('MARKET_JP', 'jp');
define('MARKET_CN', 'cn');
define('MARKET_US', 'us');

// ツイートする時間
/*
取引時間
FX      :24h
Tokyo   : 9:00〜15:00
Shanghai:10:30〜16:00
HongKong:10:30〜16:00
NewYork :22:30〜 5:00 (summer time)
         23:30〜 6:00
*/
$tweetHours = array(MARKET_FX => array(0, 1, 2, 3 ,4 ,5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23,),
                    MARKET_JP => array(                           9, 10, 11, 12, 13, 14, 15, 16,                            ),
                    MARKET_CN => array(                                  11, 12, 13, 14, 15, 16, 17,                        ),
                    MARKET_US => array(0, 1, 2, 3 ,4 ,5, 6, 7,                                                           23,),
                    );

// アセット定義
$assets = array(array('title' => 'USD',    'ticker' => 'USDJPY=X',  'unit' => '円', 'market' => MARKET_FX, 'displays_change' => false, 'price' => '', 'change' => ''),
                array('title' => 'EUR',    'ticker' => 'EURJPY=X',  'unit' => '円', 'market' => MARKET_FX, 'displays_change' => false, 'price' => '', 'change' => ''),
                array('title' => '日経',    'ticker' => '^N225',     'unit' => '円', 'market' => MARKET_JP, 'displays_change' => true,  'price' => '', 'change' => ''),
                array('title' => '香港',    'ticker' => '^HSI',      'unit' => 'pt', 'market' => MARKET_CN, 'displays_change' => true,  'price' => '', 'change' => ''),
                array('title' => '上海',    'ticker' => '000001.SS', 'unit' => 'pt', 'market' => MARKET_CN, 'displays_change' => true,  'price' => '', 'change' => , 'retrieves_from_gogole' => true, 'g_code' => '7521596'),
                array('title' => 'Dow',    'ticker' => '^DJI',      'unit' => 'pt', 'market' => MARKET_US, 'displays_change' => true,  'price' => '', 'change' => '', 'retrieves_from_gogole' => true, 'g_code' => '983582'),
                array('title' => 'S&P500', 'ticker' => '^GSPC',     'unit' => 'pt', 'market' => MARKET_US, 'displays_change' => true,  'price' => '', 'change' => ''),
                array('title' => 'Nasdaq', 'ticker' => '^IXIC',     'unit' => 'pt', 'market' => MARKET_US, 'displays_change' => true,  'price' => '', 'change' => ''),
                );

// Yahoo Finace ベースURL
define('YAHOO_BASE_URL', 'http://finance.yahoo.com/d/quotes.csv');

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
$yahooParams  = array('s', 'n', 'l1', 'd1', 't1', 'p2', );

//===============================
// メイン
//===============================
// URLの作成
$url = createUrl(YAHOO_BASE_URL, $yahooParams, $assets);

// 株価の取得
retrieveStockPrice($url, $assets);

// ツイートの作成
$tweet = createTweet($assets, $tweetHours);
echo $tweet . PHP_EOL;

// ツイートの投稿
postTweet($twitterAuth, $tweet);

//===============================
// 関数
//===============================

/*--------------------
  createUrl
---------------------*/
function createUrl($yahooBaseUrl, $yahooParams, $assets)
{
  // e.g.) http://finance.yahoo.com/d/quotes.csv?s=INDU+^IXIC+USDJPY=X+^N225&f=snl1c1p2d1t1
  
  $tickerString = '';
  
  foreach ($assets as $key => $asset)
  {
    $tickerString = $tickerString . $asset['ticker'] . '+';
  }
  
  $url= $yahooBaseUrl . '?s=' . $tickerString . '&f=' . implode('', $yahooParams);

  return $url;
}

/*--------------------
  retrieveStockPrice
---------------------*/
function retrieveStockPrice($url, &$assets)
{
  $handle = fopen($url, 'r');

  $i = 0;
  while (false != ($data = fgetcsv($handle, 1000, ',')))
  {
    if (true == isset($assets[$i]['retrieves_from_gogole']) AND
        true == $assets[$i]['retrieves_from_gogole'])
    {
      retrieveStockPriceFromGoogle($assets[$i])
    }
    else
    {
      $assets[$i]['price' ] = $data[2];
      $assets[$i]['change'] = $data[5];
    }
    
    $i++;
  }
  
  fclose($handle);

  return;
}

/*--------------------
  createTweet
---------------------*/
function createTweet($assets, $tweetHours)
{
  // e.g.) USD=120.21円 EUR=134.64円 日経=19531.63円(△0.06%) 香港=28133pt(▼0.94%) 上海=0pt(N/A) S&P500=2108.29pt(△1.09%) Nasdaq=5005.39pt(△1.29%)
  
  $tweet = '';
  $tweetTail = '';
  $currentHour = (int)date('G');
 
  foreach ($assets as $key => $asset)
  {
    // 時間外アセットはツイートの後方に
    if (false == in_array($currentHour, $tweetHours[$asset['market']]))
    {
      $tweetTail = $tweetTail . '' . createTweetOfOneAsset($asset) . ',';
      continue;
    }
    
    $tweet = $tweet . '' . createTweetOfOneAsset($asset) . ',';
  }
  
  return $tweet . $tweetTail;
}

/*--------------------
  createTweetOfOneAsset
---------------------*/
function createTweetOfOneAsset($asset)
{
  $tweetOfOneAsset = $asset['title'] . '=' . round($asset['price'], 2) . $asset['unit'];
  
  if ($asset['displays_change'])
  {
    $tweetOfOneAsset = $tweetOfOneAsset
                     . '(' . str_replace(array('+', '-'), array('△', '▼'), $asset['change']) . ')';
  }
  
  return $tweetOfOneAsset;
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
  
  $res = $connection->post('statuses/update', array('status' => $tweet));
  
  return;
}

/*--------------------
  retrieveStockPriceFromGoogle
---------------------*/
function retrieveStockPriceFromGoogle(&$asset)
{
  $html = file_get_contents('https://www.google.com/finance?q=' . $asset['g_code']);
  
  if (preg_match('/<span id="ref_' . $asset['g_code'] . '_l">(.*?)<\/span>/is', $html, $matches))
  {
    $asset['price'] = $matches[1];
  }
  
  if (preg_match('/<span class="chg" id="ref_' . $asset['g_code'] . '_cp">\((.*?)\)<\/span>/is', $html, $matches))
  {
    $asset['change'] = $matches[1];
  }
  
  return;
}

?>
