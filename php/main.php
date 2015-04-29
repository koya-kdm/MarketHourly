<?php

// OAuthスクリプトの読み込み
//require_once('twitteroauth/twitteroauth.php');
 
// Consumer key
$consumer_key = "XXXXXXXXXXXXXXXXXXXXX";

// Consumer secret
$consumer_secret = "YYYYYYYYYYYYYYYYYYYYYYYYYYYY";

// Access token
$access_token = "ZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZ";

// Access token secret
$access_token_secret = "VVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVV";
 
// つぶやき



$tickers = array('USDJPY=X', //USD
                 'INDU',     //Dow
                 '^IXIC',    //Nasdaq
                );

$params  = array('s',  //USD
                 'n',  //Dow
                 'l1', //Nasdaq
                 'd1', //Nasdaq
                 't1', //Nasdaq
                 'c',  //Nasdaq
                 'v',  //Nasdaq
                );


$url= "http://finance.yahoo.com/d/quotes.csv?s=".implode("+", $tickers)."&f=". implode('', $params);

//http://finance.yahoo.com/d/quotes.csv?s=INDU+^IXIC+USDJPY=X+^N225&f=snl1c1p2d1t1

/*
s  = Symbol
n  = Name
l1 = Last Trade (Price Only)
d1 = Last Trade Date
t1 = Last Trade Time
c  = Change and Percent Change
v  = Volume
*/


$handle = fopen($url, "r");
while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
{
  foreach($data as $d)
    echo "$d";
}
fclose($handle);


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
