<?php

require "./config.php";

// OAuthスクリプト
require "./lib/twitteroauth/autoload.php";
 
// Consumer key
$consumer_key = "XXXXXXXXXXXXXXXXXXXXX";

// Consumer secret
$consumer_secret = "YYYYYYYYYYYYYYYYYYYYYYYYYYYY";

// Access token
$access_token = "ZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZ";

// Access token secret
$access_token_secret = "VVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVV";
 
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
    $msg = $message .  '（' . $asset['change'] . '）';
  }
  
  $msg = $msg . ' ';
}

echo $message . '\n';


// つぶやく
/*
$connection = new TwitterOAuth($consumer_key,
                               $consumer_secret,
                               $access_token,
                               $access_token_secret); 

$req = $connection->OAuthRequest("https://api.twitter.com/1.1/statuses/update.json",
                                 "POST",
                                 array("status"=> $message)
                                );


*/
?>
