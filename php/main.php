<?php




// OAuthスクリプトの読み込み
require_once('twitteroauth/twitteroauth.php');
 
// Consumer key
$consumer_key = "XXXXXXXXXXXXXXXXXXXXX";

// Consumer secret
$consumer_secret = "YYYYYYYYYYYYYYYYYYYYYYYYYYYY";

// Access token
$access_token = "ZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZ";

// Access token secret
$access_token_secret = "VVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVV";
 
// つぶやき



$arr = array('^IXIC','^GSPC');
$url= "http://finance.yahoo.com/d/quotes.csv?s=".implode("+", $arr)."&f="."snl1c1p2d1t1";

/*
s  = Symbol
n  = Name
l1 = Last Trade (Price Only)
d1 = Last Trade Date
t1 = Last Trade Time
c  = Change and Percent Change
v  = Volume
*/

echo "<table>";
$handle = fopen($url, "r");
while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
{
  echo "<tr>";
  foreach($data as $d)
    echo "<td>$d</td>";
  echo "</tr>";
}
fclose($handle);
echo "</table>";

$message = 'aaaa';





// つぶやく
$connection = new TwitterOAuth($consumer_key,
                               $consumer_secret,
                               $access_token,
                               $access_token_secret); 

$req = $connection->OAuthRequest("https://api.twitter.com/1.1/statuses/update.json",
                                 "POST",
                                 array("status"=> $message)
                                );


?>
