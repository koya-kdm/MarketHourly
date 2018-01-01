<?php
/*===============================
  休場日
=================================*/
// http://markets.on.nytimes.com/research/markets/holidays/holidays.asp

$configHolidays = array(

  // 為替
  self::FX => array(),

  // 日本
  self::JP => array( '2017-01-02', // Bank Holiday 2
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

                     '2018-01-01', // New Year's Day	Tokyo Stock ExchangeJAPAN
                     '2018-01-02', // Bank Holiday 2	Tokyo Stock ExchangeJAPAN
                     '2018-01-03', // Bank Holiday 3	Tokyo Stock ExchangeJAPAN
                     '2018-01-08', // Coming of Age (Adults') Day	Tokyo Stock ExchangeJAPAN
                     '2018-02-12', // National Founding Day OBS	Tokyo Stock ExchangeJAPAN
                     '2018-03-21', // Vernal Equinox	Tokyo Stock ExchangeJAPAN
                     '2018-04-30', // Showa Day (formerly Greenery Day) OBS	Tokyo Stock ExchangeJAPAN
                     '2018-05-03', // Constitution Day	Tokyo Stock ExchangeJAPAN
                     '2018-05-04', // Greenery Day (formerly National Holiday)	Tokyo Stock ExchangeJAPAN
                     '2018-07-16', // Marine Day	Tokyo Stock ExchangeJAPAN
                     '2018-09-17', // Respect for the Aged Day	Tokyo Stock ExchangeJAPAN
                     '2018-09-24', // Autumn Equinox OBS	Tokyo Stock ExchangeJAPAN
                     '2018-10-08', // Health-Sports Day	Tokyo Stock ExchangeJAPAN
                     '2018-11-23', // Labour Thanksgiving Day	Tokyo Stock ExchangeJAPAN
                     '2018-12-24', // Emperor's Birthday OBS	Tokyo Stock ExchangeJAPAN
                     '2018-12-31', // New Year's Eve	Tokyo Stock ExchangeJAPAN

                   ),

  // 香港
  self::HK => array( '2017-01-02', // New Year's Day OBS
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

                     '2018-01-01', // New Year's Day	Hong Kong Stock ExchangeHONG KONG
                     '2018-02-16', // Lunar New Year 1	Hong Kong Stock ExchangeHONG KONG
                     '2018-02-19', // Lunar New Year 4	Hong Kong Stock ExchangeHONG KONG
                     '2018-03-30', // Good Friday	Hong Kong Stock ExchangeHONG KONG
                     '2018-04-02', // Easter Monday	Hong Kong Stock ExchangeHONG KONG
                     '2018-04-05', // Ching Ming Festival	Hong Kong Stock ExchangeHONG KONG
                     '2018-05-01', // Labour Day	Hong Kong Stock ExchangeHONG KONG
                     '2018-05-22', // Buddha's Birthday*	Hong Kong Stock ExchangeHONG KONG
                     '2018-06-18', // Tuen Ng Day*	Hong Kong Stock ExchangeHONG KONG
                     '2018-07-02', // SAR Establishment Day OBS	Hong Kong Stock ExchangeHONG KONG
                     '2018-09-25', // Day Following Mid-autumn Festival*	Hong Kong Stock ExchangeHONG KONG
                     '2018-10-01', // Chinese National Day	Hong Kong Stock ExchangeHONG KONG
                     '2018-10-17', // Chung Yeung Day*	Hong Kong Stock ExchangeHONG KONG
                     '2018-12-25', // Christmas Day	Hong Kong Stock ExchangeHONG KONG
                     '2018-12-26', // Christmas Holiday	Hong Kong Stock ExchangeHONG KONG

                   ),

  // 上海
  self::SH => array( '2017-01-02', // New Year's Day OBS
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

                     '2018-01-01', // New Year's Day	Shanghai Stock ExchangeCHINA
                     '2018-02-15', // Lunar NY Eve 1	Shanghai Stock ExchangeCHINA
                     '2018-02-16', // Lunar New Year 1	Shanghai Stock ExchangeCHINA
                     '2018-02-19', // Lunar New Year 4	Shanghai Stock ExchangeCHINA
                     '2018-02-20', // Lunar New Year 5	Shanghai Stock ExchangeCHINA
                     '2018-02-21', // Lunar New Year 6	Shanghai Stock ExchangeCHINA
                     '2018-04-05', // Ching Ming Festival	Shanghai Stock ExchangeCHINA
                     '2018-04-06', // Ching Ming Festival Holiday	Shanghai Stock ExchangeCHINA
                     '2018-04-30', // Labour Day Holiday	Shanghai Stock ExchangeCHINA
                     '2018-05-01', // Labour Day 1	Shanghai Stock ExchangeCHINA
                     '2018-06-18', // Dragon Boat Festival (Tuen Ng Day)*	Shanghai Stock ExchangeCHINA
                     '2018-09-24', // Mid-autumn Festival*	Shanghai Stock ExchangeCHINA
                     '2018-10-01', // National Day 1	Shanghai Stock ExchangeCHINA
                     '2018-10-02', // National Day 2	Shanghai Stock ExchangeCHINA
                     '2018-10-03', // National Day 3	Shanghai Stock ExchangeCHINA
                     '2018-10-04', // National Day 4	Shanghai Stock ExchangeCHINA
                     '2018-10-05', // National Day 5	Shanghai Stock ExchangeCHINA
                     '2018-12-31', // Additional New Year Holiday	Shanghai Stock ExchangeCHINA

                   ),

  // イギリス
  self::UK => array( '2017-01-02', // New Year's Day OBS
                     '2017-04-14', // Good Friday
                     '2017-04-17', // Easter Monday
                     '2017-05-01', // Early May Bank Holiday
                     '2017-05-29', // Late May Bank Holiday
                     '2017-08-28', // Summer Bank Holiday
                     '2017-12-25', // Christmas
                     '2017-12-26', // Boxing Day

                     '2018-01-01', // New Year's Day	London Stock ExchangeUNITED KINGDOM
                     '2018-03-30', // Good Friday	London Stock ExchangeUNITED KINGDOM
                     '2018-04-02', // Easter Monday	London Stock ExchangeUNITED KINGDOM
                     '2018-05-07', // Early May Bank Holiday	London Stock ExchangeUNITED KINGDOM
                     '2018-05-28', // Late May Bank Holiday	London Stock ExchangeUNITED KINGDOM
                     '2018-08-27', // Summer Bank Holiday	London Stock ExchangeUNITED KINGDOM
                     '2018-12-25', // Christmas	London Stock ExchangeUNITED KINGDOM
                     '2018-12-26', // Boxing Day	London Stock ExchangeUNITED KINGDOM

                    ),

  // ドイツ
  self::GM => array( '2017-04-14', // Good Friday
                     '2017-04-17', // Easter Monday
                     '2017-05-01', // Labour Day
                     '2017-12-25', // Christmas Day
                     '2017-12-26', // Christmas Holiday

                     '2018-01-01', // New Year's Day	Frankfurt Stock ExchangeGERMANY
                     '2018-03-30', // Good Friday	Frankfurt Stock ExchangeGERMANY
                     '2018-04-02', // Easter Monday	Frankfurt Stock ExchangeGERMANY
                     '2018-05-01', // Labour Day	Frankfurt Stock ExchangeGERMANY
                     '2018-05-21', // Whitmonday	Frankfurt Stock ExchangeGERMANY
                     '2018-10-03', // National Day	Frankfurt Stock ExchangeGERMANY
                     '2018-12-24', // Christmas Eve	Frankfurt Stock ExchangeGERMANY
                     '2018-12-25', // Christmas Day	Frankfurt Stock ExchangeGERMANY
                     '2018-12-26', // Christmas Holiday	Frankfurt Stock ExchangeGERMANY
                     '2018-12-31', // New Year's Eve	Frankfurt Stock ExchangeGERMANY

                    ),

  // アメリカ
  self::US => array( '2017-01-02', // New Year's Day OBS
                     '2017-01-16', // Martin Luther King Jr. Day
                     '2017-02-20', // Presidents' Day
                     '2017-04-14', // Good Friday
                     '2017-05-29', // Memorial Day
                     '2017-07-04', // Independence Day
                     '2017-09-04', // Labor Day
                     '2017-11-23', // Thanksgiving
                     '2017-12-25', // Christmas

                     '2018-01-01', // New Year's Day	New York Stock ExchangeUNITED STATES
                     '2018-01-15', // Martin Luther King Jr. Day	New York Stock ExchangeUNITED STATES
                     '2018-02-19', // Presidents' Day	New York Stock ExchangeUNITED STATES
                     '2018-03-30', // Good Friday	New York Stock ExchangeUNITED STATES
                     '2018-05-28', // Memorial Day	New York Stock ExchangeUNITED STATES
                     '2018-07-04', // Independence Day	New York Stock ExchangeUNITED STATES
                     '2018-09-03', // Labor Day	New York Stock ExchangeUNITED STATES
                     '2018-11-22', // Thanksgiving	New York Stock ExchangeUNITED STATES
                     '2018-12-25', // Christmas	New York Stock ExchangeUNITED STATES
                     
                   ),
  );
?>
