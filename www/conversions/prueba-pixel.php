<?php
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
            "event_name": "Purchase",
            "event_time": 1695753015,
            "action_source": "website",
            "user_data": {
                "em": [
                    "7b17fb0bd173f625b58636fb796407c22b3d16fc78302d79f0fd30c2fc2fc068"
                ],
                "ph": [
                    null
                ]
            },
            "custom_data": {
                "currency": "ARS",
                "value": "142.52"
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
echo $response;
?>
