<?php
/*===============================
  アセットクラス
=================================*/
class Asset
{
  private $title;
  private $ticker;
  private $unit;
  private $decimals;
  private $market;
  private $displaysChange;
  private $source;
  private $price;
  private $change;
  
  /*---------------------------
    __construct
  -----------------------------*/
  public function __construct($title,
                              $ticker,
                              $unit,
                              $decimals,
                              $market,
                              $displaysChange,
                              $source
                             )
  {
    $this->title          = $title;
    $this->ticker         = $ticker;
    $this->unit           = $unit;
    $this->decimals       = $decimals;
    $this->market         = $market;
    $this->displaysChange = $displaysChange;
    $this->source         = $source;
    
    return;
  }
  
  /*===========================
    アクセッサ
  =============================*/
  public function getTitle         () { return $this->title;         }
  public function getTicker        () { return $this->ticker;        }
  public function getUnit          () { return $this->unit;          }
  public function getDecimals      () { return $this->decimals;      }
  public function getMarket        () { return $this->market;        }
  public function getDisplaysChange() { return $this->displaysChange;}
  public function getSource        () { return $this->source;        }
  
  public function getPrice         () { return $this->price;         }
  public function getChange        () { return $this->change;        }

  public function setTitle         ($var) { $this->title          = $var; return; }
  public function setTicker        ($var) { $this->ticker         = $var; return; }
  public function setUnit          ($var) { $this->unit           = $var; return; }
  public function setDecimals      ($var) { $this->decimals       = $var; return; }
  public function setMarket        ($var) { $this->market         = $var; return; }
  public function setDisplaysChange($var) { $this->displaysChange = $var; return; }
  public function setSource        ($var) { $this->source         = $var; return; }
  public function setPrice         ($var) { $this->price          = $var; return; }
  public function setChange        ($var) { $this->change         = $var; return; }
  
}
?>
