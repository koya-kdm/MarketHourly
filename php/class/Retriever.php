<?php
/*===============================
  株価取得クラス
=================================*/
class Retriever
{
  // Yahoo Finace ベースURL
  const YAHOO_BASE_URL = 'http://finance.yahoo.com/d/quotes.csv';

  // Google Finace ベースURL
  const GOOGLE_BASE_URL = 'https://www.google.com/finance';
  
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
  var $yahooParams  = array('s', 'l1', 'p2');
  
  
  /*---------------------------
    createUrl
  -----------------------------*/
  function createUrl($assetsByMarket)
  {
    // e.g.) http://finance.yahoo.com/d/quotes.csv?s=INDU+^IXIC+USDJPY=X+^N225&f=snl1c1p2d1t1
    
    $tickerString = '';
    
    foreach ($assetsByMarket as $market => $assets)
    {
      foreach ($assets as $key => $asset)
      {
        $tickerString = $tickerString . $asset->getTicker() . '+';
      }
    }
    
    $url= self::YAHOO_BASE_URL . '?s=' . $tickerString . '&f=' . implode('', $this->yahooParams);

    return $url;
  }
  
  /*--------------------
    retrieveStockPrice
  ---------------------*/
  function retrieveStockPrice(&$assetsByMarket)
  {
    $url = $this->createUrl($assetsByMarket);
    
    $handle = fopen($url, 'r');
    
    foreach ($assetsByMarket as $market => $assets)
    {
      foreach ($assets as $key => $asset)
      {
        $data = fgetcsv($handle, 1000, ',');
        
        if (true == $asset->getRetrievesFromGoogle())
        {
          $this->retrieveStockPriceFromGoogle($asset);
        }
        else
        {
          $asset->setPrice ($data[1]);
          $asset->setChange($data[2]);
        }
      }
    }
    
    fclose($handle);

    return;
  }
  
  /*---------------------------
    retrieveStockPriceFromGoogle
  -----------------------------*/
  public function retrieveStockPriceFromGoogle(&$asset)
  {
    $html = file_get_contents(self::GOOGLE_BASE_URL . '?q=' . $asset->getGoogleCode());
    
    if (preg_match('/<span id="ref_' . $asset->getGoogleCode() . '_l">([\d,.]*)<\/span>/is', $html, $matches))
    {
      $asset->setPrice(str_replace(',', '', $matches[1]));
    }
    
    if (preg_match('/<span class=".*" id="ref_' . $asset->getGoogleCode() . '_cp">\(([\d.-]*%)\)<\/span>/is', $html, $matches))
    {
      $asset->setChange('+' . $matches[1]);
      $asset->setChange(str_replace('+-', '-', $asset->getChange()));
    }
    
    return;
  }
}
?>
