<?php

class MarketCtl
{
  // 市場
  const FX = 'fx'; // 為替
  const JP = 'jp'; // 日本
  const HK = 'hk'; // 香港
  const SH = 'sh'; // 上海
  const EU = 'eu'; // 欧州
  const US = 'us'; // 米国
  
  // ツイート時間（取引時間）
  var $tweetHours = array(self::FX => array(0, 1, 2, 3 ,4 ,5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23,),
                          self::JP => array(                           9, 10, 11, 12, 13, 14, 15, 16,                            ),
                          self::HK => array(                                  11, 12, 13, 14, 15, 16, 17,                        ),
                          self::SH => array(                                  11, 12, 13, 14, 15, 16, 17,                        ),
                          self::EU => array(0, 1,                                                 16, 17, 18, 19, 20, 21, 22, 23,),
                          self::US => array(0, 1, 2, 3 ,4 ,5, 6, 7,                                                           23,),
                         );
  
}
?>
