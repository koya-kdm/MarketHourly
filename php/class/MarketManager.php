<?php
/*===============================
  マーケット管理クラス
=================================*/
class MarketManager
{
  // 市場
  const FX = 'fx'; // 為替 24h
  const JP = 'jp'; // 日本  9:00〜15:00
  const HK = 'hk'; // 香港 10:30〜16:00
  const SH = 'sh'; // 上海 10:30〜16:00
  
  const UK = 'uk'; // 英   16:00〜 0:30 (summer time)
                   //      17:00〜 1:30
  
  const GM = 'gm'; // 独   16:00〜 0:30 (summer time)
                   //      17:00〜 1:30
  
  const US = 'us'; // 米   22:30〜 5:00 (summer time)
                   //      23:30〜 6:00
  
  private $markets = array(self::FX,
                           self::JP,
                           self::HK,
                           self::SH,
                           self::UK,
                           self::GM,
                           self::US,
                          );
  
  /*---------------------------
   　isValid
  -----------------------------*/
  public static function isValid($market)
  {
    if (in_array($market, self::$markets))
    {
      return true;
    }
    
    return false;
  }
  
  /*---------------------------
    isHoliday
  -----------------------------*/
  public static function isHoliday($market)
  {
    include APPLICATION_PHP_PATH . '/class/MarketManager.holidays.php'; // $configHolidays is defined here
    
    $today = date('Y-m-d');
    
    return in_array($today, $configHolidays[$market], true);
  }
}
?>
