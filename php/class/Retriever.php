<?php
/*===============================
  株価取得クラス
=================================*/
class Retriever
{
  // 取得元
  const SRC_YAHOO  = 'yh';
  const SRC_GOOGLE = 'gg';
  const SRC_MARKETW = 'mw';
  const SRC_NIKKEI = 'nk';
  const SRC_JPX    = 'jx';

  // Yahoo Finace ベースURL
  const URL_YAHOO = 'http://finance.yahoo.com/d/quotes.csv';

  // Google Finace ベースURL
  const URL_GOOGLE = 'https://finance.google.com/finance';

  // MarketWatch ベースURL
  const URL_MARKETW = 'https://www.marketwatch.com/investing/';

  // 日経平均URL
  const URL_NIKKEI = 'http://indexes.nikkei.co.jp/nkave/index/profile?idx=nk225';

  // 日本取引所グループ
  const URL_JPX = 'http://quote.jpx.co.jp/jpx/template/quote.cgi?F=tmp/real_index&QCODE=';

  // Yahoo Finance パラメータ
  /*
  s  = Symbol
  n  = Name
  l1 = Last Trade (Price Only)
  d1 = Last Trade Date
  t1 = Last Trade Time
  c  = Change and Percent Change
  v  = Volume
  c6 = Change (Realtime)
  k2 = Change Percent (Realtime)
  p2 = Change in Percent
  */
  private $yahooParams  = array('s', 'l1', 'p2');


  /*--------------------
    retrieveStockPrice
  ---------------------*/
  public function retrieveStockPrice(&$assetsByMarket)
  {
    // YahooはCSV
    $yahooHandle = false;
    $yahooUrl    = $this->createYahooUrl($assetsByMarket);
    if (false != $yahooUrl) {
      $yahooHandle = fopen($yahooUrl, 'r');
    }

    foreach ($assetsByMarket as $market => $assets)
    {
      foreach ($assets as $key => $asset)
      {
        switch ($asset->getSource())
        {
          // Yahoo
          case self::SRC_YAHOO:
            $data = fgetcsv($yahooHandle, 1000, ',');
            $asset->setPrice ($data[1]);
            $asset->setChange($data[2]);
            break;

          // Google
          case self::SRC_GOOGLE:
            $this->retrieveStockPriceFromGoogle($asset);
            break;

          // MarketWatch
          case self::SRC_MARKETW:
            $this->retrieveStockPriceFromMarketw($asset);
            break;

          // 日経新聞
          case self::SRC_NIKKEI:
            $this->retrieveStockPriceFromNikkei($asset);
            break;

          // 日本取引所グループ
          case self::SRC_JPX:
            $this->retrieveStockPriceFromJpx($asset);
            break;

        }
      }
    }

    if (false != $yahooHandle) {
      fclose($yahooHandle);
    }

    return;
  }

  /*---------------------------
    createYahooUrl
  -----------------------------*/
  private function createYahooUrl($assetsByMarket)
  {
    // e.g.) http://finance.yahoo.com/d/quotes.csv?s=INDU+^IXIC+USDJPY=X+^N225&f=snl1c1p2d1t1

    $tickerString = '';

    foreach ($assetsByMarket as $market => $assets)
    {
      foreach ($assets as $key => $asset)
      {
        if ($asset->getSource() == self::SRC_YAHOO)
        {
          $tickerString = $tickerString . $asset->getTicker() . '+';
        }
      }
    }

    if ('' == $tickerString) {
      return false;
    }

    $url= self::URL_YAHOO . '?s=' . $tickerString . '&f=' . implode('', $this->yahooParams);

    return $url;
  }

  /*---------------------------
    retrieveStockPriceFromGoogle
  -----------------------------*/
  private function retrieveStockPriceFromGoogle(&$asset)
  {
    $html = file_get_contents(self::URL_GOOGLE . '?q=' . $asset->getTicker());

    if (preg_match('/<div id="sharebox-data".*?<\/div>/is', $html, $matches))
    {
      $shareBox = $matches[0];

      // 株価
      if (preg_match('/<meta itemprop="price".*?content="([\d,.]*)".*?\/>/is', $shareBox, $matches))
      {
        $asset->setPrice(str_replace(',', '', $matches[1]));
      }

      // 前日比
      if (preg_match('/<meta itemprop="priceChange".*?content="([\d.+-]*)".*?\/>.*?<meta itemprop="priceChangePercent".*?content="([\d.-]*)".*?\/>/is', $shareBox, $matches))
      {
        $asset->setChangeByPoint($matches[1]);
        $asset->setChange('+' . $matches[2] . '%');
        $asset->setChange(str_replace('+-', '-', $asset->getChange()));
      }
    }

    return;
  }

  /*---------------------------
    retrieveStockPriceFromMarketw
  -----------------------------*/
  private function retrieveStockPriceFromMarketw(&$asset)
  {
    $html = file_get_contents(self::URL_MARKETW . $asset->getTicker());

    // 株価
    if (preg_match('/<meta name="price" content="(.*?)">/is', $html, $matches))
    {
      $asset->setPrice(str_replace(',', '', $matches[1]));
    }

    // 前日比
    if (preg_match('/<meta name="priceChange" content="(.*?)">/is', $html, $matches))
    {
      $asset->setChangeByPoint($matches[1]);
    }
    if (preg_match('/<meta name="priceChangePercent" content="(.*?)">/is', $html, $matches))
    {
      $asset->setChange('+' . $matches[1]);
      $asset->setChange(str_replace('+-', '-', $asset->getChange()));
    }

    return;
  }

