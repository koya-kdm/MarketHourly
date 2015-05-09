<?php

class Asset
{
  var $title;
  var $ticker;
  var $unit;
  var $decimals;
  var $market;
  var $displaysChange;
  var $retrievesFromGoogle;
  var $googleCode;
  
  var $price;
  var $change;
  
  /*---------------------------
    __construct
  -----------------------------*/
  public function __construct($title,
                              $ticker,
                              $unit,
                              $decimals,
                              $market,
                              $displaysChange,
                              $retrievesFromGoogle,
                              $googleCode
                             )
  {
    $this->title               = $title;
    $this->ticker              = $ticker;
    $this->unit                = $unit;
    $this->decimals            = $decimals;
    $this->market              = $market;
    $this->displaysChange      = $displaysChange;
    $this->retrievesFromGoogle = $retrievesFromGoogle;
    $this->googleCode          = $googleCode;
    
    return;
  }
  

  /*===========================
    アクセッサ
  =============================*/
  public function getTitle              () { return $this->title;              }
  public function getTicker             () { return $this->ticker;             }
  public function getUnit               () { return $this->unit;               }
  public function getDecimals           () { return $this->decimals;           }
  public function getMarket             () { return $this->market;             }
  public function getDisplaysChange     () { return $this->displaysChange;     }
  public function getRetrievesFromGoogle() { return $this->retrievesFromGoogle;}
  public function getGoogleCode         () { return $this->googleCode;         }
  public function getPrice              () { return $this->price;              }
  public function getChange             () { return $this->change;             }

  public function setTitle              ($var) { $this->title               = $var; return; }
  public function setTicker             ($var) { $this->ticker              = $var; return; }
  public function setUnit               ($var) { $this->unit                = $var; return; }
  public function setDecimals           ($var) { $this->decimals            = $var; return; }
  public function setMarket             ($var) { $this->market              = $var; return; }
  public function setDisplaysChange     ($var) { $this->displaysChange      = $var; return; }
  public function setRetrievesFromGoogle($var) { $this->retrievesFromGoogle = $var; return; }
  public function setGoogleCode         ($var) { $this->googleCode          = $var; return; }
  public function setPrice              ($var) { $this->price               = $var; return; }
  public function setChange             ($var) { $this->change              = $var; return; }
  
  
  /*---------------------------
    retrieveStockPriceFromGoogle
  -----------------------------*/
  public function retrieveStockPriceFromGoogle()
  {
    $html = file_get_contents(GOOGLE_BASE_URL . '?q=' . $this->getGoogleCode());
    
    if (preg_match('/<span id="ref_' . $this->getGoogleCode() . '_l">([\d,.]*)<\/span>/is', $html, $matches))
    {
      $this->setPrice(str_replace(',', '', $matches[1]));
    }
    
    if (preg_match('/<span class=".*" id="ref_' . $this->getGoogleCode() . '_cp">\(([\d.-]*%)\)<\/span>/is', $html, $matches))
    {
      $this->setChange('+' . $matches[1]);
      $this->setChange(str_replace('+-', '-', $this->getChange()));
    }
    
    return;
  }
  
}
?>
