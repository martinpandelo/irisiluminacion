<?php

$host = $_SERVER["HTTP_HOST"];
$url = $_SERVER["REQUEST_URI"];
$finalURL =  "https://" . $host . $url;

$prodApiConv = "{'id': '".$prod['pr_sku']."','quantity': 1,'item_price': ".number_format($prod['precioFinal'],2,'.','')."}";

        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
           $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');

$fbc = '';
if (isset($_COOKIE["_fbc"])) {
    $fbc .=  '"fbc": "'.$_COOKIE["_fbc"].'",';
}

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://graph.facebook.com/v18.0/3603298286624898/events?access_token=EAAEt01dLjs0BOZBkJzVaIUqfZBFk3SZBR5E3ZAXSveURYEjXDZBifkhueCCFuSOEMb0PhMNLZBjNq3lbIxFUBOWLvj1iC349GPmJy3hx3kO7KwHdcfMQUFaZA12bnLPlA86DdOiOgMonUfQLqafdsk6ZAIJzoIVg7xucdDMubfVuRFbxhJ5303AXurCvMH0OsyU2iAZDZD',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
    "data": [
        {
            "event_name": "ViewContent",
            "event_time": '.$_SERVER['REQUEST_TIME'].',
            "action_source": "website",
            "event_source_url": "'.$finalURL.'",
            "user_data": {
                '.$fbc.'
                "fbp": "'.$_COOKIE["_fbp"].'",
                "client_ip_address": "'.$ipaddress.'",
                "client_user_agent": "'.$_SERVER['HTTP_USER_AGENT'].'"
            },
            "custom_data": {
                "currency": "ARS",
                "value": '.number_format($prod['precioFinal'],2,'.','').',
                "content_category": "Tienda de iluminación",
                "content_name": "'.$prod['pd_titulo'].'",
                "content_type": "product",
                "contents": [
                    '.$prodApiConv.'
                  ],
                "content_ids": "['.$prod['pr_sku'].']"
            }
        }
    ]
}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);
curl_close($curl);
?>