  /*---------------------------
    retrieveStockPriceFromNikkei
  -----------------------------*/
  private function retrieveStockPriceFromNikkei(&$asset)
  {
    $html = file_get_contents(self::URL_NIKKEI);

    // 現在値
    if (preg_match('/<div class="index-close col-sm-6 col-sm-push-3 col-sm-pull-3" id="price">.*?<!--daily_changing-->([\d,.+-]*)/is', $html, $matches))
    {
      $asset->setPrice(str_replace(',', '', $matches[1]));
    }

    // 前日比
    if (preg_match('/<div class="index-rate col-sm-7 col-sm-push-3 col-sm-pull-3" id="diff">.*?<!--daily_changing-->([\d,.+-]*) \(([\d.+-]*%)\)/is', $html, $matches))
    {
      $asset->setChangeByPoint($matches[1]);
      $asset->setChange       ($matches[2]);
    }

    return;
  }

  /*---------------------------
    retrieveStockPriceFromJpx
  -----------------------------*/
  private function retrieveStockPriceFromJpx(&$asset)
  {
    $html = file_get_contents(self::URL_JPX . $asset->getTicker());

    if (preg_match('/<div class="component-normal-table">.*?<td.*?td>.*?<td.*?td>.*?<td.*?td>.*?<td.*?td>.*?<td.*?>\s*(.*?)<br \/>.*?<\/td>.*?<td.*?>\s*(.*?)<br \/>\((.*?)％\).*?<\/td>/is',
                   $html,
                   $matches))
    {
      $asset->setPrice(str_replace(',', '', $matches[1])); // 現在値
      $asset->setChangeByPoint($matches[2]);               // 前日比PT
      $asset->setChange       ($matches[3] . '%');         // 前日比％
    }

    return;
  }

  /*--------------------
    retrieveBonds
  ---------------------*/
  public function retrieveBonds()
  {
    $bonds = array();

    // 米国
    $bonds['us'] = $this->retrieveStockPriceFromCnbc('bond/tmubmusd10y?countrycode=bx');


    // 日本
    $bonds['jp'] = $this->retrieveStockPriceFromCnbc('bond/tmbmkjp-10y?countrycode=bx');

    // ドイツ
    $bonds['de'] = $this->retrieveStockPriceFromCnbc('bond/tmbmkde-10y?countrycode=bx');

    return $bonds;
  }

  /*--------------------
    retrieveCommodities
  ---------------------*/
  public function retrieveCommodities()
  {
    $commodities = array();

    // WTI Crude Oil
    $commodities['oil' ] = $this->retrieveStockPriceFromCnbc('future/crude%20oil%20-%20electronic');

    // Gold
    $commodities['gold'] = $this->retrieveStockPriceFromCnbc('future/gold');

    return $commodities;
  }

  /*---------------------------
   retrieveStockPriceFromCnbc
  -----------------------------*/
  private function retrieveStockPriceFromCnbc($quoteUrl)
  {
    $cnbcAsset = array();

    $html = file_get_contents('http://data.cnbc.com/quotes/' . $quoteUrl);
    if (preg_match('/var quoteDataObj = \[{(.*)}]/is',
                   $html,
                   $matches))
    {
      $quoteData = $this->getQuoteDataArray($matches[1]);

      $cnbcAsset['last'          ] = $quoteData['last'];
      $cnbcAsset['change'        ] = $quoteData['change'];
      $cnbcAsset['change_percent'] = floatval($quoteData['change'])
                                   / (  floatval($quoteData['last'])
                                      - floatval($quoteData['change']))
                                   * 100;
    }

    return $cnbcAsset;
  }

  /*---------------------------
   retrieveStockPriceFromMarketw2
  -----------------------------*/
  private function retrieveStockPriceFromMarketw2($quoteUrl)
  {
    $cnbcAsset = array();

    $html = file_get_contents('https://www.marketwatch.com/investing/' . $quoteUrl);

    // 株価
    if (preg_match('/<meta name="price" content="(.*?)">/is', $html, $matches))
    {
      $asset['last'] = str_replace(',', '', $matches[1]);
    }

    // 前日比
    if (preg_match('/<meta name="priceChange" content="(.*?)">/is', $html, $matches))
    {
      $asset['change'] = str_replace(',', '', $matches[1]);
    }
    if (preg_match('/<meta name="priceChangePercent" content="(.*?)">/is', $html, $matches))
    {
      $asset['change_percent'] = '+' . $matches[1];
      $asset['change_percent'] = str_replace('+-', '-', $asset['change_percent']);
    }

    return $cnbcAsset;
  }

  /*---------------------------
   getQuoteDataArray
  -----------------------------*/
  private function getQuoteDataArray($string)
  {
    $quoteData = array();

    $strings1 = explode(',', $string); // numeric array of '"key1":"value1"'
                                       //                  '"key2":"value2"'

    foreach ($strings1 as $string1)
    {
      $strings2 = explode(':', $string1);  // numeric array of '"key1"'
                                           //                  '"value1"'
                                           //                  '"key2"'
                                           //                  '"value2"'

      for ($i = 0; $i < count($strings2); $i++)
      {
        if (isset($strings2[$i]))
        {
          if (isset($strings2[$i+1]))
          {
            $quoteData[str_replace('"', '', $strings2[$i])] = str_replace('"', '', $strings2[$i+1]);
          }
          else
          {
            $quoteData[str_replace('"', '', $strings2[$i])] = '';
          }
        }
        $i++;
      }
    }

    return $quoteData;
  }
}
?>
