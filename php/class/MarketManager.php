<?php

class MarketManager
{
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

  // 市場
  const FX = 'fx'; // 為替 24h
  const JP = 'jp'; // 日本  9:00〜15:00
  const HK = 'hk'; // 香港 10:30〜16:00
  const SH = 'sh'; // 上海 10:30〜16:00
                   //      16:00〜 0:30 (summer time)
  const EU = 'eu'; // 欧州 17:00〜 1:30
  const US = 'us'; // 米国 22:30〜 5:00 (summer time)
                   //      23:30〜 6:00
  
  var $holidays;
  
  /*---------------------------
    __construct
  -----------------------------*/
  public function __construct()
  {
    global $applicationPhpPath;
    
    require_once $applicationPhpPath . '/class/MarketManager.holidays.php';
    $this->holidays = $holidays;
    
    return;
  }
  
  
  /*--------------------
    isHoliday
  ---------------------*/
  public function isHoliday($asset)
  {
    $today = date('Y-m-d');
    
    return in_array($today, $this->$holidays[$asset->getMarket()], true);
  }
}
?>
