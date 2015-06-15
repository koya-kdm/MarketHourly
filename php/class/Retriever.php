<?php
/*===============================
  株価取得クラス
=================================*/
class Retriever
{
  // 取得元
  const SRC_YAHOO  = 'yh';
  const SRC_GOOGLE = 'gg';
  const SRC_NIKKEI = 'nk';
  
  // Yahoo Finace ベースURL
  const URL_YAHOO = 'http://finance.yahoo.com/d/quotes.csv';

  // Google Finace ベースURL
  const URL_GOOGLE = 'https://www.google.com/finance';
  
  // 日経平均URL
  const URL_NIKKEI = 'http://indexes.nikkei.co.jp/nkave/index/profile?idx=nk225';
  
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
    $yahooUrl    = $this->createYahooUrl($assetsByMarket);
    $yahooHandle = fopen($yahooUrl, 'r');
    
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
          
          // 日経新聞
          case self::SRC_NIKKEI:
            $this->retrieveStockPriceFromNikkei($asset);
            break;
        }
      }
    }
    
    fclose($yahooHandle);

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
      
      // 前日比（％）
      if (preg_match('/<meta itemprop="price".*?content="([\d,.]*)".*?\/>/is', $shareBox, $matches))
      {
        $asset->setPrice(str_replace(',', '', $matches[1]));
      }
      
      // 前日比（％）
      if (preg_match('/<meta itemprop="priceChangePercent".*?content="([\d.-]*)".*?\/>/is', $shareBox, $matches))
      {
        $asset->setChange('+' . $matches[1] . '%');
        $asset->setChange(str_replace('+-', '-', $asset->getChange()));
      }
    }
    
    return;
  }
  
  /*---------------------------
    retrieveStockPriceFromNikkei
  -----------------------------*/
  private function retrieveStockPriceFromNikkei(&$asset)
  {
    $html = file_get_contents(self::URL_NIKKEI);
    
    /* HTML
    <td class="cmn-index_value">
      <!--daily_changing--><b>19,653.27</b>
    </td>
    <tr>
      <!--daily_changing-->
      <td colspan="2" class="cmn-index_up">
        <!--daily_changing--><b>+274.08 (+1.41%)</b>
      </td>
    </tr>
    */
    
    // 現在値
    if (preg_match('/cmn-index_value.*<b>([\d,.]*)<\/b>/is', $html, $matches))
    {
      $asset->setPrice(str_replace(',', '', $matches[1]));
    }
    
    // 前日比
    if (preg_match('/cmn-index_[up|down].*<b>.*\(([\d.+-]*%)\)<\/b>/is', $html, $matches))
    {
      $asset->setChange($matches[1]);
    }
    
    return;
  }
  
  
  /*--------------------
    retrieveBondsFromBloomberg
  ---------------------*/
  public function retrieveBondsFromBloomberg()
  {
    $bonds = array();
    
    $html = file_get_contents('http://www.bloomberg.com/markets/rates-bonds');
    
    /* HTML
      
         {"name":"US TREASURY N/B","longName":"United States","country":"US","coupon":2.125,"price":97.65625,"yield":2.3918054,"yieldChange1Day":0.0145931,"yieldChange1Month":11.02853,"lastUpdateTime":"2015-06-12","id":"CT10:GOV"}
      {"name":"BUNDESREPUB. DEUTSCHLAND","longName":"Germany","country":"DE","coupon":0.5,"price":96.915,"yield":0.8322577,"yieldChange1Day":-0.04895945,"yieldChange1Month":11.05648483,"lastUpdateTime":"2015-06-12","id":"CTDEM10Y:GOV"}
             {"name":"JAPAN (10 YR ISSUE)","longName":"Japan","country":"JP","coupon":0.4,"price":98.963,"yield":0.5,"yieldChange1Day":-0.02499998,"lastUpdateTime":"2015-06-12","id":"CTJPY10Y:GOV"}
    */
    
    // 米国
    //if (preg_match('/{"name":"US TREASURY N\/B","longName":"United States","country":"US","coupon":([\d.+-]*),"price":([\d.+-]*),"yield":([\d.+-]*),"yieldChange1Day":([\d.+-]*),"yieldChange1Month":([\d.+-]*),"lastUpdateTime":"([\d-]*)","id":"CT10:GOV"}/is', $html, $matches))
    if (preg_match('/{"name":"US TREASURY N\/B","longName":"United States","country":"US","coupon":([\d.+-]*),"price":([\d.+-]*),"yield":([\d.+-]*),"yieldChange1Day":([\d.+-]*),"yieldChange1Month":([\d.+-]*),/is', $html, $matches))
    {
      $bonds['us'] = array('coupon'          => $matches[1],
                           'price'           => $matches[2],
                           'yield'           => $matches[3],
                           'yieldChange1Day' => $matches[4],
                           'lastUpdateTime'  => $matches[5],
                          );
    }
    
    // 日本
    //if (preg_match('/{"name":"JAPAN \(10 YR ISSUE\)","longName":"Japan","country":"JP","coupon":([\d.+-]*),"price":([\d.+-]*),"yield":([\d.+-]*),"yieldChange1Day":([\d.+-]*),"lastUpdateTime":"([\d-]*)","id":"CTJPY10Y:GOV"}/is', $html, $matches))
    if (preg_match('/{"name":"JAPAN \(10 YR ISSUE\)","longName":"Japan","country":"JP","coupon":([\d.+-]*),"price":([\d.+-]*),"yield":([\d.+-]*),"yieldChange1Day":([\d.+-]*),/is', $html, $matches))
    {
      $bonds['jp'] = array('coupon'          => $matches[1],
                           'price'           => $matches[2],
                           'yield'           => $matches[3],
                           'yieldChange1Day' => $matches[4],
                           'lastUpdateTime'  => $matches[5],
                          );
    } 
    
    // ドイツ
    //if (preg_match('/{"name":"BUNDESREPUB\. DEUTSCHLAND","longName":"Germany","country":"DE","coupon":([\d.+-]*),"price":([\d.+-]*),"yield":([\d.+-]*),"yieldChange1Day":([\d.+-]*),"yieldChange1Month":([\d.+-]*),"lastUpdateTime":"([\d-]*)","id":"CTDEM10Y:GOV"}/is', $html, $matches))
    if (preg_match('/{"name":"BUNDESREPUB\. DEUTSCHLAND","longName":"Germany","country":"DE","coupon":([\d.+-]*),"price":([\d.+-]*),"yield":([\d.+-]*),"yieldChange1Day":([\d.+-]*),"yieldChange1Month":([\d.+-]*),/is', $html, $matches))
    {
      $bonds['de'] = array('coupon'          => $matches[1],
                           'price'           => $matches[2],
                           'yield'           => $matches[3],
                           'yieldChange1Day' => $matches[4],
                           'lastUpdateTime'  => $matches[5],
                          );
    }
    
    return $bonds;
  }
  
  /*--------------------
    retrieveBonds
  ---------------------*/
  public function retrieveBonds()
  {
    $bonds = array();
    
    /* HTML
    var quoteDataObj = [{"symbol":"US10Y","symbolType":"symbol","code":0,"name":"U.S. 10 Year Treasury","shortName":"US 10-YR","last":"2.3595","exchange":"U.S.","source":"Exchange","open":"0.00","high":"0.00","low":"0.00","change":"0.00","currencyCode":"USD","timeZone":"EDT","volume":"0","provider":"CNBC Quote Cache","altSymbol":"US10YT\u003dXX","curmktstatus":"REG_MKT","realTime":"true","assetType":"BOND","noStreaming":"false","encodedSymbol":"US10Y"}]
    var quoteDataObj = [{"symbol":"JP10Y-JP","symbolType":"symbol","code":0,"name":"Japan 10 Year Treasury","shortName":"JPN 10-YR","last":"0.507","exchange":"Japan","source":"Exchange","open":"0.00","high":"0.00","low":"0.00","change":"0.00","currencyCode":"USD","timeZone":"JST","volume":"0","provider":"CNBC Quote Cache","altSymbol":"20380005","curmktstatus":"REG_MKT","realTime":"true","assetType":"BOND","noStreaming":"false","encodedSymbol":"JP10Y-JP"}]
    var quoteDataObj = [{"symbol":"DE10Y-DE","symbolType":"symbol","code":0,"name":"Germany 10 Year Bond","shortName":"GER 10-YR","last":"0.827","exchange":"Germany","source":"Exchange","open":"0.812","high":"0.845","low":"0.785","change":"-0.00","currencyCode":"USD","timeZone":"CEST","volume":"0","provider":"CNBC Quote Cache","altSymbol":"5767338","curmktstatus":"REG_MKT","realTime":"true","assetType":"BOND","noStreaming":"false","encodedSymbol":"DE10Y-DE"}]
    */
    
    // 米国
    $html = file_get_contents('http://data.cnbc.com/quotes/US10Y');
    if (preg_match('/var quoteDataObj = \[{"symbol":"US10Y","symbolType":"symbol","code":0,"name":"U.S. 10 Year Treasury","shortName":"US 10-YR","last":"([\d.]*)",/is', 
                   $html, 
                   $matches))
    {
      $bonds['us']['yield'] = $matches[1];
    }
    
    // 日本
    $html = file_get_contents('http://data.cnbc.com/quotes/JP10Y-JP');
    if (preg_match('/var quoteDataObj = \[{"symbol":"JP10Y-JP","symbolType":"symbol","code":0,"name":"Japan 10 Year Treasury","shortName":"JPN 10-YR","last":"([\d.]*)",/is', 
                   $html, 
                   $matches))
    {
      $bonds['jp']['yield'] = $matches[1];
    }
    
    // ドイツ
    $html = file_get_contents('http://data.cnbc.com/quotes/DE10Y-DE');
    if (preg_match('/var quoteDataObj = \[{"symbol":"DE10Y-DE","symbolType":"symbol","code":0,"name":"Germany 10 Year Bond","shortName":"GER 10-YR","last":"([\d.]*)",/is', 
                   $html, 
                   $matches))
    {
      $bonds['de']['yield'] = $matches[1];
    }
    
    return $bonds;
  }
}
?>
