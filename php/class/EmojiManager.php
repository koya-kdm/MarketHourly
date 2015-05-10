<?php

class EmojiManager
{
  // 絵文字グループ
  const CURRENCY = 'currency';
  const FACE     = 'face';
  const CLOCK    = 'clock';

  // 絵文字辞書
  // http://apps.timwhitlock.info/emoji/tables/unicode
  private static $dictionary = array(
        self::CURRENCY => array('dol' => array('unicode' =>  '0024'),
                                    'eur' => array('unicode' =>  '20AC'),
                                   ),
                                   
        self::FACE     => array('p5' => array('unicode' =>  '3297'), // ≧+5% circled ideograph congratulation
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
                                    
        self::CLOCK    => array(  0  => array('unicode' => '1F55B'),
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
                                     23  => array('unicode' => '1F55A'),),
        );
  
  
  /*---------------------------
    getEmoji
  -----------------------------*/
  private static function getEmoji($group, $key)
  {
    $emoji = self::$dictionary[$group][$key];
    
    $target = str_repeat('0', 8 - strlen($emoji['unicode']))
            . $emoji['unicode'];
      
    $bin = pack('H*', $target);
      
    return mb_convert_encoding($bin, 'UTF-8', 'UTF-32BE');
  }
  
  /*---------------------------
    getCurrency
  -----------------------------*/
  public static function getCurrency($key)
  {
    return self::getEmoji(self::CURRENCY, $key);
  }
  
  /*---------------------------
    getFace
  -----------------------------*/
  public static function getFace($key)
  {
    return self::getEmoji(self::FACE, $key);
  }
  
  /*---------------------------
    getClock
  -----------------------------*/
  public static function getClock($key)
  {
    return self::getEmoji(self::CLOCK, $key);
  }
}
?>
