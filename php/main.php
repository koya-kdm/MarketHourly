<?php

$applicationPath = '/home/ec2-user/markethourly';

// 設定
require_once $applicationPath . '/php/config.php';

// OAuthスクリプト
require $applicationPath . '/php/lib/twitteroauth/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;



// つぶやき


$assets = array(array('title' => 'USD',  'ticker' => 'USDJPY=X', 'unit' => '円', 'displays_change' => false),
                array('title' => '日経', 'ticker' => '^N225',    'unit' => '円', 'displays_change' => true ),
                array('title' => 'Nsdq', 'ticker' => '^IXIC',    'unit' => 'pt', 'displays_change' => true ),
                );

$params  = array('s',  //
                 'n',  //
                 'l1', //
                 'd1', //
                 't1', //
                 'p2',  //
                );
/*
s  = Symbol
n  = Name
l1 = Last Trade (Price Only)
d1 = Last Trade Date
t1 = Last Trade Time
c  = Change and Percent Change
v  = Volume

c6: Change (Realtime)
k2: Change Percent (Realtime)	
p2: Change in Percent
*/


$tickerString = '';
foreach ($assets as $key => $asset)
{
  $tickerString = $tickerString . $asset['ticker'] . '+';
}

$url= "http://finance.yahoo.com/d/quotes.csv?s=" . $tickerString . "&f=" . implode('', $params);

//http://finance.yahoo.com/d/quotes.csv?s=INDU+^IXIC+USDJPY=X+^N225&f=snl1c1p2d1t1



$i = 0;
$handle = fopen($url, "r");
while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
{
  
  $assets[$i]['price' ] = $data[2];
  $assets[$i]['change'] = $data[5];
  
  $i++;
}
fclose($handle);

$msg = '';
foreach ($assets as $key => $asset)
{
  $msg = $msg . '■' . $asset['title'] . '：' . $asset['price'] . $asset['unit'];
  
  if ($asset['displays_change'])
  {
    $msg = $msg .  '（' . $asset['change'] . '）';
  }
  
  $msg = $msg . ' ';
}

echo $msg . '\n';


// つぶやく
$connection = new TwitterOAuth($consumer_key,
                               $consumer_secret,
                               $access_token,
                               $access_token_secret); 

$req = $connection->OAuthRequest("https://api.twitter.com/1.1/statuses/update.json",
                                 "POST",
                                 array("status"=> $msg)
                                );

?>
