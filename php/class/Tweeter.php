<?php

// OAuthスクリプトの読込み
require APPLICATION_PHP_PATH . '/lib/twitteroauth/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

class Tweeter
{
  // 各時間における表示順
  var $order;
  
  // マーケット管理クラス
  var $marketManager;
  
  // 絵文字管理クラス
  var $emojiManager;
  
  /*---------------------------
    __construct
  -----------------------------*/
  public function __construct($mm, $em)
  {
    $this->marketManager = $mm;
    $this->emojiManager  = $em;
    
    $this->order = array( 0 => array($mm::FX, $mm::US, $mm::EU, $mm::JP, $mm::SH, $mm::HK),
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
    $tweet = $this->emojiManager->getEmojiOfClock($currentHour) . ' ';
    
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
    
    if ($this->marketManager->isHoliday($asset->getMarket()))
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
        
        $changeIcon = $this->emojiManager->getEmojiOfFace($key);
        
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
