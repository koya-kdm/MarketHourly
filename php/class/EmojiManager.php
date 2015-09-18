<?php
/*===============================
  絵文字管理クラス
=================================*/
class EmojiManager
{
  // 絵文字グループ
  const CURRENCY  = 'cr';
  const FACE      = 'fc';
  const CLOCK     = 'cl';
  const COUNTRY   = 'ct';
  const COMMODITY = 'cm';

  // 絵文字辞書
  // http://apps.timwhitlock.info/emoji/tables/unicode
  private static $dictionary = array(
        self::CURRENCY => array( 'dol' => array('unicode' =>  '0024'),
                                 'eur' => array('unicode' =>  '20AC'),
                               ),
                                   
        self::FACE     => array( 'p5' => array('unicode' =>  '3297'), // ≧+5% circled ideograph congratulation
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
                                    
        self::CLOCK    => array(   0  => array('unicode' => '1F55B'),
                                   1  => array('unicode' => '1F550'),
                                   2  => array('unicode' => '1F551'),
                                   3  => array('unicode' => '1F552'),
                                   4  => array('unicode' => '1F553'),
                                   5  => array('unicode' => '1F554'),
                                   6  => array('unicode' => '1F555'),
                                   7  => array('unicode' => '1F556'),
                                   8  => array('unicode' => '1F557'),
                                   9  => array('unicode' => '1F558'),
                                  10  => array('unicode' => '1F559'),
                                  11  => array('unicode' => '1F55A'),
                                  12  => array('unicode' => '1F55B'),
                                  13  => array('unicode' => '1F550'),
                                  14  => array('unicode' => '1F551'),
                                  15  => array('unicode' => '1F552'),
                                  16  => array('unicode' => '1F553'),
                                  17  => array('unicode' => '1F554'),
                                  18  => array('unicode' => '1F555'),
                                  19  => array('unicode' => '1F556'),
                                  20  => array('unicode' => '1F557'),
                                  21  => array('unicode' => '1F558'),
                                  22  => array('unicode' => '1F559'),
                                  23  => array('unicode' => '1F55A'),
                               ),
        self::COUNTRY  => array( 'us' => array('unicode1' => '1F1FA', 'unicode2' => '1F1F8'),
                                 'jp' => array('unicode1' => '1F1EF', 'unicode2' => '1F1F5'),
                                 'de' => array('unicode1' => '1F1E9', 'unicode2' => '1F1EA'),
                               ),
        self::COMMODITY=> array( 'oil' => array('unicode' =>  '26FD'), // FUEL PUMP
                                 'gld1' => array('unicode' => '1F31F'), // GLOWING STAR
                                 'gld2' => array('unicode' => '1F536'), // LARGE ORANGE DIAMOND
                                 'gld' => array('unicode' => '1F538'), // SMALL ORANGE DIAMOND 
                                 
                               ),
        );
  
  /*---------------------------
    getEmoji
  -----------------------------*/
  private static function getEmoji($group, $key)
  {
    $emoji = self::$dictionary[$group][$key];
    
    if (isset($emoji['unicode']))
    {
      $target  = str_repeat('0', 8 - strlen($emoji['unicode']))
               . $emoji['unicode'];
    }
    else
    {
      $target1 = str_repeat('0', 8 - strlen($emoji['unicode1']))
               . $emoji['unicode1'];
              
      $target2 = str_repeat('0', 8 - strlen($emoji['unicode2']))
               . $emoji['unicode2'];
               
      $target  = $target1 . $target2;
    }
      
    $bin = pack('H*', $target);
      
    return mb_convert_encoding($bin, 'UTF-8', 'UTF-32BE');
  }
  
  /*---------------------------
    getCurrency
  -----------------------------*/
  private static function getCurrency($key)
  {
    return self::getEmoji(self::CURRENCY, $key);
  }
  
  /*---------------------------
    getDoller
  -----------------------------*/
  public static function getDoller()
  {
    return self::getEmoji(self::CURRENCY, 'dol');
  }
  /*---------------------------
    getEuro
  -----------------------------*/
  public static function getEuro()
  {
    return self::getEmoji(self::CURRENCY, 'eur');
  }
  
  /*---------------------------
    getFaceByChange
  -----------------------------*/
  public static function getFaceByChange($change)
  {
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
    
    return self::getEmoji(self::FACE, $key);
  }
  
  /*---------------------------
    getClockByHour
  -----------------------------*/
  public static function getClockByHour($hour)
  {
    return self::getEmoji(self::CLOCK, $hour);
  }
  
  
  /*---------------------------
    getFlag
  -----------------------------*/
  public static function getFlag($country)
  {
    return self::getEmoji(self::COUNTRY, $country);
  }
  
  /*---------------------------
    getOil
  -----------------------------*/
  public static function getOil()
  {
    return self::getEmoji(self::COMMODITY, 'oil');
  }
  
  /*---------------------------
    getGold
  -----------------------------*/
  public static function getGold()
  {
    return self::getEmoji(self::COMMODITY, 'gld');
  }
}
?>
