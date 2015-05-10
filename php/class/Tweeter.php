<?php

require_once APPLICATION_PHP_PATH . '/class/EmojiManager.php';

// OAuthスクリプトの読込み
require APPLICATION_PHP_PATH . '/lib/twitteroauth/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

class Tweeter
{
  // 各時間における表示順
  var $order;
  
  /*---------------------------
    __construct
  -----------------------------*/
  public function __construct()
  {    
    $this->order = array( 0 => array(MarketManager::FX, MarketManager::US, MarketManager::UK, MarketManager::GM, MarketManager::JP, MarketManager::SH, MarketManager::HK),
                          1 => array(MarketManager::FX, MarketManager::US, MarketManager::UK, MarketManager::GM, MarketManager::JP, MarketManager::SH, MarketManager::HK),
                          2 => array(MarketManager::FX, MarketManager::US, MarketManager::UK, MarketManager::GM, MarketManager::JP, MarketManager::SH, MarketManager::HK),
                          3 => array(MarketManager::FX, MarketManager::US, MarketManager::UK, MarketManager::GM, MarketManager::JP, MarketManager::SH, MarketManager::HK),
                          4 => array(MarketManager::FX, MarketManager::US, MarketManager::UK, MarketManager::GM, MarketManager::JP, MarketManager::SH, MarketManager::HK),
                          5 => array(MarketManager::FX, MarketManager::US, MarketManager::UK, MarketManager::GM, MarketManager::JP, MarketManager::SH, MarketManager::HK),
                          6 => array(MarketManager::FX, MarketManager::US, MarketManager::UK, MarketManager::GM, MarketManager::JP, MarketManager::SH, MarketManager::HK),
                          7 => array(MarketManager::FX, MarketManager::US, MarketManager::UK, MarketManager::GM, MarketManager::JP, MarketManager::SH, MarketManager::HK),
                          8 => array(MarketManager::FX, MarketManager::US, MarketManager::UK, MarketManager::GM, MarketManager::JP, MarketManager::SH, MarketManager::HK),
                          9 => array(MarketManager::FX, MarketManager::JP, MarketManager::US, MarketManager::UK, MarketManager::GM, MarketManager::SH, MarketManager::HK),
                         10 => array(MarketManager::FX, MarketManager::JP, MarketManager::US, MarketManager::UK, MarketManager::GM, MarketManager::SH, MarketManager::HK),
                         11 => array(MarketManager::FX, MarketManager::JP, MarketManager::SH, MarketManager::HK, MarketManager::US, MarketManager::UK, MarketManager::GM),
                         12 => array(MarketManager::FX, MarketManager::JP, MarketManager::SH, MarketManager::HK, MarketManager::US, MarketManager::UK, MarketManager::GM),
                         13 => array(MarketManager::FX, MarketManager::JP, MarketManager::SH, MarketManager::HK, MarketManager::US, MarketManager::UK, MarketManager::GM),
                         14 => array(MarketManager::FX, MarketManager::JP, MarketManager::SH, MarketManager::HK, MarketManager::US, MarketManager::UK, MarketManager::GM),
                         15 => array(MarketManager::FX, MarketManager::JP, MarketManager::SH, MarketManager::HK, MarketManager::US, MarketManager::UK, MarketManager::GM),
                         16 => array(MarketManager::FX, MarketManager::JP, MarketManager::SH, MarketManager::HK, MarketManager::UK, MarketManager::GM, MarketManager::US),
                         17 => array(MarketManager::FX, MarketManager::UK, MarketManager::GM, MarketManager::SH, MarketManager::HK, MarketManager::JP, MarketManager::US),
                         18 => array(MarketManager::FX, MarketManager::UK, MarketManager::GM, MarketManager::SH, MarketManager::HK, MarketManager::JP, MarketManager::US),
                         19 => array(MarketManager::FX, MarketManager::UK, MarketManager::GM, MarketManager::SH, MarketManager::HK, MarketManager::JP, MarketManager::US),
                         20 => array(MarketManager::FX, MarketManager::UK, MarketManager::GM, MarketManager::SH, MarketManager::HK, MarketManager::JP, MarketManager::US),
                         21 => array(MarketManager::FX, MarketManager::UK, MarketManager::GM, MarketManager::SH, MarketManager::HK, MarketManager::JP, MarketManager::US),
                         22 => array(MarketManager::FX, MarketManager::UK, MarketManager::GM, MarketManager::SH, MarketManager::HK, MarketManager::JP, MarketManager::US),
                         23 => array(MarketManager::FX, MarketManager::US, MarketManager::UK, MarketManager::GM, MarketManager::JP, MarketManager::SH, MarketManager::HK),
                        );
    
    return;
  }
  
  /*---------------------------
    createTweet
  -----------------------------*/
  public function createTweet($assetsByMarket)
  {
    // e.g.) USD=120.21円 EUR=134.64円 日経=19531.63円(△0.06%) 香港=28133pt(▼0.94%) 上海=0pt(N/A) S&P500=2108.29pt(△1.09%) Nasdaq=5005.39pt(△1.29%)
    
    $tweet = '';
    $currentHour = (int)date('G');
    
    // 時計アイコン
    $tweet = EmojiManager::getClock($currentHour) . ' ';
    
    foreach ($this->order[$currentHour] as $market)
    {
      foreach ($assetsByMarket[$market] as $key => $asset)
      {
        $tweet = $tweet . $this->createTweetPiece($asset) . ' ';
      }
    }
    
    return $tweet;
  }
  
  /*---------------------------
    getTweetPiece
  -----------------------------*/
  private function createTweetPiece($asset)
  {
    $piece = $asset->getTitle()
                     . ''
                     . number_format($asset->getPrice(), $asset->getDecimals());
    
    if (MarketManager::isHoliday($asset->getMarket()))
    {
      $piece = $piece . ' (休)';
    }
    else
    {
      if ($asset->getDisplaysChange())
      {
        //顔アイコン
        $changeIcon = '';
        $change = (float) str_replace('%', '', $asset->getChange());
        
        if     ($change >=  5) { $key = 'p5'; }
        elseif ($change >=  4) { $key = 'p4'; }
        elseif ($change >=  3) { $key = 'p3'; }
        elseif ($change >=  2) { $key = 'p2'; }
        elseif ($change >=  1) { $key = 'p1'; }
        elseif ($change >=  0) { $key = 'p0'; }
        elseif ($change <= -5) { $key = 'm5'; }
        elseif ($change <= -4) { $key = 'm4'; }
        elseif ($change <= -3) { $key = 'm3'; }
        elseif ($change <= -2) { $key = 'm2'; }
        elseif ($change <= -1) { $key = 'm1'; }
        elseif ($change <   0) { $key = 'm0'; }
        
        $changeIcon = EmojiManager::getFace($key);
        
        $piece = $piece
               . ' (' 
               . str_replace(array('+', '-'), array('△', '▼'), $asset->getChange()) 
               . $changeIcon 
               . ')';
      }
    }
    
    return $piece;
  }
  
  /*---------------------------
    postTweet
  -----------------------------*/
  public function postTweet($twitterAuth, $tweet)
  {
    $connection = new TwitterOAuth($twitterAuth['consumer_key'       ],
                                   $twitterAuth['consumer_secret'    ],
                                   $twitterAuth['access_token'       ],
                                   $twitterAuth['access_token_secret']);
    
    //$res = $connection->post('statuses/update', array('status' => mb_substr($tweet, 0, 140, 'UTF-8')));
    
    // var_dump($res);
    
    return;
  }
}
?>
