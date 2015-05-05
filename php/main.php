<?php

//===============================
// 定義
//===============================

// アプリ格納場所
$applicationPhpPath = dirname(__FILE__);

// 設定の読込み
require_once $applicationPhpPath . '/config.php';

// OAuthスクリプトの読込み
require $applicationPhpPath . '/lib/twitteroauth/autoload.php';
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

// 休場日
$holidays = array(MARKET_FX => array('2015' => array()),
                  MARKET_JP => array('2015' => array()),
                  MARKET_CN => array('2015' => array()),
                  MARKET_US => array('2015' => array()),
                  );

// アセット定義
$assets
  = array(
    0 => array('title' => '米',  'ticker' => 'USDJPY=X',  'unit' => '円', 'market' => MARKET_FX, 'displays_change' => false, 'decimals' => 2, 'price' => '', 'change' => ''),
    1 => array('title' => '欧',  'ticker' => 'EURJPY=X',  'unit' => '円', 'market' => MARKET_FX, 'displays_change' => false, 'decimals' => 2, 'price' => '', 'change' => ''),
    2 => array('title' => '日経', 'ticker' => '^N225',     'unit' => '円', 'market' => MARKET_JP, 'displays_change' => true, 'decimals' => 0, 'price' => '', 'change' => ''),
    3 => array('title' => '香港', 'ticker' => '^HSI',      'unit' => 'pt', 'market' => MARKET_CN, 'displays_change' => true, 'decimals' => 0, 'price' => '', 'change' => ''),
    4 => array('title' => '上海', 'ticker' => '000001.SS', 'unit' => 'pt', 'market' => MARKET_CN, 'displays_change' => true, 'decimals' => 0, 'price' => '', 'change' => ''),
    5 => array('title' => 'ダウ', 'ticker' => '^DJI',      'unit' => 'pt', 'market' => MARKET_US, 'displays_change' => true, 'decimals' => 0, 'price' => '', 'change' => ''),
    6 => array('title' => 'ナス', 'ticker' => '^IXIC',     'unit' => 'pt', 'market' => MARKET_US, 'displays_change' => true, 'decimals' => 0, 'price' => '', 'change' => ''),
    );
//6 => array('title' => 'S&P500', 'ticker' => '^GSPC',     'unit' => 'pt', 'market' => MARKET_US, 'displays_change' => true, 'decimals' => 0, 'price' => '', 'change' => ''),
  
// アセット追加定義（Yahoo非対応アセットはGoogleから取得）
$assets[4] = array_merge($assets[4], array('retrieves_from_gogole' => true, 'g_code' => '7521596')); //上海
$assets[5] = array_merge($assets[5], array('retrieves_from_gogole' => true, 'g_code' => '983582' )); //Dow

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
$yahooParams  = array('s', 'l1', 'p2');

// 絵文字辞書
// http://apps.timwhitlock.info/emoji/tables/unicode
$emojiDict = array('face'  => array('pppppp' => array('unicode' =>  '3297'), // ≧+5% circled ideograph congratulation
                                    'ppppp'  => array('unicode' => '1F60D'), // ≧+4% smiling face with heart-shaped eyes
                                    'pppp'   => array('unicode' => '1F606'), // ≧+3% smiling face with open mouth and tightly-closed eyes
                                    'ppp'    => array('unicode' => '1F603'), // ≧+2% smiling face with open mouth
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
                                          11 => array('unicode' => '1F55A'),),
                   );


//===============================
// メイン
//===============================
// URLの作成
$url = createUrl(YAHOO_BASE_URL, $yahooParams, $assets);

// 株価の取得
retrieveStockPrice($url, $assets);

// ツイートの作成
$tweet = createTweet($assets, $tweetHours, $emojiDict);

// Debug
//print_r($assets);
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
      retrieveStockPriceFromGoogle($assets[$i]);
    }
    else
    {
      $assets[$i]['price' ] = $data[1];
      $assets[$i]['change'] = $data[2];
    }
    
    $i++;
  }
  
  fclose($handle);

  return;
}

/*--------------------
  retrieveStockPriceFromGoogle
---------------------*/
function retrieveStockPriceFromGoogle(&$asset)
{
  $html = file_get_contents('https://www.google.com/finance?q=' . $asset['g_code']);
  
  if (preg_match('/<span id="ref_' . $asset['g_code'] . '_l">([\d,.]*)<\/span>/is', $html, $matches))
  {
    $asset['price'] = str_replace(',', '', $matches[1]);
  }
  
  if (preg_match('/<span class=".*" id="ref_' . $asset['g_code'] . '_cp">\(([\d.-]*%)\)<\/span>/is', $html, $matches))
  {
    $asset['change'] = '+' . $matches[1];
    $asset['change'] = str_replace('+-', '-', $asset['change']);
  }
  
  return;
}

/*--------------------
  createTweet
---------------------*/
function createTweet($assets, $tweetHours, &$emojiDict)
{
  // e.g.) USD=120.21円 EUR=134.64円 日経=19531.63円(△0.06%) 香港=28133pt(▼0.94%) 上海=0pt(N/A) S&P500=2108.29pt(△1.09%) Nasdaq=5005.39pt(△1.29%)
  
  $tweet = '';
  $tweetTail = '';
  $currentHour = (int)date('G');
  
  // 時計アイコン
  $tweet = getEmoji($emojiDict, 'clock', $currentHour);
  
  foreach ($assets as $key => $asset)
  {
    // 時間外アセットはツイートの後方に
    if (false == in_array($currentHour, $tweetHours[$asset['market']]))
    {
      $tweetTail = $tweetTail . '' . createTweetOfOneAsset($asset, $emojiDict) . ' ';
      continue;
    }
    
    $tweet = $tweet . '' . createTweetOfOneAsset($asset, $emojiDict) . ' ';
  }
  
  return $tweet . $tweetTail;
}

/*--------------------
  createTweetOfOneAsset
---------------------*/
function createTweetOfOneAsset($asset, &$emojiDict)
{
  $tweetOfOneAsset = $asset['title']
                   . ''
                   . number_format($asset['price'], $asset['decimals']);
  
  if ($asset['displays_change'])
  {
    
    //顔アイコン
    $changeIcon = '';
    $change = (int) str_replace($asset['change'], '%', '');
    
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
    
    $changeIcon = getEmoji($emojiDict, 'face', $key);
    
    $tweetOfOneAsset = $tweetOfOneAsset
                     . '(' . str_replace(array('+', '-'), array('△', '▼'), $asset['change']) . $changeIcon . ')';
  }
  
  return $tweetOfOneAsset;
}

/*--------------------
  getEmoji
---------------------*/
function getEmoji(&$emojiDict, $group, $key)
{
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
  
  $res = $connection->post('statuses/update', array('status' => mb_substr($tweet, 0, 140)));
  
  // var_dump($res);
  
  return;
}

?>
