<?php
/*===============================
  休場日
=================================*/
// http://markets.on.nytimes.com/research/markets/holidays/holidays.asp

$configHolidays = array(

  // 為替
  self::FX => array(),

  // 日本
  self::JP => array( '2018-01-01', // New Year's Day	Tokyo Stock ExchangeJAPAN
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

                     '2019-01-01', // Tuesday	New Year's Day
                     '2019-01-02', // Wednesday	Bank Holiday 2
                     '2019-01-03', // Thursday	Bank Holiday 3
                     '2019-01-14', // Monday	Coming of Age (Adults') Day
                     '2019-02-11', // Monday	National Founding Day
                     '2019-03-21', // Thursday	Vernal Equinox
                     '2019-04-29', // Monday	Showa Day (formerly Greenery Day)
                     '2019-04-30', // Tuesday	Bridge Holiday
                     '2019-05-01', // Wednesday	Accession to the Throne of New Emperor
                     '2019-05-02', // Thursday	Bridge Holiday 2
                     '2019-05-03', // Friday	Constitution Day
                     '2019-05-06', // Monday	Children's Day OBS
                     '2019-07-15', // Monday	Marine Day
                     '2019-08-12', // Monday	Mountain Day OBS
                     '2019-09-16', // Monday	Respect for the Aged Day
                     '2019-09-23', // Monday	Autumn Equinox
                     '2019-10-14', // Monday	Health-Sports Day
                     '2019-10-22', // Tuesday	Enthronement Ceremony
                     '2019-11-04', // Monday	Culture Day OBS
                     '2019-12-31', // Tuesday	New Year's Eve
                   ),

  // 香港
  self::HK => array( '2018-01-01', // New Year's Day	Hong Kong Stock ExchangeHONG KONG
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

                     '2019-01-01', // Tuesday	New Year's Day
                     '2019-02-05', // Tuesday	Lunar New Year 1
                     '2019-02-06', // Wednesday	Lunar New Year 2
                     '2019-02-07', // Thursday	Lunar New Year 3
                     '2019-04-05', // Friday	Ching Ming Festival
                     '2019-04-19', // Friday	Good Friday
                     '2019-04-22', // Monday	Easter Monday
                     '2019-05-01', // Wednesday	Labour Day
                     '2019-05-13', // Monday	Buddha's Birthday*
                     '2019-06-07', // Friday	Tuen Ng Day*
                     '2019-07-01', // Monday	SAR Establishment Day
                     '2019-10-01', // Tuesday	Chinese National Day
                     '2019-10-07', // Monday	Chung Yeung Day*
                     '2019-12-25', // Wednesday	Christmas Day
                     '2019-12-26', // Thursday	Christmas Holiday
                   ),

  // 上海
  self::SH => array( '2018-01-01', // New Year's Day	Shanghai Stock ExchangeCHINA
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

                     '2019-01-01', // Tuesday	New Year's Day
                     '2019-02-04', // Monday	Lunar NY Eve 1
                     '2019-02-05', // Tuesday	Lunar New Year 1
                     '2019-02-06', // Wednesday	Lunar New Year 2
                     '2019-02-07', // Thursday	Lunar New Year 3
                     '2019-02-08', // Friday	Lunar New Year 4
                     '2019-04-05', // Friday	Ching Ming Festival
                     '2019-05-01', // Wednesday	Labour Day 1
                     '2019-06-07', // Friday	Dragon Boat Festival (Tuen Ng Day)*
                     '2019-09-13', // Friday	Mid-autumn Festival*
                     '2019-10-01', // Tuesday	National Day 1
                     '2019-10-02', // Wednesday	National Day 2
                     '2019-10-03', // Thursday	National Day 3
                     '2019-10-04', // Friday	National Day 4
                     '2019-10-07', // Monday	National Day 7
                   ),

  // イギリス
  self::UK => array( '2018-01-01', // New Year's Day	London Stock ExchangeUNITED KINGDOM
                     '2018-03-30', // Good Friday	London Stock ExchangeUNITED KINGDOM
                     '2018-04-02', // Easter Monday	London Stock ExchangeUNITED KINGDOM
                     '2018-05-07', // Early May Bank Holiday	London Stock ExchangeUNITED KINGDOM
                     '2018-05-28', // Late May Bank Holiday	London Stock ExchangeUNITED KINGDOM
                     '2018-08-27', // Summer Bank Holiday	London Stock ExchangeUNITED KINGDOM
                     '2018-12-25', // Christmas	London Stock ExchangeUNITED KINGDOM
                     '2018-12-26', // Boxing Day	London Stock ExchangeUNITED KINGDOM

                     '2019-01-01', // Tuesday	New Year's Day
                     '2019-04-19', // Friday	Good Friday
                     '2019-04-22', // Monday	Easter Monday
                     '2019-05-06', // Monday	Early May Bank Holiday
                     '2019-05-27', // Monday	Late May Bank Holiday
                     '2019-08-26', // Monday	Summer Bank Holiday
                     '2019-12-25', // Wednesday	Christmas
                     '2019-12-26', // Thursday	Boxing Day
                    ),

  // ドイツ
  self::GM => array( '2018-01-01', // New Year's Day	Frankfurt Stock ExchangeGERMANY
                     '2018-03-30', // Good Friday	Frankfurt Stock ExchangeGERMANY
                     '2018-04-02', // Easter Monday	Frankfurt Stock ExchangeGERMANY
                     '2018-05-01', // Labour Day	Frankfurt Stock ExchangeGERMANY
                     '2018-05-21', // Whitmonday	Frankfurt Stock ExchangeGERMANY
                     '2018-10-03', // National Day	Frankfurt Stock ExchangeGERMANY
                     '2018-12-24', // Christmas Eve	Frankfurt Stock ExchangeGERMANY
                     '2018-12-25', // Christmas Day	Frankfurt Stock ExchangeGERMANY
                     '2018-12-26', // Christmas Holiday	Frankfurt Stock ExchangeGERMANY
                     '2018-12-31', // New Year's Eve	Frankfurt Stock ExchangeGERMANY

                     '2019-01-01', // Tuesday	New Year's Day
                     '2019-04-19', // Friday	Good Friday
                     '2019-04-22', // Monday	Easter Monday
                     '2019-05-01', // Wednesday	Labour Day
                     '2019-06-10', // Monday	Whitmonday
                     '2019-10-03', // Thursday	National Day
                     '2019-12-24', // Tuesday	Christmas Eve
                     '2019-12-25', // Wednesday	Christmas Day
                     '2019-12-26', // Thursday	Christmas Holiday
                     '2019-12-31', // Tuesday	New Year's Eve
                    ),

  // アメリカ
  self::US => array( '2018-01-01', // New Year's Day	New York Stock ExchangeUNITED STATES
                     '2018-01-15', // Martin Luther King Jr. Day	New York Stock ExchangeUNITED STATES
                     '2018-02-19', // Presidents' Day	New York Stock ExchangeUNITED STATES
                     '2018-03-30', // Good Friday	New York Stock ExchangeUNITED STATES
                     '2018-05-28', // Memorial Day	New York Stock ExchangeUNITED STATES
                     '2018-07-04', // Independence Day	New York Stock ExchangeUNITED STATES
                     '2018-09-03', // Labor Day	New York Stock ExchangeUNITED STATES
                     '2018-11-22', // Thanksgiving	New York Stock ExchangeUNITED STATES
                     '2018-12-25', // Christmas	New York Stock ExchangeUNITED STATES

                     '2019-01-01', // Tuesday	New Year's Day
                     '2019-01-21', // Monday	Martin Luther King Jr. Day
                     '2019-02-18', // Monday	Presidents' Day
                     '2019-04-19', // Friday	Good Friday
                     '2019-05-27', // Monday	Memorial Day
                     '2019-07-04', // Thursday	Independence Day
                     '2019-09-02', // Monday	Labor Day
                     '2019-11-28', // Thursday	Thanksgiving
                     '2019-12-25', // Wednesday	Christmas
                   ),
  );
?>
