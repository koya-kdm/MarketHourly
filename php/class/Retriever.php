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
}
?>
