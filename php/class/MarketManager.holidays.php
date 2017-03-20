<?php
/*===============================
  休場日
=================================*/
// http://markets.on.nytimes.com/research/markets/holidays/holidays.asp

$configHolidays = array(

  // 為替
  self::FX => array(),

  // 日本
  self::JP => array( '2015-01-01',  //  元日
                     '2015-01-02',  //  休場日
                     '2015-01-12',  //  成人の日
                     '2015-02-11',  //  建国記念の日
                     '2015-04-29',  //  昭和の日
                     '2015-05-04',  //  みどりの日
                     '2015-05-05',  //  こどもの日
                     '2015-05-06',  //  振替休日（憲法記念日
                     '2015-07-20',  //  海の日
                     '2015-09-21',  //  敬老の日
                     '2015-09-22',  //  国民の休日
                     '2015-09-23',  //  秋分の日
                     '2015-10-12',  //  体育の日
                     '2015-11-03',  //  文化の日
                     '2015-11-23',  //  勤労感謝の日
                     '2015-12-23',  //  天皇誕生日
                     '2015-12-31',  //  休場日

                     '2016-01-01', // New Year's Day [jp]
                     '2016-01-11', // Coming of Age (Adults') Day
                     '2016-02-11', // National Founding Day
                     '2016-03-21', // Vernal Equinox OBS
                     '2016-04-29', // Showa Day (formerly Greenery Day)
                     '2016-05-03', // Constitution Day
                     '2016-05-04', // Greenery Day (formerly National Holiday)
                     '2016-05-05', // Children's Day
                     '2016-07-18', // Marine Day
                     '2016-08-11', // Mountain Day
                     '2016-09-19', // Respect for the Aged Day
                     '2016-09-22', // Autumn Equinox
                     '2016-10-10', // Health-Sports Day
                     '2016-11-03', // Culture Day
                     '2016-11-23', // Labour Thanksgiving Day
                     '2016-12-23', // Emperor's Birthday

                     '2017-01-02', // Bank Holiday 2
                     '2017-01-03', // Bank Holiday 3
                     '2017-01-09', // Coming of Age (Adults') Day
                     '2017-03-20', // Vernal Equinox
                     '2017-05-03', // Constitution Day
                     '2017-05-04', // Greenery Day (formerly National Holiday)
                     '2017-05-05', // Children's Day
                     '2017-07-17', // Marine Day
                     '2017-08-11', // Mountain Day
                     '2017-09-18', // Respect for the Aged Day
                     '2017-10-09', // Health-Sports Day
                     '2017-11-03', // Culture Day
                     '2017-11-23', // Labour Thanksgiving Day

                   ),

  // 香港
  self::HK => array( '2015-01-01',  //  The first day of January                                    新年
                     '2015-02-19',  //  Lunar New Year’s Day                                       旧正月
                     '2015-02-20',  //  The second day of Lunar New Year                            旧正月
                     '2015-04-03',  //  Good Friday                                                 受難日（聖金曜日）
                     '2015-04-06',  //  The day following Ching Ming Festival                       清明節振替
                     '2015-04-07',  //  The day following Easter Monday                             イースター・マンデー振替
                     '2015-05-01',  //  Labour Day                                                  メーデー
                     '2015-05-25',  //  The Birthday of the Buddha                                  仏誕節（灌仏会）
                     '2015-07-01',  //  Hong Kong Special Administrative Region Establishment Day   特別行政区成立記念日
                     '2015-09-28',  //  The day following the Chinese Mid-Autumn Festival           中秋節振替
                     '2015-10-01',  //  National Day                                                建国記念日
                     '2015-10-21',  //  Chung Yeung Festival                                        重陽節
                     '2015-12-25',  //  Christmas Day                                               クリスマス

                     '2016-01-01', // New Year's Day [hk]
                     '2016-02-08', // Lunar New Year 1
                     '2016-02-09', // Lunar New Year 2
                     '2016-02-10', // Lunar New Year 3
                     '2016-03-25', // Good Friday
                     '2016-03-28', // Easter Monday
                     '2016-04-04', // Ching Ming Festival
                     '2016-05-02', // Labour Day OBS
                     '2016-06-09', // Tuen Ng Day*
                     '2016-07-01', // SAR Establishment Day
                     '2016-09-16', // Day Following Mid-autumn Festival*
                     '2016-10-10', // Chung Yeung Day*
                     '2016-12-26', // Christmas Holiday
                     '2016-12-27', // Christmas Day OBS

                     '2017-01-02', // New Year's Day OBS
                     '2017-01-30', // Lunar New Year 3
                     '2017-01-31', // Lunar New Year 4
                     '2017-04-04', // Ching Ming Festival
                     '2017-04-14', // Good Friday
                     '2017-04-17', // Easter Monday
                     '2017-05-01', // Labour Day
                     '2017-05-03', // Buddha's Birthday*
                     '2017-05-30', // Tuen Ng Day*
                     '2017-10-02', // Chinese National Day OBS
                     '2017-10-05', // Day Following Mid-autumn Festival*
                     '2017-12-25', // Christmas Day
                     '2017-12-26', // Christmas Holiday
                   ),

  // 上海
  self::SH => array( '2015-01-01',  //  新年
                     '2015-01-02',  //  新年
                     '2015-02-18',  //  旧正月
                     '2015-02-19',  //  旧正月
                     '2015-02-20',  //  旧正月
                     '2015-02-23',  //  旧正月
                     '2015-02-24',  //  旧正月
                     '2015-04-06',  //  清明節
                     '2015-05-01',  //  メーデー
                     '2015-06-22',  //  端午節
                     '2015-10-01',  //  建国記念日
                     '2015-10-02',  //  建国記念日
                     '2015-10-05',  //  建国記念日
                     '2015-10-06',  //  建国記念日
                     '2015-10-07',  //  建国記念日

                     '2016-01-01', // New Year's Day [sh]
                     '2016-02-08', // Lunar New Year 1
                     '2016-02-09', // Lunar New Year 2
                     '2016-02-10', // Lunar New Year 3
                     '2016-02-11', // Lunar New Year 4
                     '2016-02-12', // Lunar New Year 5
                     '2016-04-04', // Ching Ming Festival
                     '2016-05-02', // Labour Day Holiday 2
                     '2016-06-09', // Dragon Boat Festival (Tuen Ng Day)*
                     '2016-06-10', // Dragon Boat Festival Holiday
                     '2016-09-15', // Mid-autumn Festival*
                     '2016-09-16', // Mid-autumn Festival Holiday
                     '2016-10-03', // National Day 3
                     '2016-10-04', // National Day 4
                     '2016-10-05', // National Day 5
                     '2016-10-06', // National Day 6
                     '2016-10-07', // National Day 7

                     '2017-01-02', // New Year's Day OBS
                     '2017-01-27', // Lunar NY Eve 1
                     '2017-01-30', // Lunar New Year 3
                     '2017-01-31', // Lunar New Year 4
                     '2017-02-01', // Lunar New Year 8
                     '2017-02-02', // Lunar New Year 5
                     '2017-04-03', // Ching Ming Festival Eve
                     '2017-04-04', // Ching Ming Festival
                     '2017-05-01', // Labour Day 1
                     '2017-05-29', // Dragon Boat Festival Holiday
                     '2017-05-30', // Dragon Boat Festival (Tuen Ng Day)*
                     '2017-10-02', // National Day 2
                     '2017-10-03', // National Day 3
                     '2017-10-04', // Mid-autumn Festival*
                     '2017-10-05', // National Day 5
                     '2017-10-06', // National Day 6
                   ),

  // イギリス
  self::UK => array( '2015-01-01', // New Year's Day
                     '2015-04-03', // Good Friday
                     '2015-04-06', // Easter Monday
                     '2015-05-04', // Early May Bank Holiday
                     '2015-05-25', // Late May Bank Holiday
                     '2015-08-31', // Summer Bank Holiday
                     '2015-12-25', // Christmas
                     '2015-12-28', // Boxing Day OBS

                     '2016-01-01', // New Year's Day [uk]
                     '2016-03-25', // Good Friday
                     '2016-03-28', // Easter Monday
                     '2016-05-02', // Early May Bank Holiday
                     '2016-05-30', // Late May Bank Holiday
                     '2016-08-29', // Summer Bank Holiday
                     '2016-12-26', // Christmas OBS
                     '2016-12-27', // Boxing Day OBS

                     '2017-01-02', // New Year's Day OBS
                     '2017-04-14', // Good Friday
                     '2017-04-17', // Easter Monday
                     '2017-05-01', // Early May Bank Holiday
                     '2017-05-29', // Late May Bank Holiday
                     '2017-08-28', // Summer Bank Holiday
                     '2017-12-25', // Christmas
                     '2017-12-26', // Boxing Day

                    ),

  // ドイツ
  self::GM => array( '2015-01-01', // New Year's Day
                     '2015-04-03', // Good Friday
                     '2015-04-06', // Easter Monday
                     '2015-05-01', // Labour Day
                     '2015-05-25', // May Holiday
                     '2015-12-24', // Christmas Eve
                     '2015-12-25', // Christmas Day
                     '2015-12-31', // New Year's Eve

                     '2016-01-01', // New Year's Day [gm]
                     '2016-03-25', // Good Friday
                     '2016-03-28', // Easter Monday
                     '2016-05-16', // Whitmonday
                     '2016-10-03', // National Day
                     '2016-12-26', // Christmas Holiday

                     '2017-04-14', // Good Friday
                     '2017-04-17', // Easter Monday
                     '2017-05-01', // Labour Day
                     '2017-12-25', // Christmas Day
                     '2017-12-26', // Christmas Holiday
                    ),

  // アメリカ
  self::US => array( '2015-01-01',  //  New Years Day
                     '2015-01-19',  //  Martin Luther King, Jr. Day
                     '2015-02-16',  //  Washington's Birthday
                     '2015-04-03',  //  Good Friday
                     '2015-05-25',  //  Memorial Day
                     '2015-07-03',  //  Independence Day
                     '2015-09-07',  //  Labor Day
                     '2015-11-26',  //  Thanksgiving Day
                     '2015-12-25',  //  Christmas

                     '2015-01-01', // New Year's Day [us]
                     '2015-01-19', // Martin Luther King Jr. Day
                     '2015-02-16', // Presidents' Day
                     '2015-04-03', // Good Friday
                     '2015-05-25', // Memorial Day
                     '2015-07-03', // Independence Day OBS
                     '2015-09-07', // Labor Day
                     '2015-11-26', // Thanksgiving
                     '2015-12-25', // Christmas

                     '2017-01-02', // New Year's Day OBS
                     '2017-01-16', // Martin Luther King Jr. Day
                     '2017-02-20', // Presidents' Day
                     '2017-04-14', // Good Friday
                     '2017-05-29', // Memorial Day
                     '2017-07-04', // Independence Day
                     '2017-09-04', // Labor Day
                     '2017-11-23', // Thanksgiving
                     '2017-12-25', // Christmas
                   ),
  );
?>
