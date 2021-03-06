<?php
/*===============================
  ツイート投稿クラス
=================================*/

// 絵文字管理クラス
require_once APPLICATION_PHP_PATH . '/class/EmojiManager.php';

// マーケット管理クラス
require_once APPLICATION_PHP_PATH . '/class/MarketManager.php';

// OAuthスクリプト
require APPLICATION_PHP_PATH . '/lib/twitteroauth/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

class Tweeter
{
  // 絵文字グループ
  const SIGN_PLUS  = '+';
  const SIGN_MINUS = '-';

  // 各時間における表示順
  // - Summer Time

  private $order = array(0 => array(MarketManager::FX, MarketManager::US, MarketManager::UK, MarketManager::GM, MarketManager::JP, MarketManager::SH, MarketManager::HK),
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

  // - Normal Time
  /*
  private $order = array(0 => array(MarketManager::FX, MarketManager::US, MarketManager::UK, MarketManager::GM, MarketManager::JP, MarketManager::SH, MarketManager::HK),
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
                        23 => array(MarketManager::FX, MarketManager::UK, MarketManager::GM, MarketManager::SH, MarketManager::HK, MarketManager::JP, MarketManager::US),
                       );
  */

  // 「表示順を有効にする」フラグ
  private $enableOrder = true;

  // 「時計アイコンを有効にする」フラグ
  private $enableClock = true;

  // ヘッダー
  private $header = '';

  /*---------------------------
    enableOrder / disableOrder
  -----------------------------*/
  public function enableOrder()  { $this->enableOrder = true;  }
  public function disableOrder() { $this->enableOrder = false; }

  /*---------------------------
    enableClock / disableClock
  -----------------------------*/
  public function enableClock()  { $this->enableClock = true;  }
  public function disableClock() { $this->enableClock = false; }

  /*---------------------------
    setHeader
  -----------------------------*/
  public function setHeader($var) { $this->header = $var; }

  /*---------------------------
    createTweet
  -----------------------------*/
  public function createTweet($assetsByMarket)
  {
    // e.g.) USD=120.21円 EUR=134.64円 日経=19531.63円(△0.06%) 香港=28133pt(▼0.94%) 上海=0pt(N/A) S&P500=2108.29pt(△1.09%) Nasdaq=5005.39pt(△1.29%)

    // ①ヘッダー
    $tweet = $this->header;

    // ②時計アイコン
    $currentHour = (int)date('G');
    if ($this->enableClock)
    {
      $tweet = EmojiManager::getClockByHour($currentHour) . ' ';
    }

    // ③株価
    // ◇表示順考慮
    if ($this->enableOrder)
    {
      foreach ($this->order[$currentHour] as $market)
      {
        if (isset($assetsByMarket[$market]))
        {
          foreach ($assetsByMarket[$market] as $key => $asset)
          {
            $tweet = $tweet . $this->createTweetPiece($asset) . ' ';
          }
        }
      }
    }
    // ◇表示順無視（アセット配列に格納されている順）
    else
    {
      foreach ($assetsByMarket as $market => $assets)
      {
        foreach ($assets as $key => $asset)
        {
          $tweet = $tweet . $this->createTweetPiece($asset) . ' ';
        }
      }
    }

    return $tweet;
  }

  /*---------------------------
    getTweetPiece
  -----------------------------*/
  private function createTweetPiece($asset)
  {
    // Format: [タイトル][株価] ([△▼前日比%][顔])

    // ①タイトル
    // ②株価
    $piece = $asset->getTitle()
                     . ''
                     . number_format($asset->getPrice(), $asset->getDecimals());

    // ③前日比 + 顔
    if (MarketManager::isHoliday($asset->getMarket()))
    {
      $piece = $piece . ' (休)'; // 休場の場合
    }
    else
    {
      if ($asset->getDisplaysChange())
      {
        //顔アイコン
        $changeIcon = '';
        $change = (float) str_replace('%', '', $asset->getChange());

        if ($change == 0)
        {
          if ($asset->getDisplaysChangeByPoint())
          {
            $changeByPoint = '0 / ';
          }
          else
          {
            $changeByPoint = '';
          }

          $piece = $piece . ' (' . $changeByPoint . '0%)';
        }
        else
        {
          $changeIcon = EmojiManager::getFaceByChange($change);

          if ($asset->getDisplaysChangeByPoint())
          {
            $changeByPoint = $asset->getChangeByPoint() . ' / ';
          }
          else
          {
            $changeByPoint = '';
          }

          $piece = $piece
                 . ' ('
                 . $changeByPoint
                 . str_replace(array('+', '-'), array(self::SIGN_PLUS, self::SIGN_MINUS), $asset->getChange())
                 . $changeIcon
                 . ')';
        }
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

    $res = $connection->post('statuses/update', array('status' => mb_substr($tweet, 0, 140, 'UTF-8')));

    //var_dump($res);

    return;
  }

  /*---------------------------
    createTweetOfBonds
  -----------------------------*/
  public function createTweetOfBonds($bonds)
  {
    $tweet = '10年債';

    foreach ($bonds as $ct => $bond)
    {
      $tweet = $tweet
             . EmojiManager::getFlag($ct)
             .       $bond['last'  ]
             . '(' . $bond['change'] . 'pt' . ')'
             . ' ';
    }

    return $tweet;
  }

  /*---------------------------
    createTweetOfCommodities
  -----------------------------*/
  public function createTweetOfCommodities($commodities)
  {
    $tweet = '';

    $com = $commodities['oil'];

    $tweet = $tweet
           . EmojiManager::getOil()
           . $com['last']
           . '(' . $com['change_percent']
    /*     . EmojiManager::getFaceByChange($com['change_percent']) */
           . ')'
           . ' ';

    $com = $commodities['gold'];

    $tweet = $tweet
           . EmojiManager::getGold()
           . $com['last']
           . '(' . $com['change_percent']
    /*     . EmojiManager::getFaceByChange($com['change_percent']) */
           . ')'
           . ' ';

    return $tweet;

  }
}
?>
